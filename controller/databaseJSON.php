<?php

	// Return json
	header('Content-Type: application/json');
	
	include('config.php');
	
	global $db_host, $db_user, $db_pass;
	$rejectedHouses = array();
	
	// Connect to database
	$r = mysql_connect($db_host, $db_user, $db_pass);
	mysql_select_db('housefinder');
	
	$query = "SELECT * FROM rejected";
	$retval = mysql_query($query, $r);
	
	while ($row_user = mysql_fetch_assoc($retval))
		$rejectedHouses[] = $row_user;
	
	mysql_close($r);
	
	echo json_encode($rejectedHouses);
		
?>