<?php
//@set_time_limit(0);
require('../phpMQTT.php');

$server = '127.0.0.1';     // change if necessary
$port = 1883;                     // change if necessary
$username = '';                   // set your username
$password = '';                   // set your password
$client_id = 'phpMQTT-subscribe-msg'; // make sure this is unique for connecting to sever - you could use uniqid()

$mqtt = new Bluerhinos\phpMQTT($server, $port, $client_id);
if(!$mqtt->connect(true, NULL, $username, $password)) {
	exit(1);
}
$msg = $mqtt->subscribeAndWaitForMessage('news/aa', 0);
echo $msg;

$mqtt->close();
echo "<script>parent.insertRow('A : ".date("Y-m-d H:i:s")." --> $msg');</script>";
//echo ("<script>location.reload();</script>") ;
?>