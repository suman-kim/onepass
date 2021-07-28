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

    $dataRs = get("/v1/access-time-groups");

    $data = json_decode($dataRs);
  
    if ($data->error_code == 1) {
        $searchRows = 0;
        $str = "";
        $totalRows = count($data->access_time_group_infos);
        $startNum = ($search_start_num == 0 ) ?  ($page_no-1) * $page_count : $search_start_num;

        for ($i = $startNum; $i < $totalRows; $i++) {
            
            if (!$noSearch) {
                $accTimeGroupName = (strpos((String)$data->access_time_group_infos[$i]->name, $searchStr) !== FALSE) ? TRUE : FALSE;
            }

            if ( $accTimeGroupName || $noSearch) {
                $str .= '<tr class="access_time_group_table" value="'.$data->access_time_group_infos[$i]->id.'" style="" onclick="javascript: access_time_group_update('.$data->access_time_group_infos[$i]->id.',\'access-time-group\');table_csschg('.$data->access_time_group_infos[$i]->id.');">';
                $str .= '<td class="py-2"><input type="checkbox" name="choice[]" value="'.$data->access_time_group_infos[$i]->id.'"></td>';
                $str .= '<td class="py-2">'.$data->access_time_group_infos[$i]->name.'</td>';
                $str .= '</tr>';
                $searchRows++;
                if ($searchRows == $page_count) break;
            }
        }

        if (!$noSearch) {
            $searchRows = 0;
            for ($i = 0; $i < $totalRows; $i++) {
                $accTimeGroupName = (strpos((String)$data->access_time_group_infos[$i]->name, $searchStr) !== FALSE) ? TRUE : FALSE;

                if ( $accTimeGroupName ) {
                    $searchRows++;
                }
            }
            $totalRows = $searchRows;
        }
        $totalPage = ($totalRows == 0) ? 1 : ceil($totalRows / $page_count);
        $strPage = "";
        for ($i = 1; $i <= $totalPage; $i++) {
            $active = ($i == $page_no) ? "active" : "" ;
            $strPage .= '<li class="page-item d-none d-md-block">';
            $strPage .= '<a id="datatablePagination'.$i.'" class="page-link '.$active.'" onclick="javascript: goPage('.$i.',\'access-time-group\');" href="#" data-dt-page-to="'.$i.'">'.$i.'</a>';
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
    <link rel="stylesheet" href="../public/graindashboard/css/jquery.timepicker.min.css">
    <link rel="stylesheet" href="../public/graindashboard/css/jquery.timepicker.css">

</head>
<style>
    .sub-sidebar-menu{float: left; width: 19.5%; left: -22px; height: 55rem;}
    .sub-sidebar-btn-wrap{float: left; width: 0.5%; left: -24px; height: 55rem; padding: 26rem 0; background-color: #f3f3f3;}
    .sub-sidebar-btn{background-color: #8b8e9f; font-size: 1px; height: 100%; color: #fff; padding: 16px 0; cursor: pointer;}
    .display-content{float: left; width: 25%; min-width: 20rem;}
    table * {text-align: center;}
    table tbody tr:hover{background-color: #f3f3f3; cursor: pointer;}
    .input-label{min-width: 9rem; width: 10rem; border: 0; text-align: center; padding: .63rem 6px .63rem 0;}
    .input-label::after{display: block; content: ''; clear: both;}
    .input{min-width: 6rem; width: 9rem; background-color: #fff !important; text-align: center;}
    .input::after{display: block; content: ''; clear: both;}
    .input-1{width: 19rem;}
    .input-11{width: 11rem;}
    .wave{margin-top:10px;}
    *::-webkit-scrollbar {width: 10px; height: 10px;}
    *::-webkit-scrollbar-thumb {background-color: #cccccc;border-radius: 15px;}
    *::-webkit-scrollbar-track {background-color: #ececec;border-radius: 15px;}
    .displayHide{display: none;}
    .holidaySub{text-align: center; width: 19.5rem; padding: 6px; font-size: 1.1rem; border-right: 1px solid #eeeef1;}
    .holidaySub:hover{cursor: pointer; background-color: #f3f3f3;}
    .acc-time-content-body form .form-group{margin-bottom: 5px;}
    .input{width:100%;height:45px;}
   
    .access_time_group_table:hover{
    background-color: #eeeef1!important;
}
</style>
<body class="has-sidebar has-fixed-sidebar-and-header">

<main class="main">

    <div class="content" style="height: 100%;">
        <div class="py-4 px-3 px-md-4 d-flex">
            <!-- 출입 시간 그룹 시작 -->
            <div class="card mb-3 mb-md-4 display-content" id="type-content" style="height: 55rem; margin-right: 2%;">
                <div class="card-body">
                    <!-- Breadcrumb -->
                    <nav class="d-none d-md-block" aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item">
                                <a href="#">출입</a>
                            </li>
                            <li class="breadcrumb-item active " aria-current="page">출입 시간 그룹</li>
                        </ol>
                    </nav>
                    <!-- End Breadcrumb -->

                    <div class="mb-3 mb-md-4 d-flex justify-content-between">
                        <div class="title_fonts2">출입 시간 그룹</div>
                    </div>

                    <!-- Form -->
                    <div>
                        <form name = "search_form" method = "GET">
                            <input type="hidden" name="search_start_num" id = "search_start_num" value = "<?=$search_start_num?>">
                            <input type="hidden" name="page_count">
                            <div class="form-row">
                                <div class="form-group col-4 col-md-2">
                                    <section style="width:100px;height:32px;"></section>
                                    <input type="text" class="form-control" id="search_str" name="search_str"style="width:358%!important;" value = "<?=$searchStr?>" placeholder="검색">
                                </div>
                                <div class="form-group col-8 col-md-10" style="padding-left: 18%!important;">
                                    <button type="button" onclick = "javascript: goSearch('access-time-group');" class="btn" style="position: relative; top: 32px; color: #fff; background-color: #ffc300; border-color: #ffc300;">검색</button>
                                    <button type="button" onclick ="javascript:type_form_reset();" data-toggle="modal" data-target="#theModal" data-backdrop="static" keybord="false"  class="btn" style="position: relative; top: 32px; color: #fff; background-color: #265df1; border-color: #265df1;">추가</button>
                                    <button type="button" onclick = "javascript: goDelete('more', 'access-time-group');" class="btn" style="position: relative; top: 32px; color: #fff; background-color: #ff6500; border-color: #ff6500;">삭제</button>
                                </div>
                            </div>
                            
                        </form>
                    </div>
                    <!-- End Form -->


                    <!-- type -->
                    <div style="height: 35rem;">
                        <form name = "contents_show_form">
                            <table class="table text-nowrap mb-0">
                                <thead>
                                <tr>
                                    <th class="font-weight-semi-bold border-top-0 py-2"><input type="checkbox" id="checkAll"></th>
                                    <th class="font-weight-semi-bold border-top-0 py-2">이름</th>
                                    <!--<th class="font-weight-semi-bold border-top-0 py-2">Action</th>-->
                                </tr>
                                </thead>
                                <tbody id="access-time-group-content">
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
                                <button type = "button" id="datatablePaginationPrev" class="page-link" onclick="javascript: goPage('<?=$page_no-1?>','access-time-group');" aria-label="Previous">
                                    <i class="gd-angle-left icon-text icon-text-xs d-inline-block"></i>
                                </button>
                            </li>
                            <?=$strPage?>
                            <li class="page-item" id="page-next">
                                    <button type = "button" id="datatablePaginationNext" class="page-link" onclick="javascript: goPage('<?=$page_no+1?>','access-time-group');" aria-label="Next">
                                    <i class="gd-angle-right icon-text icon-text-xs d-inline-block"></i>
                                </button>
                            </li>
                        </ul>
                    </nav>
                    <!--
                    <div class="d-flex mb-2 mb-md-0 ml-3" style="color: #8a8a8a; float: left; cursor: pointer;" onclick="javascript: goPage('1','access-time-group');">처음으로</div>
                    <div class="d-flex mb-2 mb-md-0 ml-3" style="float: left;"><input type="text" class="form-control" id="search_page" name="search_page" value="?=$page_no?>" style="width: 3rem; text-align: center; border: 1px solid #cccccc;"></div>
                    <div class="d-flex mb-2 mb-md-0 ml-3" style="float: left;"><button type="button" class="btn" style="color: #000; background-color: #f7f7f7; border-color: #cccccc;" onclick="javascript: goPage('search_page','access-time-group');">이동</button></div>
                    <div class="d-flex mb-2 mb-md-0 ml-3" style="color: #8a8a8a;" id = "total-row">총 ?=$totalRows?>개</div>
                    <div class="d-flex mb-2 mb-md-0 ml-3" style="float: left;">
                        <select id="page_count_change" onchange="javascript: changeCount('access-time-group');" style="border:1px solid #cccccc; color: #8a8a8a; height: 44px;">
                            <option value="10" ?if($page_count == "10") echo "selected"?>>10개 항목 / 페이지</option>
                            <option value="20" ?if($page_count == "20") echo "selected"?>>20개 항목 / 페이지</option>
                            <option value="30" ?if($page_count == "30") echo "selected"?>>30개 항목 / 페이지</option>
                            <option value="50" ?if($page_count == "50") echo "selected"?>>50개 항목 / 페이지</option>
                        </select>
                    </div>
                    -->
                </div>

            </div>
            <!-- 출입 시간 그룹 끝 -->



            <!-- 주간 일정 시작 -->
            <div class="card mb-3 mb-md-4 display-content acc-time-content-body" id="type-content" style="height: 55rem; width: 73%; min-width: 30.3rem;">
                
            <div class="card-body" style="">
                                    <!-- Breadcrumb -->
                                    <nav class="d-none d-md-block" aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item">
                                <a href="#">출입</a>
                            </li>
                            <li class="breadcrumb-item active " aria-current="page">출입 시간 그룹</li>
                        </ol>
                    </nav>
                    <!-- End Breadcrumb -->
                    <div class="mb-3 mb-md-4 d-flex">
                        <div class="title_fonts2 access-time-title" style="width: 100%;">출입 시간 그룹 추가</div>
                        <div style="padding-right: 5rem;"><i onclick="javascript: refresh('access-time-group');" class = "gd-reload h4" style = "cursor: pointer;"></i></div>
                    </div>

                    <form name="acc_time_group_insert_form" method="POST">
                        <div class="form-group d-flex">
                            <input class="form-control input-label" readonly type="text" value="이름">
                            <input class="form-control input" id="acc_time_group_name" name="acc_time_group_name" style="width: 19rem;">
                            <br>
                        </div>
                    </form>
                    <span>
                        &nbsp;
                    </span>
                    <div class="mb-3 mb-md-4 d-flex" style="border: 1px solid #eeeef1; width: 29rem;">
                        <div id="holi_week" class="mb-0 holidaySub" style="background-color: #265df1; color: #fff;" onClick="javascript: changeHoliday('week');">주간 일정</div>
                        <div id="holi_group" class="mb-0 holidaySub" onClick="javascript: changeHoliday('group');">휴일 그룹</div>
                    </div>

                    <div id="acc_week_body" style="height: 36rem; overflow: auto;">
                        <form id="acc_week_insert_form" name="acc_week_insert_form" method="POST" style="width: 29rem;">
                            <table class="table text-nowrap mb-0">
                                <thead>
                                <tr>
                                    <th class="font-weight-semi-bold border-top-0 py-2"></th>
                                    <th class="font-weight-semi-bold border-top-0 py-2">시간 범위1</th>
                                    <th class="font-weight-semi-bold border-top-0 py-2">시간 범위2</th>
                                    <th class="font-weight-semi-bold border-top-0 py-2">시간 범위3</th>
                                    <th class="font-weight-semi-bold border-top-0 py-2">시간 범위4</th>
                                </tr>
                                </thead>
                                <tbody>
                                    <tr id="mon">
                                        <td class="py-3">월</td>
                                        <td class="py-1">
                                            <div class="form-group d-flex">
                                             
                                                <input type="time" class="form-control input" id="mon_time_1_start" name="mon_time_1_start" value="09:00">
                                                <span class="wave">~</span>
                                                <input type="time" class="form-control input" id="mon_time_1_end" name="mon_time_1_end" value="18:00">
                                            </div>
                                        </td>
                                        <td class="py-1">
                                            <div class="form-group d-flex">
                                                <input type="time" class="form-control input" id="mon_time_2_start" name="mon_time_2_start">
                                                <span class="wave">~</span>
                                                <input type="time" class="form-control input" id="mon_time_2_end" name="mon_time_2_end">
                                            </div>
                                        </td>
                                        <td class="py-1">
                                            <div class="form-group d-flex">
                                                <input type="time" class="form-control input" id="mon_time_3_start" name="mon_time_3_start">
                                                <span class="wave">~</span>
                                                <input type="time" class="form-control input" id="mon_time_3_end" name="mon_time_3_end">
                                            </div>
                                        </td>
                                        <td class="py-1">
                                            <div class="form-group d-flex">
                                                <input type="time" class="form-control input" id="mon_time_4_start" name="mon_time_4_start">
                                                <span class="wave">~</span>
                                                <input type="time" class="form-control input" id="mon_time_4_end" name="mon_time_4_end">
                                            </div>
                                        </td>
                                    </tr>
                                    <tr id="tue">
                                        <td class="py-3">화</td>
                                        <td class="py-1">
                                            <div class="form-group d-flex">
                                                <input type="time" class="form-control input" id="tue_time_1_start" name="tue_time_1_start" value="09:00">
                                                <span class="wave">~</span>
                                                <input type="time" class="form-control input" id="tue_time_1_end" name="tue_time_1_end" value="18:00">
                                            </div>
                                        </td>
                                        <td class="py-1">
                                            <div class="form-group d-flex">
                                                <input type="time" class="form-control input" id="tue_time_2_start" name="tue_time_2_start">
                                                <span class="wave">~</span>
                                                <input type="time" class="form-control input" id="tue_time_2_end" name="tue_time_2_end">
                                            </div>
                                        </td>
                                        <td class="py-1">
                                            <div class="form-group d-flex">
                                                <input type="time" class="form-control input" id="tue_time_3_start" name="tue_time_3_start">
                                                <span class="wave">~</span>
                                                <input type="time" class="form-control input" id="tue_time_3_end" name="tue_time_3_end">
                                            </div>
                                        </td>
                                        <td class="py-1">
                                            <div class="form-group d-flex">
                                                <input type="time" class="form-control input" id="tue_time_4_start" name="tue_time_4_start">
                                                <span class="wave">~</span>
                                                <input type="time" class="form-control input" id="tue_time_4_end" name="tue_time_4_end">
                                            </div>
                                        </td>
                                    </tr>
                                    <tr id="wed">
                                        <td class="py-3">수</td>
                                        <td class="py-2">
                                            <div class="form-group d-flex">
                                                <input type="time" class="form-control input" id="wed_time_1_start" name="wed_time_1_start" value="09:00">
                                                <span class="wave">~</span>
                                                <input type="time" class="form-control input" id="wed_time_1_end" name="wed_time_1_end" value="18:00">
                                            </div>
                                        </td>
                                        <td class="py-1">
                                            <div class="form-group d-flex">
                                                <input type="time" class="form-control input" id="wed_time_2_start" name="wed_time_2_start">
                                                <span class="wave">~</span>
                                                <input type="time" class="form-control input" id="wed_time_2_end" name="wed_time_2_end">
                                            </div>
                                        </td>
                                        <td class="py-1">
                                            <div class="form-group d-flex">
                                                <input type="time" class="form-control input" id="wed_time_3_start" name="wed_time_3_start">
                                                <span class="wave">~</span>
                                                <input type="time" class="form-control input" id="wed_time_3_end" name="wed_time_3_end">
                                            </div>
                                        </td>
                                        <td class="py-1">
                                            <div class="form-group d-flex">
                                                <input type="time" class="form-control input" id="wed_time_4_start" name="wed_time_4_start">
                                                <span class="wave">~</span>
                                                <input type="time" class="form-control input" id="wed_time_4_end" name="wed_time_4_end">
                                            </div>
                                        </td>
                                    </tr>
                                    <tr id="thu">
                                        <td class="py-3">목</td>
                                        <td class="py-1">
                                            <div class="form-group d-flex">
                                                <input type="time" class="form-control input" id="thu_time_1_start" name="thu_time_1_start" value="09:00">
                                                <span class="wave">~</span>
                                                <input type="time" class="form-control input" id="thu_time_1_end" name="thu_time_1_end" value="18:00">
                                            </div>
                                        </td>
                                        <td class="py-1">
                                            <div class="form-group d-flex">
                                                <input type="time" class="form-control input" id="thu_time_2_start" name="thu_time_2_start">
                                                <span class="wave">~</span>
                                                <input type="time" class="form-control input" id="thu_time_2_end" name="thu_time_2_end">
                                            </div>
                                        </td>
                                        <td class="py-1">
                                            <div class="form-group d-flex">
                                                <input type="time" class="form-control input" id="thu_time_3_start" name="thu_time_3_start">
                                                <span class="wave">~</span>
                                                <input type="time" class="form-control input" id="thu_time_3_end" name="thu_time_3_end">
                                            </div>
                                        </td>
                                        <td class="py-1">
                                            <div class="form-group d-flex">
                                                <input type="time" class="form-control input" id="thu_time_4_start" name="thu_time_4_start">
                                                <span class="wave">~</span>
                                                <input type="time" class="form-control input" id="thu_time_4_end" name="thu_time_4_end">
                                            </div>
                                        </td>
                                    </tr>
                                    <tr id="fri">
                                        <td class="py-3">금</td>
                                        <td class="py-1">
                                            <div class="form-group d-flex">
                                                <input type="time" class="form-control input" id="fri_time_1_start" name="fri_time_1_start" value="09:00">
                                                <span class="wave">~</span>
                                                <input type="time" class="form-control input" id="fri_time_1_end" name="fri_time_1_end" value="18:00">
                                            </div>
                                        </td>
                                        <td class="py-1">
                                            <div class="form-group d-flex">
                                                <input type="time" class="form-control input" id="fri_time_2_start" name="fri_time_2_start">
                                                <span class="wave">~</span>
                                                <input type="time" class="form-control input" id="fri_time_2_end" name="fri_time_2_end">
                                            </div>
                                        </td>
                                        <td class="py-1">
                                            <div class="form-group d-flex">
                                                <input type="time" class="form-control input" id="fri_time_3_start" name="fri_time_3_start">
                                                <span class="wave">~</span>
                                                <input type="time" class="form-control input" id="fri_time_3_end" name="fri_time_3_end">
                                            </div>
                                        </td>
                                        <td class="py-1">
                                            <div class="form-group d-flex">
                                                <input type="time" class="form-control input" id="fri_time_4_start" name="fri_time_4_start">
                                                <span class="wave">~</span>
                                                <input type="time" class="form-control input" id="fri_time_4_end" name="fri_time_4_end">
                                            </div>
                                        </td>
                                    </tr>
                                    <tr id="sat">
                                        <td class="py-3">토</td>
                                        <td class="py-1">
                                            <div class="form-group d-flex">
                                                <input type="time" class="form-control input" id="sat_time_1_start" name="sat_time_1_start" value="09:00">
                                                <span class="wave">~</span>
                                                <input type="time" class="form-control input" id="sat_time_1_end" name="sat_time_1_end" value="18:00">
                                            </div>
                                        </td>
                                        <td class="py-1">
                                            <div class="form-group d-flex">
                                                <input type="time" class="form-control input" id="sat_time_2_start" name="sat_time_2_start">
                                                <span class="wave">~</span>
                                                <input type="time" class="form-control input" id="sat_time_2_end" name="sat_time_2_end">
                                            </div>
                                        </td>
                                        <td class="py-1">
                                            <div class="form-group d-flex">
                                                <input type="time" class="form-control input" id="sat_time_3_start" name="sat_time_3_start">
                                                <span class="wave">~</span>
                                                <input type="time" class="form-control input" id="sat_time_3_end" name="sat_time_3_end">
                                            </div>
                                        </td>
                                        <td class="py-1">
                                            <div class="form-group d-flex">
                                                <input type="time" class="form-control input" id="sat_time_4_start" name="sat_time_4_start">
                                                <span class="wave">~</span>
                                                <input type="time" class="form-control input" id="sat_time_4_end" name="sat_time_4_end">
                                            </div>
                                        </td>
                                    </tr>
                                    <tr id="sun">
                                        <td class="py-3">일</td>
                                        <td class="py-1">
                                            <div class="form-group d-flex">
                                                <input type="time" class="form-control input" id="sun_time_1_start" name="sun_time_1_start" value="09:00">
                                                <span class="wave">~</span>
                                                <input type="time" class="form-control input" id="sun_time_1_end" name="sun_time_1_end" value="18:00">
                                            </div>
                                        </td>
                                        <td class="py-1">
                                            <div class="form-group d-flex">
                                                <input type="time" class="form-control input" id="sun_time_2_start" name="sun_time_2_start">
                                                <span class="wave">~</span>
                                                <input type="time" class="form-control input" id="sun_time_2_end" name="sun_time_2_end">
                                            </div>
                                        </td>
                                        <td class="py-1">
                                            <div class="form-group d-flex">
                                                <input type="time" class="form-control input" id="sun_time_3_start" name="sun_time_3_start">
                                                <span class="wave">~</span>
                                                <input type="time" class="form-control input" id="sun_time_3_end" name="sun_time_3_end">
                                            </div>
                                        </td>
                                        <td class="py-1">
                                            <div class="form-group d-flex">
                                                <input type="time" class="form-control input" id="sun_time_4_start" name="sun_time_4_start">
                                                <span class="wave">~</span>
                                                <input type="time" class="form-control input" id="sun_time_4_end" name="sun_time_4_end">
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </form>
                    </div>

                    <div id="acc_holiday_group_body" style="height: 35rem; overflow: auto; padding-right: 4.5rem; margin-bottom: 1rem; display: none;">
                    <div class="form-group d-flex">
                        <button type="button" onclick ="javascript:holiday_group_plus();" class="btn" style="position: relative; color: #fff; background-color: #265df1; border-color: #265df1; margin-left: 10px;">추가</button>
                        <button type="button" onclick ="" class="btn" style="position: relative; color: #fff; background-color: #265df1; border-color: #265df1; margin-left: 10px;">복사</button>
                    </div>      
                        <form name="acc_holiday_group_form" method = "POST" style="width: 33rem;">
                            <table class="table text-nowrap mb-0">
                                <thead>
                                <tr>
                                    <th class="font-weight-semi-bold border-top-0 py-2">휴일 이름</th>
                                    <th class="font-weight-semi-bold border-top-0 py-2">시작일</th>
                                    <th class="font-weight-semi-bold border-top-0 py-2">종료일</th>
                                    <th class="font-weight-semi-bold border-top-0 py-2">시간 범위1</th>
                                    <th class="font-weight-semi-bold border-top-0 py-2">시간 범위2</th>
                                    <th class="font-weight-semi-bold border-top-0 py-2">시간 범위3</th>
                                    <th class="font-weight-semi-bold border-top-0 py-2">시간 범위4</th>
                                    
                                </tr>
                                </thead>
                                <tbody>
                                    <tr id="hol_cfg_id1">
                                        <td class="py-1">
                                            <div class="form-group">
                                                <input type="text" class="form-control input input-11" id="hol_cfg1_name" name="hol_cfg1_name">
                                            </div>
                                        </td>
                                        <td class="py-1">
                                            <div class="form-group">
                                                <input type="date" class="form-control input input-11" id="hol_cfg1_bez_day" name="hol_cfg1_bez_date">
                                            </div>
                                        </td>
                                        <td class="py-1">
                                            <div class="form-group">
                                                <input type="date" class="form-control input input-11" id="hol_cfg1_end_date" name="hol_cfg1_end_date">
                                            </div>
                                        </td>
                                        <td class="py-1">
                                            <div class="form-group d-flex">
                                                <input type="time" class="form-control input" id="hol_cfg1_start1" name="hol_cfg1_start1" value="">
                                                <span class="wave">~</span>
                                                <input type="time" class="form-control input" id="hol_cfg1_end1" name="hol_cfg1_end1" value="">
                                            </div>
                                        </td>
                                        <td class="py-1">
                                            <div class="form-group d-flex">
                                                <input type="time" class="form-control input" id="hol_cfg1_start2" name="hol_cfg1_start2" value="">
                                                <span class="wave">~</span>
                                                <input type="time" class="form-control input" id="hol_cfg1_end2" name="hol_cfg1_end2" value="">
                                            </div>
                                        </td>
                                        <td class="py-1">
                                            <div class="form-group d-flex">
                                                <input type="time" class="form-control input" id="hol_cfg1_start3" name="hol_cfg1_start3" value="">
                                                <span class="wave">~</span>
                                                <input type="time" class="form-control input" id="hol_cfg1_end3" name="hol_cfg1_end3" value="">
                                            </div>
                                        </td>
                                        <td class="py-1">
                                            <div class="form-group d-flex">
                                                <input type="time" class="form-control input" id="hol_cfg1_start4" name="hol_cfg1_start4" value="">
                                                <span class="wave">~</span>
                                                <input type="time" class="form-control input" id="hol_cfg1_end4" name="hol_cfg1_end4" value="">
                                            </div>
                                        </td>
                                    </tr>
                                    <tr id="hol_cfg_id2" class="displayHide">
                                    <td class="py-1">
                                            <div class="form-group">
                                                <input type="text" class="form-control input input-11" id="hol_cfg2_name" name="hol_cfg2_name">
                                            </div>
                                        </td>
                                        <td class="py-1">
                                            <div class="form-group">
                                                <input type="date" class="form-control input input-11" id="hol_cfg2_bez_day" name="hol_cfg2_bez_day">
                                            </div>
                                        </td>
                                        <td class="py-1">
                                            <div class="form-group">
                                                <input type="date" class="form-control input input-11" id="hol_cfg2_end_date" name="hol_cfg2_end_date">
                                            </div>
                                        </td>
                                        <td class="py-1">
                                            <div class="form-group d-flex">
                                                <input type="time" class="form-control input" id="hol_cfg2_start1" name="hol_cfg2_start1" value="">
                                                <span class="wave">~</span>
                                                <input type="time" class="form-control input" id="hol_cfg2_end1" name="hol_cfg2_end1" value="">
                                            </div>
                                        </td>
                                        <td class="py-1">
                                            <div class="form-group d-flex">
                                                <input type="time" class="form-control input" id="hol_cfg2_start2" name="hol_cfg2_start2" value="">
                                                <span class="wave">~</span>
                                                <input type="time" class="form-control input" id="hol_cfg2_end2" name="hol_cfg2_end2" value="">
                                            </div>
                                        </td>
                                        <td class="py-1">
                                            <div class="form-group d-flex">
                                                <input type="time" class="form-control input" id="hol_cfg2_start3" name="hol_cfg2_start3" value="">
                                                <span class="wave">~</span>
                                                <input type="time" class="form-control input" id="hol_cfg2_end3" name="hol_cfg2_end3" value="">
                                            </div>
                                        </td>
                                        <td class="py-1">
                                            <div class="form-group d-flex">
                                                <input type="time" class="form-control input" id="hol_cfg2_start4" name="hol_cfg2_start4" value="">
                                                <span class="wave">~</span>
                                                <input type="time" class="form-control input" id="hol_cfg2_end4" name="hol_cfg2_end4" value="">
                                            </div>
                                        </td>
                                    </tr>
                                    <tr id="hol_cfg_id3" class="displayHide">
                                    <td class="py-1">
                                            <div class="form-group">
                                                <input type="text" class="form-control input input-11" id="hol_cfg3_name" name="hol_cfg3_name">
                                            </div>
                                        </td>
                                        <td class="py-1">
                                            <div class="form-group">
                                                <input type="date" class="form-control input input-11" id="hol_cfg3_bez_day" name="hol_cfg3_bez_day">
                                            </div>
                                        </td>
                                        <td class="py-1">
                                            <div class="form-group">
                                                <input type="date" class="form-control input input-11" id="hol_cfg3_end_date" name="hol_cfg3_end_date">
                                            </div>
                                        </td>
                                        <td class="py-1">
                                            <div class="form-group d-flex">
                                                <input type="time" class="form-control input" id="hol_cfg3_start1" name="hol_cfg3_start1" value="">
                                                <span class="wave">~</span>
                                                <input type="time" class="form-control input" id="hol_cfg3_end1" name="hol_cfg3_end1" value="" >
                                            </div>
                                        </td>
                                        <td class="py-1">
                                            <div class="form-group d-flex">
                                                <input type="time" class="form-control input" id="hol_cfg3_start2" name="hol_cfg3_start2" value="">
                                                <span class="wave">~</span>
                                                <input type="time" class="form-control input" id="hol_cfg3_end2" name="hol_cfg3_end2" value=""> 
                                            </div>
                                        </td>
                                        <td class="py-1">
                                            <div class="form-group d-flex">
                                                <input type="time" class="form-control input" id="hol_cfg3_start3" name="hol_cfg3_start3" value="">
                                                <span class="wave">~</span>
                                                <input type="time" class="form-control input" id="hol_cfg3_end3" name="hol_cfg3_end3" value="">
                                            </div>
                                        </td>
                                        <td class="py-1">
                                            <div class="form-group d-flex">
                                                <input type="time" class="form-control input" id="hol_cfg3_start4" name="hol_cfg3_start4" value="">
                                                <span class="wave">~</span>
                                                <input type="time" class="form-control input" id="hol_cfg3_end4" name="hol_cfg3_end4" value="">
                                            </div>
                                        </td>
                                    </tr>
                                    <tr id="hol_cfg_id4" class="displayHide">
                                    <td class="py-1">
                                            <div class="form-group">
                                                <input type="text" class="form-control input input-11" id="hol_cfg4_name" name="hol_cfg4_name">
                                            </div>
                                        </td>
                                        <td class="py-1">
                                            <div class="form-group">
                                                <input type="date" class="form-control input input-11" id="hol_cfg4_bez_day" name="hol_cfg4_bez_day">
                                            </div>
                                        </td>
                                        <td class="py-1">
                                            <div class="form-group">
                                                <input type="date" class="form-control input input-11" id="hol_cfg4_end_date" name="hol_cfg4_end_date">
                                            </div>
                                        </td>
                                        <td class="py-1">
                                            <div class="form-group d-flex">
                                                <input type="time" class="form-control input" id="hol_cfg4_start1" name="hol_cfg4_start1" value="">
                                                <span class="wave">~</span>
                                                <input type="time" class="form-control input" id="hol_cfg4_end1" name="hol_cfg4_end1" value="">
                                            </div>
                                        </td>
                                        <td class="py-1">
                                            <div class="form-group d-flex">
                                                <input type="time" class="form-control input" id="hol_cfg4_start2" name="hol_cfg4_start2" value="">
                                                <span class="wave">~</span>
                                                <input type="time" class="form-control input" id="hol_cfg4_end2" name="hol_cfg4_end2" value="">
                                            </div>
                                        </td>
                                        <td class="py-1">
                                            <div class="form-group d-flex">
                                                <input type="time" class="form-control input" id="hol_cfg4_start3" name="hol_cfg4_start3" value="">
                                                <span class="wave">~</span>
                                                <input type="time" class="form-control input" id="hol_cfg4_end3" name="hol_cfg4_end3" value="">
                                            </div>
                                        </td>
                                        <td class="py-1">
                                            <div class="form-group d-flex"> 
                                                <input type="time" class="form-control input" id="hol_cfg4_start4" name="hol_cfg4_start4" value="">
                                                <span class="wave">~</span>
                                                <input type="time" class="form-control input" id="hol_cfg4_end4" name="hol_cfg4_end4" value="">
                                            </div>
                                        </td>
                                    </tr>
                                    <tr id="hol_cfg_id5" class="displayHide">
                                    <td class="py-1">
                                            <div class="form-group">
                                                <input type="text" class="form-control input input-11" id="hol_cfg5_name" name="hol_cfg5_name">
                                            </div>
                                        </td>
                                        <td class="py-1">
                                            <div class="form-group">
                                                <input type="date" class="form-control input input-11" id="hol_cfg5_bez_day" name="hol_cfg5_bez_day">
                                            </div>
                                        </td>
                                        <td class="py-1">
                                            <div class="form-group">
                                                <input type="date" class="form-control input input-11" id="hol_cfg5_end_date" name="hol_cfg5_end_date">
                                            </div>
                                        </td>
                                        <td class="py-1">
                                            <div class="form-group d-flex">
                                                <input type="time" class="form-control input" id="hol_cfg5_start1" name="hol_cfg5_start1" value="">
                                                <span class="wave">~</span>
                                                <input type="time" class="form-control input" id="hol_cfg5_end1" name="hol_cfg5_end1" value="">
                                            </div>
                                        </td>
                                        <td class="py-1">
                                            <div class="form-group d-flex">
                                                <input type="time" class="form-control input" id="hol_cfg5_start2" name="hol_cfg5_start2" value="">
                                                <span class="wave">~</span>
                                                <input type="time" class="form-control input" id="hol_cfg5_end2" name="hol_cfg5_end2" value="">
                                            </div>
                                        </td>
                                        <td class="py-1">
                                            <div class="form-group d-flex">
                                                <input type="time" class="form-control input" id="hol_cfg5_start3" name="hol_cfg5_start3" value="">
                                                <span class="wave">~</span>
                                                <input type="time" class="form-control input" id="hol_cfg5_end3" name="hol_cfg5_end3" value="">
                                            </div>
                                        </td>
                                        <td class="py-1">
                                            <div class="form-group d-flex">
                                                <input type="time" class="form-control input" id="hol_cfg5_start4" name="hol_cfg5_start4" value="">
                                                <span class="wave">~</span>
                                                <input type="time" class="form-control input" id="hol_cfg5_end4" name="hol_cfg5_end4" value="">
                                            </div>
                                        </td>
                                    </tr>
                                    <tr id="hol_cfg_id6" class="displayHide">
                                    <td class="py-1">
                                            <div class="form-group">
                                                <input type="text" class="form-control input input-11" id="hol_cfg6_name" name="hol_cfg6_name">
                                            </div>
                                        </td>
                                        <td class="py-1">
                                            <div class="form-group">
                                                <input type="date" class="form-control input input-11" id="hol_cfg6_bez_day" name="hol_cfg6_bez_day">
                                            </div>
                                        </td>
                                        <td class="py-1">
                                            <div class="form-group">
                                                <input type="date" class="form-control input input-11" id="hol_cfg6_end_date" name="hol_cfg6_end_date">
                                            </div>
                                        </td>
                                        <td class="py-1">
                                            <div class="form-group d-flex">
                                                <input type="time" class="form-control input" id="hol_cfg6_start1" name="hol_cfg6_start1" value="">
                                                <span class="wave">~</span>
                                                <input type="time" class="form-control input" id="hol_cfg6_end1" name="hol_cfg6_end1" value="">
                                            </div>
                                        </td>
                                        <td class="py-1">
                                            <div class="form-group d-flex">
                                                <input type="time" class="form-control input" id="hol_cfg6_start2" name="hol_cfg6_start2" value="">
                                                <span class="wave">~</span>
                                                <input type="time" class="form-control input" id="hol_cfg6_end2" name="hol_cfg6_end2" value="">
                                            </div>
                                        </td>
                                        <td class="py-1">
                                            <div class="form-group d-flex">
                                                <input type="time" class="form-control input" id="hol_cfg6_start3" name="hol_cfg6_start3" value="">
                                                <span class="wave">~</span>
                                                <input type="time" class="form-control input" id="hol_cfg6_end3" name="hol_cfg6_end3" value="">
                                            </div>
                                        </td>
                                        <td class="py-1">
                                            <div class="form-group d-flex">
                                                <input type="time" class="form-control input" id="hol_cfg6_start4" name="hol_cfg6_start4" value="">
                                                <span class="wave">~</span>
                                                <input type="time" class="form-control input" id="hol_cfg6_end4" name="hol_cfg6_end4" value="">
                                            </div>
                                        </td>
                                    </tr>
                                    <tr id="hol_cfg_id7" class="displayHide">
                                    <td class="py-1">
                                            <div class="form-group">
                                                <input type="text" class="form-control input input-11" id="hol_cfg7_name" name="hol_cfg7_name">
                                            </div>
                                        </td>
                                        <td class="py-1">
                                            <div class="form-group">
                                                <input type="date" class="form-control input input-11" id="hol_cfg7_bez_day" name="hol_cfg7_bez_day">
                                            </div>
                                        </td>
                                        <td class="py-1">
                                            <div class="form-group">
                                                <input type="date" class="form-control input input-11" id="hol_cfg7_end_date" name="hol_cfg7_end_date">
                                            </div>
                                        </td>
                                        <td class="py-1">
                                            <div class="form-group d-flex">
                                                <input type="time" class="form-control input" id="hol_cfg7_start1" name="hol_cfg7_start1" value="">
                                                <span class="wave">~</span>
                                                <input type="time" class="form-control input" id="hol_cfg7_end1" name="hol_cfg7_end1" value="">
                                            </div>
                                        </td>
                                        <td class="py-1">
                                            <div class="form-group d-flex">
                                                <input type="time" class="form-control input" id="hol_cfg7_start2" name="hol_cfg7_start2" value="">
                                                <span class="wave">~</span>
                                                <input type="time" class="form-control input" id="hol_cfg7_end2" name="hol_cfg7_end2" value="">
                                            </div>
                                        </td>
                                        <td class="py-1">
                                            <div class="form-group d-flex">
                                                <input type="time" class="form-control input" id="hol_cfg7_start3" name="hol_cfg7_start3" value="">
                                                <span class="wave">~</span>
                                                <input type="time" class="form-control input" id="hol_cfg7_end3" name="hol_cfg7_end3" value="">
                                            </div>
                                        </td>
                                        <td class="py-1">
                                            <div class="form-group d-flex">
                                                <input type="time" class="form-control input" id="hol_cfg7_start4" name="hol_cfg7_start4" value="">
                                                <span class="wave">~</span> 
                                                <input type="time" class="form-control input" id="hol_cfg7_end4" name="hol_cfg7_end4" value="">
                                            </div>
                                        </td>
                                    </tr>
                                    <tr id="hol_cfg_id8" class="displayHide">
                                    <td class="py-1">
                                            <div class="form-group">
                                                <input type="text" class="form-control input input-11" id="hol_cfg8_name" name="hol_cfg8_name">
                                            </div>
                                        </td>
                                        <td class="py-1">
                                            <div class="form-group">
                                                <input type="date" class="form-control input input-11" id="hol_cfg8_bez_day" name="hol_cfg8_bez_day">
                                            </div>
                                        </td>
                                        <td class="py-1">
                                            <div class="form-group">
                                                <input type="date" class="form-control input input-11" id="hol_cfg8_end_date" name="hol_cfg8_end_date">
                                            </div>
                                        </td>
                                        <td class="py-1">
                                            <div class="form-group d-flex">
                                                <input type="time" class="form-control input" id="hol_cfg8_start1" name="hol_cfg8_start1" value="">
                                                <span class="wave">~</span>
                                                <input type="time" class="form-control input" id="hol_cfg8_end1" name="hol_cfg8_end1" value="">
                                            </div>
                                        </td>
                                        <td class="py-1">
                                            <div class="form-group d-flex">
                                                <input type="time" class="form-control input" id="hol_cfg8_start2" name="hol_cfg8_start2" value="">
                                                <span class="wave">~</span>
                                                <input type="time" class="form-control input" id="hol_cfg8_end2" name="hol_cfg8_end2" value="">
                                            </div>
                                        </td>
                                        <td class="py-1">
                                            <div class="form-group d-flex">
                                                <input type="time" class="form-control input" id="hol_cfg8_start3" name="hol_cfg8_start3" value="">
                                                <span class="wave">~</span>
                                                <input type="time" class="form-control input" id="hol_cfg8_end3" name="hol_cfg8_end3" value="">
                                            </div>
                                        </td>
                                        <td class="py-1">
                                            <div class="form-group d-flex">
                                                <input type="time" class="form-control input" id="hol_cfg8_start4" name="hol_cfg8_start4" value="">
                                                <span class="wave">~</span>
                                                <input type="time" class="form-control input" id="hol_cfg8_end4" name="hol_cfg8_end4" value="">
                                            </div>
                                        </td>
                                    </tr>
                                    <tr id="hol_cfg_id9" class="displayHide">
                                    <td class="py-1">
                                            <div class="form-group">
                                                <input type="text" class="form-control input input-11" id="hol_cfg9_name" name="hol_cfg9_name">
                                            </div>
                                        </td>
                                        <td class="py-1">
                                            <div class="form-group">
                                                <input type="date" class="form-control input input-11" id="hol_cfg9_bez_day" name="hol_cfg9_bez_day">
                                            </div>
                                        </td>
                                        <td class="py-1">
                                            <div class="form-group">
                                                <input type="date" class="form-control input input-11" id="hol_cfg9_end_date" name="hol_cfg9_end_date">
                                            </div>
                                        </td>
                                        <td class="py-1">
                                            <div class="form-group d-flex">
                                                <input type="time" class="form-control input" id="hol_cfg9_start1" name="hol_cfg9_start1" value="">
                                                <span class="wave">~</span>
                                                <input type="time" class="form-control input" id="hol_cfg9_end1" name="hol_cfg9_end1" value="">
                                            </div>
                                        </td>
                                        <td class="py-1">
                                            <div class="form-group d-flex">
                                                <input type="time" class="form-control input" id="hol_cfg9_start2" name="hol_cfg9_start2" value="">
                                                <span class="wave">~</span>
                                                <input type="time" class="form-control input" id="hol_cfg9_end2" name="hol_cfg9_end2" value="">
                                            </div>
                                        </td>
                                        <td class="py-1">
                                            <div class="form-group d-flex">
                                                <input type="time" class="form-control input" id="hol_cfg9_start3" name="hol_cfg9_start3" value="">
                                                <span class="wave">~</span>
                                                <input type="time" class="form-control input" id="hol_cfg9_end3" name="hol_cfg9_end3" value="">
                                            </div>
                                        </td>
                                        <td class="py-1">
                                            <div class="form-group d-flex">
                                                <input type="time" class="form-control input" id="hol_cfg9_start4" name="hol_cfg9_start4" value="">
                                                <span class="wave">~</span>
                                                <input type="time" class="form-control input" id="hol_cfg9_end4" name="hol_cfg9_end4" value="">
                                            </div>
                                        </td>
                                    </tr>
                                    <tr id="hol_cfg_id10" class="displayHide">
                                    <td class="py-1">
                                            <div class="form-group">
                                                <input type="text" class="form-control input input-11" id="hol_cfg10_name" name="hol_cfg10_name">
                                            </div>
                                        </td>
                                        <td class="py-1">
                                            <div class="form-group">
                                                <input type="date" class="form-control input input-11" id="hol_cfg10_bez_day" name="hol_cfg10_bez_day">
                                            </div>
                                        </td>
                                        <td class="py-1">
                                            <div class="form-group">
                                                <input type="date" class="form-control input input-11" id="hol_cfg10_end_date" name="hol_cfg10_end_date">
                                            </div>
                                        </td>
                                        <td class="py-1">
                                            <div class="form-group d-flex">
                                                <input type="time" class="form-control input" id="hol_cfg10_start1" name="hol_cfg10_start1" value="">
                                                <span class="wave">~</span>
                                                <input type="time" class="form-control input" id="hol_cfg10_end1" name="hol_cfg10_end1" value="">
                                            </div>
                                        </td>
                                        <td class="py-1">
                                            <div class="form-group d-flex">
                                                <input type="time" class="form-control input" id="hol_cfg10_start2" name="hol_cfg10_start2" value=""> 
                                                <span class="wave">~</span>
                                                <input type="time" class="form-control input" id="hol_cfg10_end2" name="hol_cfg10_end2" value="">
                                            </div>
                                        </td>
                                        <td class="py-1">
                                            <div class="form-group d-flex">
                                                <input type="time" class="form-control input" id="hol_cfg10_start3" name="hol_cfg10_start3" value="">
                                                <span class="wave">~</span>
                                                <input type="time" class="form-control input" id="hol_cfg10_end3" name="hol_cfg10_end3" value="">
                                            </div>
                                        </td>
                                        <td class="py-1">
                                            <div class="form-group d-flex">
                                                <input type="time" class="form-control input" id="hol_cfg10_start4" name="hol_cfg10_start4" value="">
                                                <span class="wave">~</span>
                                                <input type="time" class="form-control input" id="hol_cfg10_end4" name="hol_cfg10_end4" value="">
                                            </div>
                                        </td>
                                    </tr>
                                    <tr id="hol_cfg_id11" class="displayHide">
                                    <td class="py-1">
                                            <div class="form-group">
                                                <input type="text" class="form-control input input-11" id="hol_cfg11_name" name="hol_cfg11_name">
                                            </div>
                                        </td>
                                        <td class="py-1">
                                            <div class="form-group">
                                                <input type="date" class="form-control input input-11" id="hol_cfg11_bez_day" name="hol_cfg11_bez_day">
                                            </div>
                                        </td>
                                        <td class="py-1">
                                            <div class="form-group">
                                                <input type="date" class="form-control input input-11" id="hol_cfg11_end_date" name="hol_cfg11_end_date">
                                            </div>
                                        </td>
                                        <td class="py-1">
                                            <div class="form-group d-flex">
                                                <input type="time" class="form-control input" id="hol_cfg11_start1" name="hol_cfg11_start1" value="">
                                                <span class="wave">~</span>
                                                <input type="time" class="form-control input" id="hol_cfg11_end1" name="hol_cfg11_end1" value="">
                                            </div>
                                        </td>
                                        <td class="py-1">
                                            <div class="form-group d-flex">
                                                <input type="time" class="form-control input" id="hol_cfg11_start2" name="hol_cfg11_start2" value="">
                                                <span class="wave">~</span>
                                                <input type="time" class="form-control input" id="hol_cfg11_end2" name="hol_cfg11_end2" value="">
                                            </div>
                                        </td>
                                        <td class="py-1">
                                            <div class="form-group d-flex">
                                                <input type="time" class="form-control input" id="hol_cfg11_start3" name="hol_cfg11_start3" value="">
                                                <span class="wave">~</span>
                                                <input type="time" class="form-control input" id="hol_cfg11_end3" name="hol_cfg11_end3" value="">
                                            </div>
                                        </td>
                                        <td class="py-1">
                                            <div class="form-group d-flex">
                                                <input type="time" class="form-control input" id="hol_cfg11_start4" name="hol_cfg11_start4" value="">
                                                <span class="wave">~</span>
                                                <input type="time" class="form-control input" id="hol_cfg11_end4" name="hol_cfg11_end4" value="">
                                            </div>
                                        </td>
                                    </tr>
                                    <tr id="hol_cfg_id12" class="displayHide">
                                    <td class="py-1">
                                            <div class="form-group">
                                                <input type="text" class="form-control input input-11" id="hol_cfg12_name" name="hol_cfg12_name">
                                            </div>
                                        </td>
                                        <td class="py-1">
                                            <div class="form-group">
                                                <input type="date" class="form-control input input-11" id="hol_cfg12_bez_day" name="hol_cfg12_bez_day">
                                            </div>
                                        </td>
                                        <td class="py-1">
                                            <div class="form-group">
                                                <input type="date" class="form-control input input-11" id="hol_cfg12_end_date" name="hol_cfg12_end_date">
                                            </div>
                                        </td>
                                        <td class="py-1">
                                            <div class="form-group d-flex">
                                                <input type="time" class="form-control input" id="hol_cfg12_start1" name="hol_cfg12_start1" value="">
                                                <span class="wave">~</span>
                                                <input type="time" class="form-control input" id="hol_cfg12_end1" name="hol_cfg12_end1" value="">
                                            </div>
                                        </td>
                                        <td class="py-1">
                                            <div class="form-group d-flex">
                                                <input type="time" class="form-control input" id="hol_cfg12_start2" name="hol_cfg12_start2" value="">
                                                <span class="wave">~</span>
                                                <input type="time" class="form-control input" id="hol_cfg12_end2" name="hol_cfg12_end2" value="">
                                            </div>
                                        </td>
                                        <td class="py-1">
                                            <div class="form-group d-flex">
                                                <input type="time" class="form-control input" id="hol_cfg12_start3" name="hol_cfg12_start3" value="">
                                                <span class="wave">~</span>
                                                <input type="time" class="form-control input" id="hol_cfg12_end3" name="hol_cfg12_end3" value="">
                                            </div>
                                        </td>
                                        <td class="py-1">
                                            <div class="form-group d-flex">
                                                <input type="time" class="form-control input" id="hol_cfg12_start4" name="hol_cfg12_start4" value="">
                                                <span class="wave">~</span>
                                                <input type="time" class="form-control input" id="hol_cfg12_end4" name="hol_cfg12_end4" value="">
                                            </div>
                                        </td>
                                    </tr>
                                    <tr id="hol_cfg_id13" class="displayHide">
                                    <td class="py-1">
                                            <div class="form-group">
                                                <input type="text" class="form-control input input-11" id="hol_cfg13_name" name="hol_cfg13_name">
                                            </div>
                                        </td>
                                        <td class="py-1">
                                            <div class="form-group">
                                                <input type="date" class="form-control input input-11" id="hol_cfg13_bez_day" name="hol_cfg13_bez_day">
                                            </div>
                                        </td>
                                        <td class="py-1">
                                            <div class="form-group">
                                                <input type="date" class="form-control input input-11" id="hol_cfg13_end_date" name="hol_cfg13_end_date">
                                            </div>
                                        </td>
                                        <td class="py-1">
                                            <div class="form-group d-flex">
                                                <input type="time" class="form-control input" id="hol_cfg13_start1" name="hol_cfg13_start1" value="">
                                                <span class="wave">~</span>
                                                <input type="time" class="form-control input" id="hol_cfg13_end1" name="hol_cfg13_end1" value="">
                                            </div>
                                        </td>
                                        <td class="py-1">
                                            <div class="form-group d-flex">
                                                <input type="time" class="form-control input" id="hol_cfg13_start2" name="hol_cfg13_start2" value="">
                                                <span class="wave">~</span>
                                                <input type="time" class="form-control input" id="hol_cfg13_end2" name="hol_cfg13_end2" value="">
                                            </div>
                                        </td>
                                        <td class="py-1">
                                            <div class="form-group d-flex">
                                                <input type="time" class="form-control input" id="hol_cfg13_start3" name="hol_cfg13_start3" value="">
                                                <span class="wave">~</span>
                                                <input type="time" class="form-control input" id="hol_cfg13_end3" name="hol_cfg13_end3" value="">
                                            </div>
                                        </td>
                                        <td class="py-1">
                                            <div class="form-group d-flex">
                                                <input type="time" class="form-control input" id="hol_cfg13_start4" name="hol_cfg13_start4" value="">
                                                <span class="wave">~</span>
                                                <input type="time" class="form-control input" id="hol_cfg13_end4" name="hol_cfg13_end4" value="">
                                            </div>
                                        </td>
                                    </tr>
                                    <tr id="hol_cfg_id14" class="displayHide">
                                    <td class="py-1">
                                            <div class="form-group">
                                                <input type="text" class="form-control input input-11" id="hol_cfg14_name" name="hol_cfg14_name">
                                            </div>
                                        </td>
                                        <td class="py-1">
                                            <div class="form-group">
                                                <input type="date" class="form-control input input-11" id="hol_cfg14_bez_day" name="hol_cfg14_bez_day">
                                            </div>
                                        </td>
                                        <td class="py-1">
                                            <div class="form-group">
                                                <input type="date" class="form-control input input-11" id="hol_cfg14_end_date" name="hol_cfg14_end_date">
                                            </div>
                                        </td>
                                        <td class="py-1">
                                            <div class="form-group d-flex">
                                                <input type="time" class="form-control input" id="hol_cfg14_start1" name="hol_cfg14_start1" value="">
                                                <span class="wave">~</span>
                                                <input type="time" class="form-control input" id="hol_cfg14_end1" name="hol_cfg14_end1" value="">
                                            </div>
                                        </td>
                                        <td class="py-1">
                                            <div class="form-group d-flex">
                                                <input type="time" class="form-control input" id="hol_cfg14_start2" name="hol_cfg14_start2" value="">
                                                <span class="wave">~</span>
                                                <input type="time" class="form-control input" id="hol_cfg14_end2" name="hol_cfg14_end2" value="">
                                            </div>
                                        </td>
                                        <td class="py-1">
                                            <div class="form-group d-flex">
                                                <input type="time" class="form-control input" id="hol_cfg14_start3" name="hol_cfg14_start3" value="">
                                                <span class="wave">~</span>
                                                <input type="time" class="form-control input" id="hol_cfg14_end3" name="hol_cfg14_end3" value="">
                                            </div>
                                        </td>
                                        <td class="py-1">
                                            <div class="form-group d-flex">
                                                <input type="time" class="form-control input" id="hol_cfg14_start4" name="hol_cfg14_start4" value="">
                                                <span class="wave">~</span>
                                                <input type="time" class="form-control input" id="hol_cfg14_end4" name="hol_cfg14_end4" value="">
                                            </div>
                                        </td>
                                    </tr>
                                    <tr id="hol_cfg_id15" class="displayHide">
                                    <td class="py-1">
                                            <div class="form-group">
                                                <input type="text" class="form-control input input-11" id="hol_cfg15_name" name="hol_cfg15_name">
                                            </div>
                                        </td>
                                        <td class="py-1">
                                            <div class="form-group">
                                                <input type="date" class="form-control input input-11" id="hol_cfg15_bez_day" name="hol_cfg15_bez_day">
                                            </div>
                                        </td>
                                        <td class="py-1">
                                            <div class="form-group">
                                                <input type="date" class="form-control input input-11" id="hol_cfg15_end_date" name="hol_cfg15_end_date">
                                            </div>
                                        </td>
                                        <td class="py-1">
                                            <div class="form-group d-flex">
                                                <input type="time" class="form-control input" id="hol_cfg15_start1" name="hol_cfg15_start1" value="">
                                                <span class="wave">~</span>
                                                <input type="time" class="form-control input" id="hol_cfg15_end1" name="hol_cfg15_end1" value="">
                                            </div>
                                        </td>
                                        <td class="py-1">
                                            <div class="form-group d-flex">
                                                <input type="time" class="form-control input" id="hol_cfg15_start2" name="hol_cfg15_start2" value="">
                                                <span class="wave">~</span>
                                                <input type="time" class="form-control input" id="hol_cfg15_end2" name="hol_cfg15_end2" value="">
                                            </div>
                                        </td>
                                        <td class="py-1">
                                            <div class="form-group d-flex">
                                                <input type="time" class="form-control input" id="hol_cfg15_start3" name="hol_cfg15_start3" value="">
                                                <span class="wave">~</span>
                                                <input type="time" class="form-control input" id="hol_cfg15_end3" name="hol_cfg15_end3" value="">
                                            </div>
                                        </td>
                                        <td class="py-1">
                                            <div class="form-group d-flex">
                                                <input type="time" class="form-control input" id="hol_cfg15_start4" name="hol_cfg15_start4" value="">
                                                <span class="wave">~</span>
                                                <input type="time" class="form-control input" id="hol_cfg15_end4" name="hol_cfg15_end4" value="">
                                            </div>
                                        </td>
                                    </tr>
                                    <tr id="hol_cfg_id16" class="displayHide">
                                    <td class="py-1">
                                            <div class="form-group">
                                                <input type="text" class="form-control input input-11" id="hol_cfg16_name" name="hol_cfg16_name">
                                            </div>
                                        </td>
                                        <td class="py-1">
                                            <div class="form-group">
                                                <input type="date" class="form-control input input-11" id="hol_cfg16_bez_day" name="hol_cfg16_bez_day">
                                            </div>
                                        </td>
                                        <td class="py-1">
                                            <div class="form-group">
                                                <input type="date" class="form-control input input-11" id="hol_cfg16_end_date" name="hol_cfg16_end_date">
                                            </div>
                                        </td>
                                        <td class="py-1">
                                            <div class="form-group d-flex">
                                                <input type="time" class="form-control input" id="hol_cfg16_start1" name="hol_cfg16_start1" value="">
                                                <span class="wave">~</span>
                                                <input type="time" class="form-control input" id="hol_cfg16_end1" name="hol_cfg16_end1" value="">
                                            </div>
                                        </td>
                                        <td class="py-1">
                                            <div class="form-group d-flex">
                                                <input type="time" class="form-control input" id="hol_cfg16_start2" name="hol_cfg16_start2" value="">
                                                <span class="wave">~</span>
                                                <input type="time" class="form-control input" id="hol_cfg16_end2" name="hol_cfg16_end2" value="">
                                            </div>
                                        </td>
                                        <td class="py-1">
                                            <div class="form-group d-flex">
                                                <input type="time" class="form-control input" id="hol_cfg16_start3" name="hol_cfg16_start3" value="">
                                                <span class="wave">~</span>
                                                <input type="time" class="form-control input" id="hol_cfg16_end3" name="hol_cfg16_end3" value="">
                                            </div>
                                        </td>
                                        <td class="py-1">
                                            <div class="form-group d-flex">
                                                <input type="time" class="form-control input" id="hol_cfg16_start4" name="hol_cfg16_start4" value="" >
                                                <span class="wave">~</span>
                                                <input type="time" class="form-control input" id="hol_cfg16_end4" name="hol_cfg16_end4" value="">
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </form>
                    </div>

                    <button type="button" onclick="javascript: go_acc_time_group_insert2();" class="btn btn-primary btn-block" style="width:8rem;float:right;margin-top: 15px;">저 장</button>
                </div>
                
            </div>
            
        </div>
        
        <!-- Footer -->
        <?php include '../footer.php'?>
        <!-- End Footer -->
    </div>
    
</main>

<script src="../public/graindashboard/js/graindashboard.js"></script>
<script src="../public/graindashboard/js/graindashboard.vendor.js"></script>
<script src="../public/graindashboard/js/onepass.js"></script>
<script src="../public/graindashboard/js/jquery.timepicker.js"></script>
<script src="../public/graindashboard/js/jquery.timepicker.min.js"></script>


<!-- DEMO CHARTS -->
<script src="../public/demo/resizeSensor.js"></script>
<script src="../public/demo/chartist.js"></script>
<script src="../public/demo/chartist-plugin-tooltip.js"></script>
<script src="../public/demo/gd.chartist-area.js"></script>
<script src="../public/demo/gd.chartist-bar.js"></script>
<script src="../public/demo/gd.chartist-donut.js"></script>




<iframe type="hidden" id="hiddenfrm" name="hiddenfrm" style="display: none;"></iframe>
</body>
</html>
<script>16
    function holiday_group_plus() {
        for (let i = 1; i < 17; i++) {
            if ($("#hol_cfg_id" + i).css("display") == "none") {
                $("#hol_cfg_id" + i).css("display","table-row");
                break;
            }
        }
    }
    function changeHoliday(job) {
        if (job == "group") {
            $("#acc_week_body").hide();
            $("#acc_holiday_group_body").show();
            $("#holi_week").css("background-color","#fff");
            $("#holi_group").css("background-color","#265df1");
            $("#holi_week").css("color","#000");
            $("#holi_group").css("color","#fff");
        }else {
            $("#acc_week_body").show();
            $("#acc_holiday_group_body").hide();
            $("#holi_week").css("background-color","#265df1");
            $("#holi_group").css("background-color","#fff");
            $("#holi_week").css("color","#fff");
            $("#holi_group").css("color","#000");
        }
    }
    
    var searchStr = '<?=$searchStr?>';
    var page_no = '<?=$page_no?>';
    var page_count = '<?=$page_count?>';
    page_count = Number(page_count);
    var search_start_num = '<?=$search_start_num?>';
    search_start_num = Number(search_start_num);
    var totalPage = '<?=$totalPage?>';
    totalPage = Number(totalPage);



function table_csschg(id){

var table = document.getElementById("access-time-group-content");
var tr = table.getElementsByTagName("tr");

for(var i=0; i<tr.length; i++){
    
    if(id == $(tr[i]).attr('value')){
        $(tr[i]).attr('style','background-color:#eeeef1;');
    }else if(id != $(tr[i]).attr('value')){

        $(tr[i]).attr('style','background-color:#fff;');
    }


}
}

function type_form_reset(){
    $('#acc_time_group_name').val("");
    for(let i=2; i<=16; i++){
        $(`#hol_cfg_id${i}`).addClass('displayHide');

        $(`#mon_time_1_start`).attr('value',"09:00");
        $(`#tue_time_1_start`).attr('value',"09:00");
        $(`#wed_time_1_start`).attr('value',"09:00");
        $(`#thu_time_1_start`).attr('value',"09:00");
        $(`#fri_time_1_start`).attr('value',"09:00");
        $(`#sat_time_1_start`).attr('value',"09:00");
        $(`#sun_time_1_start`).attr('value',"09:00");

        $(`#mon_time_1_end`).attr('value',"18:00");
        $(`#tue_time_1_end`).attr('value',"18:00");
        $(`#wed_time_1_end`).attr('value',"18:00");
        $(`#thu_time_1_end`).attr('value',"18:00");
        $(`#fri_time_1_end`).attr('value',"18:00");
        $(`#sat_time_1_end`).attr('value',"18:00");
        $(`#sun_time_1_end`).attr('value',"18:00");
    }
    for(let j=1; j<=16; j++){
        $(`#hol_cfg${j}_name`).val("");
        $(`#hol_cfg${j}_bez_day`).val("");
        $(`#hol_cfg${j}_end_date`).val("");
        $(`#hol_cfg${j}_start1`).val("");
        $(`#hol_cfg${j}_start2`).val("");
        $(`#hol_cfg${j}_start3`).val("");
        $(`#hol_cfg${j}_start4`).val("");
        $(`#hol_cfg${j}_end1`).val("");
        $(`#hol_cfg${j}_end2`).val("");
        $(`#hol_cfg${j}_end3`).val("");
        $(`#hol_cfg${j}_end4`).val("");
    }
    $('.access-time-title').text("출입 시간 그룹 추가");
    $('.btn-block').attr('onclick','');
    $('.btn-block').attr('onclick','javascript: go_acc_time_group_insert2();');
}


document.addEventListener('DOMContentLoaded', function() {
    var elems = document.querySelectorAll('.timepicker');
    var instances = M.Timepicker.init(elems, options);
  });

  // Or with jQuery

  $(document).ready(function(){
    $('.timepicker').timepicker();
  });
        


</script>