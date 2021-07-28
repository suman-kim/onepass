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
								<h4 class="card-title">출입 시간 그룹 수정</h4>
								<form name = "acc_time_group_insert_form" method = "POST">
									<div class="form-group">
										<label for="access_time_group_name">출입 시간 그룹 이름</label>
										<input type="text" class="form-control" id="access_time_group_name" name="access_time_group_name">
									</div>

									<div class="form-group">
										<label for="is_default">기본 출입 시간 그룹여부</label>
										<select class="form-control" id="is_default" name="is_default">
											<option value="1">TRUE</option>
											<option value="0">FALSE</option>
										</select>
									</div>

									<div class="form-group">
										<label for="acc_week_id">주간 일정</label>
										<select class="form-control" id="acc_week_id" name="acc_week_id">
											
										</select>
									</div>
									<div class="form-group">
										<label for="acc_holiday_group_id">휴일 그룹</label>
										<select class="form-control" id="acc_holiday_group_id" name="acc_holiday_group_id">
											
										</select>
									</div>
									
									<button type="button" onclick="javascript: go_acc_time_group_update();" class="btn btn-primary btn-block">수 정</button>
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
		var acc_week_id, acc_holiday_group_id;
		if (id == "") {
			alert("잘못된 접근입니다.");
			window.close();
		}
		else {
			acc_time_group_update_info();
		}
		
	</script>
</body>
</html>
