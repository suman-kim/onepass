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
                $str .= '<tr id="access_group_table" class="access_group_table" value="'.$data->access_group_infos[$i]->id.'" onclick="javascript: access_group_update('.$data->access_group_infos[$i]->id.',\'access-group\');table_csschg('.$data->access_group_infos[$i]->id.');" data-toggle="modal" data-target="#theModal" data-backdrop="static" keybord="false" href="#" >';
                $str .= '<td class="py-2"><input type="checkbox" name="choice[]" value="'.$data->access_group_infos[$i]->id.'"></td>';
                //$str .= ($noSearch) ? '<td class="py-2">'.($i+1).'</td>' : '<td class="py-2">'.( $searchRows+(($page_no-1)*$page_count) + 1 ).'</td>';
                $str .= '<td class="py-2">'.$data->access_group_infos[$i]->name.'</td>';
                //$str .= '<td class="align-middle py-2">'.$data->access_group_infos[$i]->bez_date.'</td>';
                //$str .= '<td class="py-2">'.$data->access_group_infos[$i]->end_date.'</td>';
                //$str .= '<td class="py-2">'.$data->access_group_infos[$i]->reg_time.'</td>';
                //$str .= '<td class="py-2">';
                $str .= '<div class="position-relative">';
                //$str .= '<a class="link-dark d-inline-block" onclick="javascript: access_group_update('.$data->access_group_infos[$i]->id.',\'access-group\');" data-toggle="modal" data-target="#theModal" data-backdrop="static" keybord="false" href="#">';
                //$str .= '<i class="gd-pencil icon-text"></i>';
                //$str .= '</a>';
                //$str .= '<a class="link-dark d-inline-block" onclick="javascript: goDelete('.$data->access_group_infos[$i]->id.',\'access-group\');" href="#">';
                //$str .= '<i class="gd-trash icon-text"></i>';
                //$str .= '</a>';
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
//단말기목록
$deviceRs = get("/v1/devices");
$device = json_decode($deviceRs);
$deviceRows = $device->device_count;

// if ($device->error_code == 1) {
// 	$deviceStr = "";
// 	$devicesRows = $device->device_count;
//         $deviceStr .= '<table class="table table-hover"> ';
//         $deviceStr .= '<thead>';
//         $deviceStr .= ' <tr style="background-color:#eeeef1;">';
//         $deviceStr .= '<th class="font-weight-semi-bold border-top-0 py-2"><input type="checkbox" id="checkAll2"></th>';
//         $deviceStr .= ' <th>이름</th>';
//         $deviceStr .= ' <th>모델명</th>';
//         $deviceStr .= ' <th>IP주소</th>';
//         $deviceStr .= ' <th>시리얼 번호</th>';
//         $deviceStr .= ' </tr>';
//         $deviceStr .= ' </thead>';
// 	for ($i=0; $i < $devicesRows; $i++) { 
//         $deviceStr .= ' <div style="overflow:auto;">';
//         $deviceStr .= ' <tbody>';
//         $deviceStr .= ' <tr>';
//         $deviceStr .= ' <td><input type="checkbox" id="device_ids" name="device_ids" style="width: 20px; height: 12px;" value="'.$device->device_infos[$i]->id.'"></td>';
//         $deviceStr .= ' <td>'.$device->device_infos[$i]->name.'</td>';
//         $deviceStr .= ' <td>'.$device->device_infos[$i]->product_info->model_name.'</td>';
//         $deviceStr .= ' <td>'.$device->device_infos[$i]->device_net_info->ip_addr.'</td>';
//         $deviceStr .= ' <td>'.$device->device_infos[$i]->product_info->serial_no.'</td>';
//         $deviceStr .= ' </tbody>';
//         $deviceStr .= ' </div>';
// 	}
// }

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
    .display-content{width:19.5%; float:left;}
    table * {text-align: center;}
    .input{display: initial; width:50%}
	.input_label{display: initial; width: 25%;}
</style>
<body class="has-sidebar has-fixed-sidebar-and-header">

