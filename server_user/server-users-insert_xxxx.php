<?php include '../inc/global.php'?>
<?php
$serverUserRightsRs = get("/v1/server/user-rights");
$serverUserRights = json_decode($serverUserRightsRs);
if ($serverUserRights->error_code == 0) {
	$strServerUserRights = "";
	$totalRows = count($serverUserRights->permit_infos);
	for ($i=0; $i < $totalRows; $i++) { 
		$strServerUserRights .= "<option value='".$serverUserRights->permit_infos[$i]->id."'>".$serverUserRights->permit_infos[$i]->name."</option>";
	}
}
$serverUserTypeRs = get("/v1/server/user-types");
$serverUserType = json_decode($serverUserTypeRs);
if ($serverUserType->error_code == 0) {
	$strServerUserType = "";
	$totalRows = count($serverUserType->type_infos);
	for ($i=0; $i < $totalRows; $i++) { 
		$strServerUserType .= "<option value='".$serverUserType->type_infos[$i]->id."'>".$serverUserType->type_infos[$i]->name."</option>";
	}
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
								<h4 class="card-title">서버 사용자 추가</h4>
								<form name = "server_user_insert_form" method = "POST">
									<div class="form-group">
										<label for="server_user_name">서버 사용자 이름</label>
										<input type="text" class="form-control" id="server_user_name" name="server_user_name">
									</div>
									<div class="form-group">
										<label for="login_id">ID</label>
										<input type="text" class="form-control" id="login_id" name="login_id">
									</div>
									<div class="form-group">
										<label for="login_pw">Password</label>
										<input type="password" class="form-control" id="login_pw" name="login_pw">
									</div>

									<div class="form-group">
										<label for="type_id">서버 사용자 유형</label>
										<select class="form-control" id="type_id" name="type_id">
											<?=$strServerUserType?>
										</select>
									</div>
									<div class="form-group">
										<label for="permit_id">서버 사용자 권한</label>
										<select class="form-control" id="permit_id" name="permit_id">
											<?=$strServerUserRights?>
										</select>
									</div>
									<button type="button" onclick="javascript: go_server_user_insert();" class="btn btn-primary btn-block">생 성</button>
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
