

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
    <link rel="stylesheet" href="../public/graindashboard/css/graindashboard.css">
    
    <!-- Template -->
    <link rel="stylesheet" href="public/graindashboard/css/test.css">
</head>

<body class="has-sidebar has-fixed-sidebar-and-header">

<main class="main">

    <div class="content">
        <div class="py-4 px-3 px-md-4">
            <div class="card mb-3 mb-md-4">

                <div class="card-body">
                    <!-- Breadcrumb -->
                    <nav class="d-none d-md-block" aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item">
                                <a href="#">이벤트</a>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page">실시간</li>
                        </ol>
                    </nav>
                    <!-- End Breadcrumb -->

                    <div class="mb-3 mb-md-4 d-flex justify-content-between">
                        <div class="title_fonts2">실시간</div>
                        <div class="btn-group btn-toggle" onclick=""> 
                    <button class="btn btn-sm btn-primary active">ON</button>
                    <button class="btn btn-sm  btn-default ">OFF</button>
                    </div>
                    </div>
                    

                    <!-- Users -->
                    <div class="table-responsive-xl" style="overflow:auto; height:33rem;">
                        <table class="table text-nowrap mb-0" id="table_row" style="text-align:center">
                            <thead>
                            <tr>
                                <th class="font-weight-semi-bold border-top-0 py-2" style="position: sticky; top: 0; background-color:#fff;">번호</th>
                                <th class="font-weight-semi-bold border-top-0 py-2" style="position: sticky; top: 0; background-color:#fff;">이벤트 시간</th>
                                <th class="font-weight-semi-bold border-top-0 py-2" style="position: sticky; top: 0; background-color:#fff;">이벤트 유형</th>
                                <th class="font-weight-semi-bold border-top-0 py-2" style="position: sticky; top: 0; background-color:#fff;">이벤트 내용</th>
                                <th class="font-weight-semi-bold border-top-0 py-2" style="position: sticky; top: 0; background-color:#fff;">사용자 그룹</th>
                                <th class="font-weight-semi-bold border-top-0 py-2" style="position: sticky; top: 0; background-color:#fff;">사용자 번호</th>
                                <th class="font-weight-semi-bold border-top-0 py-2" style="position: sticky; top: 0; background-color:#fff;">사용자 이름</th>
                                <th class="font-weight-semi-bold border-top-0 py-2" style="position: sticky; top: 0; background-color:#fff;">직급</th>
                                <th class="font-weight-semi-bold border-top-0 py-2" style="position: sticky; top: 0; background-color:#fff;">카드번호</th>
                                <th class="font-weight-semi-bold border-top-0 py-2" style="position: sticky; top: 0; background-color:#fff;">온도</th>
                                <th class="font-weight-semi-bold border-top-0 py-2" style="position: sticky; top: 0; background-color:#fff;">마스크 착용</th>
                                <th class="font-weight-semi-bold border-top-0 py-2" style="position: sticky; top: 0; background-color:#fff;">단말기 이름</th>
                                <th class="font-weight-semi-bold border-top-0 py-2" style="position: sticky; top: 0; background-color:#fff;">단말기 시리얼</th>
                                <th class="font-weight-semi-bold border-top-0 py-2" style="position: sticky; top: 0; background-color:#fff;"></th>

                            </tr>
                            </thead>
                            <tbody id='live_list'>

                            </tbody>
                        </table>
                    </div>
                    <div class="card-footer d-block d-md-flex align-items-center d-print-none">
                        <!-- <div id = 'Entries' class="d-flex mb-2 mb-md-0">Showing 0 Entries</div> -->
                    </div>
                    <!-- End Users -->
                </div>
            </div>
        </div>
<div id="user_picture" class="modal fade" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" >
    <div class="modal-dialog rounded" role="document" style="top: 8rem; padding: 0!important; width: 30rem;">
        <div class="modal-content" style="overflow: auto;">
            <header class="modal-header flex-column justify-content-center" style="border-bottom: 1px solid #a7a7a7; ">
                <div style="width: 100%;">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="padding: 1.2rem 1rem 0 0;"><span aria-hidden="true">&times;</span></button>    
                <div class="title_fonts">인증 사진</div>
                </div>
            </header>
            <div class="modal-body pt-3">
                <div class="card">
                    <div class="card-body">
                        <img class="user_img" style="width:420px;height:400px;" src="">
					</div>
				</div>
            </div>
        </div>
    </div>
</div>

        

        <!-- Footer -->
        <?php include 'footer.php'?>
        <!-- End Footer -->
    </div>
</main>
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

<script type="text/javascript">

$(document).ready(function() {
    devices_events('on');
});

// setInterval(function() {
//     $("body").append('<iframe id="subscribe" src="/onepass/phpMQTT/subscribe.php" width="0" height="0"></iframe>')
// }, 15000);

// setInterval(function(){$("body").append('<iframe id="subscribe" src="/onepass/phpMQTT/subscribe2.php" width="0" height="0"></iframe>')}, 2000);


$('.btn-toggle').click(function() {
    $(this).find('.btn').toggleClass('active');  
    
    if ($(this).find('.btn-primary').length>0) {
    	$(this).find('.btn').toggleClass('btn-primary');
        $(this).find('.btn').toggleClass('btn_on');
    }
    $(this).find('.btn').toggleClass('btn-default');
});



</script>
<iframe id="subscribe" src="phpMQTT/subscribe2.php" width="0" height="0"></iframe>
</body>
</html>
