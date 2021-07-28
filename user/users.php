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
                $employee_no = (strpos(strtolower((String)$data->du_infos[$i]->secure_info->employee_no), strtolower($searchStr)) !== FALSE) ? TRUE : FALSE;
            }
        
            $pic_ox[$i] = $data->du_infos[$i]->secure_info->pic_size > 0 ? 'O' : 'X';
            $gender[$i] = $data->du_infos[$i]->secure_info->gender === true ? '여자' : '남자';
            $is_timecard[$i] =  $data->du_infos[$i]->secure_info->is_timecard ==1 ? 'O' : 'X';
            if($data->du_infos[$i]->secure_info->cardno_list[0] == "undefined"){
                $data->du_infos[$i]->secure_info->cardno_list[0] = "";
            }
            
            if ( $access_name || $name || $group_name || $job_name || $position_name || $type_name || $employee_no || $noSearch) {
                $str .= '<tr class="">';
                $str .= '<td class="py-2"><input type="checkbox" name="choice[]" value="'.$data->du_infos[$i]->id.'"></td>';
                $str .= ($noSearch) ? '<td class="py-2">'.(($i+($page_no*$page_count)-10)+1).'</td>' : '<td class="py-2">'.($searchRows+(($page_no-1)*$page_count) + 1 ).'</td>';
                $str .= '<td class="align-middle py-2">'.$data->du_infos[$i]->du_group->group_name.'</td>';
                //$str .= '<td class="py-2">'.$data->du_infos[$i]->du_access_group->access_name.'</td>';
                $str .= '<td class="py-2">'.$data->du_infos[$i]->secure_info->employee_no.'</td>';
                $str .= '<td class="py-2">'.$data->du_infos[$i]->name.'</td>';
                $str .= '<td class="py-2">'.$data->du_infos[$i]->du_job_position->name.'</td>';
                $str .= '<td class="py-2">'.$gender[$i].'</td>';
                $str .= '<td class="align-middle py-2">'.$data->du_infos[$i]->du_type->type_name.'</td>';
                $str .= '<td class="align-middle py-2">'.$data->du_infos[$i]->secure_info->cardno_list[0].'</td>';
                $str .= '<td class="py-2">'.$data->du_infos[$i]->private_info->email.'</td>';
                $str .= '<td class="py-2">'.$is_timecard[$i].'</td>';
                $str .= '<td class="py-2">'.$pic_ox[$i].'</td>';
                $str .= '<td class="py-2" id="progress_cur'.$data->du_infos[$i]->id.'"></td>';
                //$str .= '<td class="py-2">'.$data->du_infos[$i]->du_job_position->name.'</td>';
                //$str .= '<td class="py-2">'.$data->du_infos[$i]->du_type->type_name.'</td>';
                $str .= '<td class="py-2">';
                $str .= '<div class="position-relative">';
                $str .= '<a class="link-dark d-inline-block" onclick="javascript: user_update_info('.$data->du_infos[$i]->id.');" data-toggle="modal" data-target="#userModal" href="#">';
                $str .= '<i class="gd-pencil icon-text"></i>';
                $str .= '</a>';
                $str .= '<a class="link-dark d-inline-block" onclick="javascript: user_delete('.$data->du_infos[$i]->id.',\'users\');" href="#">';
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
                $employee_no = (strpos(strtolower((String)$data->du_infos[$i]->secure_info->employee_no), strtolower($searchStr)) !== FALSE) ? TRUE : FALSE;

                if ( $access_name || $name || $group_name || $job_name || $position_name || $type_name || $employee_no ) {
                        $searchRows++;
                }
            }
            $totalRows = $searchRows;
            $totalRows22 = $searchRows;
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
    // $userGroupsDataRs = str_replace('{"du_group_infos":[','[',$userGroupsDataRs);
    $userGroupsDataRs = str_replace('}],"error_code":1,"error_msg":"success","error_msg_detail":"","work_info":{"client_work_id":"","method":"GET","path":"/v1/user-groups","work_id":0}}','}]',$userGroupsDataRs);
    $userGroupsDataRs = str_replace('parent_id','pId',$userGroupsDataRs);


$accTimeGroupRs = get("/v1/access-time-groups");
$accTimeGroup = json_decode($accTimeGroupRs);

if ($accTimeGroup->error_code == 1) {
	$accTimeGroupStr = "";
	$totalRows = count($accTimeGroup->access_time_group_infos);
	for ($i=0; $i < $totalRows; $i++) {
		$accTimeGroupStr .= '<option value="'.$accTimeGroup->access_time_group_infos[$i]->id.'">'.$accTimeGroup->access_time_group_infos[$i]->name.'</option>';
	}
}

$userstotalRs = get("/v1/users-count");
$userstotal = json_decode($userstotalRs);
$userstotal = $userstotal->du_count;

$view_count = $page_no * $page_count;

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
    <!-- <script src="../public/graindashboard/js/jquery.min.js"></script>
    <script src="../public/graindashboard/js/jquery-ui.min.js"></script> -->
    <script src="../public/graindashboard/js/jquery-ui-timepicker-addon.js"></script>
    <link rel="stylesheet" href="../public/graindashboard/css/jquery-ui.css" media="all"/>
    <link rel="stylesheet" href="../public/graindashboard/css/jquery-ui-timepicker-addon.css" media="all"/>
  
