<?php include '../inc/global.php'?>
<?php
    $page_no = $_REQUEST["page_no"];
    $page_count = $_REQUEST["page_count"];
    $search_str = $_REQUEST["search_str"];
    $search_time = $_REQUEST["search_time"];
    $searchStr = strtolower($_REQUEST["search_str"]);
    $noSearch = ( $searchStr == "" ) ? true : false;
    $search_start_num = $_REQUEST["search_start_num"];
    if ($page_no == "" || $page_no == 0) $page_no = 1;
    if ($page_count == "" || $page_count == 0) $page_count = 10;
    if ($search_start_num == "" || $page_no == 1) $search_start_num = 0;
    if ($search_time == "" || $search_time == 0) $search_time = 1000;

    if ($noSearch) {
        $dataRs = get("/v1/devices?page_no=" . $page_no . "&total_page=" . $page_count);
        $data = json_decode($dataRs);
    }else {
        $dataRs = get("/v1/devices");
        $data = json_decode($dataRs);
    }

    if ($data->error_code == 1) {
        $str = "";
        $searchRows = 0;
        $totalRows = count($data->device_infos);
        if ($noSearch) {
            $startNum = 0;
        } else {
            $startNum = ($search_start_num == 0 ) ?  ($page_no-1) * $page_count : $search_start_num;
        }

        for ($i = $startNum; $i < $totalRows; $i++) {
            $strIsConnected  = ($data->device_infos[$i]->device_status->is_connected == 1) ? "온라인" : "오프라인";
            if (!$noSearch) {
                $group_name = (strpos(strtolower((String)$data->device_infos[$i]->device_group->group_name), $searchStr) !== FALSE) ? TRUE : FALSE;
                $name = (strpos(strtolower((String)$data->device_infos[$i]->name), $searchStr) !== FALSE) ? TRUE : FALSE;
                $model_name = (strpos(strtolower((String)$data->device_infos[$i]->product_info->model_name), $searchStr) !== FALSE) ? TRUE : FALSE;
                $ip_addr = (strpos(strtolower((String)$data->device_infos[$i]->device_net_info->ip_addr), $searchStr) !== FALSE) ? TRUE : FALSE;
                $tcp_port = (strpos(strtolower((String)$data->device_infos[$i]->device_net_info->tcp_port), $searchStr) !== FALSE) ? TRUE : FALSE;
                $web_port = (strpos(strtolower((String)$data->device_infos[$i]->device_net_info->web_port), $searchStr) !== FALSE) ? TRUE : FALSE;
                $mac_addr = (strpos(strtolower((String)$data->device_infos[$i]->device_net_info->mac_addr), $searchStr) !== FALSE) ? TRUE : FALSE;
                $serial_no = (strpos(strtolower((String)$data->device_infos[$i]->product_info->serial_no), $searchStr) !== FALSE) ? TRUE : FALSE;
                $is_connected = (strpos(strtolower((String)$strIsConnected), $searchStr) !== FALSE) ? TRUE : FALSE;
            }

            if ( $group_name || $name || $model_name || $ip_addr || $tcp_port || $web_port || $mac_addr || $serial_no || $is_connected || $noSearch) {
                $str .= '<tr class="">';
                $str .= '<td class="py-2"><input type="checkbox" name="choice[]" value="'.$data->device_infos[$i]->id.'"></td>';
                $str .= ($noSearch) ? '<td class="py-2">'.($i+1).'</td>' : '<td class="py-2">'.( $searchRows+(($page_no-1)*$page_count) + 1 ).'</td>';
                $str .= '<td class="py-2">'.$data->device_infos[$i]->device_group->group_name.'</td>';
                $str .= '<td class="py-2">'.$data->device_infos[$i]->name.'</td>';
                $str .= '<td class="py-2">'.$data->device_infos[$i]->product_info->model_name.'</td>';
                $str .= '<td class="py-2">'.$data->device_infos[$i]->device_net_info->ip_addr.'</td>';
                $str .= '<td class="py-2">'.$data->device_infos[$i]->device_net_info->tcp_port.'</td>';
                $str .= '<td class="py-2">'.$data->device_infos[$i]->device_net_info->web_port.'</td>';
                $str .= '<td class="py-2">'.$data->device_infos[$i]->device_net_info->mac_addr.'</td>';
                $str .= '<td class="py-2">'.$data->device_infos[$i]->product_info->serial_no.'</td>';
                $str .= '<td class="py-2">'.$strIsConnected.'</td>';
                $str .= '<td class="py-2">';
                $str .= '<div class="position-relative">';
                $str .= '<a class="link-dark d-inline-block" onclick="javascript: device_update_info('.$data->device_infos[$i]->id.');" data-toggle="modal" data-target="#deviceUpdateModal" data-backdrop="static" keybord="false" href="#">';
                $str .= '<i class="gd-pencil icon-text"></i>';
                $str .= '&nbsp;&nbsp;&nbsp;</a>';
                $str .= '<a class="link-dark d-inline-block" onclick="javascript: goDelete('.$data->device_infos[$i]->id.',\'devices\');" href="#">';
                $str .= '<i class="gd-trash icon-text"></i>';
                $str .= '&nbsp;&nbsp;&nbsp;</a>';
                $str .= '<a class="link-dark d-inline-block" href="#">';
                $str .= '<i class="gd-loop icon-text"></i>';
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
                $strIsConnected  = ($data->device_infos[$i]->device_status->is_connected == 1) ? "온라인" : "오프라인";
                $group_name = (strpos(strtolower((String)$data->device_infos[$i]->device_group->group_name), $searchStr) !== FALSE) ? TRUE : FALSE;
                $name = (strpos(strtolower((String)$data->device_infos[$i]->name), $searchStr) !== FALSE) ? TRUE : FALSE;
                $model_name = (strpos(strtolower((String)$data->device_infos[$i]->product_info->model_name), $searchStr) !== FALSE) ? TRUE : FALSE;
                $ip_addr = (strpos(strtolower((String)$data->device_infos[$i]->device_net_info->ip_addr), $searchStr) !== FALSE) ? TRUE : FALSE;
                $tcp_port = (strpos(strtolower((String)$data->device_infos[$i]->device_net_info->tcp_port), $searchStr) !== FALSE) ? TRUE : FALSE;
                $web_port = (strpos(strtolower((String)$data->device_infos[$i]->device_net_info->web_port), $searchStr) !== FALSE) ? TRUE : FALSE;
                $mac_addr = (strpos(strtolower((String)$data->device_infos[$i]->device_net_info->mac_addr), $searchStr) !== FALSE) ? TRUE : FALSE;
                $serial_no = (strpos(strtolower((String)$data->device_infos[$i]->product_info->serial_no), $searchStr) !== FALSE) ? TRUE : FALSE;
                $is_connected = (strpos(strtolower((String)$strIsConnected), $searchStr) !== FALSE) ? TRUE : FALSE;

                if ( $group_name || $name || $model_name || $ip_addr || $mac_addr || $serial_no || $tcp_port || $web_port || $is_connected ) {
                    $searchRows++;
                }
            }
            $totalRows = $searchRows;
        }
        else{
            $deviceCntRs = get("/v1/devices-count");
            $deviceCnt = json_decode($deviceCntRs);
            $totalRows = $deviceCnt->device_count;
        }
        $totalPage = ($totalRows == 0) ? 1 : ceil($totalRows / $page_count);
        $strPage = "";
        for ($i = 1; $i <= $totalPage; $i++) {
            $active = ($i == $page_no) ? "active" : "" ;
            $strPage .= '<li class="page-item d-none d-md-block">';
            $strPage .= '<a id="datatablePagination'.$i.'" class="page-link '.$active.'" onclick="javascript: goPage('.$i.',\'devices\');" href="#" data-dt-page-to="'.$i.'">'.$i.'</a>';
            $strPage .= '</li>';
        }
    }else{
        $str  = '<tr><td style="border: 0;"></td></tr>';
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
        $str .= '<span >단말기를 찾지 못했습니다.</span>';
        $str .= '</div>';
        $str .= '</td>';
        $str .= '</tr>';

        $strPage = '<li class="page-item d-none d-md-block">';
        $strPage .= '<a id="datatablePagination1" class="page-link active" onclick="javascript: goPage(1,\'server-user-permit\');" href="#" data-dt-page-to="1">1</a>';
        $strPage .= '</li>';
    }

    $deviceGroupsDataRs = get("/v1/device-groups");
    $deviceGroupsDataRs = preg_replace('/\r\n|\r|\n|\s/','',$deviceGroupsDataRs);
    $deviceGroupsDataRs = str_replace('parent_id','pId',$deviceGroupsDataRs);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <!-- Title -->
    <title>devices | Graindashboard UI Kit</title>

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
	.input{display: initial; width: 50%;}
	.input_label{display: initial; width: 30%;}
    .input_label_right{display: initial; width: 30%; background-color: #fff!important; text-align: right;}
    *::-webkit-scrollbar {width: 10px; height: 10px;}
    *::-webkit-scrollbar-thumb {background-color: #cccccc;border-radius: 15px;}
    *::-webkit-scrollbar-track {background-color: #ececec;border-radius: 15px;}
    #deviceScanForm{height: 15rem; overflow: auto; margin-bottom: 1rem;}
    #deviceScanForm:hover{background-color: #b5bcff30; cursor: pointer;}
    #deviceScanForm.searchingEnd:hover{background-color: #fff; cursor: auto;}
    #deviceScanForm::-webkit-scrollbar {width: 10px;}
    #deviceScanForm::-webkit-scrollbar-thumb {background-color: #cccccc;border-radius: 15px;}
    #deviceScanForm::-webkit-scrollbar-track {background-color: #ececec;border-radius: 15px;}

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
                        <div class="title_fonts2">단말기 그룹</div>
                    </div>
                    <div class="card-body tree-menu" style="overflow: auto;">
                        <ul id='ztree' class='ztree'>
                        </ul>
                    </div>
                    <div>
                        <button type="button" class="btn con_fonts" style="color:#fff; background-color:#265df1; border-color:#265df1; float:right; margin-left: 5px;" onclick="javascript: deviceGroupSet('add',0);" data-toggle="modal" data-target="#deviceGroupModal" data-dismiss="modal" data-backdrop="static" keybord="false">추가</button>
                        <button type="button" class="btn con_fonts" onclick="javascript: device_group_del();" style="color: #fff; background-color:#ff6500; border-color:#ff6500; float: right;">삭제</button>
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
                                <a href="#">단말기</a>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page">단말기</li>
                        </ol>
                    </nav>
                    <!-- End Breadcrumb -->

                    <div class="mb-3 mb-md-4 d-flex justify-content-between">
                        <div class="title_fonts2">단말기</div>
                        <div style="padding-right: 5rem;"><i onclick="javascript: refresh('devices');" class = "gd-reload h4" style = "cursor : pointer;"></i></div>
                    </div>
                

                    <!-- Form -->
                    <div>
                        <form name = "search_form" method = "GET">
                            <input type="hidden" name="search_start_num" value = "<?=$search_start_num?>">
                            <input type="hidden" name="page_count">
                            <div class="form-row">
                                <div class="form-group col-4 col-md-2">
                                <section style="width:100px;height:32px;"></section>
                                    <input type="text" class="form-control" id="search_str" name="search_str" value = "<?=$search_str?>" placeholder="검색">
                                </div>
                                <div class="form-group col-4 col-md-10">
                                    <button type="button" onclick = "javascript: goSearch('devices');" class="btn con_fonts" style="position: relative; top: 32px; color: #fff; background-color: #ffc300; border-color: #ffc300;">검색</button>
                                    <button type="button" class="btn con_fonts" onclick="javascript: deviceInsertReset();" style="position: relative; top: 32px; color: #fff; background-color: #265df1; border-color: #265df1;" data-toggle="modal" data-target="#deviceInsertModal" data-dismiss="modal" data-backdrop="static" keybord="false">추가</button>
                                    <button type="button" onclick = "javascript: goDelete('more','devices');" class="btn con_fonts" style="position: relative; top: 32px; color: #fff; background-color: #ff6500; border-color: #ff6500;">삭제</button>
                                </div>
                            </div>
                            
                        </form>
                    </div>
                    <!-- End Form -->


                    <!-- devices -->
                    <div style="height: 35rem; overflow: auto;" class="">
                        <form name = "contents_show_form">
                            <input type="hidden" name="id">
                            <input type="hidden" name="job">
                            <table class="table text-nowrap mb-0">
                                <thead>
                                <tr>
                                    <th class="font-weight-semi-bold border-top-0 py-2"><input type="checkbox" id="checkAll"></th>
                                    <th class="font-weight-semi-bold border-top-0 py-2">번호</th>
                                    <th class="font-weight-semi-bold border-top-0 py-2">그룹</th>
                                    <th class="font-weight-semi-bold border-top-0 py-2">이름</th>
                                    <th class="font-weight-semi-bold border-top-0 py-2">모델명</th>
                                    <th class="font-weight-semi-bold border-top-0 py-2">IP 주소</th>
                                    <th class="font-weight-semi-bold border-top-0 py-2">TCP 포트</th>
                                    <th class="font-weight-semi-bold border-top-0 py-2">WEB 포트</th>
                                    <th class="font-weight-semi-bold border-top-0 py-2">MAC 주소</th>
                                    <th class="font-weight-semi-bold border-top-0 py-2">시리얼 번호</th>
                                    <th class="font-weight-semi-bold border-top-0 py-2">연결상태</th>
                                    <th class="font-weight-semi-bold border-top-0 py-2">동작</th>
                                </tr>
                                </thead>
                                <tbody id="device-content">
                                    <?=$str?>
                                </tbody>
                            </table>
                        </form>
                    </div>
                </div>

                <div class="card-footer d-block d-md-flex align-items-center d-print-none" style="border-top: 1px solid #d8d8d8;">
                    <nav class="d-flex d-print-none" aria-label="Pagination">
                        <ul class="pagination justify-content-end font-weight-semi-bold mb-0">
                            <li class="page-item" id="page-prev">
                                <button type = "button" id="datatablePaginationPrev" class="page-link" onclick="javascript: goPage('<?=$page_no-1?>','devices');" aria-label="Previous">
                                    <i class="gd-angle-left icon-text icon-text-xs d-inline-block"></i>
                                </button>
                            </li>
                                <?=$strPage?>
                            <li class="page-item" id="page-next">
                                    <button type = "button" id="datatablePaginationNext" class="page-link" onclick="javascript: goPage('<?=$page_no+1?>','devices');" aria-label="Next">
                                    <i class="gd-angle-right icon-text icon-text-xs d-inline-block"></i>
                                </button>
                            </li>
                        </ul>
                    </nav>
                    <div class="d-flex mb-2 mb-md-0 ml-3 con_fonts" style="color: #8a8a8a; float: left; cursor: pointer;" onclick="javascript: goPage('1','devices');">처음으로</div>
                    <div class="d-flex mb-2 mb-md-0 ml-3 con_fonts" style="float: left;"><input type="text" class="form-control" id="search_page" name="search_page" value="<?=$page_no?>" style="width: 3rem; text-align: center; border: 1px solid #cccccc;"></div>
                    <div class="d-flex mb-2 mb-md-0 ml-3 con_fonts" style="float: left;"><button type="button" class="btn con_fonts" style="color: #000; background-color: #f7f7f7; border-color: #cccccc;" onclick="javascript: goPage('search_page','devices');">이동</button></div>
                    <div class="d-flex mb-2 mb-md-0 ml-3 con_fonts" style="color: #8a8a8a;" id = "total-row">총 <?=$totalRows?>개</div>
                    <div class="d-flex mb-2 mb-md-0 ml-3 con_fonts" style="float: left;">
                        <select id="page_count_change" onchange="javascript: changeCount('devices');" style="border:1px solid #cccccc; color: #8a8a8a; height: 44px;">
                            <option value="10" <?if($page_count == 10) echo "selected"?>> 10개 항목 / 페이지</option>
                            <option value="20" <?if($page_count == 20) echo "selected"?>> 20개 항목 / 페이지</option>
                            <option value="30" <?if($page_count == 30) echo "selected"?>> 30개 항목 / 페이지</option>
                            <option value="50" <?if($page_count == 50) echo "selected"?>> 40개 항목 / 페이지</option>
                        </select>
                    </div>
                </div>

            </div>
            <!-- End devices -->
        </div>

        <!-- Footer -->
        <?php include '../footer.php'?>
        <!-- End Footer -->
    </div>
</main>
<?php
    $deviceGroupDataRs = get("/v1/device-groups");
    $deviceGroupData = json_decode($deviceGroupDataRs);
	$totalRows = count($deviceGroupData->d_group_infos);
	$deviceGroupsStr = "";
	for ($i=0; $i < $totalRows; $i++) { 
		$deviceGroupsStr .= "<option value='".$deviceGroupData->d_group_infos[$i]->id."'>".$deviceGroupData->d_group_infos[$i]->name."</option>";
	}
?>

<!-- Device Insert Modal -->
<div id="deviceInsertModal" class="modal fade" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog rounded" role="document" style="top: 3.5rem; padding: 0!important; width: 65rem;">
        <div class="modal-content" style="padding: 10px 0!important; height: 50rem;">
            <header class="modal-header flex-column justify-content-center" style="border-bottom: 1px solid #a7a7a7;">
                <div style="width: 100%;">
                    <div class="title_fonts2">단말기 추가
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="padding: 1.2rem 1rem 0 0;"><span aria-hidden="true">&times;</span></button>
                    </div>
                </div>
            </header>

            <div class="modal-body pt-3" style="overflow: auto;">
                <div class="card">
                    <div class="card-body">
                        <div class="form-group col-4 col-md-12">
                            <div class="card-title">&nbsp;
                                <button type="button" onclick="javascript: deviceSearch();" class="btn con_fonts" style="float: right; color: #fff; background-color: #ffc300; border-color: #ffc300;">검색</button>
                                <span style="color: #8a8a8a; float: right; margin: 0.5rem 2rem 0 0; font-size: 14px;" id="device-scan-total-row"></span>
                            </div>
                        </div>
                        <!-- device-scan -->
                        <div id="deviceScanForm" onclick="javascript: deviceSearch();">
                            <form>
                                <table class="table text-nowrap mb-0">
                                    <thead>
                                    <tr>
                                        <th class="font-weight-semi-bold border-top-0 py-2">번호</th>
                                        <th class="font-weight-semi-bold border-top-0 py-2">모델명</th>
                                        <th class="font-weight-semi-bold border-top-0 py-2">IP주소</th>
                                        <th class="font-weight-semi-bold border-top-0 py-2">TCP PORT</th>
                                        <th class="font-weight-semi-bold border-top-0 py-2">PORT</th>
                                        <th class="font-weight-semi-bold border-top-0 py-2">MAC 주소</th>
                                        <th class="font-weight-semi-bold border-top-0 py-2">서브넷 마스크</th>
                                        <th class="font-weight-semi-bold border-top-0 py-2">게이트웨이</th>
                                        <th class="font-weight-semi-bold border-top-0 py-2">시리얼 번호</th>
                                        <!-- <th class="font-weight-semi-bold border-top-0 py-2">활성화 유무</th>
                                        <th class="font-weight-semi-bold border-top-0 py-2">DHCP</th>
                                        <th class="font-weight-semi-bold border-top-0 py-2">동작</th> -->
                                    </tr>
                                    </thead>
                                    <tbody id="device-scan-seraching" style="display: none;">
                                        <tr><td style="border: 0;"></td></tr>
                                        <tr><td style="border: 0;"></td></tr>
                                        <tr><td style="border: 0;"></td></tr>
                                        <tr>
                                            <td style="border: 0;"></td>
                                            <td style="border: 0;"></td>
                                            <td style="border: 0;"></td>
                                            <td style="border: 0;"></td>
                                            <td style="border: 0;"></td>
                                            <td class="py-2" style="border: 0;">
                                                <div class="spinner-border" style="width: 3rem; height: 3rem;" role="status">
                                                    <span class="sr-only">Loading...</span>
                                                </div>
                                            </td>
                                            <td style="border: 0;"></td>

                                        </tr>
                                    </tbody>
                                    <tbody id="device-scan-content">
                                        <tr><td style="border: 0;"></td></tr>
                                        <tr><td style="border: 0;"></td></tr>
                                        <tr><td style="border: 0;"></td></tr>
                                        <tr>
                                            <td style="border: 0;"></td>
                                            <td style="border: 0;"></td>
                                            <td style="border: 0;"></td>
                                            <td style="border: 0;"></td>
                                            <td style="border: 0;"></td>
                                            <td class="font-weight-semi-bold border-top-0 py-2" style="border: 0;">
                                                검색
                                            </td>
                                            <td style="border: 0;"></td>

                                        </tr>
                                    </tbody>
                                </table>
                                <input type="hidden" name="id">
                            </form>
                        </div>
                        <!-- device-scan End -->

                        <form name="device_insert_form" class="deviceFrom" method="POST">
                            <div style="width: 50%; float: left;">
                                <div class="form-group">
                                    <input class="form-control input_label" value="단말기 이름" disabled>
                                    <input type="text" class="form-control input" name="deviceName">
                                </div>
                                <div class="form-group">
                                    <input class="form-control input_label" value="단말기 그룹" disabled>
                                    <select class="form-control input" name="group_id" id="deviceInsertGroupId" style="width: 37%;">
                                        <?=$deviceGroupsStr?>
                                    </select>
                                    <button type="button" class="btn con_fonts" onclick="javascript: deviceGroupSet('addBtn',0);" data-toggle="modal" data-target="#deviceGroupModal" data-backdrop="static" keybord="false" href="#" style="position: relative; top: -2.2px; color: #fff; background-color: #265df1; border-color: #265df1;">추가</button>
                                </div>

                                <div class="form-group">
                                    <input class="form-control input_label" value="IP 주소" disabled>
                                    <input type="text" class="form-control input"name="ip_addr">
                                </div>
                                <div class="form-group">
                                    <input class="form-control input_label" value="TCP Port" disabled>
                                    <input type="text" class="form-control input" name="tcp_port">
                                </div>
                                <div class="form-group">
                                    <input class="form-control input_label" value="Port" disabled>
                                    <input type="text" class="form-control input" name="web_port">
                                </div>
                                <div class="form-group">
                                    <input class="form-control input_label" value="ID" disabled>
                                    <input type="text" class="form-control input" name="account_id">
                                </div>
                                <div class="form-group">
                                    <input class="form-control input_label" value="비밀번호" disabled>
                                    <input type="password" class="form-control input" name="account_pw">
                                </div>
                            </div>

                            <div style="width: 50%; float: right;">
                                <div id="device_insert_sync_form">
                                    <div class="form-group">
                                        <input class="form-control input_label_right" value="시리얼 번호" disabled>
                                        <input type="text" class="form-control input" name="serial_no">
                                    </div>
                                    <div class="form-group">
                                        <input class="form-control input_label_right" value="모델 이름" disabled>
                                        <input type="text" class="form-control input" name="model_name" style="background-color: #fff;" readonly>
                                    </div>
                                    <div class="form-group">
                                        <input class="form-control input_label_right" value="카메라 이름" disabled>
                                        <input type="text" class="form-control input" name="link_name">
                                    </div>
                                    <div class="form-group">
                                        <input class="form-control input_label_right" value="카메라 RTSP URL" disabled>
                                        <input type="text" class="form-control input" name="link_rtsp_url">
                                    </div>
                                    <div class="form-group">
                                        <input class="form-control input_label_right" value="카메라 UID" disabled>
                                        <input type="text" class="form-control input" name="link_uid">
                                    </div>
                                    <div class="form-group">
                                        <input class="form-control input_label_right" value="카메라 비밀번호" disabled>
                                        <input type="password" class="form-control input" name="link_password">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <input type="checkbox" id="device_insert_time_sync" name="device_insert_time_sync" value="1">
                                    <label class="con_fonts" for="device_insert_time_sync">연결시 시간 동기화하기</label>
                                </div>
                            </div>

							
						</form>
					</div>
				</div>
                <div style="text-align: center;">
            <button type="button" id="userInsertBtn" onclick="javascript: go_device_insert();" class="btn_success">저 장</button>
            </div>
            </div>
        </div>
    </div>
</div>
<!-- End Modal -->

<!-- Modal -->
<div id="deviceUpdateModal" class="modal fade" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog rounded py-5" role="document" style="top: 3.5rem; padding: 0!important; width: 65rem;">
        <div class="modal-content" style="padding: 10px 0!important; height: 34rem;">
            <header class="modal-header flex-column justify-content-center" style="border-bottom: 1px solid #a7a7a7; ">
                <div style="width: 100%;">
                    <div class="title_fonts2">단말기 수정
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="padding: 1.2rem 1rem 0 0;"><span aria-hidden="true">&times;</span></button>
                    </div>
                </div>
            </header>

            <div class="modal-body pt-3">
                <div class="card">
                    <div class="card-body">
                        <form name="device_update_form" method = "POST">
                            <div style="width: 50%; float: left;">
                                <div class="form-group">
                                    <input class="form-control input_label" value="단말기 이름" disabled>
                                    <input type="text" class="form-control input" name="deviceName">
                                </div>
                                <div class="form-group">
                                    <input class="form-control input_label" value="단말기 그룹" disabled>
                                    <select class="form-control input" name="group_id" id="deviceUpdateGroupId" style="width: 37%;">
                                        <?=$deviceGroupsStr?>
                                    </select>
                                    <button type="button" class="btn con_fonts" onclick="javascript: deviceGroupSet('addBtn',0);" data-toggle="modal" data-target="#deviceGroupModal" data-backdrop="static" keybord="false" href="#" style="position: relative; top: -2.2px; color: #fff; background-color: #265df1; border-color: #265df1;">추가</button>
                                </div>
                                <div class="form-group">
                                    <input class="form-control input_label" value="IP 주소" disabled>
                                    <input type="text" class="form-control input" name="ip_addr">
                                </div>
                                <div class="form-group">
                                    <input class="form-control input_label" value="TCP Port" disabled>
                                    <input type="text" class="form-control input" name="tcp_port">
                                </div>
                                <div class="form-group">
                                    <input class="form-control input_label" value="Web Port" disabled>
                                    <input type="text" class="form-control input" name="web_port">
                                </div>
                                <div class="form-group">
                                    <input class="form-control input_label" value="ID" disabled>
                                    <input type="text" class="form-control input" name="account_id">
                                </div>
                                <div class="form-group">
                                    <input class="form-control input_label" value="비밀번호" disabled>
                                    <input type="password" class="form-control input" name="account_pw">
                                </div>
                            </div>

                            <div style="width: 50%; float: right;">
                                <div class="form-group">
                                    <input class="form-control input_label" value="시리얼 번호" disabled>
                                    <input type="text" class="form-control input" name="serial_no">
                                </div>
                                <div class="form-group">
                                    <input class="form-control input_label" value="모델 이름" disabled>
                                    <input type="text" class="form-control input" name="model_name" style="background-color: #fff;" readonly>
                                </div>
                                <div id="device_update_sync_form">
                                    <div class="form-group">
                                        <input class="form-control input_label" value="카메라 이름" disabled>
                                        <input type="text" class="form-control input" name="link_name">
                                    </div>
                                    <div class="form-group">
                                        <input class="form-control input_label" value="카메라 RTSP URL" disabled>
                                        <input type="text" class="form-control input" name="link_rtsp_url">
                                    </div>
                                    <div class="form-group">
                                        <input class="form-control input_label" value="카메라 RTSP URL" disabled>
                                        <input type="text" class="form-control input" name="link_uid">
                                    </div>
                                    <div class="form-group">
                                        <input class="form-control input_label" value="카메라 비밀번호" disabled>
                                        <input type="password" class="form-control input" name="link_password">
                                    </div>
                                    <div class="form-group con_fonts">
                                        <input type="checkbox" id="device_update_time_sync" name="device_update_time_sync" value="1">
                                        <label class="con_fonts" for="device_update_time_sync">연결시 시간 동기화하기</label>
                                    </div>
                                </div>
                            </div>
							
							
						</form>
					</div>
                    <div style="text-align: center;">
        <button type="button" id="userInsertBtn" onclick="javascript:go_user_insert2('job');" class="btn_success">저 장</button>
        </div>
				</div>
            </div>
        </div>
    </div>
</div>
<!-- End Modal -->


<!-- Modal -->
<div id="deviceGroupModal" class="modal fade" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" style="background: rgba(0, 0, 0, 0.5);">
    <div class="modal-dialog rounded" role="document" style="top: 3.5rem; padding: 0!important; width: 30rem;">
        <div class="modal-content" style="">
            <header class="modal-header flex-column justify-content-center" style="border-bottom: 1px solid #a7a7a7; padding-bottom: 0;">
                <div style="width: 100%;">
                    <h4 class="title_fonts2">
                    </h4>
                </div>
            </header>

            <div class="modal-body pt-3">
                <div class="card">
                    <div class="card-body">
                        <form name="deviceGroupForm" method = "POST">
							<div class="form-group">
								<input class="form-control input_label" value="상위 그룹" disabled>
								<select class="form-control input" name="parentId">
                                    <option value="0" selected>최상위</option>
									<?=$deviceGroupsStr?>
								</select>
                            </div>
							<div class="form-group">
								<input class="form-control input_label" value="그룹 이름" disabled>
								<input type="text" class="form-control input" name="groupName">
							</div>
						</form>
					</div>
                    <div class="btn_append" style="text-align:center;">

                    </div>
				</div>
            </div>
        </div>
    </div>
</div>
<!-- End Modal -->
<ul class="contextmenu">
    <li><a id="addDevice" data-toggle="modal" data-target="#deviceGroupModal" data-dismiss="modal" data-backdrop="static" keybord="false" href="#" >추가</a></li>
</ul>
<script src="../public/graindashboard/js/graindashboard.js"></script>
<script src="../public/graindashboard/js/graindashboard.vendor.js"></script>

<!-- DEMO CHARTS -->
<script src="../public/demo/resizeSensor.js"></script>
<script src="../public/demo/chartist.js"></script>
<script src="../public/demo/chartist-plugin-tooltip.js"></script>
<script src="../public/demo/gd.chartist-area.js"></script>
<script src="../public/demo/gd.chartist-bar.js"></script>
<script src="../public/demo/gd.chartist-donut.js"></script>

<link rel="stylesheet" href="../public/graindashboard/css/zTreeStyle.css">
<script src="../public/graindashboard/js/jquery.ztree.core.min.js"></script>
<script src="../public/graindashboard/js/jquery.ztree.excheck.min.js"></script>
<script src="../public/graindashboard/js/onepass.js"></script>


<script>
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
    var deviceGroupsDataRs = '<?=$deviceGroupsDataRs?>';
    var zNodes = JSON.parse(deviceGroupsDataRs);
    $(document).ready(function(){
        //Show contextmenu:
        $(".card-body.tree-menu").contextmenu(function(e){
            if (e.target.attributes.class.value == "node_name") {
                $("#updDevice").remove();
                $("#delDevice").remove();
                var device_group_id = e.target.attributes.deviceid.value;
                let updDelStr = '<li><a id="updDevice" onclick="deviceGroupSet(\'upd\',\''+device_group_id+'\');" data-toggle="modal" data-target="#deviceGroupModal" data-dismiss="modal" data-backdrop="static" keybord="false" href="#" >수정</a></li>';
                updDelStr +='<li><a id="delDevice" onclick="device_group_delete(\'1\', \''+device_group_id+'\');" href="#">삭제</a></li>';
                $(".contextmenu").append(updDelStr);
            }else{
                $("#updDevice").remove();
                $("#delDevice").remove();
                var device_group_id = 0;
                if (e.target.attributes.class.value == "level0 curSelectedNode") {
                    device_group_id = e.toElement.children[1].attributes.deviceid.value
                    console.log(device_group_id);
                    let updDelStr = '<li><a id="updDevice" onclick="deviceGroupSet(\'upd\',\''+device_group_id+'\');" data-toggle="modal" data-target="#deviceGroupModal" data-dismiss="modal" data-backdrop="static" keybord="false" href="#" >수정</a></li>';
                    updDelStr +='<li><a id="delDevice" onclick="device_group_delete(\'1\', \''+device_group_id+'\');" href="#">삭제</a></li>';
                    $(".contextmenu").append(updDelStr);
                }
            }
            $("#addDevice").attr("onclick","javascript: deviceGroupSet('add','"+device_group_id+"');");

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
        // zTree 초기화
        $.fn.zTree.init($("#ztree"), setting, zNodes.d_group_infos);
        var treeObj = $.fn.zTree.getZTreeObj("ztree");
        treeObj.expandAll(true);
        
        for (let i = 0; i < $("#ztree .node_name").length; i++) {
            var deviceGroup = zNodes.d_group_infos.filter(function (e) {
                return e.name == $("#ztree .node_name").eq(i).text();
            });
            $("#ztree .node_name").eq(i).attr("deviceId", deviceGroup[0].id);
        }
    });
    var searchStr = '<?=$searchStr?>';
    var search_time = '<?=$search_time?>';
    var page_no = '<?=$page_no?>';
    var page_count = '<?=$page_count?>';
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