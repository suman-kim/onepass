<?php include '../inc/global.php'?>
<?php
    $id = $_POST["id"];
	
    $dataRs = get("/v1/devices/".$id);
    $data = json_decode($dataRs);

    $deviceGroupDataRs = get("/v1/device-groups");
    $deviceGroupData = json_decode($deviceGroupDataRs);
	$totalRows = count($deviceGroupData->d_group_infos);
	$deviceGroupsStr = "";
	for ($i=0; $i < $totalRows; $i++) { 
		$deviceGroupsStr .= "<option value='".$deviceGroupData->d_group_infos[$i]->id."'>".$deviceGroupData->d_group_infos[$i]->name."</option>";
	}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <!-- Title -->
    <title>Create new account | Graindashboard UI Kit</title>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta http-equiv="x-ua-compatible" content="ie=edge">


</head>
    <!-- Favicon -->
    <link rel="shortcut icon" href="../public/img/favicon.ico">
    <!-- Template -->
    <link rel="stylesheet" href="../public/graindashboard/css/graindashboard.css">
	<style>
	.input{display: initial; width: 68%;}
	.input_label{display: initial; width: 30%;}
	</style>
<body class="">

    <main class="main">

		<div class="content">

			<div class="container-fluid pb-5">

				<div class="row justify-content-md-center">
					<div class="card-wrapper col-12 col-md-10 mt-3">
						<div class="brand text-center mb-3">
							<a href="#" style="color: #000; font-size: 3rem; font-weight: bold;">ONE PASS</a>
						</div>
						<div class="card">
							<div class="card-body">
								<h4 class="card-title">단말기 수정</h4>
								<form name = "device_form" method = "POST">
									<div class="form-group">
										<input class="form-control input_label" value="단말기 그룹" disabled>
										<select class="form-control input" id="group_id" name="group_id" value="<?=$data->device_info->device_group->group_id?>">
											<?=$deviceGroupsStr?>
										</select>
									</div>
									<div class="form-group">
										<input class="form-control input_label" value="단말기 이름" disabled>
										<input type="text" class="form-control input" id="name" name="name" value="<?=$data->device_info->name?>">
									</div>
									<div class="form-group">
										<input class="form-control input_label" value="IP 주소" disabled>
										<input type="text" class="form-control input" id="ip_addr" name="ip_addr" value="<?=$data->device_info->device_net_info->ip_addr?>">
									</div>
									<div class="form-group">
										<input class="form-control input_label" value="TCP Port" disabled>
										<input type="text" class="form-control input" id="tcp_port" name="tcp_port" value="<?=$data->device_info->device_net_info->tcp_port?>">
									</div>
									<div class="form-group">
										<input class="form-control input_label" value="Web Port" disabled>
										<input type="text" class="form-control input" id="web_port" name="web_port" value="<?=$data->device_info->device_net_info->web_port?>">
									</div>
									<div class="form-group">
										<input class="form-control input_label" value="ID" disabled>
										<input type="text" class="form-control input" id="account_id" name="account_id" value="<?=$data->device_info->product_info->account_id?>">
									</div>
									<div class="form-group">
										<input class="form-control input_label" value="비밀번호" disabled>
										<input type="password" class="form-control input" id="account_pw" name="account_pw" value="<?=$data->device_info->product_info->account_pw?>">
									</div>
									<div class="form-group">
										<input class="form-control input_label" value="유형" disabled>
										<input type="text" class="form-control input" id="type_code" name="type_code" value="<?=$data->device_info->product_info->type_code?>">
									</div>
									<div class="form-group">
										<input type="checkbox" id="time_sync" name="time_sync" value="1">
										<label for="time_sync">연결시 시간 동기화하기</label>
									</div>

									<div class="form-group">
										<input class="form-control input_label" value="카메라 이름" disabled>
										<input type="text" class="form-control input" id="link_name" name="link_name" value="<?=$data->device_info->link_device->link_name?>">
									</div>
									<div class="form-group">
										<input class="form-control input_label" value="카메라 RTSP URL" disabled>
										<input type="text" class="form-control input" id="link_rtsp_url" name="link_rtsp_url" value="<?=$data->device_info->link_device->rtsp_url?>">
									</div>
									<div class="form-group">
										<input class="form-control input_label" value="카메라 RTSP URL" disabled>
										<input type="text" class="form-control input" id="link_uid" name="link_uid" value="<?=$data->device_info->link_device->link_uid?>">
									</div>
									<div class="form-group">
										<input class="form-control input_label" value="카메라 비밀번호" disabled>
										<input type="text" class="form-control input" id="link_password" name="link_password" value="<?=$data->device_info->link_device->link_password?>">
									</div>
									
									<button type="button" onclick="javascript: go_device_update();" class="btn btn-primary btn-block">수 정</button>
								</form>
							</div>
						</div>
					</div>
				</div>
			</div>
			
		</div>
    </main>
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
		var id = '<?=$id?>';
		if (id == "") {
			alert("잘못된 접근입니다.");
			window.close();
		}
	</script>
</body>
</html>
