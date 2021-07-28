<?php include '../inc/global.php'?>
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
								<h4 class="card-title">사용자 그룹 추가</h4>
								<form name = "group_insert_form" method = "POST">
									<input type="hidden" id="du_groupinfo" name="du_groupinfo">
									<div class="form-group">
										<label for="group_name">사용자 그룹 이름</label>
										<input type="text" class="form-control" id="group_name" name="group_name">
									</div>

									<div class="form-group">
										<label for="parent_id">부모 그룹</label>
										<input type="text" class="form-control" id="parent_id" name="parent_id">
									</div>
									
									<button type="button" onclick="javascript: go_user_group_insert();" class="btn btn-primary btn-block">생 성</button>
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
