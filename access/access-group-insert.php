<?php include '../inc/global.php'?>
<?php


$deviceRs = get("/v1/devices");
$device = json_decode($deviceRs);

if ($device->error_code == 1) {
	$deviceStr = "";
	$totalRows = $device->device_count;
	for ($i=0; $i < $totalRows; $i++) { 
		$deviceStr .= '<label style="margin-right: 2rem;"><input type="checkbox" id="device_ids[]" name="device_ids" style="width: 20px; height: 12px;" value="'.$device->device_infos[$i]->id.'"><span>'.$device->device_infos[$i]->product_info->model_name.'</span></label>';
	}
}

$accTimeGroupRs = get("/v1/access-time-groups");
$accTimeGroup = json_decode($accTimeGroupRs);

if ($accTimeGroup->error_code == 1) {
	$accTimeGroupStr = "";
	$totalRows = count($accTimeGroup->access_time_group_infos);
	for ($i=0; $i < $totalRows; $i++) { 
		$accTimeGroupStr .= '<option value="'.$accTimeGroup->access_time_group_infos[$i]->id.'">'.$accTimeGroup->access_time_group_infos[$i]->name.'</option>';
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
<!-- Modal -->
<div class="modal fade" id="theModal" role="dialog" style="margin-top:5%;">
    <div class="modal-dialog rounded py-5" role="document" style="padding-top: 0!important; width: 50rem; left: 3rem;">
        <div class="modal-content py-5" style="padding-top: 10px!important; overflow: auto; height: 50rem;">
            <header class="modal-header flex-column justify-content-center" style="border-bottom: 1px solid #a7a7a7; padding-bottom: 0;">
                <div style="width: 100%;">
                    <h4 class="card-title">출입 그룹 생성
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="padding: 1.2rem 1rem 0 0;"><span aria-hidden="true">&times;</span></button>
                    </h4>
                </div>
            </header>
                    
				<div class="modal__body">
                <div class="container-fluid pb-5">

				<div class="row justify-content-md-center">
					<div class="card-wrapper col-12 col-md-10 mt-3">
						<div class="brand text-center mb-3">
						</div>
						<div class="card">
							<div class="card-body">
								<form name = "access_insert_form" method = "POST">
									<input type="hidden"name="acc_group_info">
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
										<?=$deviceStr?>
									</div>

									<div class="form-group">
										<label for="acc_time_group_id">출입 시간 그룹</label>
										<select class="form-control" id="acc_time_group_id" name="acc_time_group_id">
											<?=$accTimeGroupStr?>
										</select>
									</div>
									
									<button type="button" onclick="javascript: go_acc_group_insert();" class="btn btn-primary btn-block">생 성</button>
								</form>
							</div>
						</div>
					</div>
				</div>

			</div>
			</div>
									
		
		</form>
                </div>
            </div>
        </div>

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
		//acc_group_insert_info();
	</script>
</body>
</html>


