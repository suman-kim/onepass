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
								<h4 class="card-title">출입 그룹 수정</h4>
								<form name = "access_group_update_form" method = "POST">
									<div class="form-group">
										<label for="access_name">출입 그룹 이름</label>
										<input type="text" class="form-control" id="access_name" name="access_name">
									</div>

									<div class="form-group">
										<label for="bez_date">출입 그룹 시작 시간</label>
										<input type="text" class="form-control" id="bez_date" name="bez_date">
									</div>

									<div class="form-group">
										<label for="end_date">출입 그룹 종료 시간</label>
										<input type="text" class="form-control" id="end_date" name="end_date">
									</div>

									<label >출입그룹 단말기 등록</label><br>
										<div class="form-group" style="border: 1px solid #eeeef1; padding: .63rem 1rem .23rem 1rem;" id="device-ids">
									</div>

									<div class="form-group">
										<label for="acc_time_group_id">출입 시간 그룹</label>
										<select class="form-control" id="acc_time_group_id" name="acc_time_group_id">
										
										</select>
									</div>
									
									<button type="button" onclick="javascript: go_acc_group_update();" class="btn btn-primary btn-block">수 정</button>
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
		else {
			acc_group_update_info();
		}
	</script>
</body>
</html>
