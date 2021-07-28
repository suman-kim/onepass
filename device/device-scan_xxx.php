<?php include '../inc/global.php'?>
<?php
    $searchStr = $_REQUEST["search_str"];
    $search_time = ($_REQUEST["search_time_change"] == "") ? 1000 : $_REQUEST["search_time_change"];
    $noSearch = ( $searchStr == "" ) ? true : false;

    $dataRs = get("/v1/device-scan?type=0&timeout_sec=".$search_time."&reply_to=news/aa&reply_method=MQTT&reply_msg=device-scan");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <!-- Title -->
    <title>device | Graindashboard UI Kit</title>

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
	.input_label{display: initial; width: 30%;}
	.input{display: initial; width: 68%;}
    table * {text-align: center;}
</style>
<body class="has-sidebar has-fixed-sidebar-and-header">

<main class="main">

    <div class="content" style="height: 100%;">
        <div class="py-4 px-3 px-md-4">
            

            <div class="card mb-3 mb-md-4 " id="device-content" style="height: 55rem;">
                <div class="card-body">
                    <!-- Breadcrumb -->
                    <nav class="d-none d-md-block" aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item">
                                <a href="#">단말기</a>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page">단말기 검색</li>
                        </ol>
                    </nav>
                    <!-- End Breadcrumb -->

                    <!-- Form -->
                    <form name = "search_form" method = "GET">
                        <input type="hidden" name="search_start_num" value = "<?=$search_start_num?>">
                        <input type="hidden" name="noSearch" value = "<?=$noSearch?>">
                        <div class="mb-3 mb-md-4 d-flex justify-content-between">
                            <div class="h3 mb-0">단말기 검색</div>
                            <div style="padding-right: 5rem;">
                                <sapn style="float: left; margin-top: 3px;">검색시간</sapn>
                                <div class="d-flex mb-2 mb-md-0 ml-3" style="float: left;">
                                    <select id="search_time_change" name="search_time_change" style="border:1px solid #cccccc; color: #8a8a8a; height: 30px; margin-right: 20px;">
                                        <option value="1000" <?if($search_time == 1000) echo "selected"?>>1초</option>
                                        <option value="2000" <?if($search_time == 2000) echo "selected"?>>2초</option>
                                        <option value="3000" <?if($search_time == 3000) echo "selected"?>>3초</option>
                                        <option value="5000" <?if($search_time == 5000) echo "selected"?>>5초</option>
                                    </select>
                                </div>
                                <i onclick="javascript: goSearch('device-scan');" class = "gd-reload h4" style = "cursor : pointer;"></i>
                            </div>
                        </div>

                        <div>
                            <div class="form-row">
                                <div class="form-group col-4 col-md-2">
                                    <label for="device_id">검색</label>
                                    <input type="text" class="form-control" id="search_str" name="search_str" value = "<?=$searchStr?>" placeholder="Search">
                                </div>
                                <div class="form-group col-4 col-md-10">
                                    <button type="button" onclick = "javascript: goSearch('device-scan');" class="btn" style="position: relative; top: 32px; color: #fff; background-color: #ffc300; border-color: #ffc300;">검색</button>
                                <span style="color: #8a8a8a; float: right; margin: 3rem 2rem 0 0;" id = "total-row"></span>
                                </div>
                            </div>
                        </div>
                    </form>
                    <!-- End Form -->


                    <!-- device -->
                    <div style="height: 35rem; overflow: auto;">
                        <form name = "contents_show_form">
                            <table class="table text-nowrap mb-0">
                                <thead>
                                <tr>
                                    <th class="font-weight-semi-bold border-top-0 py-2">번호</th>
                                    <th class="font-weight-semi-bold border-top-0 py-2">IP주소</th>
                                    <th class="font-weight-semi-bold border-top-0 py-2">PORT</th>
                                    <th class="font-weight-semi-bold border-top-0 py-2">WEB PORT</th>
                                    <th class="font-weight-semi-bold border-top-0 py-2">MAC 주소</th>
                                    <th class="font-weight-semi-bold border-top-0 py-2">서브넷 마스크</th>
                                    <th class="font-weight-semi-bold border-top-0 py-2">게이트웨이</th>
                                    <th class="font-weight-semi-bold border-top-0 py-2">시리얼 번호</th>
                                    <th class="font-weight-semi-bold border-top-0 py-2">활성화 유무</th>
                                    <th class="font-weight-semi-bold border-top-0 py-2">DHCP</th>
                                    <th class="font-weight-semi-bold border-top-0 py-2">동작</th>
                                </tr>
                                </thead>
                                <tbody id="device-scan-content">
                                    <tr><td style="border: 0;"></td></tr>
                                    <tr><td style="border: 0;"></td></tr>
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
                                    </tr>
                                </tbody>
                            </table>
                            <input type="hidden" name="id">
                        </form>
                    </div>
                </div>

            </div>
            <!-- End device -->
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
<!-- User Insert Modal -->
<div id="deviceScanModal" class="modal fade" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog rounded py-5" role="document">
        <div class="modal-content py-5">
            <header class="modal-header flex-column justify-content-center border-0 mb-3 mb-xl-5">
                
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 id="exampleModalLabel" class="modal-title mx-auto"><a href="#" style="color: #000; font-size: 3rem; font-weight: bold;">ONE PASS</a></h4>
            </header>

            <div class="modal-body pt-3 mb-5 mb-md-7">
                <div class="card">
                    <div class="card-body">
						<h4 class="card-title"></h4>
                        <form name = "device_scan_form" method = "POST">
							<div class="form-group">
								<input class="form-control input_label" value="단말기 그룹" disabled>
								<select class="form-control input" id="group_id" name="group_id">
									<?=$deviceGroupsStr?>
								</select>
							</div>
							<div class="form-group">
								<input class="form-control input_label" value="단말기 이름" disabled>
								<input type="text" class="form-control input" id="name" name="name">
							</div>
							<div class="form-group">
								<input class="form-control input_label" value="IP 주소" disabled>
								<input type="text" class="form-control input" id="ip_addr" name="ip_addr">
							</div>
							<div class="form-group">
								<input class="form-control input_label" value="TCP Port" disabled>
								<input type="text" class="form-control input" id="tcp_port" name="tcp_port">
							</div>
							<div class="form-group">
								<input class="form-control input_label" value="Web Port" disabled>
								<input type="text" class="form-control input" id="web_port" name="web_port">
							</div>
							<div class="form-group">
								<input class="form-control input_label" value="ID" disabled>
								<input type="text" class="form-control input" id="account_id" name="account_id">
							</div>
							<div class="form-group">
								<input class="form-control input_label" value="비밀번호" disabled>
								<input type="password" class="form-control input" id="account_pw" name="account_pw">
							</div>
							<!--<div class="form-group">
								<input class="form-control input_label" value="유형" disabled>
								<input type="text" class="form-control input" id="type_code" name="type_code">
							</div>-->
							<div class="form-group">
								<input type="checkbox" id="scan_time_sync" name="scan_time_sync">
								<label for="scan_time_sync">연결시 시간 동기화하기</label>
							</div>

                            <div id="scan_sync_form">
                                <div class="form-group">
                                    <input class="form-control input_label" value="카메라 이름" disabled>
                                    <input type="text" class="form-control input" id="link_name" name="link_name">
                                </div>
                                <div class="form-group">
                                    <input class="form-control input_label" value="카메라 RTSP URL" disabled>
                                    <input type="text" class="form-control input" id="link_rtsp_url" name="link_rtsp_url">
                                </div>
                                <div class="form-group">
                                    <input class="form-control input_label" value="카메라 RTSP URL" disabled>
                                    <input type="text" class="form-control input" id="link_uid" name="link_uid">
                                </div>
                                <div class="form-group">
                                    <input class="form-control input_label" value="카메라 비밀번호" disabled>
                                    <input type="text" class="form-control input" id="link_password" name="link_password">
                                </div>
                            </div>
							
							<button type="button" id="devicScanInsertBtn" onclick="javascript: go_device_insert('scan');" class="btn btn-primary btn-block">생 성</button>
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

<!-- DEMO CHARTS -->
<script src="../public/demo/resizeSensor.js"></script>
<script src="../public/demo/chartist.js"></script>
<script src="../public/demo/chartist-plugin-tooltip.js"></script>
<script src="../public/demo/gd.chartist-area.js"></script>
<script src="../public/demo/gd.chartist-bar.js"></script>
<script src="../public/demo/gd.chartist-donut.js"></script>
<script src="../public/graindashboard/js/onepass.js"></script>
<script>
$(function () {
    $("#scan_sync_form").hide();
    $("#scan_time_sync").click(function () {
        var sync = $("#scan_time_sync").is(":checked");
        if (sync) {
            $("#scan_sync_form").show();
        } else {
            $("#scan_sync_form").hide();
        }
    });
});
</script>

<iframe type="hidden" id="hiddenfrm" name="hiddenfrm" style="display: none;"></iframe>
<iframe src="../phpMQTT/examples/subscribe.php" width="5000" height="100"></iframe>

</body>
</html>