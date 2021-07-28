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

    $dataRs = get("/v1/access-groups");
    $data = json_decode($dataRs);

    if ($data->error_code == 1) {
        $str = "";
        $searchRows = 0;
        $totalRows = count($data->access_group_infos);
        $startNum = ($search_start_num == 0 ) ?  ($page_no-1) * $page_count : $search_start_num;

        for ($i = $startNum; $i < $totalRows; $i++) {

            if (!$noSearch) {
                $accessGroupId = (strpos((String)$data->access_group_infos[$i]->id, $searchStr) !== FALSE) ? TRUE : FALSE;
                $accessGroupName = (strpos((String)$data->access_group_infos[$i]->name, $searchStr) !== FALSE) ? TRUE : FALSE;
                $accessGroupBezDate = (strpos((String)$data->access_group_infos[$i]->bez_date, $searchStr) !== FALSE) ? TRUE : FALSE;
                $accessGroupEndDate = (strpos((String)$data->access_group_infos[$i]->end_date, $searchStr) !== FALSE) ? TRUE : FALSE;
                $accessGroupRegTime = (strpos((String)$data->access_group_infos[$i]->reg_time, $searchStr) !== FALSE) ? TRUE : FALSE;
            }

            if ( $accessGroupId || $accessGroupName || $accessGroupBezDate || $accessGroupEndDate || $accessGroupRegTime || $noSearch) {
                $str .= '<tr class="">';
                $str .= '<td class="py-2"><input type="checkbox" name="choice[]" value="'.$data->access_group_infos[$i]->id.'"></td>';
                $str .= ($noSearch) ? '<td class="py-2">'.($i+1).'</td>' : '<td class="py-2">'.( $searchRows+(($page_no-1)*$page_count) + 1 ).'</td>';
                $str .= '<td class="py-2">'.$data->access_group_infos[$i]->name.'</td>';
                $str .= '<td class="align-middle py-2">'.$data->access_group_infos[$i]->bez_date.'</td>';
                $str .= '<td class="py-2">'.$data->access_group_infos[$i]->end_date.'</td>';
                $str .= '<td class="py-2">'.$data->access_group_infos[$i]->reg_time.'</td>';
                $str .= '<td class="py-2">';
                $str .= '<div class="position-relative">';
                $str .= '<a class="link-dark d-inline-block" onclick="javascript: access_group_update('.$data->access_group_infos[$i]->id.',\'access-group\');" data-toggle="modal" data-target="#theModal" data-backdrop="static" keybord="false" href="#">';
                $str .= '<i class="gd-pencil icon-text"></i>';
                $str .= '</a>';
                $str .= '<a class="link-dark d-inline-block" onclick="javascript: goDelete('.$data->access_group_infos[$i]->id.',\'access-group\');" href="#">';
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
                $accessGroupId = (strpos((String)$data->access_group_infos[$i]->id, $searchStr) !== FALSE) ? TRUE : FALSE;
                $accessGroupName = (strpos((String)$data->access_group_infos[$i]->name, $searchStr) !== FALSE) ? TRUE : FALSE;
                $accessGroupBezDate = (strpos((String)$data->access_group_infos[$i]->bez_date, $searchStr) !== FALSE) ? TRUE : FALSE;
                $accessGroupEndDate = (strpos((String)$data->access_group_infos[$i]->end_date, $searchStr) !== FALSE) ? TRUE : FALSE;
                $accessGroupRegTime = (strpos((String)$data->access_group_infos[$i]->reg_time, $searchStr) !== FALSE) ? TRUE : FALSE;
            }

            if ( $accessGroupId || $accessGroupName || $accessGroupBezDate || $accessGroupEndDate || $accessGroupRegTime) {
                    $searchRows++;
            }
            $totalRows = $searchRows;
        }
        $totalPage = ($totalRows == 0) ? 1 : ceil($totalRows / $page_count);
        $strPage = "";
        for ($i = 1; $i <= $totalPage; $i++) {
            $active = ($i == $page_no) ? "active" : "" ;
            $strPage .= '<li class="page-item d-none d-md-block">';
            $strPage .= '<a id="datatablePagination'.$i.'" class="page-link '.$active.'" onclick="javascript: goPage('.$i.',\'access-group\');" href="#" data-dt-page-to="'.$i.'">'.$i.'</a>';
            $strPage .= '</li>';
        }
    }


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <!-- Title -->
    <title>type | Graindashboard UI Kit</title>

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
    <link rel="stylesheet" href="../public/graindashboard/css/jquery-ui.css" media="all"/>
    <link rel="stylesheet" href="../public/graindashboard/css/jquery-ui-timepicker-addon.css" media="all"/>
