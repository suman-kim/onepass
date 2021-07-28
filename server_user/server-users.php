<?php include '../inc/global.php'?>
<?php
    $page_no = $_REQUEST["page_no"];
    $page_count = $_REQUEST["page_count"];
    $searchStr = $_REQUEST["search_str"];
    $noSearch = ( $searchStr == "" ) ? TRUE : FALSE;
    $search_start_num = $_REQUEST["search_start_num"];
    if ($page_no == "" || $page_no == 0) $page_no = 1;
    if ($page_count == "" || $page_count == 0) $page_count = 10;
    if ($search_start_num == "" || $page_no == 1) $search_start_num = 0;

    if ($noSearch) {
        $dataRs = get("/v1/server/users?page_no=" . $page_no . "&total_page=" . $page_count);
        $data = json_decode($dataRs);
    }else {
        $dataRs = get("/v1/server/users");
        $data = json_decode($dataRs);
    }
    
    if ($data->error_code == 1) {
        $str = "";
        $searchRows = 0;
        $totalRows = count($data->user_infos);
        if ($noSearch) {
            $startNum = 0;
        } else {
            $startNum = ($search_start_num == 0 ) ?  ($page_no-1) * $page_count : $search_start_num;
        }
        

        for ($i = $startNum; $i < $totalRows; $i++) {
            $isLogin = ($data->user_infos[$i]->login_status->is_login == 1) ? "TRUE": "FALSE";
            if (!$noSearch) {
                $name = (strpos((String)$data->user_infos[$i]->name, $searchStr) !== FALSE) ? TRUE : FALSE;
                $login_id = (strpos((String)$data->user_infos[$i]->login_id, $searchStr) !== FALSE) ? TRUE : FALSE;
                $type_name = (strpos((String)$data->user_infos[$i]->user_type->name, $searchStr) !== FALSE) ? TRUE : FALSE;
                $permit_name = (strpos((String)$data->user_infos[$i]->user_permit->name, $searchStr) !== FALSE) ? TRUE : FALSE;
                $login_time = (strpos((String)$data->user_infos[$i]->login_status->login_time, $searchStr) !== FALSE) ? TRUE : FALSE;
                $logout_time = (strpos((String)$data->user_infos[$i]->login_status->logout_time, $searchStr) !== FALSE) ? TRUE : FALSE;
                $login_ip = (strpos((String)$data->user_infos[$i]->login_status->login_ip, $searchStr) !== FALSE) ? TRUE : FALSE;
                $is_login = (strpos((String)$isLogin, $searchStr) !== FALSE) ? TRUE : FALSE;
            }

            if ( $name || $login_id || $type_name || $permit_name || $login_time || $logout_time || $is_login || $login_ip || $noSearch) {
                $str .= '<tr class="">';
                $str .= '<td class="py-2"><input type="checkbox" name="choice[]" value="'.$data->user_infos[$i]->id.'"></td>';
                $str .= ($noSearch) ? '<td class="py-2">'.($i+1).'</td>' : '<td class="py-2">'.( $searchRows+(($page_no-1)*$page_count) + 1 ).'</td>';
                $str .= '<td class="py-2">'.$data->user_infos[$i]->login_id.'</td>';
                $str .= '<td class="align-middle py-2">'.$data->user_infos[$i]->name.'</td>';
                $str .= '<td class="py-2">'.$data->user_infos[$i]->user_type->name.'</td>';
                $str .= '<td class="py-2">'.$data->user_infos[$i]->user_permit->name.'</td>';
                $str .= '<td class="py-2">'.$data->user_infos[$i]->login_status->login_time.'</td>';
                $str .= '<td class="py-2">'.$data->user_infos[$i]->login_status->logout_time.'</td>';
                $str .= '<td class="py-2">'.$isLogin.'</td>';
                $str .= '<td class="py-2">'.$data->user_infos[$i]->login_status->login_ip.'</td>';
                $str .= '<td class="py-2">';
                $str .= '<div class="position-relative">';
                $str .= '<a class="link-dark d-inline-block" onclick="javascript: server_user_update_info('.$data->user_infos[$i]->id.');" data-toggle="modal" data-target="#serverUserModal" href="#">';
                $str .= '<i class="gd-pencil icon-text"></i>';
                $str .= '</a>';
                $str .= '<a class="link-dark d-inline-block" onclick="javascript: goDelete('.$data->user_infos[$i]->id.',\'server-users\');" href="#">';
                $str .= '<i class="gd-trash icon-text"></i>';
                $str .= '</a>';
                $str .= '</div>';
                $str .= '</td>';
                $str .= '</tr>';
                $searchRows++;
                if ($searchRows == $page_count) break;
            }
        }
        if (!$noSearch) {
            $searchRows = 0;
            for ($i = 0; $i < $totalRows; $i++) {
                $isLogin = ($data->user_infos[$i]->login_status->is_login == 1) ? "TRUE": "FALSE";
                $name = (strpos((String)$data->user_infos[$i]->name, $searchStr) !== FALSE) ? TRUE : FALSE;
                $login_id = (strpos((String)$data->user_infos[$i]->login_id, $searchStr) !== FALSE) ? TRUE : FALSE;
                $type_name = (strpos((String)$data->user_infos[$i]->user_type->name, $searchStr) !== FALSE) ? TRUE : FALSE;
                $permit_name = (strpos((String)$data->user_infos[$i]->user_permit->name, $searchStr) !== FALSE) ? TRUE : FALSE;
                $login_time = (strpos((String)$data->user_infos[$i]->login_status->login_time, $searchStr) !== FALSE) ? TRUE : FALSE;
                $logout_time = (strpos((String)$data->user_infos[$i]->login_status->logout_time, $searchStr) !== FALSE) ? TRUE : FALSE;
                $login_ip = (strpos((String)$data->user_infos[$i]->login_status->login_ip, $searchStr) !== FALSE) ? TRUE : FALSE;
                $is_login = (strpos((String)$isLogin, $searchStr) !== FALSE) ? TRUE : FALSE;

                if ( $name || $login_id || $type_name || $permit_name || $login_time || $logout_time || $is_login || $login_ip ) {
                        $searchRows++;
                }
            }
            $totalRows = $searchRows;
        }
        else{
            $usersCntRs = get("/v1/server/users-count");
            $usersCnt = json_decode($usersCntRs);
            $totalRows = $usersCnt->user_count;
        }
        $totalPage = ($totalRows == 0) ? 1 : ceil($totalRows / $page_count);
        $strPage = "";
        for ($i = 1; $i <= $totalPage; $i++) {
            $active = ($i == $page_no) ? "active" : "" ;
            $strPage .= '<li class="page-item d-none d-md-block">';
            $strPage .= '<a id="datatablePagination'.$i.'" class="page-link '.$active.'" onclick="javascript: goPage('.$i.',\'server-users\');" href="#" data-dt-page-to="'.$i.'">'.$i.'</a>';
            $strPage .= '</li>';
        }
    }else{
        $str  = '<tr><td style="border: 0;"></td></tr>';
        $str .= '<tr><td style="border: 0;"></td></tr>';
        $str .= '<tr><td style="border: 0;"></td></tr>';
        $str .= '<tr><td style="border: 0;"></td></tr>';
        $str .= '<tr><td style="border: 0;"></td></tr>';
        $str .= '<tr class="">';
        $str .= '<td class="py-2" style="border: 0;">';
        $str .= '<div>';
        $str .= '<span >사용자를 찾지 못했습니다.</span>';
        $str .= '</div>';
        $str .= '</td>';
        $str .= '</tr>';

        $strPage = '<li class="page-item d-none d-md-block">';
        $strPage .= '<a id="datatablePagination1" class="page-link" onclick="javascript: goPage(1,\'server-users\');" href="#" data-dt-page-to="1">1</a>';
        $strPage .= '</li>';
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <!-- Title -->
    <title>Users | Graindashboard UI Kit</title>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta http-equiv="x-ua-compatible" content="ie=edge">

    <!-- Favicon -->
    <link rel="shortcut icon" href="../public/img/favicon.ico">
    <!-- DEMO CHARTS -->
    <link rel="stylesheet" href="../public/demo/chartist.css">
    <link rel="stylesheet" href="../public/demo/chartist-plugin-tooltip.css">
    <!-- Template -->
    <link rel="stylesheet" href="../public/graindashboard/css/graindashboard.css">
    <link rel="stylesheet" href="../public/graindashboard/css/tree.css">
</head>
<style>
    .sub-sidebar-menu{float: left; width: 19.5%; left: -22px; height: 55rem;}
    .sub-sidebar-btn-wrap{float: left; width: 0.5%; left: -24px; height: 55rem; padding: 26rem 0; background-color: #f3f3f3;}
    .sub-sidebar-btn{background-color: #8b8e9f; font-size: 1px; height: 100%; color: #fff; padding: 16px 0; cursor: pointer;}
    .display-content{float: right; width: 80%;}
    table * {text-align: center;}
</style>
<body class="has-sidebar has-fixed-sidebar-and-header">
<!-- Header -->
<!-- End Header -->

<main class="main">
    <!-- Sidebar Nav -->
    <!-- End Sidebar Nav -->

    <div class="content" style="height: 100%;">
        <div class="py-4 px-3 px-md-4">
            <div id="sub-sidebar">
                <div class="card mb-3 mb-md-4 sub-sidebar-menu">
                    <div class="card-body" style="overflow: auto;">

                    <ul class="tree">
                        <li>
                            <input type="checkbox" id="root">
                            <label for="root">ROOT</label>
                            <ul>
                            <li>
                                <input type="checkbox" id="node1">
                                <label for="node1" class="lastTree">node1</label>
                            </li>
                            <li>
                                <input type="checkbox" id="node2">
                                <label for="node2">node2</label>
                                <ul>
                                <li>
                                    <input type="checkbox" id="node21">
                                    <label for="node21" class="lastTree">node21</label>
                                </li>
                                </ul>
                            <li>
                                <input type="checkbox" id="node3">
                                <label for="node3">node3</label>
                                <ul>
                                <li>
                                    <input type="checkbox" id="node31">
                                    <label for="node31">node31</label>
                                    <ul>
                                    <li>
                                        <input type="checkbox" id="node311">
                                        <label for="node311" class="lastTree">node311</label>
                                    </li>
                                    </ul>   
                                </li>
                                <li>
                                    <input type="checkbox" id="node32">
                                    <label for="node32">node32</label>
                                    <ul>
                                    <li>
                                        <input type="checkbox" id="node321">
                                        <label for="node321" class="lastTree">node321</label>
                                    </li>
                                    <li>
                                        <input type="checkbox" id="node322">
                                        <label for="node322" class="lastTree">node322</label>
                                    </li>
                                    <li>
                                        <input type="checkbox" id="node323">
                                        <label for="node323" class="lastTree">node323</label>
                                    </li>
                                    </ul>
                                <li>
                                    <input type="checkbox" id="node33">
                                    <label for="node33" class="lastTree">node33</label>
                                </li>
                                </ul>
                            </li>
                            </ul>
                        </li>
                    </ul>

                    </div>
                </div>
            </div>

            <div class="card mb-3 mb-md-4 sub-sidebar-btn-wrap">
                <div class="sub-sidebar-btn" onclick="javascript: displayOnOff();">◀</div>
            </div>

            <div class="card mb-3 mb-md-4 display-content" id="type-content" style="height: 55rem;">
                <div class="card-body">
                    <!-- Breadcrumb -->
                    <nav class="d-none d-md-block" aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item">
                                <a href="#">사용자</a>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page">사용자</li>
                        </ol>
                    </nav>
                    <!-- End Breadcrumb -->

                    <div class="mb-3 mb-md-4 d-flex justify-content-between">
                        <div class="h3 mb-0">사용자</div>
                        <div style="padding-right: 5rem;"><i onclick="javascript: refresh('server-users');" class = "gd-reload h4" style = "cursor : pointer;"></i></div>
                    </div>

                    <!-- Form -->
                    <div>
                        <form name = "search_form" method = "GET">
                            <input type="hidden" name="search_start_num" value = "<?=$search_start_num?>">
                            <input type="hidden" name="page_count">
                            <div class="form-row">
                                <div class="form-group col-4 col-md-2">
                                    <label for="user_id">Search</label>
                                    <input type="text" class="form-control" id="search_str" name="search_str" value = "<?=$searchStr?>" placeholder="Search">
                                </div>
                                <div class="form-group col-4 col-md-10">
                                    <button type="button" onclick = "javascript: goSearch('server-users');" class="btn" style="position: relative; top: 32px; color: #fff; background-color: #ffc300; border-color: #ffc300;">검색</button>
                                    <button type="button" class="btn" onclick="server_user_form_reset();" style="position: relative; top: 32px; color: #fff; background-color: #265df1; border-color: #265df1;" data-toggle="modal" data-target="#serverUserModal">추가</button>
                                    <button type="button" onclick = "javascript: goDelete('more','server-users');" class="btn" style="position: relative; top: 32px; color: #fff; background-color: #ff6500; border-color: #ff6500;">삭제</button>
                                </div>
                            </div>
                            
                        </form>
                    </div>
                    <!-- End Form -->


                    <!-- Users -->
                    <div style="height: 35rem; overflow: auto;">
                        <form name = "contents_show_form">
                            <table class="table text-nowrap mb-0">
                                <thead>
                                <tr>
                                    <th class="font-weight-semi-bold border-top-0 py-2"><input type="checkbox" id="checkAll"></th>
                                    <th class="font-weight-semi-bold border-top-0 py-2">No.</th>
                                    <th class="font-weight-semi-bold border-top-0 py-2">ID</th>
                                    <th class="font-weight-semi-bold border-top-0 py-2">이름</th>
                                    <th class="font-weight-semi-bold border-top-0 py-2">직군</th>
                                    <th class="font-weight-semi-bold border-top-0 py-2">권한</th>
                                    <th class="font-weight-semi-bold border-top-0 py-2">로그인 시각</th>
                                    <th class="font-weight-semi-bold border-top-0 py-2">로그아웃 시각</th>
                                    <th class="font-weight-semi-bold border-top-0 py-2">실시간 로그인</th>
                                    <th class="font-weight-semi-bold border-top-0 py-2">로그인 IP</th>
                                    <th class="font-weight-semi-bold border-top-0 py-2">비고</th>
                                </tr>
                                </thead>
                                <tbody id="user-content">
                                    <?=$str?>
                                </tbody>
                            </table>
                            <input type="hidden" name="id">
                            <input type="hidden" name="job">
                        </form>
                    </div>
                </div>

                <div class="card-footer d-block d-md-flex align-items-center d-print-none" style="border-top: 1px solid #d8d8d8;">
                    <nav class="d-flex d-print-none" aria-label="Pagination">
                        <ul class="pagination justify-content-end font-weight-semi-bold mb-0">
                            <li class="page-item" id="page-prev">
                                <button type = "button" id="datatablePaginationPrev" class="page-link" onclick="javascript: goPage('<?=$page_no-1?>','server-users');" aria-label="Previous">
                                    <i class="gd-angle-left icon-text icon-text-xs d-inline-block"></i>
                                </button>
                            </li>
                                <?=$strPage?>
                            <li class="page-item" id="page-next">
                                    <button type = "button" id="datatablePaginationNext" class="page-link" onclick="javascript: goPage('<?=$page_no+1?>','server-users');" aria-label="Next">
                                    <i class="gd-angle-right icon-text icon-text-xs d-inline-block"></i>
                                </button>
                            </li>
                        </ul>
                    </nav>
                    <div class="d-flex mb-2 mb-md-0 ml-3" style="color: #8a8a8a; float: left; cursor: pointer;" onclick="javascript: goPage('1','server-users');">처음으로</div>
                    <div class="d-flex mb-2 mb-md-0 ml-3" style="float: left;"><input type="text" class="form-control" id="search_page" name="search_page" value="<?=$page_no?>" style="width: 3rem; text-align: center; border: 1px solid #cccccc;"></div>
                    <div class="d-flex mb-2 mb-md-0 ml-3" style="float: left;"><button type="button" class="btn" style="color: #000; background-color: #f7f7f7; border-color: #cccccc;" onclick="javascript: goPage('search_page','server-users');">이동</button></div>
                    <div class="d-flex mb-2 mb-md-0 ml-3" style="color: #8a8a8a;" id = "total-row">총 <?=$totalRows?>개</div>
                    <div class="d-flex mb-2 mb-md-0 ml-3" style="float: left;">
                        <select id="page_count_change" onchange="javascript: changeCount('server-users');" style="border:1px solid #cccccc; color: #8a8a8a; height: 44px;">
                            <option value="10" <?if($page_count == 10) echo "selected"?>>10개 항목 / 페이지</option>
                            <option value="20" <?if($page_count == 20) echo "selected"?>>20개 항목 / 페이지</option>
                            <option value="30" <?if($page_count == 30) echo "selected"?>>30개 항목 / 페이지</option>
                            <option value="50" <?if($page_count == 50) echo "selected"?>>50개 항목 / 페이지</option>
                        </select>
                    </div>
                </div>
            </div>
            <!-- End Users -->
        </div>
        <!-- Footer -->
        <?php include '../footer.php'?>
        <!-- End Footer -->
    </div>
</main>

<?php
$serverUserRightsRs = get("/v1/server/user-rights");
$serverUserRights = json_decode($serverUserRightsRs);
if ($serverUserRights->error_code == 1) {
	$strServerUserRights = "";
	$totalRows = count($serverUserRights->permit_infos);
	for ($i=0; $i < $totalRows; $i++) { 
		$strServerUserRights .= "<option value='".$serverUserRights->permit_infos[$i]->id."'>".$serverUserRights->permit_infos[$i]->name."</option>";
	}
}
$serverUserTypeRs = get("/v1/server/user-types");
$serverUserType = json_decode($serverUserTypeRs);
if ($serverUserType->error_code == 1) {
	$strServerUserType = "";
	$totalRows = count($serverUserType->type_infos);
	for ($i=0; $i < $totalRows; $i++) { 
		$strServerUserType .= "<option value='".$serverUserType->type_infos[$i]->id."'>".$serverUserType->type_infos[$i]->name."</option>";
	}
}
?>

<!-- User Insert Modal -->
<div id="serverUserModal" class="modal fade" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog rounded py-5" role="document">
        <div class="modal-content py-5">
            <header class="modal-header flex-column justify-content-center border-0 mb-3 mb-xl-5">
                <h4 id="exampleModalLabel" class="modal-title mx-auto"><a href="#" style="color: #000; font-size: 3rem; font-weight: bold;">ONE PASS</a></h4>
            </header>
            <div class="modal-body pt-3 mb-5 mb-md-7">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title"></h4>
                        <form name = "server_user_form" method = "POST">
							<div class="form-group">
								<label for="login_id">ID</label>
								<input type="text" class="form-control" id="login_id" name="login_id">
							</div>
							<div class="form-group">
								<label for="login_pw">Password</label>
								<input type="password" class="form-control" id="login_pw" name="login_pw">
							</div>
							<div class="form-group">
								<label for="server_user_name">서버 사용자 이름</label>
								<input type="text" class="form-control" id="server_user_name" name="server_user_name">
							</div>

							<div class="form-group">
								<label for="type_id">서버 사용자 유형</label>
								<select class="form-control" id="type_id" name="type_id">
									<?=$strServerUserType?>
								</select>
							</div>
							<div class="form-group">
								<label for="permit_id">서버 사용자 권한</label>
								<select class="form-control" id="permit_id" name="permit_id">
									<?=$strServerUserRights?>
								</select>
							</div>
							<button type="button" id="serverUserInsertBtn" onclick="javascript: go_server_user_insert();" class="btn btn-primary btn-block">생 성</button>
						</form>
					</div>
				</div>
            </div>

            <footer class="modal-footer justify-content-between border-0">
                <p>Modal footer text goes here.</p>
            </footer>
        </div>
    </div>
</div>
<!-- End Modal -->

<script src="../public/graindashboard/js/graindashboard.js"></script>
<script src="../public/graindashboard/js/graindashboard.vendor.js"></script>
<script src="../public/graindashboard/js/onepass.js"></script>

<!-- DEMO CHARTS -->
<script src="../public/demo/resizeSensor.js"></script>
<script src="../public/demo/chartist.js"></script>
<script src="../public/demo/chartist-plugin-tooltip.js"></script>
<script src="../public/demo/gd.chartist-area.js"></script>
<script src="../public/demo/gd.chartist-bar.js"></script>
<script src="../public/demo/gd.chartist-donut.js"></script>

<script>
    function server_user_form_reset() {
        var f = $("form[name='server_user_form']").find("input[class=form-control]");
        for (let i = 0; i < f.length; i++) {
            f[i].value="";
        }
        $("#login_id").removeAttr("readonly");
        $("#serverUserModal .card-title").text("서버 사용자 추가");
        $("#serverUserUpdateBtn").remove();
        $("#serverUserInsertBtn").remove();
        var btn = '<button type="button" id="serverUserInsertBtn" onclick="javascript: go_server_user_insert();" class="btn btn-primary btn-block">생 성</button>';
        $("form[name='server_user_form']").append(btn);
    }

    var searchStr = '<?=$searchStr?>';
    var page_no = '<?=$page_no?>';
    var page_count = '<?=$page_count?>';
    page_count = Number(page_count);
    var search_start_num = '<?=$search_start_num?>';
    search_start_num = Number(search_start_num);
    var totalPage = '<?=$totalPage?>';
    totalPage = Number(totalPage);
</script>

<iframe type="hidden" id="hiddenfrm" name="hiddenfrm" style="display: none;"></iframe>
</body>
</html>