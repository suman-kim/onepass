

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
                                <a href="#">Live</a>
                            </li>
                        </ol>
                    </nav>
                    <!-- End Breadcrumb -->

                    <div class="mb-3 mb-md-4 d-flex justify-content-between">
                        <div class="h3 mb-0">Live</div>
                    </div>


                    <!-- Users -->
                    <div class="table-responsive-xl" style="overflow:auto; height:33rem;">
                        <table class="table text-nowrap mb-0">
                            <thead>
                            <tr>
                                <th class="font-weight-semi-bold border-top-0 py-2" style="position: sticky; top: 0; background-color:#fff;">#</th>
                                <th class="font-weight-semi-bold border-top-0 py-2" style="position: sticky; top: 0; background-color:#fff;">Name</th>
                                <th class="font-weight-semi-bold border-top-0 py-2" style="position: sticky; top: 0; background-color:#fff;">msg</th>
                                <th class="font-weight-semi-bold border-top-0 py-2" style="position: sticky; top: 0; background-color:#fff;">Registration Date</th>
                                <th class="font-weight-semi-bold border-top-0 py-2" style="position: sticky; top: 0; background-color:#fff;">Status</th>
                            </tr>
                            </thead>
                            <tbody id = 'live_list'>

                            </tbody>
                        </table>
                    </div>
                    <div class="card-footer d-block d-md-flex align-items-center d-print-none">
                        <div id = 'Entries' class="d-flex mb-2 mb-md-0">Showing 0 Entries</div>
                    </div>
                    <!-- End Users -->
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
//$('#live').addClass('active'); 
var i  = 1;
    function insertRow(date, msg) {
        var str = '<tr><td class="py-0">'+i+'</td><td class="align-middle py-0"><div class="d-flex align-items-center"><div class="mr-1">';
        str += '<span class="avatar-placeholder mr-md-2" style="height: 100%;">T</span></div>TEST</div></td>';
        str += '<td class="py-0">'+msg+'</td>';
        str += '<td class="py-0">'+date+'</td>';
        str += '<td class="py-0"><span class="badge badge-pill badge-success">문열림</span></td></tr>';

        $("#live_list").prepend(str);
        $("#Entries").text("Showing " + i + " Entries");
        i += 1;
    }

    Date.prototype.format = function (f) {

        if (!this.valueOf()) return " ";

        var weekKorName = ["일요일", "월요일", "화요일", "수요일", "목요일", "금요일", "토요일"];
        var weekKorShortName = ["일", "월", "화", "수", "목", "금", "토"];
        var weekEngName = ["Sunday", "Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday"];
        var weekEngShortName = ["Sun", "Mon", "Tue", "Wed", "Thu", "Fri", "Sat"];
        var d = this;

        return f.replace(/(yyyy|yy|MM|dd|KS|KL|ES|EL|HH|hh|mm|ss|a\/p)/gi, function ($1) {
            switch ($1) {
                case "yyyy": return d.getFullYear(); // 년 (4자리)
                case "yy": return (d.getFullYear() % 1000).zf(2); // 년 (2자리)
                case "MM": return (d.getMonth() + 1).zf(2); // 월 (2자리)
                case "dd": return d.getDate().zf(2); // 일 (2자리)
                case "KS": return weekKorShortName[d.getDay()]; // 요일 (짧은 한글)
                case "KL": return weekKorName[d.getDay()]; // 요일 (긴 한글)
                case "ES": return weekEngShortName[d.getDay()]; // 요일 (짧은 영어)
                case "EL": return weekEngName[d.getDay()]; // 요일 (긴 영어)
                case "HH": return d.getHours().zf(2); // 시간 (24시간 기준, 2자리)
                case "hh": return ((h = d.getHours() % 12) ? h : 12).zf(2); // 시간 (12시간 기준, 2자리)
                case "mm": return d.getMinutes().zf(2); // 분 (2자리)
                case "ss": return d.getSeconds().zf(2); // 초 (2자리)
                case "a/p": return d.getHours() < 12 ? "오전" : "오후"; // 오전/오후 구분
                default: return $1;
            }
        });
    };
    String.prototype.string = function (len) { var s = '', i = 0; while (i++ < len) { s += this; } return s; };
    String.prototype.zf = function (len) { return "0".string(len - this.length) + this; };
    Number.prototype.zf = function (len) { return this.toString().zf(len); };
    
    insertRow(new Date().format('yyyy-MM-dd HH:mm:ss'), "서버에 접속했습니다.");


    window.onload = function(){
        devices_events();
    
    }
</script>

<iframe src="phpMQTT/subscribe3.php" width="500" height="100" style="display: none;"></iframe>

</body>
</html>
