<?php

	include('../config/config.php');
	
	// Get the data
	$username = $_POST['username'];
	$password = md5($_POST['password']);
	
	// Connect to database
	$r = mysql_connect($db_host, $db_user, $db_pass);
	mysql_select_db('housefinder');
	if (!$r) {
	    echo "Could not connect to server\n";
	} else {
	    echo "Connection established\n"; 
	}
	
	// Access the data
	$sql = "SELECT * FROM users WHERE username='$username' and password='$password'";
	$results = mysql_query($sql);
	$row = mysql_fetch_assoc($results);
	$firstname = $row['firstname'];
	$count = mysql_num_rows($results);
	
	// Allow or deny
	if ($count >= 1) {
		session_start();
		$_SESSION['firstname'] = $firstname;
		$_SESSION['username'] = $username;
		header('Location: /?msg=loginSuccess');
	} else {
		header('Location: /?msg=loginFailed');
	}
	
	// Close connection
	mysql_close($r);

?>