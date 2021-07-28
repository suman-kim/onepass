<?php
//session_start();
?>
<!-- Header -->
<header class="header">
    <nav class="navbar flex-nowrap p-0" >
        <div class="navbar-brand-wrapper d-flex align-items-center col-auto" style="background-color: rgb(0 36 66); margin-top: -21px;">
            <!-- Logo For Mobile View -->
            <a class="navbar-brand navbar-brand-mobile" href="#" onclick = "javascript: goDashboard();" style="margin-left: -10px;">
                <span class="img-fluid w-100" ><h4 style="color: #fff; font-weight: bold;">ONE</h4></span>
            </a>
            <!-- End Logo For Mobile View -->

            <!-- Logo For Desktop View -->
            <a class="navbar-brand navbar-brand-desktop" href="#Dashboard" onclick = "javascript: goDashboard();">
            
                <span class="side-nav-show-on-closed" style="margin-left: -10px;"><h4 style="color: #fff; font-weight: bold;">ONE</h4></span>
                <span class="side-nav-hide-on-closed" style="margin-left: 25px;"><h2 style="color: #fff; font-weight: bold;">ONE PASS</h2></span>
                <!--
                <img class="side-nav-show-on-closed" src="public/img/logo-mini.png" alt="Graindashboard" style="width: auto; height: 33px;">
                <img class="side-nav-hide-on-closed" src="public/img/logo.png" alt="Graindashboard" style="width: auto; height: 33px;">
                -->
            </a>
            <!-- End Logo For Desktop View -->
        </div>

        <div class="header-content col px-md-3" style="padding-top: 10px;">
            <div class="d-flex align-items-center">
                <!-- Side Nav Toggle -->
                <a class="js-side-nav header-invoker d-flex mr-md-2" href="#" data-close-invoker="#sidebarClose" data-target="#sidebar" data-target-wrapper="body">
                    <i class="gd-align-left"></i>
                </a>
                <!-- End Side Nav Toggle -->

                <!-- User Notifications -->
                <div class="dropdown ml-auto">
                    <a id="notificationsInvoker" class="header-invoker" href="#" aria-controls="notifications" aria-haspopup="true" aria-expanded="false" data-unfold-event="click" data-unfold-target="#notifications" data-unfold-type="css-animation" data-unfold-duration="300" data-unfold-animation-in="fadeIn" data-unfold-animation-out="fadeOut">
                        <span class="indicator indicator-bordered indicator-top-right indicator-primary rounded-circle"></span>
                        <i class="gd-bell"></i>
                    </a>

                    <div id="notifications" class="dropdown-menu dropdown-menu-center py-0 mt-4 w-18_75rem w-md-22_5rem unfold-css-animation unfold-hidden" aria-labelledby="notificationsInvoker" style="animation-duration: 300ms;">
                        <div class="card">
                            <div class="card-header d-flex align-items-center border-bottom py-3">
                                <h5 class="mb-0">Notifications</h5>
                                <a class="link small ml-auto" href="#">Clear All</a>
                            </div>

                            <div class="card-body p-0">
                                <div class="list-group list-group-flush">
                                    <div class="list-group-item list-group-item-action">
                                        <div class="d-flex align-items-center text-nowrap mb-2">
                                            <i class="gd-info-alt icon-text text-primary mr-2"></i>
                                            <h6 class="font-weight-semi-bold mb-0">New Update</h6>
                                            <span class="list-group-item-date text-muted ml-auto">just now</span>
                                        </div>
                                        <p class="mb-0">
                                            Order <strong>#10000</strong> has been updated.
                                        </p>
                                        <a class="list-group-item-closer text-muted" href="#"><i class="gd-close"></i></a>
                                    </div>
                                    <div class="list-group-item list-group-item-action">
                                        <div class="d-flex align-items-center text-nowrap mb-2">
                                            <i class="gd-info-alt icon-text text-primary mr-2"></i>
                                            <h6 class="font-weight-semi-bold mb-0">New Update</h6>
                                            <span class="list-group-item-date text-muted ml-auto">just now</span>
                                        </div>
                                        <p class="mb-0">
                                            Order <strong>#10001</strong> has been updated.
                                        </p>
                                        <a class="list-group-item-closer text-muted" href="#"><i class="gd-close"></i></a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- End User Notifications -->
                <!-- User Avatar -->
                <div class="dropdown mx-3 dropdown ml-2">
                    <!--<a id="profileMenuInvoker" class="header-complex-invoker" href="#" aria-controls="profileMenu" aria-haspopup="true" aria-expanded="false" data-unfold-event="click" data-unfold-target="#profileMenu" data-unfold-type="css-animation" data-unfold-duration="300" data-unfold-animation-in="fadeIn" data-unfold-animation-out="fadeOut">
                        <span class="mr-md-2 avatar-placeholder">?=iconv_substr($_SESSION["user_name"],0,1)?></span>
                        <span class="d-none d-md-block">?=$_SESSION["user_name"]?></span>
                        <i class="gd-angle-down d-none d-md-block ml-2"></i>
                    </a>-->
                    <a id="profileMenuInvoker" class="header-complex-invoker" href="Login/login.php?logout=1">
                        <span class="unfold-item-icon mr-3"><i class="gd-power-off"></i></span>
                        Sign Out
                    </a>

                    <ul id="profileMenu" class="unfold unfold-user unfold-light unfold-top unfold-centered position-absolute pt-2 pb-1 mt-4 unfold-css-animation unfold-hidden fadeOut" aria-labelledby="profileMenuInvoker" style="animation-duration: 300ms;">
                        <li class="unfold-item">
                            <a class="unfold-link d-flex align-items-center text-nowrap" href="#">
                                <span class="unfold-item-icon mr-3"><i class="gd-user"></i></span>
                                My Profile
                            </a>
                        </li>
                        <li class="unfold-item unfold-item-has-divider">
                            <a class="unfold-link d-flex align-items-center text-nowrap" href="Login/login.php?logout=1">
                                <span class="unfold-item-icon mr-3"><i class="gd-power-off"></i></span>
                                Sign Out
                            </a>
                        </li>
                    </ul>
                </div>
                <!-- End User Avatar -->
            </div>
            <div class="header-content px-md-3 header-tab">
                <ul id="tabs">
                    <li id ="home" style="padding: 0;">
                        <a onclick = "javascript: goDashboard();" href='#Dashboard' style="font-size: 24px;">
                            <div class='side-nav-fadeout-on-closed media-body'><i class="gd-home"></i></div>
                        </a>
                    </li>
                </ul>
                <span class='side-nav-fadeout-on-closed media-body moretabInvoker' id="moretabInvoker" href="#" aria-controls="moretab" aria-haspopup="true" aria-expanded="false" data-unfold-event="click" data-unfold-target="#moretab" data-unfold-type="css-animation" data-unfold-duration="300" data-unfold-animation-in="fadeIn" data-unfold-animation-out="fadeOut" style="opacity: 1;">
                    <i class="gd-angle-double-right"></i>
                </span>
                <div id="moretab" class="py-0 w-18_75rem w-md-22_5rem unfold-css-animation unfold-hidden moreTab" aria-labelledby="moretabInvoker">
                    <div class="card">
                        <div class="card-header d-flex align-items-center border-bottom py-3">
                            <h5 class="mb-0">Tab</h5>
                            <a class="link small ml-auto" onclick="javascript: clearAllTab();" href="#">Clear All</a>
                        </div>

                        <div class="card-body p-0">
                            <div id="tabList" class="list-group list-group-flush">
                                
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </nav>
</header>
<!-- End Header -->