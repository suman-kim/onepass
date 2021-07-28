<?php include '../inc/global.php'?>
<?php
	$id = $_POST["id"];

	$dataRs = get("/v1/users/".$id);
	$data = json_decode($dataRs);
	$duTypeId = $data->du_info->du_type->type_id;
	$duGroupId = $data->du_info->du_group->group_id;
	$duJobGroupId = $data->du_info->du_job_group->id;
	$duAccGroupId = $data->du_info->du_access_group->access_id;
	$duJobPositionId = $data->du_info->du_job_position->id;

	$userGroupsRs = get("/v1/user-groups");
	$userGroups = json_decode($userGroupsRs);
	$totalRows = count($userGroups->du_group_infos);
	$userGroupsStr = "";
	for ($i=0; $i < $totalRows; $i++) { 
		$selected = ($duGroupId == $userGroups->du_group_infos[$i]->id) ? 'selected' : '';
		$userGroupsStr .= "<option value='".$userGroups->du_group_infos[$i]->id."' ".$selected.">".$userGroups->du_group_infos[$i]->name."</option>";
	}

	$jobPositionsRs = get("/v1/job-positions");
	$jobPositions = json_decode($jobPositionsRs);
	$totalRows = count($jobPositions->du_job_position_infos);
	$jobPositionsStr = "";
	for ($i=0; $i < $totalRows; $i++) { 
		$selected = ($duJobPositionId == $jobPositions->du_job_position_infos[$i]->id) ? 'selected' : '';
		$jobPositionsStr .= "<option value='".$jobPositions->du_job_position_infos[$i]->id."' ".$selected.">".$jobPositions->du_job_position_infos[$i]->name."</option>";
	}

    $jobGroupsRs = get("/v1/job-groups");
    $jobGroups = json_decode($jobGroupsRs);
	$totalRows = count($jobGroups->du_job_group_infos);
	$jobGroupsStr = "";
	for ($i=0; $i < $totalRows; $i++) { 
		$selected = ($duJobGroupId == $jobGroups->du_job_group_infos[$i]->id) ? 'selected' : '';
		$jobGroupsStr .= "<option value='".$jobGroups->du_job_group_infos[$i]->id."' ".$selected.">".$jobGroups->du_job_group_infos[$i]->name."</option>";
	}

    $userTypesRs = get("/v1/user-types");
    $userTypes = json_decode($userTypesRs);
	$totalRows = count($userTypes->du_type_infos);
	$userTypesStr = "";
	for ($i=0; $i < $totalRows; $i++) {
		$selected = ($duTypeId == $userTypes->du_type_infos[$i]->id) ? 'selected' : '';
		$userTypesStr .= "<option value='".$userTypes->du_type_infos[$i]->id."' ".$selected.">".$userTypes->du_type_infos[$i]->name."</option>";
	}

	
    $accGroupsRs = get("/v1/access-groups");
    $accGroups = json_decode($accGroupsRs);
	$totalRows = count($accGroups->access_group_infos);
	$accGroupsStr = "";
	for ($i=0; $i < $totalRows; $i++) { 
		$selected = ($duAccGroupId == $accGroups->access_group_infos[$i]->id) ? 'selected' : '';
		$accGroupsStr .= "<option value='".$accGroups->access_group_infos[$i]->id."' ".$selected.">".$accGroups->access_group_infos[$i]->name."</option>";
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
								<h4 class="card-title">사용자 수정</h4>
								<form name = "user_update_form" method = "POST">
								<div class="form-group">
										<label for="user_id">사용자 ID</label>
										<input type="text" class="form-control" id="user_id" name="user_id" value="<?=$data->du_info->id?>">
									</div>
									<div class="form-group">
										<label for="user_name">사용자 이름</label>
										<input type="text" class="form-control" id="user_name" name="user_name" value="<?=$data->du_info->name?>">
									</div>
									<div class="form-group">
										<label for="group_id">사용자 그룹</label>
										<select class="form-control" id="group_id" name="group_id">
											<?=$userGroupsStr?>
										</select>
									</div>
									<div class="form-group">
										<label for="job_position_id">사용자 직급</label>
										<select class="form-control" id="job_position_id" name="job_position_id">
											<?=$jobPositionsStr?>
										</select>
									</div>
									<div class="form-group">
										<label for="job_group_id">사용자 직군</label>
										<select class="form-control" id="job_group_id" name="job_group_id">
											<?=$jobGroupsStr?>
										</select>
									</div>
									<div class="form-group">
										<label for="type_id">사용자 유형</label>
										<select class="form-control" id="type_id" name="type_id">
											<?=$userTypesStr?>
										</select>
									</div>
									<div class="form-group">
										<label for="access_id">출입 그룹</label>
										<select class="form-control" id="access_id" name="access_id">
											<?=$accGroupsStr?>
										</select>
									</div>

									<div class="form-group">
										<label for="home_addr">주소</label>
										<input type="text" class="form-control" id="home_addr" name="home_addr" value="<?=$data->du_info->private_info->home_addr?>">
									</div>
									<div class="form-group">
										<label for="phone">연락처</label>
										<input type="text" class="form-control" id="phone" name="phone" value="<?=$data->du_info->private_info->phone?>">
									</div>
									<div class="form-group">
										<label for="email">E-Mail</label>
										<input type="text" class="form-control" id="email" name="email" value="<?=$data->du_info->private_info->email?>">
									</div>
									<div class="form-group">
										<label for="birthday">생년 월일</label>
										<input type="text" class="form-control" id="birthday" name="birthday" value="<?=$data->du_info->private_info->birthday?>">
									</div>
									<div class="form-group">
										<label for="join_company">입사일</label>
										<input type="text" class="form-control" id="join_company" name="join_company" value="<?=$data->du_info->private_info->join_company?>">
									</div>
									<div class="form-group">
										<label for="leave_company">퇴사일</label>
										<input type="text" class="form-control" id="leave_company" name="leave_company" value="<?=$data->du_info->private_info->leave_company?>">
									</div>

									<div class="form-group">
										<label for="cardno_count">카드 개수</label>
										<input type="text" class="form-control" id="cardno_count" name="cardno_count" value="<?=$data->du_info->secure_info->cardno_count?>">
									</div>
									<div class="form-group">
										<label for="cardnos">카드번호 목록</label>
										<input type="text" class="form-control" id="cardnos" name="cardnos" value="<?=$data->du_info->secure_info->cardno_list[0]?>">
									</div>
									<div class="form-group">
										<label for="pin_num">핀 번호</label>
										<input type="text" class="form-control" id="pin_num" name="pin_num" value="<?=$data->du_info->secure_info->pin_num?>">
									</div>
									<div class="form-group">
										<label for="pic_url">사진 주소</label>
										<input type="text" class="form-control" id="pic_url" name="pic_url" value="<?=$data->du_info->secure_info->pic_url?>">
									</div>
									<div class="form-group">
										<label for="pic_size">사진 크기</label>
										<input type="text" class="form-control" id="pic_size" name="pic_size" value="<?=$data->du_info->secure_info->pic_size?>">
									</div>
									<div class="form-group">
										<label for="pic_data">사진 DATA</label>
										<input type="text" class="form-control" id="pic_data" name="pic_data" value="<?=$data->du_info->secure_info->pic_data?>">
									</div>
									<div class="form-group">
										<label for="finger_size1">손가락 크기1</label>
										<input type="text" class="form-control" id="finger_size1" name="finger_size1" value="<?=$data->du_info->secure_info->finger_size1?>">
									</div>
									<div class="form-group">
										<label for="finger_data1">손가락 DATA1</label>
										<input type="text" class="form-control" id="finger_data1" name="finger_data1" value="<?=$data->du_info->secure_info->finger_data1?>">
									</div>
									<div class="form-group">
										<label for="finger_size2">손가락 크기2</label>
										<input type="text" class="form-control" id="finger_size2" name="finger_size2" value="<?=$data->du_info->secure_info->finger_size2?>">
									</div>
									<div class="form-group">
										<label for="finger_data2">손가락 DATA2</label>
										<input type="text" class="form-control" id="finger_data2" name="finger_data2" value="<?=$data->du_info->secure_info->finger_data2?>">
									</div>
									<div class="form-group">
										<label for="is_timecard">is_timecard</label>
										<input type="text" class="form-control" id="is_timecard" name="is_timecard" value="<?=$data->du_info->secure_info->is_timecard?>">
									</div>
									<div class="form-group">
										<label for="timecard_rule_id">timecard_rule_id</label>
										<input type="text" class="form-control" id="timecard_rule_id" name="timecard_rule_id" value="<?=$data->du_info->secure_info->timecard_rule_id?>">
									</div>
									<div class="form-group">
										<label for="use_access_time">인증 가능기간 설정 유무</label>
										<input type="text" class="form-control" id="use_access_time" name="use_access_time" value="<?=$data->du_info->secure_info->use_access_time?>">
									</div>
									<div class="form-group">
										<label for="access_time_bez">인증 시작일</label>
										<input type="text" class="form-control" id="access_time_bez" name="access_time_bez" value="<?=$data->du_info->secure_info->access_time_bez?>">
									</div>
									<div class="form-group">
										<label for="access_time_end">인증 종료일</label>
										<input type="text" class="form-control" id="access_time_end" name="access_time_end" value="<?=$data->du_info->secure_info->access_time_end?>">
									</div>
									<div class="form-group">
										<label for="employee_no">user_no</label>
										<input type="text" class="form-control" id="employee_no" name="employee_no" value="<?=$data->du_info->secure_info->employee_no?>">
									</div>
									<div class="form-group">
										<label for="employee_code">사원 코드</label>
										<input type="text" class="form-control" id="employee_code" name="employee_code" value="<?=$data->du_info->secure_info->employee_code?>">
									</div>

									<div class="form-group">
										<label for="reply_to">reply_to</label>
										<input type="text" class="form-control" id="reply_to" name="reply_to">
									</div>
									<div class="form-group">
										<label for="reply_method">reply_method</label>
										<input type="text" class="form-control" id="reply_method" name="reply_method">
									</div>
									<div class="form-group">
										<label for="reply_msg">reply_msg</label>
										<input type="text" class="form-control" id="reply_msg" name="reply_msg">
									</div>
									
									<button type="button" onclick="javascript: go_user_update();" class="btn btn-primary btn-block">수 정</button>
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
	</script>
</body>
</html>
