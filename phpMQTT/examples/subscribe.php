<script src="../../public/graindashboard/js/graindashboard.js"></script>
<script src="../../public/graindashboard/js/graindashboard.vendor.js"></script>
<script src="../../public/graindashboard/js/onepass.js"></script>
<?php
//에러 메세지 끄기
ini_set('display_errors','0');
require('../phpMQTT.php');

$server = '192.168.2.187';     // change if necessary
$port = 1883;                     // change if necessary
$username = '';                   // set your username
$password = '';                   // set your password
$client_id = 'phpMQTT-subscriber'; // make sure this is unique for connecting to sever - you could use uniqid()

$mqtt = new Bluerhinos\phpMQTT($server, $port, $client_id);

if(!$mqtt->connect(true, NULL, $username, $password)) {
	exit(1);
}

$mqtt->debug = true;
echo "<script>alert('<?=$mqtt?>');</script>";

$topics['news/#'] = array('qos' => 0, 'function' => 'procMsg');
$mqtt->subscribe($topics, 0);

while($mqtt->proc()) {
	
}

$mqtt->close();

function procMsg($topic, $msg){
	
	//echo $msg;
	$rs = json_encode($msg);
	
	echo "<script>device_scan('<?=$rs?>');</script>";
	$mqtt->disconnect();
}
?>