</head>
<style>
    .sub-sidebar-menu{float: left; width: 19.5%; left: -22px; height: 55rem;}
    .sub-sidebar-btn-wrap{float: left; width: 0.5%; left: -24px; height: 55rem; padding: 26rem 0; background-color: #f3f3f3;}
    .sub-sidebar-btn{background-color: #8b8e9f; font-size: 1px; height: 100%; color: #fff; padding: 16px 0; cursor: pointer;}
    .display-content{float: right; width: 80%;}

    table { display: block; }
    table * {text-align: center;}
    table th:nth-of-type(1){padding: 0.75rem;}
    table tbody { display: block; max-height: 531px; overflow: auto;}
    table thead, table thead tr, table tbody tr{display: block; width: 100%;}
    table th:nth-of-type(1), table td:nth-of-type(1) { width: 1%; }
    table th:nth-of-type(2), table td:nth-of-type(2) { width: 5%; }
    table th:nth-of-type(3), table td:nth-of-type(3) { width: 12%; }
    table th:nth-of-type(4), table td:nth-of-type(4) { width: 15%; }
    table th:nth-of-type(5), table td:nth-of-type(5) { width: 15%; }
    table th:nth-of-type(6), table td:nth-of-type(6) { width: 15%; }
    table th:nth-of-type(7), table td:nth-of-type(7) { width: 12%; }
</style>
<body class="has-sidebar has-fixed-sidebar-and-header">

<main class="main">

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
                                <a href="#">출입</a>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page">출입 그룹</li>
                        </ol>
                    </nav>
                    <!-- End Breadcrumb -->

                    <div class="mb-3 mb-md-4 d-flex justify-content-between">
                        <div class="h3 mb-0">출입 그룹</div>
                        <div style="padding-right: 5rem;"><i onclick="javascript: refresh('access-group');" class = "gd-reload h4" style = "cursor : pointer;"></i></div>
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
                                <div class="form-group col-8 col-md-10">
                                    <button type="button" onclick = "javascript: goSearch('access-group');" class="btn" style="position: relative; top: 32px; color: #fff; background-color: #ffc300; border-color: #ffc300;">검색</button>
                                    <button type="button" onclick="javascript:type_form_reset();" data-toggle="modal" data-target="#theModal" data-backdrop="static" keybord="false" class="btn" style="position: relative; top: 32px; color: #fff; background-color: #265df1; border-color: #265df1;">추가</button>
                                    <button type="button" onclick = "javascript: goDelete('more','access-group');" class="btn" style="position: relative; top: 32px; color: #fff; background-color: #ff6500; border-color: #ff6500;">삭제</button>
                                    
                                </div>
                            </div>
                            
                        </form>
                    </div>
                    <!-- End Form -->


                    <!-- type -->
                    <div style="height: 35rem; overflow: auto;">
                        <form name = "contents_show_form">
                            <table class="table text-nowrap mb-0">
                                <thead>
                                <tr>
                                    <th class="font-weight-semi-bold border-top-0 py-2"><input type="checkbox" id="checkAll"></th>
                                    <th class="font-weight-semi-bold border-top-0 py-2">No.</th>
                                    <th class="font-weight-semi-bold border-top-0 py-2">이름</th>
                                    <th class="font-weight-semi-bold border-top-0 py-2">시작 시간</th>
                                    <th class="font-weight-semi-bold border-top-0 py-2">종료 시간</th>
                                    <th class="font-weight-semi-bold border-top-0 py-2">등록 시간</th>
                                    <th class="font-weight-semi-bold border-top-0 py-2">Action</th>
                                </tr>
                                </thead>
                                <tbody id="access-group-content">
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
                                <button type = "button" id="datatablePaginationPrev" class="page-link" onclick="javascript: goPage('<?=$page_no-1?>','access-group');" aria-label="Previous">
                                    <i class="gd-angle-left icon-text icon-text-xs d-inline-block"></i>
                                </button>
                            </li>
                                <?=$strPage?>
                            <li class="page-item" id="page-next">
                                    <button type = "button" id="datatablePaginationNext" class="page-link" onclick="javascript: goPage('<?=$page_no+1?>','access-group');" aria-label="Next">
                                    <i class="gd-angle-right icon-text icon-text-xs d-inline-block"></i>
                                </button>
                            </li>
                        </ul>
                    </nav>
                    <div class="d-flex mb-2 mb-md-0 ml-3" style="color: #8a8a8a; float: left; cursor: pointer;" onclick="javascript: goPage('1','access-group');">처음으로</div>
                    <div class="d-flex mb-2 mb-md-0 ml-3" style="float: left;"><input type="text" class="form-control" id="search_page" name="search_page" value="<?=$page_no?>" style="width: 3rem; text-align: center; border: 1px solid #cccccc;"></div>
                    <div class="d-flex mb-2 mb-md-0 ml-3" style="float: left;"><button type="button" class="btn" style="color: #000; background-color: #f7f7f7; border-color: #cccccc;" onclick="javascript: goPage('search_page','access-group');">이동</button></div>
                    <div class="d-flex mb-2 mb-md-0 ml-3" style="color: #8a8a8a;" id = "total-row">총 <?=$totalRows?>개</div>
                    <div class="d-flex mb-2 mb-md-0 ml-3" style="float: left;">
                        <select id="page_count_change" onchange="javascript: changeCount('access-group');" style="border:1px solid #cccccc; color: #8a8a8a; height: 44px;">
                            <option value="10" <?if($page_count == "10") echo "selected"?>>10개 항목 / 페이지</option>
                            <option value="20" <?if($page_count == "20") echo "selected"?>>20개 항목 / 페이지</option>
                            <option value="30" <?if($page_count == "30") echo "selected"?>>30개 항목 / 페이지</option>
                            <option value="50" <?if($page_count == "50") echo "selected"?>>50개 항목 / 페이지</option>
                        </select>
                    </div>
                </div>

            </div>
            <!-- End type -->
        </div>

        <!-- Footer -->
        <?php include '../footer.php'?>
        <!-- End Footer -->
    </div>
</main>

<script src="../public/graindashboard/js/graindashboard.js"></script>
<script src="../public/graindashboard/js/graindashboard.vendor.js"></script>
<script src="../public/graindashboard/js/onepass.js"></script>
<script src="../public/graindashboard/js/jquery.min.js"></script>
<script src="../public/graindashboard/js/jquery-ui.min.js"></script>
<script src="../public/graindashboard/js/jquery-ui-timepicker-addon.js"></script>

<!-- DEMO CHARTS -->
<script src="../public/demo/resizeSensor.js"></script>
<script src="../public/demo/chartist.js"></script>
<script src="../public/demo/chartist-plugin-tooltip.js"></script>
<script src="../public/demo/gd.chartist-area.js"></script>
<script src="../public/demo/gd.chartist-bar.js"></script>
<script src="../public/demo/gd.chartist-donut.js"></script>

<script>
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
<?php


$deviceRs = get("/v1/devices");
$device = json_decode($deviceRs);

if ($device->error_code == 1) {
	$deviceStr = "";
	$totalRows = $device->device_count;
	for ($i=0; $i < $totalRows; $i++) { 
		$deviceStr .= '<label style="margin-right: 2rem;"><input type="checkbox" id="device_ids[]" name="device_ids" style="width: 20px; height: 12px;" value="'.$device->device_infos[$i]->id.'"><span>'.$device->device_infos[$i]->product_info->model_name.'</span></label>';
	}
}

$accTimeGroupRs = get("/v1/access-time-groups");
$accTimeGroup = json_decode($accTimeGroupRs);

if ($accTimeGroup->error_code == 1) {
	$accTimeGroupStr = "";
	$totalRows = count($accTimeGroup->access_time_group_infos);
	for ($i=0; $i < $totalRows; $i++) { 
		$accTimeGroupStr .= '<option value="'.$accTimeGroup->access_time_group_infos[$i]->id.'">'.$accTimeGroup->access_time_group_infos[$i]->name.'</option>';
	}
}
?>


	<!-- Modal -->
	<div class="modal fade" id="theModal" role="dialog" style="margin-top:5%;">
    <div class="modal-dialog rounded py-5" role="document" style="padding-top: 0!important; width: 50rem; left: 3rem;">
        <div class="modal-content py-5" style="padding-top: 10px!important; overflow: auto; height: 50rem;">
            <header class="modal-header flex-column justify-content-center" style="border-bottom: 1px solid #a7a7a7; padding-bottom: 0;">
           
            <div style="width: 100%;" class="modal_header22">
                    <h4 class="card-title">출입 그룹 생성
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="padding: 1.2rem 1rem 0 0;"><span aria-hidden="true">&times;</span></button>
                    </h4>
                </div>
            </header>                   
				<div class="modal__body">
                <div class="container-fluid pb-5">
				<div class="row justify-content-md-center">
					<div class="card-wrapper col-12 col-md-10 mt-3">
						<div class="brand text-center mb-3">
						</div>
						<div class="card">
							<div class="card-body">
								<form name = "access_insert_form" method = "POST">
									<input type="hidden"name="acc_group_info">
                             
									<div class="form-group">
										<label for="access_name">출입 그룹 이름</label>
										<input type="text" class="form-control" id="access_name" name="access_name">
									</div>

									<div class="form-group">
										<label for="bez_date">출입 그룹 시작 시간</label>
                                        <input type="text"  class="form-control" id="bez_date" name="bez_date">
                                        <div src=""></div>
									</div>

									<div class="form-group">
										<label for="end_date">출입 그룹 종료 시간</label>
										<input type="text" class="form-control" id="end_date" name="end_date">
									</div>

									<label >출입그룹 단말기 등록</label><br>
									<div class="form-group" style="border: 1px solid #eeeef1; padding: .63rem 1rem .23rem 1rem;" id="device-ids">
                                    <?=$deviceStr?>
									</div>

									<div class="form-group">
										<label for="acc_time_group_id">출입 시간 그룹</label>
										<select class="form-control" id="acc_time_group_id" name="acc_time_group_id">
											<?=$accTimeGroupStr?>
										</select>
									</div>
									
									<!-- <button type="button" onclick="javascript: go_acc_group_insert();" id="insert_btn" class="btn btn-primary btn-block">생 성</button> -->
                                   
								</form>
							</div>
						</div>
					</div>
				</div>

			</div>
			</div>
									
		
		</form>
                </div>
            </div>
        </div>

        <script>
            window.onload = function(){

             for (var i = 0; i < $(".ui-datepicker-trigger").length; i++) {

            $(".ui-datepicker-trigger").eq(i).attr('id', `datepicker${i}`);
        }
    }
    function type_form_reset() {
        var f = $("form[name='access_insert_form']").find("input[class=form-control]");
        for (let i = 0; i < f.length; i++) {
            f[i].value="";
        }
        $("#theModal .card-title").text("출입 그룹 생성");
        let closebtn = '<button type="button" class="close" data-dismiss="modal" aria-label="Close" style="padding: 1.2rem 1rem 0 0;"><span aria-hidden="true">&times;</span></button>';
        $(".card-title").append(closebtn);
        $("#typeInsertBtn").remove();
        $("#typeUpdateBtn").remove();
        var btn = '<button type="button" id="typeInsertBtn" onclick="javascript:go_acc_group_insert();" class="btn btn-primary btn-block">생 성</button>';
        $("form[name='access_insert_form']").append(btn);
    }

    var searchStr = '<?=$searchStr?>';
    var page_no = '<?=$page_no?>';
    var page_count = '<?=$page_count?>';
    page_count = Number(page_count);
    var search_start_num = '<?=$search_start_num?>';
    search_start_num = Number(search_start_num);
    var totalPage = '<?=$totalPage?>';
    totalPage = Number(totalPage);

    $.datepicker.setDefaults({
        dayNames:['월요일','화요일','수요일','목요일','금요일','토요일','일요일',],
        dayNamesMin: ['월','화','수','목','금','토','일'],
        monthNamesShort: ['1월','2월','3월','4월','5월','6월','7월','8월','9월','10월','11월','12월'],
        duration:"fast",
        changeYear:true,
        changeMonth:true,
        showOn:'button',
        buttonImage:"../public/graindashboard/css/img/icons8-schedule-48.png",
        buttonImageOnly:true,
        nextText:'다음 달',
        prevText:'이전 달',
        showButtonPanel: true,
        currentText:'오늘 날짜',
        closeText: '닫기',
        dateFormat:"yy-mm-dd",
        showMonthAfterYear:true
    });
$(function(){
    $("#bez_date, #end_date").datetimepicker({
        showSecond: true,
        timeFormat:'HH:mm:ss',
        controlType:'select',
        oneLine:true
    });

});

</script>

<style>
#datepicker0{
  position: absolute;
  top: 135px;
  right: 7px;
  transition: right 0.2s;
    cursor:pointer;
}
#datepicker1{
  position: absolute;
  top: 230px;
  right: 7px;
  transition: right 0.2s;
    cursor:pointer;
}
</style>