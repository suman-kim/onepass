<!-- Sidebar Nav -->
<style>
    #sideNav>li>ul{background-color: #001d35; margin-left: -34px;}
    #sideNav>li>ul a{padding-left: 50px;}
    #sidebar #sideNav i {color: #fff;}
    span{
        font-size:14px;
        font-weight: 600;
    }
    a{
        font-size:14px;
        font-weight: 600;
    }
</style>

<aside id="sidebar" class="js-custom-scroll side-nav" style="background-color: rgb(0 36 66);">
    <ul id="sideNav" class="side-nav-menu side-nav-menu-top-level mb-0">
        <!-- Title -->
        <!--<li class="sidebar-heading h6">Dashboard</li>-->
        <!-- End Title -->

        <!-- Dashboard -->
        <li class="side-nav-menu-item side-nav-opened">
            <a class="side-nav-menu-link media align-items-center addTab dashboardTab" rel="dashboard" title="index.php" href="#Dashboard">
                <span class="side-nav-menu-icon d-flex mr-3">
                    <i class="gd-dashboard"></i>
                </span>
                <span class="side-nav-fadeout-on-closed media-body">Dashboard</span>
            </a>
        </li>
        <!-- End Dashboard -->

        <!-- Documentation -->
        <!-- <li class="side-nav-menu-item">
            <a class="side-nav-menu-link media align-items-center" href="documentation/">
                <span class="side-nav-menu-icon d-flex mr-3">
                    <i class="gd-file"></i>
                </span>
                <span class="side-nav-fadeout-on-closed media-body">Documentation</span>
            </a>
        </li> -->
        <!-- End Documentation -->

        <!-- Title -->
        <!--<li class="sidebar-heading h6">Examples</li>-->
        <!-- End Title -->

        <!-- device -->
        <li class="side-nav-menu-item side-nav-has-menu" >
            <a class="side-nav-menu-link media align-items-center" id="device_parent" href="#" data-target="#subDevice">
                <span class="side-nav-menu-icon d-flex mr-3">
                    <i class="gd-user"></i>
                </span>
                <span class="side-nav-fadeout-on-closed media-body">단말기</span>
                <span class="side-nav__indicator side-nav-fadeout-on-closed"></span>
            </a>

            <!-- Users: subDevice -->
            <ul id="subDevice" class="side-nav-menu side-nav-menu-second-level mb-0">
                <li class="side-nav-menu-item">
                    <a class="side-nav-menu-link addTab" rel="devices" title="device/devices.php" href="#" child="device">단말기</a>
                </li>
            </ul>
            <!-- End Users: subDevice -->
        </li>
        <!-- End device -->

        <!-- Users -->
        <li class="side-nav-menu-item side-nav-has-menu" >
            <a class="side-nav-menu-link media align-items-center" id="users_parent" href="#" data-target="#subUsers">
                <span class="side-nav-menu-icon d-flex mr-3">
                    <i class="gd-user"></i>
                </span>
                <span class="side-nav-fadeout-on-closed media-body">사용자</span>
                <span class="side-nav__indicator side-nav-fadeout-on-closed"></span>
            </a>

            <!-- Users: subUsers -->
            <ul id="subUsers" class="side-nav-menu side-nav-menu-second-level mb-0">
                <li class="side-nav-menu-item">
                    <a class="side-nav-menu-link addTab" rel="users" title="user/users.php" href="#" child="users">사용자</a>
                </li>
            </ul>
            <!-- End Users: subUsers -->
        </li>
        <!-- End Users -->

        <!-- Access -->
        <li class="side-nav-menu-item side-nav-has-menu" >
            <a class="side-nav-menu-link media align-items-center" id="access_parent" href="#" data-target="#subAccess">
                <span class="side-nav-menu-icon d-flex mr-3">
                    <i class="gd-user"></i>
                </span>
                <span class="side-nav-fadeout-on-closed media-body">출입</span>
                <!--<span class="side-nav-control-icon d-flex">
                    <i class="gd-angle-right side-nav-fadeout-on-closed"></i>
                </span>-->
                <span class="side-nav__indicator side-nav-fadeout-on-closed"></span>
            </a>

            <!-- Users: subUsers -->
            <ul id="subAccess" class="side-nav-menu side-nav-menu-second-level mb-0">
                <li class="side-nav-menu-item">
                    <a class="side-nav-menu-link addTab" rel="access-group" title="access/access-group.php" href="#" child="access">출입 그룹</a>
                </li>
                <li class="side-nav-menu-item">
                    <a class="side-nav-menu-link addTab" rel="access-time-group" title="access/access-time-group.php" href="#" child="access">출입 시간 그룹</a>
                </li>
            </ul>
            <!-- End Users: subUsers -->
        </li>
        <!-- End Access -->

        <!-- server-users -->
        <!-- <li class="side-nav-menu-item side-nav-has-menu" >
            <a class="side-nav-menu-link media align-items-center" id="server_user_parent" href="#" data-target="#serverUser">
                <span class="side-nav-menu-icon d-flex mr-3">
                    <i class="gd-user"></i>
                </span>
                <span class="side-nav-fadeout-on-closed media-body">서버 사용자</span>
                <span class="side-nav__indicator side-nav-fadeout-on-closed"></span>
            </a>

          
            <ul id="serverUser" class="side-nav-menu side-nav-menu-second-level mb-0">
                <li class="side-nav-menu-item">
                    <a class="side-nav-menu-link addTab" rel="server-users" title="server_user/server-users.php" href="#" child="server_user">서버 사용자</a>
                </li>
                <li class="side-nav-menu-item">
                    <a class="side-nav-menu-link addTab" rel="server-type" title="server_user/server-user-type.php" href="#" child="server_user">서버 사용자 타입</a>
                </li>
                <li class="side-nav-menu-item">
                    <a class="side-nav-menu-link addTab" rel="server-permit" title="server_user/server-user-permit.php" href="#" child="server_user">서버 사용자 권한</a>
                </li>
            </ul>
          
        </li> -->
        <!-- End server-users -->


        <!-- Live -->
        <li  class="side-nav-menu-item side-nav-has-menu" >
            <a class="side-nav-menu-link media align-items-center"  href="#" data-target="#subevent">
                <span class="side-nav-menu-icon d-flex mr-3">
                    <i class="gd-time"></i>
                </span>
                <span class="side-nav-fadeout-on-closed media-body">이벤트 조회</span>
            </a>

            <ul id="subevent" class="side-nav-menu side-nav-menu-second-level mb-0">
                <li class="side-nav-menu-item">
                    <a class="side-nav-menu-link addTab" rel="live" title="liveList.php" href="#" child="event">실시간 이벤트</a>
                </li>
            </ul>
        </li>
        <!-- End Live -->

        <!-- Settings -->
        <!-- <li class="side-nav-menu-item">
            <a class="side-nav-menu-link media align-items-center addTab" rel="settings" title="settings.php" href="#">
                <span class="side-nav-menu-icon d-flex mr-3">
                    <i class="gd-settings"></i>
                </span>
                <span class="side-nav-fadeout-on-closed media-body">Settings</span>
            </a>
        </li> -->
        <!-- End Settings -->

        <!-- Static -->
        <!-- <li class="side-nav-menu-item">
            <a class="side-nav-menu-link media align-items-center" href="static-non-auth.php">
                <span class="side-nav-menu-icon d-flex mr-3">
                    <i class="gd-file"></i>
                </span>
                <span class="side-nav-fadeout-on-closed media-body">Static page</span>
            </a>
        </li> -->
        <!-- End Static -->

    </ul>
</aside>
<!-- End Sidebar Nav -->