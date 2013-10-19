<?php
#include_once 'send.php';
$query = mysql_escape_string($_REQUEST['query']);
$state = mysql_escape_string($_REQUEST['state']);
$error = 0;
//validation
if ($state == 'TO' or $state == 'to') {
	$state2 = 'tokey';
}
elseif ($state == 'FROM' or $state == 'from') {
	$state2 = 'fromkey';
}
else {
	$error = 1;
}
$query2 = mysql_escape_string($_POST["query"]);
//get data
if ($error == 0) {
	$db = mysqli_connect('localhost','330479','cadbury123','330479');//Don't drop table please, I'll fix this later.
	$results = $db->query('SELECT * FROM `messages` WHERE `'.
		$state2 .
		'` = "' .
		$query2 .
		'"');
	if ($results) {
		$assoc = $results->fetch_assoc();
	}
	echo json_encode($assoc);
}
else {
	echo 'Error ' . $error . ' occured<br />';
	echo $state . '<br />';
	echo $query . '<br />';
}
?>
