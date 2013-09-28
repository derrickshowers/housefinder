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
	$rejected = $_POST['rejected'];
	$notes = addslashes($_POST['notes']);
	$shortlisted = (isset($_POST['shortlist'])) ? 'Y' : 'N';
	
	// Make sure shortlisted houses aren't rejected
	if ($shortlisted) $rejected = 'N';
	
	// Connect to database
	$r = mysql_connect($db_host, $db_user, $db_pass);
	mysql_select_db('housefinder');
	if (!$r) {
	    echo "Could not connect to server\n";
	} else {
	    echo "Connection established\n"; 
	}
	
	// Store it to the database and close
	if ($notes == "")
		$query = "DELETE FROM houses WHERE address='$address'";
	else
		$query = "INSERT INTO houses (address, notes, rejected, shortlisted) VALUES ('$address', '$notes', '$rejected', '$shortlisted') ON DUPLICATE KEY UPDATE notes = '$notes', rejected = '$rejected', shortlisted = '$shortlisted'";
	$retval = mysql_query($query, $r);
	if (!$retval) {
		die('Could not enter data: ' . mysql_error());
	}
	echo "Entered data successfully\n";
	mysql_close($r);
	
?>