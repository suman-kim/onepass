<?php
    $id = $_POST["id"];
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
	.day-choice{margin: 0 30px;}
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
								<h4 class="card-title">주간 일정 수정</h4>
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
									<button type="button" onclick="javascript: go_acc_week_update();" class="btn btn-primary btn-block">수 정</button>
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
		let strOption = '<option value="0">00:00</option><option value="30">00:30</option><option value="100">01:00</option><option value="130">01:30</option><option value="200">02:00</option><option value="230">02:30</option><option value="300">03:00</option><option value="330">03:30</option><option value="400">04:00</option><option value="430">04:30</option><option value="500">05:00</option><option value="530">05:30</option><option value="600">06:00</option><option value="630">06:30</option><option value="700">07:00</option><option value="730">07:30</option><option value="800">08:00</option><option value="830">08:30</option><option value="900">09:00</option><option value="930">09:30</option><option value="1000">10:00</option><option value="1030">10:30</option><option value="1100">11:00</option><option value="1130">11:30</option><option value="1200">12:00</option><option value="1230">12:30</option><option value="1300">13:00</option><option value="1330">13:30</option><option value="1400">14:00</option><option value="1430">14:30</option><option value="1500">15:00</option><option value="1530">15:30</option><option value="1600">16:00</option><option value="1630">16:30</option><option value="1700">17:00</option><option value="1730">17:30</option><option value="1800">18:00</option><option value="1830">18:30</option><option value="1900">19:00</option><option value="1930">19:30</option><option value="2000">20:00</option><option value="2030">20:30</option><option value="2100">21:00</option><option value="2130">21:30</option><option value="2200">22:00</option><option value="2230">22:30</option><option value="2300">23:00</option><option value="2330">23:30</option><option value="2400">24:00</option>';
		$("select").append(strOption);

		if (id == "") {
			alert("잘못된 접근입니다.");
			window.close();
		}
		else {
			acc_week_update();
		}
	</script>
</body>
</html>