<main class="main">

    <div class="content" style="height: 100%;">
        <div class="py-4 px-3 px-md-4">
        
            <div class="card mb-3 mb-md-4 display-content" id="type-content" style="height:55rem;left:-22px;">
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
                        <div class="title_fonts2">출입 그룹</div>
                    </div>

                    <!-- Form -->
                    <div>
                        <form name = "search_form" method = "GET">
                            <input type="hidden" name="search_start_num" value = "<?=$search_start_num?>">
                            <input type="hidden" name="page_count">
                            <div class="form-row">
                                <div class="form-group col-4 col-md-2">
                                    <section style="width:100px;height:32px;"></section>
                                    <input type="text" class="form-control" id="search_str" name="search_str" value = "<?=$searchStr?>" placeholder="검색" style="width:358%!important;">
                                </div>
                                
                                <div class="form-group col-8 col-md-10" style="padding-left:18%!important;">
                                    <button type="button" onclick = "javascript: goSearch('access-group');" class="btn con_fonts" style="position: relative; top: 32px; color: #fff; background-color: #ffc300; border-color: #ffc300;">검색</button>
                                    <button type="button" onclick="javascript:type_form_reset();" data-toggle="modal" data-target="#theModal" data-backdrop="static" keybord="false" class="btn con_fonts" style="position: relative; top: 32px; color: #fff; background-color: #265df1; border-color: #265df1;">추가</button>
                                    <button type="button" onclick = "javascript: goDelete('more','access-group');" class="btn con_fonts" style="position: relative; top: 32px; color: #fff; background-color: #ff6500; border-color: #ff6500;">삭제</button>
                                    
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
                                <tr style="">
                                    <th class="font-weight-semi-bold border-top-0 py-2"><input type="checkbox" id="checkAll"></th>
                                    <th class="font-weight-semi-bold border-top-0 py-2">이름</th>

                                </tr>
                                </thead>
                                <tbody id="access-group-content" style="cursor:pointer;">
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
                    <!-- <div class="d-flex mb-2 mb-md-0 ml-3" style="color: #8a8a8a; float: left; cursor: pointer;" onclick="javascript: goPage('1','access-group');">처음으로</div>
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
                    </div> -->
                </div>

            </div>
            <!-- End type -->

            <div class="card mb-3 mb-md-4" id="type-content" style="height:55rem;padding:10px;">
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
                    <div class="mb-3 mb-md-4 d-flex">
                    <h4 class="title_fonts2 view_title" style="width:100%;">출입 그룹 추가</h4>     
                    <div style="padding-right: 5rem;"><i onclick="javascript: refresh('access-group');" class = "gd-reload h4" style = "cursor : pointer;"></i></div>         
                    </div>
                        <div class="row">
                       
                            <div class="card-wrapper col-12 col-md-10 mt-3">
                               
                                <div class="brand text-center mb-3">
                                </div>
                                <div class="card" style="max-width:60%;">
                                    <div class="card-body" style="margin-top: 12px;">
                                    
                                        <form id="access_insert_form" name="access_insert_form" method = "POST">
                                            <input type="hidden"name="acc_group_info">
                                            <div class="form-group">
                                            <input class="form-control input_label" value="이름" disabled>
                                                <input type="text" class="form-control input" id="access_name" name="access_name">
                                            </div>

                                            <div class="form-group">
                                            <input class="form-control input_label" value="출입 기간" disabled>
                                                <input type="text"  class="form-control input" id="bez_date" name="bez_date" style="width:23.8%">
                                                ~
                                                <input type="text" class="form-control input" id="end_date" name="end_date" style="width:23.8%">
                                            </div>
                                            <div class="form-group">
                                            <input class="form-control input_label" value="출입 시간 그룹" disabled>
                                                <select class="form-control input" id="acc_time_group_id" name="acc_time_group_id">
                                                <?=$accTimeGroupStr?>
                                                </select>
                                            </div>

                                            <div class="title_fonts_sms" style="margin-top:1%;margin-bottom:1%;">단말기 등록</div>
                                            <div style="overflow:auto;max-height:400px;"> 
                                        <table style="margin-top:2%;" class="table table-hover"> 
                                        <thead>
                                         <tr style="background-color:#eeeef1;">
                                            <th class="font-weight-semi-bold border-top-0 py-2"><input type="checkbox" id="checkAll2"></th>
                                            <th>이름</th>
                                            <th>모델명</th>
                                            <th>IP주소</th>
                                            <th>시리얼번호</th>
                                            </tr>
                                            </thead>
                                            
                                            <?php for($i=0; $i<$deviceRows; $i++){?> 
                                            <tbody>
                                            <tr>
                                            <td><input type="checkbox" id="device_ids" name="device_ids" style="width: 20px; height: 12px;" value=<?=$device->device_infos[$i]->id?>></td>
                                            <td><?= $device->device_infos[$i]->name?></td>
                                            <td><?= $device->device_infos[$i]->product_info->model_name?></td>
                                            <td><?= $device->device_infos[$i]->device_net_info->ip_addr?></td> 
                                            <td><?= $device->device_infos[$i]->product_info->serial_no?></td>
                                            </tr>
                                            </tbody>
                                            <?php } ?>
                                            </table>
                                            </div>
                                            <!-- <button type="button" onclick="javascript: go_acc_group_insert();" id="insert_btn" class="btn btn-primary btn-block">생 성</button> -->
                                        </form>
                                        <div class="btn_append" style="text-align:center;">

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
            </div>

        </div>

</main>


<script src="../public/graindashboard/js/graindashboard.js"></script>
<script src="../public/graindashboard/js/onepass.js"></script>
<script src="../public/graindashboard/js/jquery.min.js"></script>
<script src="../public/graindashboard/js/jquery-ui.min.js"></script>
<script src="../public/graindashboard/js/jquery-ui-timepicker-addon.js"></script>

<!-- DEMO CHARTS -->
<script src="../public/demo/resizeSensor.js"></script>
<script src="../public/demo/chartist.js"></script>
<script src="../public/demo/chartist-plugin-tooltip.js"></script>

