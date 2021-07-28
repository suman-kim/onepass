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
					<div class="card-wrapper col-12 col-md-4 mt-5">
						<div class="brand text-center mb-3">
							<a href="login.php"><img src="../public/img/logo.png"></a>
						</div>
						<div class="card">
							<div class="card-body">
								<h4 class="card-title">Create new member</h4>
								<form name = "join_form" method = "POST">
			
									<div class="form-group">
										<label for="user_id">ID</label>
										<input id="user_id" type="text" class="form-control" name="user_id">
									</div>

									<div class="form-row">
										<div class="form-group col-md-6">
											<label for="password">Password
											</label>
											<input id="password" type="password" class="form-control" name="user_pw">
										</div>
										<div class="form-group col-md-6">
											<label for="user_pw_confirm">Confirm Password
											</label>
											<input id="user_pw_confirm" type="password" class="form-control" name="user_pw_confirm">
										</div>
									</div>

									<div class="form-group">
										<label for="name">Name</label>
										<input type="text" class="form-control" id="user_name" name="user_name">
									</div>

									<div class="form-group">
										<label for="name">E-mail</label>
										<input type="email" class="form-control" id="user_email" name="user_email">
									</div>

									<div class="form-group">
										<label for="name">Address</label>
										<input type="text" class="form-control" id="user_address" name="user_address">
									</div>

									<div class="form-group no-margin">
										
									<button type="button" onclick="javascript: goJoin();" class="btn btn-primary btn-block">Sign In</button>
									</div>
									<div class="text-center mt-3 small">
										Already have an account? <a href="login.php">Sign In</a>
									</div>
								</form>
							</div>
						</div>
						<footer class="footer mt-3">
							<div class="container-fluid">
								<div class="footer-content text-center small">
									<span class="text-muted">&copy; 2019 Graindashboard. All Rights Reserved.</span>
								</div>
							</div>
						</footer>
					</div>
				</div>



			</div>

		</div>
    </main>


	<iframe type="hidden" id="hiddenfrm" name="hiddenfrm" style="width: 100%; height: 500;"></iframe>
	<script src="../public/graindashboard/js/graindashboard.js"></script>
    <script src="../public/graindashboard/js/graindashboard.vendor.js"></script>

	<script>
		function goJoin() {
			var f = document.join_form;
			if (f.user_id.value == "") {
				alert("ID를 입력해주세요."); f.user_id.focus();
				return;
			}else if (f.user_pw.value == "") {
				alert("비밀번호를 입력해주세요."); f.user_pw.focus();
				return;
			}else if (f.user_pw_confirm.value == "") {
				alert("비밀번호 확인을 입력해주세요."); f.user_pw_confirm.focus();
				return;
			}else if (f.user_name.value == "") {
				alert("이름을 입력해주세요."); f.user_name.focus();
				return;
			}else if (f.user_pw.value != f.user_pw_confirm.value) {
				alert("비밀번호확인이 일치하지않습니다. "); f.user_pw_confirm.focus();
				return;
			}

			var user_info = {
							"name": f.user_name.value,
							"login_id": f.user_id.value,
							"login_pw": f.user_pw.value,
							"type_id":1, 			
							"permit_id":1
							}

			$.ajax({
				type : "POST",
				url : "http://192.168.2.162:9980/v1/server/users",
				dataType : 'json',
				contentType : 'application/json',
				data : JSON.stringify(user_info),
				success : function(data) {
					if (data.error_code == 1) {
						alert("회원가입에 성공하였습니다. 로그인해주세요.");
						location.href = "login.php";
					}
					else{
						alert("ID가 중복되었습니다.");
						f.user_id.focus();
					}
				},
				error: function(jqXHR, textStatus, errorThrown) {
					console.log(jqXHR);
					console.log("error");
				}
			});
		}
	</script>
<form name="hiddenJoinFrm" method="POST">
	<input type = "hidden" name="user_info">
<form>
</body>
</html>
