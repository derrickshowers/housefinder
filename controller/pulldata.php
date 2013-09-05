<?php

	include('config.php');
	
	// Define Variables
	date_default_timezone_set('America/New_York');
	$townships = array(
		"Montgomery_County" => array("Lower Merion Township"),
		"Chester_County" => array("Tredyffrin Township","Willistown Township"),
		"Delaware_County" => array("Radnor Township","Haverford Township")
	);
	$list = "";
	$row = new DOMDocument();
	$daysSelected = (empty($_GET['days'])) ? 5 : $_GET['days'];
	$priceFilter = (empty($_GET['priceFilter'])) ? false : true;
	$rejectedHouses = getRejectedAddresses();

	for ($days=0; $days < $daysSelected; $days++) {
	
		$currentDate = new DateTime();
		$currentDate -> modify('-8 hour');
		$currentDate -> modify('-' . $days . ' day');
		$newDateDay = (int)$currentDate -> format('d');
		$newDateMonth = $currentDate -> format('F');
		
		foreach ($townships as $key => $value) {
			
			// Get file based on date and township
			$filename = 'http://www.everyhome.com/Homes_For_Sale/Pennsylvania/'.$key.'/New_Listings/' . $newDateMonth . '_' . $newDateDay . '_2013.htm';
			$content = file_get_contents($filename);
			
			foreach ($townships[$key] as $value) {
			
				preg_match_all('/<tr><td class=\'addr_pcct\'>.*'.$value.'.*<\/tr>/', $content, $matches);
				
				foreach ($matches as $val) {
					
					for ($x=0; $x < count($val); $x++) {
						
						// Get stuff from HTML page
						$row -> loadHTML($val[$x]);
						$cols = $row -> getElementsByTagName('td');
						$url = $row -> getElementsByTagName('a') -> item(1) -> getAttribute('href');
						$township = $cols -> item(1) -> nodeValue;
						$address = $cols -> item(2) -> nodeValue;
						$price = ($cols -> item(4) -> nodeValue != "") ? $cols -> item(4) -> nodeValue : $cols -> item(5) -> nodeValue;
						$priceInt = (int)str_replace(array("$",","),"",$price);
						$notes = getAddressNotes($address, $rejectedHouses);
						
						// Conditional for price filter
						if (!$priceFilter || ($priceInt >= 200000 && $priceInt <= 400000)) {
											
							// Add class if new today and/or rejected
							$list .= "<tr class='detailsArea";
							if (isAddressRejected($address, $rejectedHouses))
								$list .= " error'";
							else if ($days == 0)
								$list .= " success'";
							else
								$list .="'";
							$list .= ">";
							
							// Build out details area
							if ($days == 0) 
								$list .= "<td>New Today!</td>";
							else if ($days == 1)
								$list .= "<td>" . $days . " day ago</td>";
							else
								$list .= "<td>" . $days . " days ago</td>";
							$list .= "<td>" . $township . "</td>";
							$list .= "<td>" . $address . "</td>";
							$list .= "<td>" . $price . "</td>";
							$list .= "<td><a target='_blank' href='" . $url . "'>Go</a></td>";
							$list .= "</tr>";
							
							// Reject form
							$list .= "<tr class='rejectArea'><td colspan='5'><form class='rejectForm'>";
							$list .= "<input type='text' name='notes' value='" . $notes . "' />";
							$list .= "<input type='hidden' name='address' value='" . $address . "' />";
							$list .= "<input type='submit' name='reject' value='reject' />";
							$list .= "</form></td></tr>";
							
						}
					}
				}		
			}
		}	
	}
	
	function getRejectedAddresses () {
		
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
		
		return $rejectedHouses;

	}
	
	function isAddressRejected ($address, $rejectedHouses) {
		
		foreach ($rejectedHouses as $rejectedHouse) {
			if ($rejectedHouse['address'] == $address) return true;
		}
		
		return false;
		
	}
	
	function getAddressNotes ($address, $rejectedHouses) {
		
		foreach ($rejectedHouses as $rejectedHouse) {
			if ($rejectedHouse['address'] == $address) return $rejectedHouse['notes'];
		}
		
		return "";
		
	}

?>