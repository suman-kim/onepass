<?php


session_start();
$_SESSION["user_id"] = "";
$_SESSION["user_name"] = "";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <!-- Title -->
    <title>Login | Graindashboard UI Kit</title>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta http-equiv="x-ua-compatible" content="ie=edge">

    <!-- Favicon -->
    <link rel="shortcut icon" href="../public/img/favicon.ico">
    <!-- Template -->
    <link rel="stylesheet" href="../public/graindashboard/css/graindashboard.css">
</head>

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
								<h4 class="card-title">Login</h4>
								<form name="login_form" method="POST">
									<div class="form-group">
										<label for="user_id">ID</label>
										<input id="user_id" type="text" class="form-control" name="user_id">
									</div>

									<div class="form-group">
										<label for="password">Password
										</label>
										<input id="password" type="password" class="form-control" name="user_pw">
										<div class="text-right">
											<a href="password-reset.php" class="small">
												Forgot Your Password?
											</a>
										</div>
									</div>

									<div class="form-group">
										<div class="form-check position-relative mb-2">
											<input type="checkbox" class="form-check-input d-none" id="remember_id" name="remember_id">
											<label class="checkbox checkbox-xxs form-check-label ml-1" for="remember_id"
												data-icon="&#xe936">Remember Me</label>
										</div>
									</div>

									<div class="form-group no-margin">
										<button onclick="javascript: goLogin();" class="btn btn-primary btn-block">Sign In</button>
									</div>
									<div class="text-center mt-3 small">
										Don't have an account? <a href="join.php">Sign Up</a>
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
		$(document).ready(function(){
			
			// ????????? ???????????? ???????????? ID ?????? ????????????. ????????? ???????????? ?????????.
			var user_id_key = getCookie("user_id_key");
			$("#user_id").val(user_id_key); 
			
			if($("#user_id").val() != ""){ // ??? ?????? ID??? ???????????? ?????? ????????? ?????? ???, ?????? ?????? ????????? ID??? ????????? ????????????,
				$("#remember_id").attr("checked", true); // ID ??????????????? ?????? ????????? ??????.
			}
			
			$("#remember_id").change(function(){ // ??????????????? ????????? ?????????,
				if($("#remember_id").is(":checked")){ // ID ???????????? ???????????? ???,
					setCookie("user_id_key", $("#user_id").val(), 7); // 7??? ?????? ?????? ??????
				}else{ // ID ???????????? ?????? ?????? ???,
					deleteCookie("user_id_key");
				}
			});
			
			// ID ??????????????? ????????? ???????????? ID??? ???????????? ??????, ?????? ?????? ?????? ??????.
			$("#user_id").keyup(function(){ // ID ?????? ?????? ID??? ????????? ???,
				if($("#remember_id").is(":checked")){ // ID ??????????????? ????????? ????????????,
					setCookie("user_id_key", $("#user_id").val(), 7); // 7??? ?????? ?????? ??????
				}
			});
		});

		function setCookie(cookieName, value, exdays){
			var exdate = new Date();
			exdate.setDate(exdate.getDate() + exdays);
			var cookieValue = escape(value) + ((exdays==null) ? "" : "; expires=" + exdate.toGMTString());
			document.cookie = cookieName + "=" + cookieValue;
		}

		function deleteCookie(cookieName){
			var expireDate = new Date();
			expireDate.setDate(expireDate.getDate() - 1);
			document.cookie = cookieName + "= " + "; expires=" + expireDate.toGMTString();
		}

		function getCookie(cookieName) {
			cookieName = cookieName + '=';
			var cookieData = document.cookie;
			var start = cookieData.indexOf(cookieName);
			var cookieValue = '';
			if(start != -1){
				start += cookieName.length;
				var end = cookieData.indexOf(';', start);
				if(end == -1)end = cookieData.length;
				cookieValue = cookieData.substring(start, end);
			}
			return unescape(cookieValue);
		}

		function goLogin() {
			var f = document.login_form;
			f.target = "hiddenfrm";
			f.action = "login_proc.php";
			f.submit();
		}

	</script>
</body>
</html>