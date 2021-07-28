<?php
    
    session_start();
    if ($_SESSION["user_id"] == "") {
        //echo '<script>alert("로그아웃되었습니다. 다시 로그인해주세요.");location.href="Login/login.php";</script>';
    }

    // if(!isset($_SESSION)) { 
    //     session_start(); 
    //   } 
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <!-- Title -->
    <title>Users | Graindashboard UI Kit</title>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <!-- Favicon -->
    <link rel="shortcut icon" href="public/img/favicon.ico">
    <!-- DEMO CHARTS -->
    <link rel="stylesheet" href="public/demo/chartist.css">
    <link rel="stylesheet" href="public/demo/chartist-plugin-tooltip.css">
    <!-- Template -->
    <link rel="stylesheet" href="public/graindashboard/css/graindashboard.css">
    <link rel="stylesheet" href="public/graindashboard/css/onepass.css">

    <script src="public/graindashboard/js/graindashboard.js"></script>
    <script src="public/graindashboard/js/graindashboard.vendor.js"></script>
    <script src="public/graindashboard/js/onepass.js"></script>

    <!-- DEMO CHARTS -->
    <script src="public/demo/resizeSensor.js"></script>
    <script src="public/demo/chartist.js"></script>
    <script src="public/demo/chartist-plugin-tooltip.js"></script>
    <script src="public/demo/gd.chartist-area.js"></script>
    <script src="public/demo/gd.chartist-bar.js"></script>
    <script src="public/demo/gd.chartist-donut.js"></script>
</head>



<body class="has-sidebar has-fixed-sidebar-and-header">
<!-- Header -->
<?php include 'header.php'?>
<!-- End Header -->

<?php include 'sidebar.php'?>


<script type="text/javascript">
    $(document).ready(function () {
        $(".addTab").click(function () {
            $("#sideNav li").removeClass("side-nav-opened");
            addTab($(this));
        });
    });

    
</script>


<main id="main_content" class="main" >
    <iframe id="main_index"  src="index.php" style="width: 100%; border: 0; position: relative; top: -3rem;"></iframe>
</main>