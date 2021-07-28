<?php include '../inc/global.php'?>
<?php
    $page_no = $_REQUEST["page_no"];
    $page_count = $_REQUEST["page_count"];
    $searchStr = $_REQUEST["search_str"];
    $noSearch = ( $searchStr == "" ) ? true : false;
    $search_start_num = $_REQUEST["search_start_num"];
    if ($page_no == "" || $page_no == 0) $page_no = 1;
    if ($page_count == "" || $page_count == 0) $page_count = 10;
    if ($search_start_num == "" || $page_no == 1) $search_start_num = 0;

    if ($noSearch) {
        $dataRs = get("/v1/users?page_no=" . $page_no . "&total_page=" . $page_count);
        $data = json_decode($dataRs);
    }else {
        $dataRs = get("/v1/users");
        $data = json_decode($dataRs);
    }
    
    if ($data->error_code == 1) {
        $str = "";
        $searchRows = 0;
        $totalRows = count($data->du_infos);
        if ($noSearch) {
            $startNum = 0;
        } else {
            $startNum = ($search_start_num == 0 ) ?  ($page_no-1) * $page_count : $search_start_num;
        }
        

        for ($i = $startNum; $i < $totalRows; $i++) {
            $strIsActivate = ($data->du_infos[$i]->device_status->is_activate == 1) ? "TRUE" : "FALSE";
            if (!$noSearch) {
                $access_name = (strpos(strtolower((String)$data->du_infos[$i]->du_access_group->access_name), strtolower($searchStr)) !== FALSE) ? TRUE : FALSE;
                $name = (strpos(strtolower((String)$data->du_infos[$i]->name), strtolower($searchStr)) !== FALSE) ? TRUE : FALSE;
                $group_name = (strpos(strtolower((String)$data->du_infos[$i]->du_group->group_name), strtolower($searchStr)) !== FALSE) ? TRUE : FALSE;
                $job_name = (strpos(strtolower((String)$data->du_infos[$i]->du_job_group->name), strtolower($searchStr)) !== FALSE) ? TRUE : FALSE;
                $position_name = (strpos(strtolower((String)$data->du_infos[$i]->du_job_position->name), strtolower($searchStr)) !== FALSE) ? TRUE : FALSE;
                $type_name = (strpos(strtolower((String)$data->du_infos[$i]->du_type->type_name), strtolower($searchStr)) !== FALSE) ? TRUE : FALSE;
            }

            if ( $access_name || $name || $group_name || $job_name || $position_name || $type_name || $noSearch) {
                $str .= '<tr class="">';
                $str .= '<td class="py-2"><input type="checkbox" name="choice[]" value="'.$data->du_infos[$i]->id.'"></td>';
                $str .= ($noSearch) ? '<td class="py-2">'.($i+1).'</td>' : '<td class="py-2">'.( $searchRows+(($page_no-1)*$page_count) + 1 ).'</td>';
                $str .= '<td class="py-2">'.$data->du_infos[$i]->name.'</td>';
                $str .= '<td class="align-middle py-2">'.$data->du_infos[$i]->du_group->group_name.'</td>';
                $str .= '<td class="py-2">'.$data->du_infos[$i]->du_job_group->name.'</td>';
                $str .= '<td class="py-2">'.$data->du_infos[$i]->du_job_position->name.'</td>';
                $str .= '<td class="py-2">'.$data->du_infos[$i]->du_type->type_name.'</td>';
                $str .= '<td class="py-2">';
                $str .= '<div class="position-relative">';
                $str .= '<a class="link-dark d-inline-block" onclick="javascript: user_update_info('.$data->du_infos[$i]->id.');" data-toggle="modal" data-target="#userModal" href="#">';
                $str .= '<i class="gd-pencil icon-text"></i>';
                $str .= '</a>';
                $str .= '<a class="link-dark d-inline-block" onclick="javascript: goDelete('.$data->du_infos[$i]->id.',\'users\');" href="#">';
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
                $access_name = (strpos(strtolower((String)$data->du_infos[$i]->du_access_group->access_name), strtolower($searchStr)) !== FALSE) ? TRUE : FALSE;
                $name = (strpos(strtolower((String)$data->du_infos[$i]->name), strtolower($searchStr)) !== FALSE) ? TRUE : FALSE;
                $group_name = (strpos(strtolower((String)$data->du_infos[$i]->du_group->group_name), strtolower($searchStr)) !== FALSE) ? TRUE : FALSE;
                $job_name = (strpos(strtolower((String)$data->du_infos[$i]->du_job_group->name), strtolower($searchStr)) !== FALSE) ? TRUE : FALSE;
                $position_name = (strpos(strtolower((String)$data->du_infos[$i]->du_job_position->name), strtolower($searchStr)) !== FALSE) ? TRUE : FALSE;
                $type_name = (strpos(strtolower((String)$data->du_infos[$i]->du_type->type_name), strtolower($searchStr)) !== FALSE) ? TRUE : FALSE;

                if ( $access_name || $name || $group_name || $job_name || $position_name || $type_name ) {
                        $searchRows++;
                }
            }
            $totalRows = $searchRows;
        }
        else{
            $usersCntRs = get("/v1/users-count");
            $usersCnt = json_decode($usersCntRs);
            $totalRows = $usersCnt->du_count;
        }
        $totalPage = ($totalRows == 0) ? 1 : ceil($totalRows / $page_count);
        $strPage = "";
        for ($i = 1; $i <= $totalPage; $i++) {
            $active = ($i == $page_no) ? "active" : "" ;
            $strPage .= '<li class="page-item d-none d-md-block">';
            $strPage .= '<a id="datatablePagination'.$i.'" class="page-link '.$active.'" onclick="javascript: goPage('.$i.',\'users\');" href="#" data-dt-page-to="'.$i.'">'.$i.'</a>';
            $strPage .= '</li>';
        }
    }else{
        $str  = '<tr><td style="border: 0;"></td></tr>';
        $str .= '<tr><td style="border: 0;"></td></tr>';
        $str .= '<tr><td style="border: 0;"></td></tr>';
        $str .= '<tr><td style="border: 0;"></td></tr>';
        $str .= '<tr><td style="border: 0;"></td></tr>';
        $str .= '<tr class="">';
        $str .= '<td style="border: 0;"></td>';
        $str .= '<td style="border: 0;"></td>';
        $str .= '<td style="border: 0;"></td>';
        $str .= '<td style="border: 0;"></td>';
        $str .= '<td class="py-2" style="border: 0;">';
        $str .= '<div>';
        $str .= '<span >사용자를 찾지 못했습니다.</span>';
        $str .= '</div>';
        $str .= '</td>';
        $str .= '</tr>';

        $strPage = '<li class="page-item d-none d-md-block">';
        $strPage .= '<a id="datatablePagination1" class="page-link active" onclick="javascript: goPage(1,\'users\');" href="#" data-dt-page-to="1">1</a>';
        $strPage .= '</li>';
    }

    $userGroupsDataRs = get("/v1/user-groups");

    $userGroupsDataRs = preg_replace('/\r\n|\r|\n|\s/','',$userGroupsDataRs);
    $userGroupsDataRs = str_replace('{"du_group_infos":[','[',$userGroupsDataRs);
    $userGroupsDataRs = str_replace('}],"error_code":1,"error_msg":"success","error_msg_detail":"","work_info":{"client_work_id":"","method":"GET","path":"/v1/user-groups","work_id":0}}','}]',$userGroupsDataRs);
    $userGroupsDataRs = str_replace('parent_id','pId',$userGroupsDataRs);
    

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

    <script src="../public/graindashboard/js/graindashboard.js"></script>
    <script src="../public/graindashboard/js/graindashboard.vendor.js"></script>

    <!-- DEMO CHARTS -->
    <script src="../public/demo/resizeSensor.js"></script>
    <script src="../public/demo/chartist.js"></script>
    <script src="../public/demo/chartist-plugin-tooltip.js"></script>
    <script src="../public/demo/gd.chartist-area.js"></script>
    <script src="../public/demo/gd.chartist-bar.js"></script>
    <script src="../public/demo/gd.chartist-donut.js"></script>
    <script src="../public/graindashboard/js/onepass.js"></script>
    

    
    <link rel="stylesheet" href="../public/graindashboard/css/zTreeStyle.css">
    <script src="../public/graindashboard/js/jquery.ztree.core.min.js"></script>
    <script src="../public/graindashboard/js/jquery.ztree.excheck.min.js"></script>
  
