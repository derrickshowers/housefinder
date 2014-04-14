<?php
/*
* Convert JSON file to CSV and output it.
*
* JSON should be an array of objects, dictionaries with simple data structure
* and the same keys in each object.
* The order of keys it took from the first element.
*
* Example:
* json:
* [
*  { "key1": "value", "kye2": "value", "key3": "value" },
*  { "key1": "value", "kye2": "value", "key3": "value" },
*  { "key1": "value", "kye2": "value", "key3": "value" }
* ]
*
* The csv output: (keys will be used for first row):
* 1. key1, key2, key3
* 2. value, value, value
* 3. value, value, value
* 4. value, value, value
*
* Uses:
* json-to-csv.php file.json > file.csv
*/
 
include('../config/config.php');
	
global $db_host, $db_user, $db_pass;
$rejectedHouses = array();

// Connect to database
$r = mysql_connect($db_host, $db_user, $db_pass);
mysql_select_db('housefinder');

$query = "SELECT * FROM houses WHERE shortlisted='Y'";
$retval = mysql_query($query, $r);

while ($row_user = mysql_fetch_assoc($retval))
	$rejectedHouses[] = $row_user;

mysql_close($r);

$json = json_encode($rejectedHouses);
 
$array = json_decode($json, true);
$filename = '../cache/shortlisted.csv';
$file = fopen('../cache/shortlisted.csv', 'w');
 
$firstLineKeys = false;
foreach ($array as $line)
{
	if (empty($firstLineKeys))
	{
		$firstLineKeys = array_keys($line);
		fputcsv($file, $firstLineKeys);
		$firstLineKeys = array_flip($firstLineKeys);
	}
	// Using array_merge is important to maintain the order of keys acording to the first element
	fputcsv($file, array_merge($firstLineKeys, $line));
}

if (file_exists($filename)) {
    header('Content-Description: File Transfer');
    header('Content-Type: application/CSV');
    header('Content-Disposition: attachment; filename='.basename($filename));
    header('Content-Transfer-Encoding: binary');
    header('Expires: 0');
    header('Cache-Control: must-revalidate');
    header('Pragma: public');
    header('Content-Length: ' . filesize($filename));
    readfile($filename);
}

?>