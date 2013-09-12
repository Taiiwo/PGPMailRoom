<?php
#include_once 'send.php';
$query = $_REQUEST['query'];
$state = $REQUEST['state'];
$error = 0;
//validation
if ($state != 'TO' and $state != 'to' and $state != 'FROM' and $state != 'from'){
	$error = 1;
}
$db = mysql_connect('localhost','330479','Nottpypingmypasswordonabus','330479');
$results = mysql_query('SELECT * FROM `messages` WHERE `' . $state . '`
?>