</head>
<style>
    .sub-sidebar-menu{float: left; width: 19.5%; left: -22px; height: 55rem;}
    .sub-sidebar-btn-wrap{float: left; width: 0.5%; left: -24px; height: 55rem; padding: 26rem 0; background-color: #f3f3f3;}
    .sub-sidebar-btn{background-color: #8b8e9f; font-size: 1px; height: 100%; color: #fff; padding: 16px 0; cursor: pointer;}
    .display-content{float: right; width: 80%;}
    table * {text-align: center;}


    .contextmenu {
        display: none;
        position: absolute;
        width: 200px;
        margin: 0;
        padding: 0;
        background: #FFFFFF;
        border-radius: 5px;
        list-style: none;
        box-shadow:0 15px 35px rgba(50,50,90,0.1), 0 5px 15px rgba(0,0,0,0.07);
        overflow: hidden;
        z-index: 999999;
    }
    .contextmenu li {
        border-left: 3px solid transparent;
        transition: ease .2s;
    }
    .contextmenu li a {
        display: block;
        padding: 10px;
        color: #B0BEC5;
        text-decoration: none;
        transition: ease .2s;
    }
    .contextmenu li:hover {
        /*background: #265df17a;*/
        background: #265df1;
        border-left: 3px solid #265df1;
    }
    .contextmenu li:hover a {
        color: #FFFFFF;
    }
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
                <div style="padding: 1rem 0 0 1rem;">
                        <h4>사용자 그룹</h4>
                    </div>
                    <div class="card-body tree-menu" style="overflow: auto;">
                        <ul id='ztree' class='ztree'>
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
                        <div style="padding-right: 5rem;"><i onclick="javascript: refresh('users');" class = "gd-reload h4" style = "cursor : pointer;"></i></div>
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
                                    <button type="button" onclick = "javascript: goSearch('users');" class="btn" style="position: relative; top: 32px; color: #fff; background-color: #ffc300; border-color: #ffc300;">검색</button>
                                    <button type="button" class="btn" onclick="user_form_reset();" style="position: relative; top: 32px; color: #fff; background-color: #265df1; border-color: #265df1;" data-toggle="modal" data-target="#userModal">추가</button>
                                    <button type="button" onclick = "javascript: goDelete('more','users');" class="btn" style="position: relative; top: 32px; color: #fff; background-color: #ff6500; border-color: #ff6500;">삭제</button>
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
                                    <th class="font-weight-semi-bold border-top-0 py-2">이름</th>
                                    <th class="font-weight-semi-bold border-top-0 py-2">사용자 직군</th>
                                    <th class="font-weight-semi-bold border-top-0 py-2">사용자 그룹 이름</th>
                                    <th class="font-weight-semi-bold border-top-0 py-2">사용자 직급</th>
                                    <th class="font-weight-semi-bold border-top-0 py-2">사용자 유형</th>
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
                                <button type = "button" id="datatablePaginationPrev" class="page-link" onclick="javascript: goPage('<?=$page_no-1?>','users');" aria-label="Previous">
                                    <i class="gd-angle-left icon-text icon-text-xs d-inline-block"></i>
                                </button>
                            </li>
                                <?=$strPage?>
                            <li class="page-item" id="page-next">
                                    <button type = "button" id="datatablePaginationNext" class="page-link" onclick="javascript: goPage('<?=$page_no+1?>','users');" aria-label="Next">
                                    <i class="gd-angle-right icon-text icon-text-xs d-inline-block"></i>
                                </button>
                            </li>
                        </ul>
                    </nav>
                    <div class="d-flex mb-2 mb-md-0 ml-3" style="color: #8a8a8a; float: left; cursor: pointer;" onclick="javascript: goPage('1','users');">처음으로</div>
                    <div class="d-flex mb-2 mb-md-0 ml-3" style="float: left;"><input type="text" class="form-control" id="search_page" name="search_page" value="<?=$page_no?>" style="width: 3rem; text-align: center; border: 1px solid #cccccc;"></div>
                    <div class="d-flex mb-2 mb-md-0 ml-3" style="float: left;"><button type="button" class="btn" style="color: #000; background-color: #f7f7f7; border-color: #cccccc;" onclick="javascript: goPage('search_page','users');">이동</button></div>
                    <div class="d-flex mb-2 mb-md-0 ml-3" style="color: #8a8a8a;" id = "total-row">총 <?=$totalRows?>개</div>
                    <div class="d-flex mb-2 mb-md-0 ml-3" style="float: left;">
                        <select id="page_count_change" onchange="javascript: changeCount('users');" style="border:1px solid #cccccc; color: #8a8a8a; height: 44px;">
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
$userGroupsRs = get("/v1/user-groups");
$userGroups = json_decode($userGroupsRs);
$totalRows = count($userGroups->du_group_infos);
$userGroupsStr = "";
for ($i=0; $i < $totalRows; $i++) { 
    $userGroupsStr .= "<option value='".$userGroups->du_group_infos[$i]->id."'>".$userGroups->du_group_infos[$i]->name."</option>";
   
}

$jobPositionsRs = get("/v1/job-positions");
$jobPositions = json_decode($jobPositionsRs);
$totalRows = count($jobPositions->du_job_position_infos);
$jobPositionsStr = "";
for ($i=0; $i < $totalRows; $i++) { 
    $jobPositionsStr .= "<option value='".$jobPositions->du_job_position_infos[$i]->id."'>".$jobPositions->du_job_position_infos[$i]->name."</option>";
}

$jobGroupsRs = get("/v1/job-groups");
$jobGroups = json_decode($jobGroupsRs);
$totalRows = count($jobGroups->du_job_group_infos);
$jobGroupsStr = "";
for ($i=0; $i < $totalRows; $i++) { 
    $jobGroupsStr .= "<option value='".$jobGroups->du_job_group_infos[$i]->id."'>".$jobGroups->du_job_group_infos[$i]->name."</option>";
}

$userTypesRs = get("/v1/user-types");
$userTypes = json_decode($userTypesRs);
$totalRows = count($userTypes->du_type_infos);
$userTypesStr = "";
for ($i=0; $i < $totalRows; $i++) { 
    $userTypesStr .= "<option value='".$userTypes->du_type_infos[$i]->id."'>".$userTypes->du_type_infos[$i]->name."</option>";
}

$accGroupsRs = get("/v1/access-groups");
$accGroups = json_decode($accGroupsRs);
$totalRows = count($accGroups->access_group_infos);
$accGroupsStr = "";
for ($i=0; $i < $totalRows; $i++) { 
    $accGroupsStr .= "<option value='".$accGroups->access_group_infos[$i]->id."'>".$accGroups->access_group_infos[$i]->name."</option>";
}
?>

<!-- User Insert Modal -->
<div id="userModal" class="modal fade" role="dialog" aria-labelledby="exampleModalLabel" style="margin-top:5%;" aria-hidden="true">
<div class="modal-dialog rounded py-5" role="document" style="padding-top: 0!important; width: 50rem; left: 3rem;">
        <div class="modal-content py-5" style="padding-top: 10px!important; overflow: auto; height: 50rem;">
            <header class="modal-header flex-column justify-content-center" style="border-bottom: 1px solid #a7a7a7; padding-bottom: 0;">
            <div style="width: 100%;" class="modal_header22">
                    <h4 class="card-title">사용자 추가
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="padding: 1.2rem 1rem 0 0;"><span aria-hidden="true">&times;</span></button>
                    </h4>
                </div>
            </header>
                <div>
                    <div class="left">
                    사진 영역
                    </div>
                    <div class="right">
                    QR 영역
                    </div>
                </div>

            <div class="modal-body pt-3 mb-5 mb-md-7">
                <div class="card">
                <div class="card-body">
						<form name = "user_form" method = "POST">
							<div class="form-group">
								<label for="user_id">사용자 ID</label>
								<input type="text" class="form-control" id="user_id" name="user_id">
							</div>
							<div class="form-group">
								<label for="user_name">사용자 이름</label>
								<input type="text" class="form-control" id="user_name" name="user_name">
							</div>
							<div class="form-group">
								<label for="group_id">사용자 그룹</label>
								<select class="form-control" id="group_id" name="group_id">
									<?=$userGroupsStr?>
								</select>
							</div>
							<div class="form-group">
								<label for="job_position_id">사용자 직급</label>
								<select class="form-control" id="job_position_id" name="job_position_id">
									<?=$jobPositionsStr?>
								</select>
							</div>
							<div class="form-group">
								<label for="job_group_id">사용자 직군</label>
								<select class="form-control" id="job_group_id" name="job_group_id">
									<?=$jobGroupsStr?>
								</select>
							</div>
							<div class="form-group">
								<label for="type_id">사용자 유형</label>
								<select class="form-control" id="type_id" name="type_id">
									<?=$userTypesStr?>
								</select>
							</div>
							<div class="form-group">
								<label for="access_id">출입 그룹</label>
								<select class="form-control" id="access_id" name="access_id">
									<?=$accGroupsStr?>
								</select>
							</div>

							<div class="form-group">
								<label for="home_addr">주소</label>
								<input type="text" class="form-control" id="home_addr" name="home_addr">
							</div>
							<div class="form-group">
								<label for="phone">연락처</label>
								<input type="text" class="form-control" id="phone" name="phone">
							</div>
							<div class="form-group">
								<label for="email">E-Mail</label>
								<input type="text" class="form-control" id="email" name="email">
							</div>
							<div class="form-group">
								<label for="birthday">생년 월일</label>
								<input type="text" class="form-control" id="birthday" name="birthday">
							</div>
							<div class="form-group">
								<label for="join_company">입사일</label>
								<input type="text" class="form-control" id="join_company" name="join_company">
							</div>
							<div class="form-group">
								<label for="leave_company">퇴사일</label>
								<input type="text" class="form-control" id="leave_company" name="leave_company">
                            </div>

							<div class="form-group">
								<label for="cardno_count">카드 개수</label>
								<input type="text" class="form-control" id="cardno_count" name="cardno_count">
							</div>
							<div class="form-group">
								<label for="cardnos">카드번호 목록</label>
								<input type="text" class="form-control" id="cardnos" name="cardnos">
							</div>
							<div class="form-group">
								<label for="pin_num">핀 번호</label>
								<input type="text" class="form-control" id="pin_num" name="pin_num">
							</div>
							<div class="form-group">
								<label for="pic_url">사진 주소</label>
								<input type="text" class="form-control" id="pic_url" name="pic_url">
							</div>
							<div class="form-group">
                                <label for="pic_size">사진 크기</label>
								<input type="text" class="form-control" id="pic_size" name="pic_size">
							</div>
							<div class="form-group">
								<label for="pic_data">사진 DATA</label>
								<input type="text" class="form-control" id="pic_data" name="pic_data">
							</div>
							<div class="form-group">
								<label for="finger_size1">손가락 크기1</label>
								<input type="text" class="form-control" id="finger_size1" name="finger_size1">
							</div>
							<div class="form-group">
								<label for="finger_data1">손가락 DATA1</label>
								<input type="text" class="form-control" id="finger_data1" name="finger_data1">
							</div>
							<div class="form-group">
								<label for="finger_size2">손가락 크기2</label>
								<input type="text" class="form-control" id="finger_size2" name="finger_size2">
							</div>
							<div class="form-group">
								<label for="finger_data2">손가락 DATA2</label>
								<input type="text" class="form-control" id="finger_data2" name="finger_data2">
							</div>
							<div class="form-group">
								<label for="is_timecard">is_timecard</label>
								<input type="text" class="form-control" id="is_timecard" name="is_timecard">
							</div>
							<div class="form-group">
								<label for="timecard_rule_id">timecard_rule_id</label>
								<input type="text" class="form-control" id="timecard_rule_id" name="timecard_rule_id">
							</div>
							<div class="form-group">
								<label for="use_access_time">인증 가능기간 설정 유무</label>
								<input type="text" class="form-control" id="use_access_time" name="use_access_time">
							</div>
							<div class="form-group">
								<label for="access_time_bez">인증 시작일</label>
								<input type="text" class="form-control" id="access_time_bez" name="access_time_bez">
							</div>
							<div class="form-group">
								<label for="access_time_end">인증 종료일</label>
								<input type="text" class="form-control" id="access_time_end" name="access_time_end">
							</div>
							<div class="form-group">
								<label for="employee_no">user_no</label>
								<input type="text" class="form-control" id="employee_no" name="employee_no">
							</div>
							<div class="form-group">
								<label for="employee_code">사원 코드</label>
								<input type="text" class="form-control" id="employee_code" name="employee_code">
							</div>

							<div class="form-group">
								<label for="reply_to">reply_to</label>
								<input type="text" class="form-control" id="reply_to" name="reply_to">
							</div>
							<div class="form-group">
								<label for="reply_method">reply_method</label>
								<input type="text" class="form-control" id="reply_method" name="reply_method">
							</div>
							<div class="form-group">
								<label for="reply_msg">reply_msg</label>
								<input type="text" class="form-control" id="reply_msg" name="reply_msg">
							</div>

							<button type="button" id="userInsertBtn" onclick="javascript: go_user_insert();" class="btn btn-primary btn-block">생 성</button>
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

<ul class="contextmenu">
    <li><a id="adduser" data-toggle="modal" data-target="#userGroupModal"  data-dismiss="modal" data-backdrop="static" keybord="false" href="#" >추가</a></li>
</ul>
<script>


    function user_form_reset() {
        var f = $("form[name='user_form']").find("input[class=form-control]");
        for (let i = 0; i < f.length; i++) {
            f[i].value = "";
        }
        $("#userModal .card-title").text("사용자 추가");
        let closebtn = '<button type="button" class="close" data-dismiss="modal" aria-label="Close" style="padding: 1.2rem 1rem 0 0;"><span aria-hidden="true">&times;</span></button>';
        $(".card-title").append(closebtn);
        $("#userUpdateBtn").remove();
        $("#userInsertBtn").remove();
        var btn = '<button type="button" id="userInsertBtn" onclick="javascript: go_user_insert();" class="btn btn-primary btn-block">생 성</button>';
        $("form[name='user_form']").append(btn);
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

<script>
    $(document).ready(function(){
        //Show contextmenu:
        $(".card-body.tree-menu").contextmenu(function(e){
            if (e.target.attributes.class.value == "node_name") {
                $("#updDevice").remove();
                $("#delDevice").remove();
                var user_group_id = e.target.attributes.userid.value;
                let updDelStr = '<li><a id="updDevice" onclick="userGroupSet(\'upd\',\''+user_group_id+'\');" data-toggle="modal" data-target="#userGroupModal" data-dismiss="modal" data-backdrop="static" keybord="false" href="#" >수정</a></li>';
                updDelStr +='<li><a id="delDevice" onclick="user_group_delete2(\'1\', \''+user_group_id+'\');" href="#">삭제</a></li>';
                $(".contextmenu").append(updDelStr);
            }else{
                $("#updDevice").remove();
                $("#delDevice").remove();
                var user_group_id = 0;
                if (e.target.attributes.class.value == "level0 curSelectedNode") {
                    user_group_id = e.toElement.children[1].attributes.userid.value
                    console.log(user_group_id);
                    let updDelStr = '<li><a id="updDevice" onclick="userGroupSet(\'upd\',\''+user_group_id+'\');" data-toggle="modal" data-target="#userGroupModal" data-dismiss="modal" data-backdrop="static" keybord="false" href="#" >수정</a></li>';
                    updDelStr +='<li><a id="delDevice" onclick="user_group_delete2(\'1\', \''+user_group_id+'\');" href="#">삭제</a></li>';
                    $(".contextmenu").append(updDelStr);
                }
            }
            $("#adduser").attr("onclick","javascript: userGroupSet('add','"+user_group_id+"');");

            //Get window size:
            var winWidth = $(document).width();
            var winHeight = $(document).height();
            //Get pointer position:
            var posX = e.pageX;
            var posY = e.pageY;
            //Get contextmenu size:
            var menuWidth = $(".contextmenu").width();
            var menuHeight = $(".contextmenu").height();
            //Security margin:
            var secMargin = 10;
            //Prevent page overflow:
            if(posX + menuWidth + secMargin >= winWidth && posY + menuHeight + secMargin >= winHeight){
                //Case 1: right-bottom overflow:
                posLeft = posX - menuWidth - secMargin + "px";
                posTop = posY - menuHeight - secMargin + "px";
            }
            else if(posX + menuWidth + secMargin >= winWidth){
                //Case 2: right overflow:
                posLeft = posX - menuWidth - secMargin + "px";
                posTop = posY + secMargin + "px";
            }
            else if(posY + menuHeight + secMargin >= winHeight){
                //Case 3: bottom overflow:
                posLeft = posX + secMargin + "px";
                posTop = posY - menuHeight - secMargin + "px";
            }
            else {
                //Case 4: default values:
                posLeft = posX + secMargin + "px";
                posTop = posY + secMargin + "px";
            }

            //Display contextmenu:
            $(".contextmenu").css({
                "left": posLeft,
                "top": posTop
            }).show();
    //Prevent browser default contextmenu.
    return false;
        });
        //Hide contextmenu:
        $(document).click(function(){
            $(".contextmenu").hide();
        });

    // zTree 설정 
    var setting = {
        data: {
            simpleData: {
                enable: true,
            }
        },
        check: {
            enable: true,
            chkStyle: "checkbox",
            chkboxType: { "Y": "", "N": "" }
        },
        view: {
            showIcon: false,
        }
    };

    // Data
    var userGroupsDataRs = '<?=$userGroupsDataRs?>';
    var zNodes = JSON.parse(userGroupsDataRs);
        // zTree 초기화
        $.fn.zTree.init($("#ztree"), setting, zNodes);
        var treeObj = $.fn.zTree.getZTreeObj("ztree");
        treeObj.expandAll(true);
        
        
        for (let i = 0; i < $("#ztree .node_name").length; i++) {
            
            var userGroup = zNodes.filter(function (e) {
                return e.name == $("#ztree .node_name").eq(i).text();
            });
            $("#ztree .node_name").eq(i).attr("userId", userGroup[0].id);
           
        }
    });
</script>




        <!-- 사용자 그룹 추가,수정시 나오는 모달 -->
        <div id="userGroupModal" class="modal fade" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog rounded" role="document" style="top: 15rem; padding: 0!important; width: 40rem;">
        <div class="modal-content" style="overflow: auto;">
            <header class="modal-header flex-column justify-content-center" style="border-bottom: 1px solid #a7a7a7; padding-bottom: 0;">
                <div style="width: 100%;">
                    <h4>
                    </h4>
                </div>
            </header>

            <div class="modal-body pt-3">
                <div class="card">
                    <div class="card-body">
                        <form name="userGroupForm" method = "POST">
							<div class="form-group">
								<input class="form-control input_label" value="상위 그룹" disabled>
								<select class="form-control input" name="parentId">
                                    <option value="0" selected>최상위</option>
									<?=$userGroupsStr?>
								</select>
                            </div>
							<div class="form-group">
								<input class="form-control input_label" value="그룹 이름" disabled>
								<input type="text" class="form-control input" name="groupName">
							</div>
						</form>
					</div>
				</div>
            </div>
        </div>
    </div>
</div>
<!-- End Modal -->

<style>

.left {
    width:160px; 
    height:100px;
    background-color:red; 
    float:left;
}
</style>