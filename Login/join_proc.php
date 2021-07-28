<?php
    require('../inc/global.php');

    $userId = AntiCrack(trim($_POST["user_id"]));
    $userPw = AntiCrack(trim($_POST["user_pw"]));
    $userName = AntiCrack(trim($_POST["user_name"]));
    $userEmail = AntiCrack2(trim($_POST["user_email"]));
    $userAddress = AntiCrack2($_POST["user_address"]);
    $userPw = base64_encode(hash('sha256', $userPw, true));

    if ($userId != "") {
        require '../inc/dbconn.php';
        $selectSql = "select user_id from user where user_id = '$userId'";
        $selectRs = mysqli_query($conn, $selectSql);
        $total_rows = mysqli_num_rows($selectRs);

        if ($selectRs->num_rows > 0) {
            $msg = "아이디가 중복되었습니다.";
            ?><script>alert("<?=$msg?>");parent.reload;</script><?php
            mysqli_close($conn);
            exit();
        }
        else {
            $insertSql = "insert into user(user_id, user_pw, user_name, user_email, user_address) values ('$userId','$userPw','$userName','$userEmail','$userAddress')";
            $insertRs = mysqli_query($conn, $insertSql);
            if ($insertRs) {
                $msg = "회원가입에 성공하였습니다.";
            }
            else {
                $msg = "회원가입에 실패하였습니다.";
                ?><script>alert("<?=$msg?>");parent.reload;</script><?php
                mysqli_close($conn);
                exit();
            }
        }
        mysqli_close($conn);
    }
?>

<script>
    alert("<?=$msg?>");
    parent.location.href="login.php";
</script>