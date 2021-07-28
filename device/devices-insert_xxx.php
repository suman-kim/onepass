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
								<h4 class="card-title">단말기 생성</h4>
								<form name = "device_insert_form" method = "POST">
									<div class="form-group">
										<label for="name">단말기 이름</label>
										<input type="text" class="form-control" id="name" name="name">
									</div>

									<div class="form-group">
										<label for="product_code">단말기 코드</label>
										<input type="text" class="form-control" id="product_code" name="product_code">
									</div>
									<div class="form-group">
										<label for="model_name">모델 이름</label>
										<input type="text" class="form-control" id="model_name" name="model_name">
									</div>
									<div class="form-group">
										<label for="serial_no">시리얼 No</label>
										<input type="text" class="form-control" id="serial_no" name="serial_no">
									</div>
									<div class="form-group">
										<label for="serial_no_ex">풀시리얼 No</label>
										<input type="text" class="form-control" id="serial_no_ex" name="serial_no_ex">
									</div>
									<div class="form-group">
										<label for="account_id">관제 ID</label>
										<input type="text" class="form-control" id="account_id" name="account_id">
									</div>
									<div class="form-group">
										<label for="account_pw">관제 Pw</label>
										<input type="text" class="form-control" id="account_pw" name="account_pw">
									</div>
									<div class="form-group">
										<label for="type_code">유형 코드</label>
										<input type="text" class="form-control" id="type_code" name="type_code">
									</div>

									<div class="form-group">
										<label for="group_id">단말기 그룹</label>
										<select class="form-control" id="group_id" name="group_id">
											<option value="1">1</option>
										</select>
									</div>

									<div class="form-group">
										<label for="ip_addr">IP 주소</label>
										<input type="text" class="form-control" id="ip_addr" name="ip_addr">
									</div>
									<div class="form-group">
										<label for="tcp_port">TCP Port</label>
										<input type="text" class="form-control" id="tcp_port" name="tcp_port">
									</div>
									<div class="form-group">
										<label for="web_port">Web Port</label>
										<input type="text" class="form-control" id="web_port" name="web_port">
									</div>
									<div class="form-group">
										<label for="mac_addr">Mac 주소</label>
										<input type="text" class="form-control" id="mac_addr" name="mac_addr">
									</div>

									<div class="form-group">
										<label for="is_master">Master 유무</label>
										<select class="form-control" id="is_master" name="is_master">
											<option value="1">TRUE</option>
											<option value="0">FALSE</option>
										</select>
									</div>
									<div class="form-group">
										<label for="is_active">사용 유무</label>
										<select class="form-control" id="is_active" name="is_active">
											<option value="1">TRUE</option>
											<option value="0">FALSE</option>
										</select>
									</div>
									<div class="form-group">
										<label for="is_connected">연결 유무</label>
										<select class="form-control" id="is_connected" name="is_connected">
											<option value="1">TRUE</option>
											<option value="0">FALSE</option>
										</select>
									</div>
									<div class="form-group">
										<label for="is_twoway_audio">양방향 오디오 유무</label>
										<select class="form-control" id="is_twoway_audio" name="is_twoway_audio">
											<option value="1">TRUE</option>
											<option value="0">FALSE</option>
										</select>
									</div>
									<div class="form-group">
										<label for="is_event_arming">이벤트 알람 설정 유무</label>
										<select class="form-control" id="is_event_arming" name="is_event_arming">
											<option value="1">TRUE</option>
											<option value="0">FALSE</option>
										</select>
									</div>
									<div class="form-group">
										<label for="event_arming_type">이벤트 알람 유형</label>
										<select class="form-control" id="event_arming_type" name="event_arming_type">
											<option value="1">TRUE</option>
											<option value="0">FALSE</option>
										</select>
									</div>
									<div class="form-group">
										<label for="rtsp_url">RTSP URL</label>
										<input type="text" class="form-control" id="rtsp_url" name="rtsp_url">
									</div>
									<div class="form-group">
										<label for="door_count">Door Count</label>
										<input type="text" class="form-control" id="door_count" name="door_count">
									</div>
									<div class="form-group">
										<label for="link_name">연결된 단말기 이름</label>
										<input type="text" class="form-control" id="link_name" name="link_name">
									</div>
									<div class="form-group">
										<label for="link_rtsp_url">연결된 단말기 RTSP URL</label>
										<input type="text" class="form-control" id="link_rtsp_url" name="link_rtsp_url">
									</div>
									<div class="form-group">
										<label for="link_ip">연결된 단말기 IP</label>
										<input type="text" class="form-control" id="link_ip" name="link_ip">
									</div>
									<div class="form-group">
										<label for="link_port">연결된 단말기 Port</label>
										<input type="text" class="form-control" id="link_port" name="link_port">
									</div>
									<div class="form-group">
										<label for="link_uid">연결된 단말기 UID</label>
										<input type="text" class="form-control" id="link_uid" name="link_uid">
									</div>
									<div class="form-group">
										<label for="link_password">연결된 단말기 PWD</label>
										<input type="text" class="form-control" id="link_password" name="link_password">
									</div>
									
									<button type="button" onclick="javascript: go_device_insert();" class="btn btn-primary btn-block">생 성</button>
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
	</script>
</body>
</html>
