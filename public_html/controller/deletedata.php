<?php

	include('../config/config.php');
	session_start();
	
	// Redirect home if data not part of request
	if (empty($_POST['address'])) {
		header("Location: /?msg=error");
		exit;
	}
	else if (!isset($_SESSION['firstname'])) {
		echo "Login Needed";
		exit;
	}
	
	// Store important variables
	$address = $_POST['address'];
	
	// Connect to database
	$r = mysql_connect($db_host, $db_user, $db_pass);
	mysql_select_db('housefinder');
	if (!$r) {
	    echo "Could not connect to server\n";
	} else {
	    echo "Connection established\n"; 
	}
	
	// Store it to the database abd close
	$query = "DELETE FROM houses WHERE address='$address'";
	$retval = mysql_query($query, $r);
	if (!$retval) {
		die('Could not enter data: ' . mysql_error());
	}
	echo "Entered data successfully\n";
	mysql_close($r);
	
?>