<?php 
    session_start();

	$host = 'localhost';
	$user = 'root';
	$pwd = '1234567890';	
	$dbname = 'gbook';
	$db = new mysqli($host, $user, $pwd, $dbname);

	// Check connection
	if($db->connect_errno <> 0){
		echo 'Failure to link database';
		echo $db->connect_errno;
		exit;
	}
?>