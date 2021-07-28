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
								<h4 class="card-title">서버 사용자 권한 추가</h4>
								<form name = "server_user_permit_insert_form" method = "POST">
									<div class="form-group">
										<label for="permit_name">사용자 권한 이름</label>
										<input type="text" class="form-control" id="permit_name" name="permit_name">
									</div>
									<div class="form-group">
										<label for="major">Major</label>
										<input type="email" class="form-control" id="major" name="major">
									</div>
									<div class="form-group">
										<label for="minor">Minor</label>
										<input type="email" class="form-control" id="minor" name="minor">
									</div>
									<button type="button" onclick="javascript: go_server_user_permit_insert();" class="btn btn-primary btn-block">생 성</button>
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
</body>
</html>