<iframe type="hidden" id="hiddenfrm" name="hiddenfrm" style="display: none;"></iframe>
</body>
</html>




	<!-- Modal -->
	<!-- <div class="modal fade" id="theModal" role="dialog" style="margin-top:5%;">
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
                                        <form id="access_insert_form" name="access_insert_form" method = "POST">
                                            <input type="hidden"name="acc_group_info">
                                            <div class="form-group">
                                                <label for="access_name">이름</label>
                                                <input type="text" class="form-control" id="access_name" name="access_name">
                                            </div>

                                            <div class="form-group">
                                                <label for="bez_date">시작 시간</label>
                                                <input type="text"  class="form-control" id="bez_date" name="bez_date">
                                                <div src=""></div>
                                            </div>

                                            <div class="form-group">
                                                <label for="end_date">종료 시간</label>
                                                <input type="text" class="form-control" id="end_date" name="end_date">
                                            </div>
                                            <div class="form-group">
                                                <label for="acc_time_group_id">출입 시간 그룹</label>
                                                <select class="form-control" id="acc_time_group_id" name="acc_time_group_id">
                                                  
                                                </select>
                                            </div>

                                           
                                            <label >단말기 등록</label><br>
                                            
                                           
                                            
                                             <button type="button" onclick="javascript: go_acc_group_insert();" id="insert_btn" class="btn btn-primary btn-block">생 성</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div> -->

<script>
    window.onload = function(){
        for (var i = 0; i < $(".ui-datepicker-trigger").length; i++) {
            $(".ui-datepicker-trigger").eq(i).attr('id', `datepicker${i}`);
        }
        
      var btn = '<button type="button" id="typeInsertBtn" onclick="javascript:go_acc_group_insert();" class="btn_success" style="margin-top: 5%;">저 장</button>';
        
        //$("form[name='access_insert_form']").append(btn);
        $('.btn_append').append(btn);
    }
    function type_form_reset() {
   
        $(".view_title").text("출입 그룹 추가");

        $('#access_name').val('');
        $('#bez_date').attr('value',`${nowdate}`);
        $('#end_date').attr('value',`${threedate}`);

        $("#acc_time_group_id").val("1").attr("selected",true);
        $("#checkAll2").prop("checked",false);

        var device_chk =  $('#access_insert_form').find('#device_ids');
        
      
        for(i=0; i<device_chk.length; i++){

        $("input[name='device_ids']").prop('checked',false);
        }

        $("#typeInsertBtn").remove();
        $("#typeUpdateBtn").remove();
        var btn = '<button type="button" id="typeInsertBtn" onclick="javascript:go_acc_group_insert();" class="btn_success" style="margin-top: 5%;">저 장</button>';
        //$("form[name='access_insert_form']").append(btn);
        $('.btn_append').append(btn);
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
       // showOn:'button',
       // buttonImage:"../public/graindashboard/css/img/icons8-schedule-48.png",
       // buttonImageOnly:true,
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

function table_csschg(id){

    var table = document.getElementById("access-group-content");
	var tr = table.getElementsByTagName("tr");
    
	for(var i=0; i<tr.length; i++){
		
        if(id == $(tr[i]).attr('value')){
            $(tr[i]).attr('style','background-color:#eeeef1;');
        }else if(id != $(tr[i]).attr('value')){

            
            $(tr[i]).attr('style','background-color:#fff;');
        }


	}
}




var today = new Date();

var year = today.getFullYear();
var month = ('0' + (today.getMonth() + 1)).slice(-2);
var day = ('0' + today.getDate()).slice(-2);

var dateString1 = year + '-' + month  + '-' + day;



var today = new Date();   

var hours = ('0' + today.getHours()).slice(-2); 
var minutes = ('0' + today.getMinutes()).slice(-2);
var seconds = ('0' + today.getSeconds()).slice(-2); 

var timeString = hours + ':' + minutes  + ':' + seconds;

//현재 시간 yy-mm-dd hh:mm:ss
var nowdate = dateString1 + timeString;


var threeYearLater = new Date(today.setFullYear(today.getFullYear() + 3));
var year = threeYearLater.getFullYear();
var month = ('0' + (threeYearLater.getMonth() + 1)).slice(-2);
var day = ('0' + threeYearLater.getDate()).slice(-2);

var dateString2 = year + '-' + month  + '-' + day;

//3년후 시간
var threedate = dateString2 + timeString;

var nowdate = nowdate.slice(0,10) + ' ' + nowdate.slice(10,18);
var threedate = threedate.slice(0,10) + ' ' + threedate.slice(10,18);

$('#bez_date').attr('value',`${nowdate}`);
$('#end_date').attr('value',`${threedate}`);

</script>

<style>
#datepicker0{
  position: absolute;
  right:120px;
  transition: right 0.2s;
    cursor:pointer;
}
#datepicker1{
  position: absolute;
  right:120px;
  transition: right 0.2s;
    cursor:pointer;
}

.access_group_table:hover{
    background-color: #eeeef1!important;
}


</style>