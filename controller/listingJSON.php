<?php
	
	include('config.php');
	
	// Set time zone
	date_default_timezone_set('America/New_York');
	
	// Return json
	header('Content-Type: application/json');
	
	// See if json is available
	$cacheFile = '../cache/listings.json';
	
	if (file_exists($cacheFile) && date("U",filectime($cacheFile) >= time() - 3600)) {
		
		// Get file contents
		$fh = fopen($cacheFile, 'r');
		$content = fread($fh, filesize($cacheFile));
		fclose($fh);
		
		// Output file
		echo $content;
		
		// Get outta here
		exit;
		
	}
	
	// Define Variables
	date_default_timezone_set('America/New_York');
	$townships = array(
		"Montgomery_County" => "Lower Merion Township",
		"Chester_County" => "Tredyffrin Township",
		"Chester_County" => "Willistown Township",
		"Delaware_County" => "Radnor Township",
		"Delaware_County" => "Haverford Township",
	);
	$json = '{"properties": [';
	$list = "";
	$listing = new DOMDocument();
	$daysSelected = (empty($_GET['days'])) ? 5 : $_GET['days'];
	$index = 0;

	for ($days=0; $days < $daysSelected; $days++) {
	
		$currentDate = new DateTime();
		$currentDate -> modify('-8 hour');
		$currentDate -> modify('-' . $days . ' day');
		$newDateDay = (int)$currentDate -> format('d');
		$newDateMonth = $currentDate -> format('F');
		
		foreach ($townships as $county => $township) {
			
			// Get file based on date and township
			$filename = 'http://www.everyhome.com/Homes_For_Sale/Pennsylvania/'. $county .'/New_Listings/' . $newDateMonth . '_' . $newDateDay . '_2013.htm';
			$content = file_get_contents($filename);
			
				preg_match_all('/<tr><td class=\'addr_pcct\'>.*'.$township.'.*<\/tr>/', $content, $matches);
				
				foreach ($matches as $val) {
					
					for ($x=0; $x < count($val); $x++) {
						
						// Start listing
						if ($index != 0) $json .= ',';
						$json .= '{"listing" : {';
						
						// Get stuff from HTML page
						$listing -> loadHTML($val[$x]);
						$cols = $listing -> getElementsByTagName('td');
						$url = $listing -> getElementsByTagName('a') -> item(1) -> getAttribute('href');
						$township = $cols -> item(1) -> nodeValue;
						$address = $cols -> item(2) -> nodeValue;
						$price = ($cols -> item(4) -> nodeValue != "") ? $cols -> item(4) -> nodeValue : $cols -> item(5) -> nodeValue;
						$priceInt = (int)str_replace(array("$",","),"",$price);
						
						// Place into json object
						$json .= '"age" : "' . $days . '",';
						$json .= '"township" : "' . $township . '",';
						$json .= '"address" : "' . $address . '",';
						$json .= '"price" : "' . $price . '",';
						$json .= '"url" : "' . $url . '"';
						
						// End of listing
						$json .= '}}';
						
						// Increment index value
						$index++;	
						
					}
					
			}
		
		}
			
	}
	
	$json .= "]}";
	
	echo $json;
	
	// Store json to file
	$jsonFile = fopen('../cache/listings.json', 'w');
	fwrite($jsonFile, $json);
	fclose($jsonFile);

?>