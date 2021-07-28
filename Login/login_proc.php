<?php
    session_start();
    require('../inc/global.php');

    $userId = AntiCrack($_POST["user_id"]);
    $userPw = AntiCrack($_POST["user_pw"]);
    $userPw = base64_encode(hash('sha256', $userPw, true));

    if ($userId != "") {
        require '../inc/dbconn.php';
        $selectSql = "select user_id, user_name, user_email from user where user_id = '".$userId."' and user_pw = '".$userPw."'";
        $selectRs = mysqli_query($conn, $selectSql);
        $total_rows = mysqli_num_rows($selectRs);
        $row = mysqli_fetch_array($selectRs);
        
        if ($total_rows == 0) {
            ?><script>alert("아이디가 또는 비밀번호가 틀렸습니다.");</script><?php
            mysqli_close($conn);
            exit();
        }
        else {
            $_SESSION["user_id"] = "$row[user_id]";
            $_SESSION["user_name"] = "$row[user_name]";
            $_SESSION["user_email"] = "$row[user_email]";
            $msg = "로그인에 성공했습니다.";
        }
        mysqli_close($conn);
    }
?>

<script>
    parent.location.href="../index.php";
</script>