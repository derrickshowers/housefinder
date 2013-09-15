<?php

	include('../config/config.php');
	
	// Redirect home if data not part of request
	if (empty($_POST['address'])) {
		header("Location: /?msg=error");
		exit;
	}
	
	// Store important variables
	$address = $_POST['address'];
	$rejected = $_POST['rejected'];
	$notes = addslashes($_POST['notes']);
	
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
		$query = "DELETE FROM rejected WHERE address='$address'";
	else
		$query = "INSERT INTO rejected (address, notes, rejected) VALUES ('$address', '$notes', '$rejected') ON DUPLICATE KEY UPDATE notes = '$notes', rejected = '$rejected'";
	$retval = mysql_query($query, $r);
	if (!$retval) {
		die('Could not enter data: ' . mysql_error());
	}
	echo "Entered data successfully\n";
	mysql_close($r);
	
?>