</head>
<style>
    .sub-sidebar-menu{float: left; width: 19.5%; left: -22px; height: 55rem;}
    .sub-sidebar-btn-wrap{float: left; width: 0.5%; left: -24px; height: 55rem; padding: 26rem 0; background-color: #f3f3f3;}
    .sub-sidebar-btn{background-color: #8b8e9f; font-size: 1px; height: 100%; color: #fff; padding: 16px 0; cursor: pointer;}
    .display-content{float: right; width: 80%;}
    table * {text-align: center;}
	.input{display: initial; width: 50%;}
	.input_label{display: initial; width: 30%;}

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
                        <div class="title_fonts2">사용자 그룹</div>
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
                        <!-- <input id="testtest" value="zz"> -->
                        <div class="title_fonts2">사용자</div>
                        <div style="padding-right: 5rem;"><i onclick="javascript: refresh('users');" class = "gd-reload h4" style = "cursor : pointer;"></i></div>
                    </div>

                    <!-- Form -->
                    <div>
                        <form name = "search_form" method = "GET">
                            <input type="hidden" name="search_start_num" value = "<?=$search_start_num?>">
                            <input type="hidden" name="page_count">
                            <div class="form-row">
                                <div class="form-group col-4 col-md-2">
                                <section style="width:100px;height:32px;"></section>
                                    <input type="text" class="form-control" id="search_str" name="search_str" value = "<?=$searchStr?>" placeholder="검색">
                                </div>
                                <div class="form-group col-4 col-md-10">
                                    <button type="button" onclick = "javascript:goSearch('users');" class="btn con_fonts" style="position: relative; top: 32px; color: #fff; background-color: #ffc300; border-color: #ffc300;">검색</button>
                                    <button type="button" class="btn con_fonts" onclick="javascript: user_form_reset();" style="position: relative; top: 32px; color: #fff; background-color: #265df1; border-color: #265df1;" data-toggle="modal" data-target="#userModal" data-backdrop="static" keybord="false">추가</button>
                                    <button type="button" onclick = "javascript: goDelete('more','users');" class="btn con_fonts" style="position: relative; top: 32px; color: #fff; background-color: #ff6500; border-color: #ff6500;">삭제</button>
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
                                <tr >
                                    <th class="font-weight-semi-bold border-top-0 py-2"><input type="checkbox" id="checkAll"></th>
                                    <th class="font-weight-semi-bold border-top-0 py-2">번호</th>
                                    <th class="font-weight-semi-bold border-top-0 py-2">그룹(부서)</th>
                                    <th class="font-weight-semi-bold border-top-0 py-2">사번</th>
                                    <th class="font-weight-semi-bold border-top-0 py-2" style="width:1px;">이름</th>
                                    <th class="font-weight-semi-bold border-top-0 py-2">직급</th>
                                    <th class="font-weight-semi-bold border-top-0 py-2">성별</th>
                                    <th class="font-weight-semi-bold border-top-0 py-2">유형</th>
                                    <th class="font-weight-semi-bold border-top-0 py-2">카드번호</th>
                                    <th class="font-weight-semi-bold border-top-0 py-2">이메일</th>
                                    <th class="font-weight-semi-bold border-top-0 py-2">근태 유무</th>
                                    <th class="font-weight-semi-bold border-top-0 py-2">사진 유무</th>
                                    <th class="font-weight-semi-bold border-top-0 py-2">상태</th>
                                    <th class="font-weight-semi-bold border-top-0 py-2">동작</th>
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
                    <div class="d-flex mb-2 mb-md-0 ml-3 con_fonts" style="color: #8a8a8a; float: left; cursor: pointer;" onclick="javascript: goPage('1','users');">처음으로</div>
                    <div class="d-flex mb-2 mb-md-0 ml-3" style="float: left;"><input type="text" class="form-control" id="search_page" name="search_page" value="<?=$page_no?>" style="width: 3rem; text-align: center; border: 1px solid #cccccc;"></div>
                    <div class="d-flex mb-2 mb-md-0 ml-3" style="float: left;"><button type="button" class="btn con_fonts" style="color: #000; background-color: #f7f7f7; border-color: #cccccc;" onclick="javascript: goPage('search_page','users');">이동</button></div>
                    <div class="d-flex mb-2 mb-md-0 ml-3 con_fonts" style="color: #8a8a8a;" id = "total-row">총 <?= $noSearch ? $userstotal :  $totalRows22?>개</div>
                    <div class="d-flex mb-2 mb-md-0 ml-3" style="float: left;">
                        <select class="con_fonts" id="page_count_change" onchange="javascript: changeCount2('users');" style="border:1px solid #cccccc; color: #8a8a8a; height: 44px; ">
                            <option value="10" <?php if($view_count == 10){?>selected <?php }?>>10개 항목 / 페이지</option>
                            <option value="20" <?php if($view_count == 20){?>selected <?php }?>>20개 항목 / 페이지</option>
                            <option value="30" <?php if($view_count == 30){?>selected <?php }?>>30개 항목 / 페이지</option>
                            <option value="30" <?php if($view_count == 40){?>selected <?php }?>>40개 항목 / 페이지</option>
                            <option value="50" <?php if($view_count == 50){?>selected <?php }?>>50개 항목 / 페이지</option>
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
$deviceRs = get("/v1/devices");
$device = json_decode($deviceRs);
$deviceRows = $device->device_count;

// if ($device->error_code == 1) {
// 	$deviceStr = "";
// 	$devicesRows = $device->device_count;
//         $deviceStr .= '<table class="table table-hover"> ';
//         $deviceStr .= '<thead>';
//         $deviceStr .= ' <tr style="background-color:#eeeef1;">';
//         $deviceStr .= ' <th></th>';
//         $deviceStr .= ' <th>이름</th>';
//         $deviceStr .= ' <th>모델명</th>';
//         $deviceStr .= ' <th>ip주소</th>';
//         $deviceStr .= ' <th>시리얼 번호</th>';
//         $deviceStr .= ' </tr>';
//         $deviceStr .= ' </thead>';
// 	for ($i=0; $i < $devicesRows; $i++) { 
//         $deviceStr .= ' <tbody>';
//         $deviceStr .= ' <tr>';
//         $deviceStr .= ' <td><input type="checkbox" id="device_ids" name="device_ids" style="width: 20px; height: 12px;" value="'.$device->device_infos[$i]->id.'"></td>';
//         $deviceStr .= ' <td>'.$device->device_infos[$i]->name.'</td>';
//         $deviceStr .= ' <td>'.$device->device_infos[$i]->product_info->model_name.'</td>';
//         $deviceStr .= ' <td>'.$device->device_infos[$i]->device_net_info->ip_addr.'</td>';
//         $deviceStr .= ' <td>'.$device->device_infos[$i]->product_info->serial_no.'</td>';
//         $deviceStr .= ' </tbody>';
// 	}
// }


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
<div id="userModal" class="modal fade" role="dialog" aria-labelledby="exampleModalLabel" style="margin-top:3%;" aria-hidden="true">
<div class="modal-dialog rounded " role="document" style="padding-top: 0!important; width: 35rem; left: 3rem;">
        <div class="modal-content " style="padding-top: 10px 0!important; height: 50rem;">
        <header class="modal-header flex-column justify-content-center" style="border-bottom: 1px solid #a7a7a7;">
                    <div style="width: 100%;" class="modal_header22">
                    <div class="card-title title_fonts2" style="margin-bottom:0px;">사용자 추가 </div>
                    </div>
                </header>  
            <div class="modal-body pt-3" style="overflow: auto;">
                <div class="card">
                <div class="card-body">
			<form id="user_form" name="user_form" method = "POST">
            
            <div class="content_parent">

            <div class="content_child1" id="content_child1" onmouseout="" onmouseover="" onclick="javascript:conclick();pic_dele();">
            <input type="file" name="fic_file" id="fic_file" accept="image/*" style="display:none" onchange="previewImage(this);" />
            <div id="preview"></div>
            </div>
            <!-- <div class="content_child2" >
            사진버튼
            </div> -->
            <div class="content_child3">
            </div>
            <!-- <div class="content_child2">
            QR버튼
            </div> -->

                </div>
               <div>
                <div class="tap_parent tab_menu">

                <div class="tap_child tap_child1 is_on" href="#tab1">
                <div class="tap_btn  title_fonts3" href="#tab1" style="display:table-cell;vertical-align:middle;">일반</div>
                </div>
                
                <div class="tap_child tap_child2" href="#tab2">
                <div class="tap_btn title_fonts3" href="#tab2" style="display:table-cell;vertical-align:middle;">인증</div>
                </div>

                <div class="tap_child tap_child3" href="#tab3">
                <div class="tap_btn title_fonts3" href="#tab3" style="display:table-cell;vertical-align:middle;">출입 그룹</div>
                </div>

                <div class="tap_child tap_child4" href="#tab4">
                <div class="tap_btn title_fonts3" href="#tab4" style="display:table-cell;vertical-align:middle;">근태</div>
                </div>

                </div>
                            <div class="simple_cont cont_area" id="tab1">
                            <div class="form-group">
								<input class="form-control input_label" for="group_id" value="부서(그룹)" disabled>
                                <select class="form-control input input_small" id="group_id" name="group_id">
									<?=$userGroupsStr?>
								</select>
                                <button type="button" class="input_btn con_fonts" id="input_btn" onclick="javascript:userGroupSet('add',0);"  data-toggle="modal" data-backdrop="static" keybord="false" href="#"  data-target="#userGroupModal" >추가</button>
							</div>
                            
                            <div class="form-group">
                            <input class="form-control input_label" for="employee_no" value="사번" disabled>
							<input type="number" class="form-control input" id="employee_no" name="employee_no">
							</div>

							<div class="form-group">
                            <input class="form-control input_label" for="user_name" value="이름" disabled>
                            <input type="text" class="form-control input" id="user_name" name="user_name" value="">
							</div>

							<div class="form-group">
                            <input class="form-control input_label" for="job_position_id" value="직급" disabled>
								<select class="form-control input input_small" id="job_position_id" name="job_position_id">
									<?=$jobPositionsStr?>
								</select>
                                <button type="button" class="input_btn con_fonts" id="input_btn" onclick=""  data-toggle="modal" data-backdrop="static" keybord="false" href="#"  data-target="#userJopPositionModal" >추가</button>
							</div>

							<div class="form-group">
                            <input class="form-control input_label" for="job_group_id" value="직군" disabled>
								<select class="form-control input input_small" id="job_group_id" name="job_group_id">
                                <?=$jobGroupsStr?>
								</select>
                                <button type="button" class="input_btn con_fonts" id="input_btn" onclick=""  data-toggle="modal" data-backdrop="static" keybord="false" href="#"  data-target="#userJobGroupModal" >추가</button>
							</div>

                            <div class="form-group">
                            <input class="form-control input_label" for="birthday" value="생년월일" disabled>
                            <input type="date" class="form-control input" value="" id="birthday" name="birthday">
							</div>

                            <div class="form-group">
                            <input class="form-control input_label" for="phone" value="연락처" disabled>
                            <input type="text" class="form-control input" id="phone" name="phone" >
							</div>
                            <div class="form-group">
                            <input class="form-control input_label" for=" home_addr" value="주소" disabled>
                            <input type="text" class="form-control input" id="home_addr" name="home_addr" >
							</div>
                           
                            <div class="form-group">
                            <input class="form-control input_label" for="email" value="이메일" disabled>
                            <input type="email" class="form-control input" id="email" name="email">
							</div>
                            
                            <div class="form-group">
                            <input class="form-control input_label" for="join_company" value="입사일" disabled>
                            <input type="date" class="form-control input" value="" id="join_company" name="join_company" >
							</div>

                            <div class="form-group">
                            <input class="form-control input_label" for="leave_company" value="퇴사일" disabled>
                            <input type="date" class="form-control input" value="" id="leave_company" name="leave_company" >
							</div>


                            <div class="form-group">
                            <input class="form-control input_label" for="gender" value="성별" disabled>
								<select class="form-control input" id="gender" name="gender">
									<option value="false">남자</option>
                                    <option value="true">여자</option>
								</select>
							</div>

                            <div class="form-group">
                            <input class="form-control input_label" for="type_id" value="사용자 유형" disabled>
								<select class="form-control input input_small" id="type_id" name="type_id">
                                    <?=$userTypesStr?>
								</select>
                                <button type="button" class="input_btn con_fonts" id="input_btn" onclick=""  data-toggle="modal" data-backdrop="static" keybord="false" href="#"  data-target="#userTypeModal" >추가</button>
							</div>

                            <div class="form-group">
                            <input class="form-control input_label" for="" value="근태" disabled>
                            <div class="con_fonts" style="display:initial;margin-left:1%;">
                                <input type="checkbox" name="is_timecard" id="is_timecard" value=1><span style="margin-left:1%;">근태 사용</span>
                            </div>
							</div>
                            <div style="display: initial;margin-left: 37%;">
                            <!-- <input  type="checkbox"><span style="margin-left:1%;"> 닫지 않고 연속해서 추가하기</span>
                            <button type="button" href="#tab2" id="btn_popbz" class="btn_popbz">다 음</button> -->
                            </div>

</div>                          
<div class="cert_cont cont_area" id="tab2">

                        <div class="cert_content1" style="margin-bottom:3%;">
            
                           
                            <div class="title_fonts_sms" style="margin-bottom:1%;">인증 유효 기간</div>
                          
                            <div class="con_fonts" style="margin-left:2%;">
                            <input type="checkbox" name="cert_chk" onclick="javascript:cert_chkeve();" id="cert_chk" value="cert"><span style="margin-left:1%;">사용 하기</span></div>
							<div class="form-group">
                            <input class="form-control input_label" style="margin-top:1%;" for="access_time_bez" value="시작일" disabled>
                            <input type="datetime" class="form-control input" value="" id="access_time_bez" name="access_time_bez" style="background-color:#fff;color:#fff" disabled>
							</div>
                            <div class="form-group">
                            <input class="form-control input_label" for="access_time_end" value="종료일" disabled>
                            <input type="datetime" class="form-control input" value="" id="access_time_end" name="access_time_end" style="background-color:#fff;color:#fff" disabled>
							</div>
                        </div>

                                            
                        <div class="cert_content1" style="margin-bottom:3%;">
                        
                            <div class="title_fonts_sms" style="margin-bottom:1%;">카드 정보</div>
							<div class="form-group">
                            <input class="form-control input_label" for="" value="카드 유형" disabled>
                           
                            <select class="form-control input" id="" name=""style="background-color:white!important;" disabled>
									<option value="일반카드">일반 카드</option>
							</select>
							</div>
							<div class="form-group">
                            <input class="form-control input_label" for="" value="카드 정보" disabled>
								<input type="text" class="form-control input" id="cardno_list" name="cardno_list">
							</div>
                            <button type="button" href="#tab3" style="margin-left: 40%;" class="btn_popbz2 con_fonts">수동 입력</button>
                            <button type="button" href="#tab3" class="btn_popbz2 con_fonts">저장</button>
                            
                        </div>  
							<div class="form-group">
                            <input class="form-control input_label" for="" value="인증 비밀번호" disabled>
								<input type="password" class="form-control input" id="" name="">
							</div>

                            <div style="display: initial;margin-left: 37%;">
                            <!-- <input  type="checkbox"><span style="margin-left:1%;"> 닫지 않고 연속해서 추가하기</span>
                            <button type="button" href="#tab3" class="btn_popbz">다 음</button> -->
                            </div>
                            
</div>
<div class="acce_cont cont_area" id="tab3">
							<div class="form-group" >
                            <input class="form-control input_label" for="access_id" value="출입 그룹" disabled>
                            <select class="form-control input input_small " onchange="javascript:accgroup_device_list();" id="access_id" name="access_id">
                                <?=$accGroupsStr?>
							</select>
                            <button type="button" class="input_btn con_fonts" id="input_btn" onclick=""  data-toggle="modal" data-backdrop="static" keybord="false" href="#"  data-target="#theModal" >추가</button>
							</div>
                            <div class="cert_content1" style="margin-bottom:3%;">
                            
                            <div class="title_fonts_sms">단말기 목록</div>
                            
                            <div class="table_app2" style="overflow:auto;height:400px;"></div>    
               
                            </div>
                            <div style="display: initial;margin-left: 37%;">
                            <!-- <input  type="checkbox"><span style="margin-left:1%;"> 닫지 않고 연속해서 추가하기</span>
                            <button type="button" href="#tab4" class="btn_popbz">다 음</button> -->
                            </div>
                            
</div>

                            <div class="atte_cont cont_area" id="tab4">
                            <div class="form-group" >
                            <input class="form-control input_label" for="" value="근태 규칙" disabled>
                            <select class="form-control input" id="" name="">
									<option>기본</option>
							</select>
                            </div>
                            <div class="cert_content1" style="margin-bottom:3%;">
                            
                            <div class="title_fonts_sms">휴가/출장 목록</div>
                            <table style="margin-top:2%;width:100%" class="table table-hover"> 
                                <thead>
                                <tr style="background-color:#eeeef1;">
                                    <th>NO.</th>
                                    <th>명칭</th>
                                    <th>유형</th>
                                    <th>시작일</th>
                                    <th>종료일</th>
                       
                                    </tr>
                                 </thead> 
                                 <tbody> 
                                <tr> 
                                    <td>1</td>
                                    <td>개인사정</td>
                                    <td>연차</td> 
                                    <td>2021-06-22</td>
                                    <td>2021-06-23</td>
                     
                                </tr>
                                </tbody>
                                </table>
                        
                                <section style="height:30px;"></section>
                            <div class="form-group">
								<input class="form-control input_label" for="" value="휴가 명칭" disabled>
								<input type="text" class="form-control input" id="" name="">
							</div>
                            <div class="form-group" >
                            <input class="form-control input_label" for="" value="휴가 유형" disabled>
                            <select class="form-control input" id="" name="">
									<option>반차</option>
                                    <option>연차</option>
                                    <option>병가</option>
							</select>
                            </div>      
                            <div class="form-group">
								<input class="form-control input_label" for="" value="휴가 기간" disabled>
								<input type="date" class="form-control input" id="holi_start" name="" style="width:33%!important;font-size:14px;">
                                <div style="display:initial;">~</div>
                                <input type="date" class="form-control input" id="holi_end" name="" style="width:33%!important;font-size:14px;">
							</div>
                            <button type="button" href="#tab3" style="margin-left:38%;" class="btn_popbz2 con_fonts">추가</button>
                            <button type="button" href="#tab3" class="btn_popbz2 con_fonts">수정</button>
                            <button type="button" href="#tab3" class="btn_popbz2 con_fonts">삭제</button>
                            </div>
                            
                            <div style="display: initial;margin-left: 37%;">
                            <!-- <input  type="checkbox"><span style="margin-left:1%;"> 닫지 않고 연속해서 추가하기</span> -->

                            </div>
                            
</div>
        <div style="text-align: center;">
        <button type="button" id="userInsertBtn" onclick="javascript:go_user_insert2('job');" class="btn_success">저 장</button>
        </div>
						</form>
					</div>
				</div>
            </div>
</div>
            <!-- <footer class="modal-footer justify-content-between border-0">
                <p>Modal footer text goes here.</p>
            </footer> -->
        </div>
    </div>
</div>

<!-- End Modal -->

<ul class="contextmenu">
    <li><a id="adduser" data-toggle="modal" data-target="#userGroupModal"  data-dismiss="modal" data-backdrop="static" keybord="false" href="#" >추가</a></li>
</ul>
<script>


    function user_form_reset() {
        
        $('#imgatt').remove();

        $("#userModal .card-title").text("사용자 추가");
        let closebtn = '<button type="button" class="close" data-dismiss="modal" aria-label="Close" style="padding: 1.2rem 1rem 0 0;"><span aria-hidden="true">&times;</span></button>';
        $(".card-title").append(closebtn);
        // $("#userUpdateBtn").remove();
        // $("#userInsertBtn").remove();
        // var btn = '<button type="button" id="userInsertBtn" onclick="javascript: go_user_insert();" class="btn btn-primary btn-block">생 성</button>';
        // $("form[name='user_form']").append(btn);
        var job = 'add';
        $('#userInsertBtn').attr('onclick','javascript:go_user_insert2(\'' + job + '\');');
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

    console.log(userGroupsDataRs);
    //alert(userGroupsDataRs);
    var zNodes = JSON.parse(userGroupsDataRs);
   
        // zTree 초기화
        $.fn.zTree.init($("#ztree"), setting, zNodes.du_group_infos);
        var treeObj = $.fn.zTree.getZTreeObj("ztree");
        treeObj.expandAll(true);
        
        
        for (let i = 0; i < $("#ztree .node_name").length; i++) {
            
            var userGroup = zNodes.du_group_infos.filter(function (e) {
                return e.name == $("#ztree .node_name").eq(i).text();
            });
            $("#ztree .node_name").eq(i).attr("userId", userGroup[0].id);
           
        }
    });
</script>

<!-- 사용자 그룹 추가,수정시 나오는 모달 -->
        <div id="userGroupModal" class="modal fade" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" style="background: rgba(0, 0, 0, 0.5);">
    <div class="modal-dialog rounded" role="document" style="top: 15rem; padding: 0!important; width: 40rem;">
        <div class="modal-content" style="">
            <header class="modal-header flex-column justify-content-center" style="border-bottom: 1px solid #a7a7a7; ">
                <div style="width: 100%;">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="padding: 1.2rem 1rem 0 0;"><span aria-hidden="true">&times;</span></button>
                    <div class="title_fonts">사용자 그룹 추가</div>
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
								<input type="text" class="form-control input" id="groupName" name="groupName">
							</div>
						</form>
					</div>
                    <div style="text-align: center;">
                    <button type="button" id="userGroupBtn" onclick="javascript: go_user_group_insert2('user-group');" class="btn_success">저 장</button>
                    </div>
				</div>
            </div>
        </div>
    </div>
</div>
<!-- End Modal -->


<!-- 사용자 직급 추가 모달 --> 
<div id="userJopPositionModal" class="modal fade" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" style="background: rgba(0, 0, 0, 0.5);">
    <div class="modal-dialog rounded" role="document" style="top: 15rem; padding: 0!important; width: 40rem;">
        <div class="modal-content" style="">
            <header class="modal-header flex-column justify-content-center" style="border-bottom: 1px solid #a7a7a7; ">
                <div style="width: 100%;">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="padding: 1.2rem 1rem 0 0;"><span aria-hidden="true">&times;</span></button>    
                <div class="title_fonts">사용자 직급 추가</div>
                </div>
            </header>
            <div class="modal-body pt-3">
                <div class="card">
                    <div class="card-body">
                    <form name = "job_positions_form" method = "POST">
							<div class="form-group">
                            <input class="form-control input_label" value="직급 이름" disabled>
								<input type="text" class="form-control input" id="job_positions_name" name="job_positions_name">
							</div>
						</form>
					</div>
                    <div style="text-align: center;">
                    <button type="button" id="jobPositionInsertBtn" onclick="javascript: go_user_job_position_insert();" class="btn_success">저 장</button>
                    </div>
				</div>
            </div>
        </div>
    </div>
</div>
<!-- End Modal -->

<!-- 사용자 직군 추가 모달 -->
<div id="userJobGroupModal" class="modal fade" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" style="background: rgba(0, 0, 0, 0.5);">
    <div class="modal-dialog rounded" role="document" style="top: 15rem; padding: 0!important; width: 40rem;">
        <div class="modal-content" style="">
            <header class="modal-header flex-column justify-content-center" style="border-bottom: 1px solid #a7a7a7; ">
                <div style="width: 100%;">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="padding: 1.2rem 1rem 0 0;"><span aria-hidden="true">&times;</span></button>    
                <div class="title_fonts">사용자 직군 추가</div>
                </div>
            </header>
            <div class="modal-body pt-3">
                <div class="card">
                    <div class="card-body">
                    <form name = "job_groups_form" method = "POST">
							<div class="form-group">
                            <input class="form-control input_label" value="직군 이름" disabled>
								<input type="text" class="form-control input" id="job_groups_name" name="job_groups_name">
							</div>
							
						</form>
					</div>
                    <div style="text-align: center;">
                    <button type="button" id="jobGroupInsertBtn" onclick="javascript: go_user_job_group_insert();" class="btn_success">저 장</button>
                    </div>
				</div>
            </div>
        </div>
    </div>
</div>
<!-- End Modal -->

<!-- 사용자 유형 추가 모달 -->
<!-- <div id="userTypeModal" class="modal fade" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" style="background: rgba(0, 0, 0, 0.5);">
    <div class="modal-dialog rounded" role="document" style="top: 15rem; padding: 0!important; width: 40rem;">
        <div class="modal-content" style="">
            <header class="modal-header flex-column justify-content-center" style="border-bottom: 1px solid #a7a7a7; ">
                <div style="width: 100%;">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="padding: 1.2rem 1rem 0 0;"><span aria-hidden="true">&times;</span></button>    
                <div class="title_fonts">사용자 유형 추가</div>
                </div>
            </header>
            <div class="modal-body pt-3">
                <div class="card">
                    <div class="card-body">
                    <form name = "type_form" method = "POST">
							<div class="form-group">
                            <input class="form-control input_label" value="유형 이름" disabled>
								<input type="text" class="form-control input" id="type_name" name="type_name">
							</div>

							<div class="form-group">
                            <input class="form-control input_label" value="Level" disabled>
								<input type="text" class="form-control input" id="level" name="level">
							</div>

							<div class="form-group">
                            <input class="form-control input_label" value="Is Admin" disabled>
								<select class="form-control input" id="is_admin" name="is_admin">
									<option value="True">True</option>
									<option value="False">False</option>
								</select>
							</div>

						
						</form>
					</div>
                    <div style="text-align: center;">
                    <button type="button" id="typeInsertBtn" onclick="javascript: go_user_type_insert();" class="btn_success">저 장</button>
                    </div>
				</div>
            </div>
        </div>
    </div>
</div> -->
<!-- End Modal -->


<!-- 출입 그룹 생성 모달 -->
<div class="modal fade" id="theModal" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" style="background: rgba(0, 0, 0, 0.5);" >
        <div class="modal-dialog rounded" role="document" style="padding-top: 0!important; width: 35rem;top: 10rem;">
            <div class="modal-content" style="padding-top: 0px!important;">
                <header class="modal-header flex-column justify-content-center" style="border-bottom: 1px solid #a7a7a7; ">
                    <div style="width: 100%;" class="modal_header22">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="padding: 1.2rem 1rem 0 0;"><span aria-hidden="true">&times;</span></button>    
                    <div class="title_fonts">출입 그룹 추가</h4>
                    </div>
                </header>                   
                <div class="modal__body" style="overflow: auto;">
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
                                            <input class="form-control input_label" value="이름" disabled>
                                                <input type="text" class="form-control input" id="access_name" name="access_name">
                                            </div>

                                            <div class="form-group">
                                            <input class="form-control input_label" value="시작 시간" disabled>
                                                <input type="datetime"  class="form-control input" id="bez_date" name="bez_date">
                                                <div src=""></div>
                                            </div>

                                            <div class="form-group">
                                            <input class="form-control input_label" value="종료 시간" disabled>
                                                <input type="datetime" class="form-control input" id="end_date" name="end_date">
                                            </div>
                                            <div class="form-group">
                                            <input class="form-control input_label" value="출입 시간 그룹" disabled>
                                                <select class="form-control input" id="acc_time_group_id" name="acc_time_group_id">
                                                <?=$accTimeGroupStr?>
                                                </select>
                                            </div>

                                            <div class="title_fonts_sms">단말기 등록</div>
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
                                           
                                            </div>
                                        </form>
                                    </div>
                                    <div style="text-align: center;">
                                    <button type="button" onclick="javascript: go_acc_group_insert('users');" id="insert_btn" class="btn_success" style="margin-top:5%;">저 장</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <iframe id="subscribe" src="../phpMQTT/subscribe.php" width="500" height="0"></iframe>




<script>
  const tabList = document.querySelectorAll(".tap_child");
  const contents = document.querySelectorAll('.cont_area');
  const btn = document.querySelectorAll(".btn_popbz");
    
  let activeCont = ''; // 현재 활성화 된 컨텐츠 (기본:#tab1 활성화)

  for(var i = 0; i < tabList.length; i++){
  
    tabList[i].querySelector('.tap_btn').addEventListener('click', function(e){
    
      e.preventDefault();
      for(var j = 0; j < tabList.length; j++){
        // 나머지 버튼 클래스 제거
        tabList[j].classList.remove('is_on');

        // 나머지 컨텐츠 display:none 처리
        contents[j].style.display = 'none';
      }

      // 버튼 관련 이벤트
      this.parentNode.classList.add('is_on');

      // 버튼 클릭시 컨텐츠 전환
      activeCont = this.getAttribute('href');
      document.querySelector(activeCont).style.display = 'block';
    });
  }
  for(var i =0; i< btn.length; i++){

      btn[i].onclick = function(e){
        e.preventDefault();
        for(var j = 0; j < btn.length; j++){
            tabList[j].classList.remove('is_on');
            
            contents[j].style.display = 'none';
            }
            
           
        activeCont = this.getAttribute('href');
        document.querySelector(activeCont).style.display = 'block';
        
        var activec = $(`.tap_child1`).attr('href');
        var activecc = $(`.tap_child2`).attr('href');
        var activeccc = $(`.tap_child3`).attr('href');
        var activecccc = $(`.tap_child4`).attr('href');
        if(activeCont == activec){
            $('.tap_child1').addClass('is_on');
        }
        else if(activeCont == activecc){
            $('.tap_child2').addClass('is_on');
        }
        else if(activeCont == activeccc){
            $('.tap_child3').addClass('is_on');
        }
        else if(activeCont == activecccc){
            $('.tap_child4').addClass('is_on');
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

                 
function cert_chkeve(){
    
   if($('#cert_chk').is(":checked") == true){
    
    $('#access_time_bez').attr('disabled',false);
    $('#access_time_end').attr('disabled',false);
    $('#access_time_bez').attr('style','');
    $('#access_time_end').attr('style','');
    $('#access_time_bez').attr('value',`${nowdate}`);
    $('#access_time_end').attr('value',`${threedate}`);

    }else if($('#cert_chk').is(":checked") == false){
    
    $('#access_time_bez').attr('disabled',true);
    $('#access_time_end').attr('disabled',true);
    $('#access_time_bez').attr('style','background-color:#fff;color:#fff');
    $('#access_time_end').attr('style','background-color:#fff;color:#fff');
    $('#access_time_bez').attr('value','');
    $('#access_time_end').attr('value','');

    }
 }
                        
                  


//input file 지우기위함
function conclick(){
 document.user_form.fic_file.click();
};

//사진업로드
function previewImage(f){

var file = f.files;
var maxSize  = 200000 //200kb
// 확장자 체크
if(file[0].name != ""){
if(!/\.(gif|jpg|jpeg|png)$/i.test(file[0].name)){
    alert('gif, jpg, png 파일만 선택해 주세요.\n\n현재 파일 : ' + file[0].name);
    // 선택한 파일 초기화
    //f.outerHTML = f.outerHTML;
    //document.getElementById('preview').innerHTML = '';
}
else if(file[0].size > maxSize){
    alert("파일 크기는 200kb 이하로 등록 가능합니다");
}
else {

    // FileReader 객체 사용
    var reader = new FileReader();
    // 파일 읽기가 완료되었을때 실행
    reader.onload = function(rst){
    
    var img = document.createElement("img");
    img.src = rst.target.result;
    var canvas = document.createElement("canvas");      

    var ctx = canvas.getContext("2d");
    ctx.drawImage(img, 0, 0);
   
    var MIN_WIDTH = 400;
    var MIN_HEIGHT = 400;
    var width = img.width;
    var height = img.height;


        if (width < MIN_WIDTH) {
      
            //height *= MIN_WIDTH / width;
            width = MIN_WIDTH;
        }
        if (height < MIN_HEIGHT) {
            //width *= MIN_HEIGHT / height;
            height = MIN_HEIGHT;
        }
        if (width > MIN_WIDTH) {
      
            //height *= MIN_WIDTH / width;
            width = MIN_WIDTH;
        }
        if (height > MIN_HEIGHT) {
            //width *= MIN_HEIGHT / height;
            height = MIN_HEIGHT;
            }
    
    canvas.width = width;
    canvas.height = height;

    var ctx = canvas.getContext("2d");
   
    ctx.drawImage(img, 0, 0, width, height);
    
    var dataurl = canvas.toDataURL("image/png");
  
    
    //var res = rst.target.result;
    document.getElementById('preview').innerHTML = '<img id="imgatt" onclick="javascript:pic_dele();" size="" style="width:200px;height:200px;" src="' + rst.target.result + '">';
    //부모요소 res변수 넣기위함
    $('#content_child1').attr('onmouseout','javascript:pic_out(\'' + rst.target.result + '\');');
    $('#content_child1').attr('onmouseover','javascript:pic_over();');
    $('#imgatt').attr('size',`${file[0].size}`);
    //console.log(file[0].name);
}
    // 파일을 읽는다
    reader.readAsDataURL(file[0]);
    }
  }
}
function pic_over(){
   $('#imgatt').attr('src','');
   $('#imgatt').attr('style','');
}
function pic_out(res){
        $('#imgatt').attr('src',`${res}`);
        $('#imgatt').attr('style','width:200px;height:200px;');
}
function pic_dele(){
    $('#imgatt').remove();
}

window.onload = function(){
        for (var i = 0; i < $(".ui-datepicker-trigger").length; i++) {
            $(".ui-datepicker-trigger").eq(i).attr('id', `datepicker${i}`);
        }
        //go_user_insert2();
        //$("body").append('<iframe id="subscribe" src="../phpMQTT/subscribe.php" width="500" height="0"></iframe>');
        accgroup_device_list();
    
    }
$.datepicker.setDefaults({
        minDate: '-100y',
        dayNames:['월요일','화요일','수요일','목요일','금요일','토요일','일요일',],
        dayNamesMin: ['월','화','수','목','금','토','일'],
        monthNamesShort: ['1월','2월','3월','4월','5월','6월','7월','8월','9월','10월','11월','12월'],
        duration:"fast",
        changeYear:true,
        changeMonth:true,
        //showOn:'button',
        //buttonImage:"../public/graindashboard/css/img/icons8-schedule-48.png",
        //buttonImageOnly:true,
        nextText:'다음 달',
        prevText:'이전 달',
        showButtonPanel: true,
        currentText:'오늘 날짜',
        closeText: '닫기',
        dateFormat:"yy-mm-dd",
        showMonthAfterYear:true
    });
// $(function(){
//     $("#holi_start,#holi_end,#birthday,#join_company,#leave_company").datetimepicker({
//         showSecond: true,
//         timeFormat:'HH:mm:ss',
//         controlType:'select',
//         oneLine:true
//     });
// });

$(function(){
    $("#bez_date, #end_date,#access_time_bez,#access_time_end").datetimepicker({
        showSecond: true,
        timeFormat:'HH:mm:ss',
        controlType:'select',
        oneLine:true
    });
});


$('#access_time_bez').attr('value',`${dateString1}`);
$('#access_time_end').attr('value',`${dateString1}`);

$('#birthday').attr('value',`${dateString1}`);
$('#join_company').attr('value',`${dateString1}`);
$('#leave_company').attr('value',`${dateString2}`);

$('#bez_date').attr('value',`${nowdate}`);
$('#end_date').attr('value',`${threedate}`);
</script>

<style>
.left {
    width:160px; 
    height:100px;
    background-color:red; 
    float:left;
}
.content_parent{
    display:flex;
    padding-bottom:5%;
}
.content_child1{
    width:200px;
    height:200px; 
    border:1px solid #eeeef1;
    float:left;
    
    cursor: pointer;
    align-items: center;
    justify-content: center;
    display: flex;
    background-image:url('../public/graindashboard/css/img/icons8-image-48.png');
    background-repeat:no-repeat;
    background-position: center;
}
.content_child3{
    width:200px;
    height:200px; 
    border:1px solid #eeeef1;
    float:left;
    margin-left: 20%;
    cursor: pointer;
    align-items: center;
    justify-content: center;
    display: flex;
    background-image:url('../public/graindashboard/css/img/icons8-qr-code-48.png');
    background-repeat:no-repeat;
    background-position: center;
}
.content_child2{
    width:30px;
    height:150px;
    border:1px solid green;
    float:left;
}
.tap_parent{
    display:flex; 
    padding-bottom:3%;
    width: 100%;
}
.tap_child{
    cursor:pointer;
    width:100%;
    height:45px;
    border:1px solid #eeeef1;
    display:table;
    text-align:center;
}
.is_on{ font-weight:bold;     
        color: #fff;
        background-color: #0f48e2;
        border-color: #0e44d6;}
#tab2,#tab3,#tab4{display:none}

.btn_popbz{
    width: 30%;
    height: 40px;
    color: #fff;
    background-color: #0f48e2;
    border-color: #0e44d6;
    
    margin-top:2%;
    
}

.btn_popbz2{
    width: 20%;
    height: 30px;
    color: #fff;
    background-color: #0f48e2;
    border-color: #0e44d6;
    margin-bottom:1%;
    margin-top:2%;
    border: 1px solid transparent;
    
}


legend{
    font-size:18px;
    font-weight:700;
}
.input_small{
    width: 37%!important;
   
}
.input_btn{
    color: #fff;
    background-color: #0f48e2;
    border-color: #0e44d6;
    margin-left:1%;
    width:12%;
    height:40px;
    display: initial;
    border: 1px solid transparent;
    line-height: 1.4;
    border-radius: .125rem;
    transition: color .15s ease-in-out,background-color .15s ease-in-out,border-color .15s ease-in-out,box-shadow .15s ease-in-out;
    vertical-align: middle;
}

#datepicker0{
  position: absolute;
  right:10px;
  transition: right 0.2s;
  cursor:pointer;
  margin-top: 5px;
}
#datepicker1{
  position: absolute;
  right:10px;
  transition: right 0.2s;
    cursor:pointer;
}
#datepicker2{
    position: absolute;
    right: 10px;
    transition: right 0.2s;
    cursor: pointer;
}
#datepicker3{
    position: absolute;
    right: 10px;
    transition: right 0.2s;
    cursor: pointer;
}
#datepicker4{
    position: absolute;
    right: 38px;
    transition: right 0.2s;
    cursor: pointer;
}
</style>

