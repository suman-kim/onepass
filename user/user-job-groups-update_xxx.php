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
								<h4 class="card-title">사용자 직군 수정</h4>
								<form name = "job_groups_update_form" method = "POST">
									<div class="form-group">
										<label for="job_groups_name">사용자 직군 이름</label>
										<input type="text" class="form-control" id="job_groups_name" name="job_groups_name">
									</div>
									<button type="button" onclick="javascript: go_user_job_group_update();" class="btn btn-primary btn-block">수 정</button>
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
			user_job_group_update_info();
		}
	</script>
</body>
</html>
