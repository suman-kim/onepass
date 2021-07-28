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
    
    $dataRs = get("/v1/access-weeks");
    $data = json_decode($dataRs);

    if ($data->error_code == 1) {
        $str = "";
        $searchRows = 0;
        $totalRows = count($data->access_week_infos);
        $startNum = ($search_start_num == 0 ) ?  ($page_no-1) * $page_count : $search_start_num;

        for ($i = $startNum; $i < $totalRows; $i++) {
            if (!$noSearch) {
                $accWeekName = (strpos((String)$data->access_week_infos[$i]->name, $searchStr) !== FALSE) ? TRUE : FALSE;
                $monTime1 = (strpos((String)$data->access_week_infos[$i]->mon_time_1, $searchStr) !== FALSE) ? TRUE : FALSE;
                $monTime2 = (strpos((String)$data->access_week_infos[$i]->mon_time_2, $searchStr) !== FALSE) ? TRUE : FALSE;
                $monTime3 = (strpos((String)$data->access_week_infos[$i]->mon_time_3, $searchStr) !== FALSE) ? TRUE : FALSE;
                $monTime4 = (strpos((String)$data->access_week_infos[$i]->mon_time_4, $searchStr) !== FALSE) ? TRUE : FALSE;
                $tueTime1 = (strpos((String)$data->access_week_infos[$i]->tue_time_1, $searchStr) !== FALSE) ? TRUE : FALSE;
                $tueTime2 = (strpos((String)$data->access_week_infos[$i]->tue_time_2, $searchStr) !== FALSE) ? TRUE : FALSE;
                $tueTime3 = (strpos((String)$data->access_week_infos[$i]->tue_time_3, $searchStr) !== FALSE) ? TRUE : FALSE;
                $tueTime4 = (strpos((String)$data->access_week_infos[$i]->tue_time_4, $searchStr) !== FALSE) ? TRUE : FALSE;
                $wedTime1 = (strpos((String)$data->access_week_infos[$i]->wed_time_1, $searchStr) !== FALSE) ? TRUE : FALSE;
                $wedTime2 = (strpos((String)$data->access_week_infos[$i]->wed_time_2, $searchStr) !== FALSE) ? TRUE : FALSE;
                $wedTime3 = (strpos((String)$data->access_week_infos[$i]->wed_time_3, $searchStr) !== FALSE) ? TRUE : FALSE;
                $wedTime4 = (strpos((String)$data->access_week_infos[$i]->wed_time_4, $searchStr) !== FALSE) ? TRUE : FALSE;
                $thuTime1 = (strpos((String)$data->access_week_infos[$i]->thu_time_1, $searchStr) !== FALSE) ? TRUE : FALSE;
                $thuTime2 = (strpos((String)$data->access_week_infos[$i]->thu_time_2, $searchStr) !== FALSE) ? TRUE : FALSE;
                $thuTime3 = (strpos((String)$data->access_week_infos[$i]->thu_time_3, $searchStr) !== FALSE) ? TRUE : FALSE;
                $thuTime4 = (strpos((String)$data->access_week_infos[$i]->thu_time_4, $searchStr) !== FALSE) ? TRUE : FALSE;
                $friTime1 = (strpos((String)$data->access_week_infos[$i]->fri_time_1, $searchStr) !== FALSE) ? TRUE : FALSE;
                $friTime2 = (strpos((String)$data->access_week_infos[$i]->fri_time_2, $searchStr) !== FALSE) ? TRUE : FALSE;
                $friTime3 = (strpos((String)$data->access_week_infos[$i]->fri_time_3, $searchStr) !== FALSE) ? TRUE : FALSE;
                $friTime4 = (strpos((String)$data->access_week_infos[$i]->fri_time_4, $searchStr) !== FALSE) ? TRUE : FALSE;
                $satTime1 = (strpos((String)$data->access_week_infos[$i]->sat_time_1, $searchStr) !== FALSE) ? TRUE : FALSE;
                $satTime2 = (strpos((String)$data->access_week_infos[$i]->sat_time_2, $searchStr) !== FALSE) ? TRUE : FALSE;
                $satTime3 = (strpos((String)$data->access_week_infos[$i]->sat_time_3, $searchStr) !== FALSE) ? TRUE : FALSE;
                $satTime4 = (strpos((String)$data->access_week_infos[$i]->sat_time_4, $searchStr) !== FALSE) ? TRUE : FALSE;
                $sunTime1 = (strpos((String)$data->access_week_infos[$i]->sun_time_1, $searchStr) !== FALSE) ? TRUE : FALSE;
                $sunTime2 = (strpos((String)$data->access_week_infos[$i]->sun_time_2, $searchStr) !== FALSE) ? TRUE : FALSE;
                $sunTime3 = (strpos((String)$data->access_week_infos[$i]->sun_time_3, $searchStr) !== FALSE) ? TRUE : FALSE;
                $sunTime4 = (strpos((String)$data->access_week_infos[$i]->sun_time_4, $searchStr) !== FALSE) ? TRUE : FALSE;
            }

            if ( $accWeekName || $noSearch ) {
                $str .= '<tr class="">';
                $str .= '<td class="py-2"><input type="checkbox" name="choice[]" value="'.$data->access_week_infos[$i]->id.'"></td>';
                $str .= ($noSearch) ? '<td class="py-2">'.($i+1).'</td>' : '<td class="py-2">'.( $searchRows+(($page_no-1)*$page_count) + 1 ).'</td>';
                //$str .= '<td class="py-2">'.$data->access_week_infos[$i]->id.'</td>';
                $str .= '<td class="align-middle py-2">'.$data->access_week_infos[$i]->name.'</td>';
                $str .= '<td class="py-2">'.$data->access_week_infos[$i]->name.'</td>';
                $str .= '<td class="py-2">';
                $str .= '<div class="position-relative">';
                $str .= '<a class="link-dark d-inline-block" onclick="javascript: access_week_update('.$data->access_week_infos[$i]->id.');" data-toggle="modal" data-target="#accWeekModal" data-backdrop="static" keybord="false" href="#">';
                $str .= '<i class="gd-pencil icon-text"></i>';
                $str .= '</a>';
                $str .= '<a class="link-dark d-inline-block" onclick="javascript: goDelete('.$data->access_week_infos[$i]->id.',\'access-week\');" href="#">';
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
                $accWeekName = (strpos((String)$data->access_week_infos[$i]->name, $searchStr) !== FALSE) ? TRUE : FALSE;
                $monTime1 = (strpos((String)$data->access_week_infos[$i]->mon_time_1, $searchStr) !== FALSE) ? TRUE : FALSE;
                $monTime2 = (strpos((String)$data->access_week_infos[$i]->mon_time_2, $searchStr) !== FALSE) ? TRUE : FALSE;
                $monTime3 = (strpos((String)$data->access_week_infos[$i]->mon_time_3, $searchStr) !== FALSE) ? TRUE : FALSE;
                $monTime4 = (strpos((String)$data->access_week_infos[$i]->mon_time_4, $searchStr) !== FALSE) ? TRUE : FALSE;
                $tueTime1 = (strpos((String)$data->access_week_infos[$i]->tue_time_1, $searchStr) !== FALSE) ? TRUE : FALSE;
                $tueTime2 = (strpos((String)$data->access_week_infos[$i]->tue_time_2, $searchStr) !== FALSE) ? TRUE : FALSE;
                $tueTime3 = (strpos((String)$data->access_week_infos[$i]->tue_time_3, $searchStr) !== FALSE) ? TRUE : FALSE;
                $tueTime4 = (strpos((String)$data->access_week_infos[$i]->tue_time_4, $searchStr) !== FALSE) ? TRUE : FALSE;
                $wedTime1 = (strpos((String)$data->access_week_infos[$i]->wed_time_1, $searchStr) !== FALSE) ? TRUE : FALSE;
                $wedTime2 = (strpos((String)$data->access_week_infos[$i]->wed_time_2, $searchStr) !== FALSE) ? TRUE : FALSE;
                $wedTime3 = (strpos((String)$data->access_week_infos[$i]->wed_time_3, $searchStr) !== FALSE) ? TRUE : FALSE;
                $wedTime4 = (strpos((String)$data->access_week_infos[$i]->wed_time_4, $searchStr) !== FALSE) ? TRUE : FALSE;
                $thuTime1 = (strpos((String)$data->access_week_infos[$i]->thu_time_1, $searchStr) !== FALSE) ? TRUE : FALSE;
                $thuTime2 = (strpos((String)$data->access_week_infos[$i]->thu_time_2, $searchStr) !== FALSE) ? TRUE : FALSE;
                $thuTime3 = (strpos((String)$data->access_week_infos[$i]->thu_time_3, $searchStr) !== FALSE) ? TRUE : FALSE;
                $thuTime4 = (strpos((String)$data->access_week_infos[$i]->thu_time_4, $searchStr) !== FALSE) ? TRUE : FALSE;
                $friTime1 = (strpos((String)$data->access_week_infos[$i]->fri_time_1, $searchStr) !== FALSE) ? TRUE : FALSE;
                $friTime2 = (strpos((String)$data->access_week_infos[$i]->fri_time_2, $searchStr) !== FALSE) ? TRUE : FALSE;
                $friTime3 = (strpos((String)$data->access_week_infos[$i]->fri_time_3, $searchStr) !== FALSE) ? TRUE : FALSE;
                $friTime4 = (strpos((String)$data->access_week_infos[$i]->fri_time_4, $searchStr) !== FALSE) ? TRUE : FALSE;
                $satTime1 = (strpos((String)$data->access_week_infos[$i]->sat_time_1, $searchStr) !== FALSE) ? TRUE : FALSE;
                $satTime2 = (strpos((String)$data->access_week_infos[$i]->sat_time_2, $searchStr) !== FALSE) ? TRUE : FALSE;
                $satTime3 = (strpos((String)$data->access_week_infos[$i]->sat_time_3, $searchStr) !== FALSE) ? TRUE : FALSE;
                $satTime4 = (strpos((String)$data->access_week_infos[$i]->sat_time_4, $searchStr) !== FALSE) ? TRUE : FALSE;
                $sunTime1 = (strpos((String)$data->access_week_infos[$i]->sun_time_1, $searchStr) !== FALSE) ? TRUE : FALSE;
                $sunTime2 = (strpos((String)$data->access_week_infos[$i]->sun_time_2, $searchStr) !== FALSE) ? TRUE : FALSE;
                $sunTime3 = (strpos((String)$data->access_week_infos[$i]->sun_time_3, $searchStr) !== FALSE) ? TRUE : FALSE;
                $sunTime4 = (strpos((String)$data->access_week_infos[$i]->sun_time_4, $searchStr) !== FALSE) ? TRUE : FALSE;

                if ( $accTimeGroupName || $inAccWeekName || $inAccHolidayName ) {
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
            $strPage .= '<a id="datatablePagination'.$i.'" class="page-link '.$active.'" onclick="javascript: goPage('.$i.',\'access-week\');" href="#" data-dt-page-to="'.$i.'">'.$i.'</a>';
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
</head>
<style>
    .sub-sidebar-menu{float: left; width: 19.5%; left: -22px; height: 55rem;}
    .sub-sidebar-btn-wrap{float: left; width: 0.5%; left: -24px; height: 55rem; padding: 26rem 0; background-color: #f3f3f3;}
    .sub-sidebar-btn{background-color: #8b8e9f; font-size: 1px; height: 100%; color: #fff; padding: 16px 0; cursor: pointer;}
    .display-content{float: right; width: 80%;}
    table * {text-align: center;}
	.day-choice{margin: 0 16px;}
	.input{display: initial; width: 68%;}
	.input_label{display: initial; width: 30%;}
    .modal-content.py-5::-webkit-scrollbar {width: 10px;}
    .modal-content.py-5::-webkit-scrollbar-thumb {background-color: #cccccc;border-radius: 15px;}
    .modal-content.py-5::-webkit-scrollbar-track {background-color: #ececec;border-radius: 15px;}
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
                            <li class="breadcrumb-item active" aria-current="page">주간 일정</li>
                        </ol>
                    </nav>
                    <!-- End Breadcrumb -->

                    <div class="mb-3 mb-md-4 d-flex justify-content-between">
                        <div class="h3 mb-0">주간 일정</div>
                        <div style="padding-right: 5rem;"><i onclick="javascript: refresh('access-week');" class = "gd-reload h4" style = "cursor : pointer;"></i></div>
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
                                    <button type="button" onclick = "javascript: goSearch('access-week');" class="btn" style="position: relative; top: 32px; color: #fff; background-color: #ffc300; border-color: #ffc300;">검색</button>
                                    <button type="button" class="btn" onclick="deviceInsertReset(); type_form_reset();" style="position: relative; top: 32px; color: #fff; background-color: #265df1; border-color: #265df1;" data-toggle="modal" data-target="#accWeekModal" data-dismiss="modal" data-backdrop="static" keybord="false">추가</button>
                                    <button type="button" onclick = "javascript: goDelete('more', 'access-week');" class="btn" style="position: relative; top: 32px; color: #fff; background-color: #ff6500; border-color: #ff6500;">삭제</button>
                                    
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
                                    <th class="font-weight-semi-bold border-top-0 py-2">주간일정 그룹 이름</th>
                                    <th class="font-weight-semi-bold border-top-0 py-2">주간일정 그룹 이름</th>
                                    <th class="font-weight-semi-bold border-top-0 py-2">Action</th>
                                </tr>
                                </thead>
                                <tbody id="access-week-content">
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
                                <button type = "button" id="datatablePaginationPrev" class="page-link" onclick="javascript: goPage('<?=$page_no-1?>','access-week');" aria-label="Previous">
                                    <i class="gd-angle-left icon-text icon-text-xs d-inline-block"></i>
                                </button>
                            </li>
                            <?=$strPage?>
                            <li class="page-item" id="page-next">
                                    <button type = "button" id="datatablePaginationNext" class="page-link" onclick="javascript: goPage('<?=$page_no+1?>','access-week');" aria-label="Next">
                                    <i class="gd-angle-right icon-text icon-text-xs d-inline-block"></i>
                                </button>
                            </li>
                        </ul>
                    </nav>
                    <div class="d-flex mb-2 mb-md-0 ml-3" style="color: #8a8a8a; float: left; cursor: pointer;" onclick="javascript: goPage('1','access-week');">처음으로</div>
                    <div class="d-flex mb-2 mb-md-0 ml-3" style="float: left;"><input type="text" class="form-control" id="search_page" name="search_page" value="<?=$page_no?>" style="width: 3rem; text-align: center; border: 1px solid #cccccc;"></div>
                    <div class="d-flex mb-2 mb-md-0 ml-3" style="float: left;"><button type="button" class="btn" style="color: #000; background-color: #f7f7f7; border-color: #cccccc;" onclick="javascript: goPage('search_page','access-week');">이동</button></div>
                    <div class="d-flex mb-2 mb-md-0 ml-3" style="color: #8a8a8a;" id = "total-row">총 <?=$totalRows?>개</div>
                    <div class="d-flex mb-2 mb-md-0 ml-3" style="float: left;">
                        <select id="page_count_change" onchange="javascript: changeCount('access-week');" style="border:1px solid #cccccc; color: #8a8a8a; height: 44px;">
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

<!-- Modal -->
<div id="accWeekModal" class="modal fade" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog rounded py-5" role="document" style="top: 6rem; padding: 0!important; width: 40rem;">
    <div class="modal-content py-5" style="padding-top: 10px!important; overflow: auto; height: 50rem;">
    <header class="modal-header flex-column justify-content-center" style="border-bottom: 1px solid #a7a7a7; padding-bottom: 0;">
        <div style="width: 100%;">
         <h4 class="card-title">주간 일정 생성
         <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="padding: 1.2rem 1rem 0 0;"><span aria-hidden="true">&times;</span></button>
        </h4>
         </div>
     </header>
            <div class="modal-body pt-3 mb-5 mb-md-7">
                <div class="card">
                    <div class="card-body">
								<form name="acc_week_insert_form" method = "POST">
									<div class="form-group">
										<label for="access_week_name">주간 일정 이름</label>
										<input type="text" class="form-control" id="access_week_name" name="access_week_name">
									</div>

									<div class="card-footer d-block d-md-flex align-items-center d-print-none">
										<nav class="d-flex d-print-none" aria-label="Pagination">
											<ul class="pagination justify-content-end font-weight-semi-bold mb-0">
												<li class="day-choice">
													<button type="button" class="page-link" onclick="javascript: day_add('mon');">월</button>
												</li>
												<li class="day-choice">
													<button type="button" class="page-link" onclick="javascript: day_add('tue');">화</button>
												</li>
												<li class="day-choice">
													<button type="button" class="page-link" onclick="javascript: day_add('wed');">수</button>
												</li>
												<li class="day-choice">
													<button type="button" class="page-link" onclick="javascript: day_add('thu');">목</button>
												</li>
												<li class="day-choice">
													<button type="button" class="page-link" onclick="javascript: day_add('fri');">금</button>
												</li>
												<li class="day-choice">
													<button type="button" class="page-link" onclick="javascript: day_add('sat');">토</button>
												</li>
												<li class="day-choice">
													<button type="button" class="page-link" onclick="javascript: day_add('sun');">일</button>
												</li>
											</ul>
										</nav>
									</div>

									<div class="form-group" style="display: none;" id="mon_1">
										<br><label>월요일 시간 1</label><br>
										<select class="form-control" id="mon_time_1_start" name="mon_time_1_start" style="width: 40%; float: left;">
										</select> &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; ~
										<select class="form-control" id="mon_time_1_end" name="mon_time_1_end" style="width: 40%; float: right;">
										</select><br>
									</div>
									<div class="form-group" style="display: none;" id="mon_2">
										<br><label>월요일 시간 2</label><br>
										<select class="form-control" id="mon_time_2_start" name="mon_time_2_start" style="width: 40%; float: left;">
										</select> &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; ~
										<select class="form-control" id="mon_time_2_end" name="mon_time_2_end" style="width: 40%; float: right;">
										</select><br>
									</div>
									<div class="form-group" style="display: none;" id="mon_3">
										<br><label>월요일 시간 3</label><br>
										<select class="form-control" id="mon_time_3_start" name="mon_time_3_start" style="width: 40%; float: left;">
										</select> &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; ~
										<select class="form-control" id="mon_time_3_end" name="mon_time_3_end" style="width: 40%; float: right;">
										</select><br>
									</div>
									<div class="form-group" style="display: none;" id="mon_4">
										<br><label>월요일 시간 4</label><br>
										<select class="form-control" id="mon_time_4_start" name="mon_time_4_start" style="width: 40%; float: left;">
										</select> &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; ~
										<select class="form-control" id="mon_time_4_end" name="mon_time_4_end" style="width: 40%; float: right;">
										</select><br>
									</div>
									<div class="form-group" style="display: none;" id="tue_1">
										<br><label>화요일 시간 1</label><br>
										<select class="form-control" id="tue_time_1_start" name="tue_time_1_start" style="width: 40%; float: left;">
										</select> &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; ~
										<select class="form-control" id="tue_time_1_end" name="tue_time_1_end" style="width: 40%; float: right;">
										</select><br>
									</div>
									<div class="form-group" style="display: none;" id="tue_2">
										<br><label>화요일 시간 2</label><br>
										<select class="form-control" id="tue_time_2_start" name="tue_time_2_start" style="width: 40%; float: left;">
										</select> &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; ~
										<select class="form-control" id="tue_time_2_end" name="tue_time_2_end" style="width: 40%; float: right;">
										</select><br>
									</div>
									<div class="form-group" style="display: none;" id="tue_3">
										<br><label>화요일 시간 3</label><br>
										<select class="form-control" id="tue_time_3_start" name="tue_time_3_start" style="width: 40%; float: left;">
										</select> &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; ~
										<select class="form-control" id="tue_time_3_end" name="tue_time_3_end" style="width: 40%; float: right;">
										</select><br>
									</div>
									<div class="form-group" style="display: none;" id="tue_4">
										<br><label>화요일 시간 4</label><br>
										<select class="form-control" id="tue_time_4_start" name="tue_time_4_start" style="width: 40%; float: left;">
										</select> &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; ~
										<select class="form-control" id="tue_time_4_end" name="tue_time_4_end" style="width: 40%; float: right;">
										</select><br>
									</div>
									<div class="form-group" style="display: none;" id="wed_1">
										<br><label>수요일 시간 1</label><br>
										<select class="form-control" id="wed_time_1_start" name="wed_time_1_start" style="width: 40%; float: left;">
										</select> &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; ~
										<select class="form-control" id="wed_time_1_end" name="wed_time_1_end" style="width: 40%; float: right;">
										</select><br>
									</div>
									<div class="form-group" style="display: none;" id="wed_2">
										<br><label>수요일 시간 2</label><br>
										<select class="form-control" id="wed_time_2_start" name="wed_time_2_start" style="width: 40%; float: left;">
										</select> &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; ~
										<select class="form-control" id="wed_time_2_end" name="wed_time_2_end" style="width: 40%; float: right;">
										</select><br>
									</div>
									<div class="form-group" style="display: none;" id="wed_3">
										<br><label>수요일 시간 3</label><br>
										<select class="form-control" id="wed_time_3_start" name="wed_time_3_start" style="width: 40%; float: left;">
										</select> &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; ~
										<select class="form-control" id="wed_time_3_end" name="wed_time_3_end" style="width: 40%; float: right;">
										</select><br>
									</div>
									<div class="form-group" style="display: none;" id="wed_4">
										<br><label>수요일 시간 4</label><br>
										<select class="form-control" id="wed_time_4_start" name="wed_time_4_start" style="width: 40%; float: left;">
										</select> &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; ~
										<select class="form-control" id="wed_time_4_end" name="wed_time_4_end" style="width: 40%; float: right;">
										</select><br>
									</div>
									<div class="form-group" style="display: none;" id="thu_1">
										<br><label>목요일 시간 1</label><br>
										<select class="form-control" id="thu_time_1_start" name="thu_time_1_start" style="width: 40%; float: left;">
										</select> &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; ~
										<select class="form-control" id="thu_time_1_end" name="thu_time_1_end" style="width: 40%; float: right;">
										</select><br>
									</div>
									<div class="form-group" style="display: none;" id="thu_2">
										<br><label>목요일 시간 2</label><br>
										<select class="form-control" id="thu_time_2_start" name="thu_time_2_start" style="width: 40%; float: left;">
										</select> &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; ~
										<select class="form-control" id="thu_time_2_end" name="thu_time_2_end" style="width: 40%; float: right;">
										</select><br>
									</div>
									<div class="form-group" style="display: none;" id="thu_3">
										<br><label>목요일 시간 3</label><br>
										<select class="form-control" id="thu_time_3_start" name="thu_time_3_start" style="width: 40%; float: left;">
										</select> &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; ~
										<select class="form-control" id="thu_time_3_end" name="thu_time_3_end" style="width: 40%; float: right;">
										</select><br>
									</div>
									<div class="form-group" style="display: none;" id="thu_4">
										<br><label>목요일 시간 4</label><br>
										<select class="form-control" id="thu_time_4_start" name="thu_time_4_start" style="width: 40%; float: left;">
										</select> &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; ~
										<select class="form-control" id="thu_time_4_end" name="thu_time_4_end" style="width: 40%; float: right;">
										</select><br>
									</div>
									<div class="form-group" style="display: none;" id="fri_1">
										<br><label>금요일 시간 1</label><br>
										<select class="form-control" id="fri_time_1_start" name="fri_time_1_start" style="width: 40%; float: left;">
										</select> &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; ~
										<select class="form-control" id="fri_time_1_end" name="fri_time_1_end" style="width: 40%; float: right;">
										</select><br>
									</div>
									<div class="form-group" style="display: none;" id="fri_2">
										<br><label>금요일 시간 2</label><br>
										<select class="form-control" id="fri_time_2_start" name="fri_time_2_start" style="width: 40%; float: left;">
										</select> &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; ~
										<select class="form-control" id="fri_time_2_end" name="fri_time_2_end" style="width: 40%; float: right;">
										</select><br>
									</div>
									<div class="form-group" style="display: none;" id="fri_3">
										<br><label>금요일 시간 3</label><br>
										<select class="form-control" id="fri_time_3_start" name="fri_time_3_start" style="width: 40%; float: left;">
										</select> &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; ~
										<select class="form-control" id="fri_time_3_end" name="fri_time_3_end" style="width: 40%; float: right;">
										</select><br>
									</div>
									<div class="form-group" style="display: none;" id="fri_4">
										<br><label>금요일 시간 4</label><br>
										<select class="form-control" id="fri_time_4_start" name="fri_time_4_start" style="width: 40%; float: left;">
										</select> &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; ~
										<select class="form-control" id="fri_time_4_end" name="fri_time_4_end" style="width: 40%; float: right;">
										</select><br>
									</div>
									<div class="form-group" style="display: none;" id="sat_1">
										<br><label>토요일 시간 1</label><br>
										<select class="form-control" id="sat_time_1_start" name="sat_time_1_start" style="width: 40%; float: left;">
										</select> &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; ~
										<select class="form-control" id="sat_time_1_end" name="sat_time_1_end" style="width: 40%; float: right;">
										</select><br>
									</div>
									<div class="form-group" style="display: none;" id="sat_2">
										<br><label>토요일 시간 2</label><br>
										<select class="form-control" id="sat_time_2_start" name="sat_time_2_start" style="width: 40%; float: left;">
										</select> &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; ~
										<select class="form-control" id="sat_time_2_end" name="sat_time_2_end" style="width: 40%; float: right;">
										</select><br>
									</div>
									<div class="form-group" style="display: none;" id="sat_3">
										<br><label>토요일 시간 3</label><br>
										<select class="form-control" id="sat_time_3_start" name="sat_time_3_start" style="width: 40%; float: left;">
										</select> &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; ~
										<select class="form-control" id="sat_time_3_end" name="sat_time_3_end" style="width: 40%; float: right;">
										</select><br>
									</div>
									<div class="form-group" style="display: none;" id="sat_4">
										<br><label>토요일 시간 4</label><br>
										<select class="form-control" id="sat_time_4_start" name="sat_time_4_start" style="width: 40%; float: left;">
										</select> &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; ~
										<select class="form-control" id="sat_time_4_end" name="sat_time_4_end" style="width: 40%; float: right;">
										</select><br>
									</div>
									<div class="form-group" style="display: none;" id="sun_1">
										<br><label>일요일 시간 1</label><br>
										<select class="form-control" id="sun_time_1_start" name="sun_time_1_start" style="width: 40%; float: left;">
										</select> &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; ~
										<select class="form-control" id="sun_time_1_end" name="sun_time_1_end" style="width: 40%; float: right;">
										</select><br>
									</div>
									<div class="form-group" style="display: none;" id="sun_2">
										<br><label>일요일 시간 2</label><br>
										<select class="form-control" id="sun_time_2_start" name="sun_time_2_start" style="width: 40%; float: left;">
										</select> &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; ~
										<select class="form-control" id="sun_time_2_end" name="sun_time_2_end" style="width: 40%; float: right;">
										</select><br>
									</div>
									<div class="form-group" style="display: none;" id="sun_3">
										<br><label>일요일 시간 3</label><br>
										<select class="form-control" id="sun_time_3_start" name="sun_time_3_start" style="width: 40%; float: left;">
										</select> &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; ~
										<select class="form-control" id="sun_time_3_end" name="sun_time_3_end" style="width: 40%; float: right;">
										</select><br>
									</div>
									<div class="form-group" style="display: none;" id="sun_4">
										<br><label>일요일 시간 4</label><br>
										<select class="form-control" id="sun_time_4_start" name="sun_time_4_start" style="width: 40%; float: left;">
										</select> &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; ~
										<select class="form-control" id="sun_time_4_end" name="sun_time_4_end" style="width: 40%; float: right;">
										</select><br>
									</div>
									<br>
									<!-- <button type="button" onclick="javascript: go_acc_week_insert();" class="btn btn-primary btn-block">생 성</button> -->
								</form>
					</div>
				</div>
            </div>

            <!--
            <footer class="modal-footer justify-content-between border-0">
                <p>Modal footer text goes here.</p>
            </footer>
            -->
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
	let strOption = '<option value="0">00:00</option><option value="30">00:30</option><option value="100">01:00</option><option value="130">01:30</option><option value="200">02:00</option><option value="230">02:30</option><option value="300">03:00</option><option value="330">03:30</option><option value="400">04:00</option><option value="430">04:30</option><option value="500">05:00</option><option value="530">05:30</option><option value="600">06:00</option><option value="630">06:30</option><option value="700">07:00</option><option value="730">07:30</option><option value="800">08:00</option><option value="830">08:30</option><option value="900">09:00</option><option value="930">09:30</option><option value="1000">10:00</option><option value="1030">10:30</option><option value="1100">11:00</option><option value="1130">11:30</option><option value="1200">12:00</option><option value="1230">12:30</option><option value="1300">13:00</option><option value="1330">13:30</option><option value="1400">14:00</option><option value="1430">14:30</option><option value="1500">15:00</option><option value="1530">15:30</option><option value="1600">16:00</option><option value="1630">16:30</option><option value="1700">17:00</option><option value="1730">17:30</option><option value="1800">18:00</option><option value="1830">18:30</option><option value="1900">19:00</option><option value="1930">19:30</option><option value="2000">20:00</option><option value="2030">20:30</option><option value="2100">21:00</option><option value="2130">21:30</option><option value="2200">22:00</option><option value="2230">22:30</option><option value="2300">23:00</option><option value="2330">23:30</option><option value="2400">24:00</option>';
	$("select").append(strOption);

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
function type_form_reset() {
        var f = $("form[name='acc_week_insert_form']").find("input[class=form-control]");
        for (let i = 0; i < f.length; i++) {
            f[i].value="";
        }
        $("#accWeekModal .card-title").text("주간 일정 생성");
        let closebtn = '<button type="button" class="close" data-dismiss="modal" aria-label="Close" style="padding: 1.2rem 1rem 0 0;"><span aria-hidden="true">&times;</span></button>';
        $(".card-title").append(closebtn);
        $("#typeInsertBtn").remove();
        $("#typeUpdateBtn").remove();
        var btn = '<button type="button" id="typeInsertBtn" onclick="javascript:go_acc_week_insert();" class="btn btn-primary btn-block">생 성</button>';
        $("form[name='acc_week_insert_form']").append(btn);
    }
</script>