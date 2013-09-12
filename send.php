<?php
$debug = false;
function is_pgp($my_string) {

	$blank = strpos($my_string, "\r\n\r\n");
	$end = strpos($my_string, "----END");
	$pub = strpos($my_string, "BEGIN PGP PUBLIC");
	$mes = strpos($my_string, "BEGIN PGP MESSAGE");
	$begin =  strpos($my_string, "-----BEGIN PGP");
	if ($begin == FALSE) {
		return false;
	}
	elseif ($blank == FALSE) {
		return false;
	}
	elseif ($end == FALSE) {
		return false;
	}
	elseif ($end < $begin) {
		return false;
	}
	elseif ($pub != FALSE) {
		return 'PUBLIC';
	}
	elseif ($mes != false) {
		return 'MESSAGE';
	}
}
//validate
$validation = true;
$message = mysql_escape_string($_REQUEST['message']);
$tokey = mysql_escape_string($_REQUEST['tokey']);
if (!array_key_exists('fromkey', $_REQUEST) or is_pgp($_REQUEST['fromkey']) == false) {
	$fromkey = 'ANONYMOUS';
}
else {
	$fromkey = mysql_escape_string($_REQUEST['fromkey']);
}
if ($debug == true) {
	echo is_pgp($message) . is_pgp($tokey) . is_pgp($fromkey);
}
if (is_pgp($message) == 'MESSAGE' and is_pgp($tokey) == 'PUBLIC') {//Check if valid PGP
	if ($fromkey == 'ANONYMOUS' or is_pgp($fromkey) == 'PUBLIC') {
		$validation = true;
	}
	else {
		$validation = false;
	}
}
else {
	$validation = 'debug';
}
$query = 'INSERT INTO `messages` (`tokey`,`fromkey`, `message`,`time`) VALUES ("' .
	$tokey . '","' .
	$fromkey . '","' .
	$message . '",' .
	'NOW())';
if ($validation == true) {
	$db = mysqli_connect($host = "localhost",$username = '330479',$passwd = 'cadbury123',$dbname = '330479');
	$query = $db->query($query);
	echo 'Sucess';
}
else{
	echo 'Data validation failed. Nothing was added to the database';
}
if ($debug == true){
	echo $validation;
}
?>
