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
								<form name="acc_holiday_group_form" method = "POST">
									<div class="form-group">
										<label for="access_holiday_group_name">주간 일정 이름</label>
										<input type="text" class="form-control" id="access_holiday_group_name" name="access_holiday_group_name">
									</div>
									<div class="form-group">
										<label for="hol_cfg_id1">휴일 속성 1</label>
										<select class="form-control" id="hol_cfg_id1" name="hol_cfg_id1">
										</select>
									</div>
									<div class="form-group">
										<label for="hol_cfg_id2">휴일 속성 2</label>
										<select class="form-control" id="hol_cfg_id2" name="hol_cfg_id2">
										</select>
									</div>
									<div class="form-group">
										<label for="hol_cfg_id3">휴일 속성 3</label>
										<select class="form-control" id="hol_cfg_id3" name="hol_cfg_id3">
										</select>
									</div>
									<div class="form-group">
										<label for="hol_cfg_id4">휴일 속성 4</label>
										<select class="form-control" id="hol_cfg_id4" name="hol_cfg_id4">
										</select>
									</div>
									<div class="form-group">
										<label for="hol_cfg_id5">휴일 속성 5</label>
										<select class="form-control" id="hol_cfg_id5" name="hol_cfg_id5">
										</select>
									</div>
									<div class="form-group">
										<label for="hol_cfg_id6">휴일 속성 6</label>
										<select class="form-control" id="hol_cfg_id6" name="hol_cfg_id6">
										</select>
									</div>
									<div class="form-group">
										<label for="hol_cfg_id7">휴일 속성 7</label>
										<select class="form-control" id="hol_cfg_id7" name="hol_cfg_id7">
										</select>
									</div>
									<div class="form-group">
										<label for="hol_cfg_id8">휴일 속성 8</label>
										<select class="form-control" id="hol_cfg_id8" name="hol_cfg_id8">
										</select>
									</div>
									<div class="form-group">
										<label for="hol_cfg_id9">휴일 속성 9</label>
										<select class="form-control" id="hol_cfg_id9" name="hol_cfg_id9">
										</select>
									</div>
									<div class="form-group">
										<label for="hol_cfg_id10">휴일 속성 10</label>
										<select class="form-control" id="hol_cfg_id10" name="hol_cfg_id10">
										</select>
									</div>
									<div class="form-group">
										<label for="hol_cfg_id11">휴일 속성 11</label>
										<select class="form-control" id="hol_cfg_id11" name="hol_cfg_id11">
										</select>
									</div>
									<div class="form-group">
										<label for="hol_cfg_id12">휴일 속성 12</label>
										<select class="form-control" id="hol_cfg_id12" name="hol_cfg_id12">
										</select>
									</div>
									<div class="form-group">
										<label for="hol_cfg_id13">휴일 속성 13</label>
										<select class="form-control" id="hol_cfg_id13" name="hol_cfg_id13">
										</select>
									</div>
									<div class="form-group">
										<label for="hol_cfg_id14">휴일 속성 14</label>
										<select class="form-control" id="hol_cfg_id14" name="hol_cfg_id14">
										</select>
									</div>
									<div class="form-group">
										<label for="hol_cfg_id15">휴일 속성 15</label>
										<select class="form-control" id="hol_cfg_id15" name="hol_cfg_id15">
										</select>
									</div>
									<div class="form-group">
										<label for="hol_cfg_id16">휴일 속성 16</label>
										<select class="form-control" id="hol_cfg_id16" name="hol_cfg_id16">
										</select>
									</div>

									<button type="button" onclick="javascript: go_acc_holiday_group_update();" class="btn btn-primary btn-block">생 성</button>
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
		var strOption = '<option value="1">1</option><option value="2">2</option>';
		$("select").append(strOption);

		if (id == "") {
			alert("잘못된 접근입니다.");
			window.close();
		}
		else {
			acc_holiday_group_update();
		}
	</script>
</body>
</html>
