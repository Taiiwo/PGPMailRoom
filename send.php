<?php
#include_once 'include.php';
$debug = 0;
function is_pgp($my_string) {

	$blank = strpos($my_string, "\r\n\r\n");
	$end = strpos($my_string, "----END");
	$pub = strpos($my_string, "BEGIN PGP PUBLIC");
	$mes = strpos($my_string, "BEGIN PGP MESSAGE");
	$begin =  strpos($my_string, "-----BEGIN PGP");
	if ($begin == 0 or $end == 0 or $blank == 0 or $end < $begin) {
		return 0;
	}
	else {
		if ($pub != 0) {
			return 'PUBLIC';
		}
		if ($mes != 0) {
			return 'MESSAGE';
		}
		return 0;
	}
}
//validate
$validation = 1;
$message = mysql_escape_string($_POST['message']);
$tokey = mysql_escape_string($_POST['tokey']);
if (!array_key_exists('fromkey', $_POST) or is_pgp($_POST['fromkey']) == 0) {
	$fromkey = 'ANONYMOUS';
}
else {
	$fromkey = mysql_escape_string($_POST['fromkey']);
}
if ($debug == 1) {
	echo is_pgp($message) . is_pgp($tokey) . is_pgp($fromkey);
}
if (is_pgp($message) == 'MESSAGE' and is_pgp($tokey) == 'PUBLIC') {//Check if valid PGP
	if ($fromkey == 'ANONYMOUS' or is_pgp($fromkey) == 'PUBLIC') {
		$validation = 1;
	}
	else {
		$validation = 0;
	}
}
else {
	$validation = 0;
}
$query = 'INSERT INTO `messages` (`tokey`,`fromkey`, `message`,`time`) VALUES ("' .
	$tokey . '","' .
	$fromkey . '","' .
	$message . '",' .
	'NOW())';
if ($validation == 1) {
	$db = mysqli_connect($host = "localhost",$username = '330479',$passwd = $dbpass,$dbname = '330479');
	$query = $db->query($query);
	echo 'Sucess';
}
else{
	echo 'Data validation failed. Nothing was added to the database.';
}
if ($debug == 1){
	echo $validation;
}
?>
