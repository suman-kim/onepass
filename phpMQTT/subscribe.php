<script src="../public/graindashboard/js/graindashboard.js"></script>
<script src="../public/graindashboard/js/graindashboard.vendor.js"></script>
<script src="../public/graindashboard/js/onepass.js"></script>

<?php
//에러 메세지 끄기

ini_set('display_errors','0');
require('phpMQTT.php');
require('../inc/global.php');

$server = $GLOBALS['mqttip'];    // change if necessary
$port = 1883;                     // change if necessary
$username = '';                   // set your username
$password = '';                   // set your password
$client_id = 'phpMQTT-subscriber'; // make sure this is unique for connecting to sever - you could use uniqid()

$mqtt = new Bluerhinos\phpMQTT($server, $port, $client_id);

if(!$mqtt->connect(true, NULL, $username, $password)) {
	exit(1);
}

$mqtt->debug = true;

$topics['devices/devices'] = array('qos' => 0, 'function' => 'procMsg');
$topics['users/users'] = array('qos' => 0, 'function' => 'procMsg2');


$mqtt->subscribe($topics, 0);

while($mqtt->proc()) {
	
}

//$mqtt->close();

function procMsg($topic, $msg){
	
	$rs = json_encode($msg);
	
	echo "<script>device_scan('<?=$rs?>');</script>";
	$mqtt->disconnect();
}
function procMsg2($topic, $msg){

	$rs = json_encode($msg);
	echo "<script>user_scan('<?=$rs?>');</script>";
	
	//echo "<script>location.href='../user/users.php';<script>";
	//$mqtt->disconnect();
}


?>