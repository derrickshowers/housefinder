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
	$index = 0;

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
											
							// Increment index value
							$index++;
							
							// Add class if new today and/or rejected
							$list .= "<tr class='detailsArea";
							if (isAddressRejected($address, $rejectedHouses))
								$list .= " danger hidden'";
							else if ($days == 0)
								$list .= " success'";
							else
								$list .="'";
							$list .= ">";
							
							// Build out details area
							$list .= "<td class='hidden-xs'>";
							if ($days == 0) 
								$list .= "New Today!</td>";
							else if ($days == 1)
								$list .= $days . " day ago</td>";
							else
								$list .= "" . $days . " days ago</td>";
							$list .= "<td>" . $township . "</td>";
							$list .= "<td>" . $address . "</td>";
							$list .= "<td>" . $price . "</td>";
							
							// Options menu
							$list .= "<td><div id='optionMenu" . $index . "' class='btn-group pull-right' data-address='" . $address . "' data-notes='" . $notes . "'><button type='button' class='btn btn-primary btn-xs dropdown-toggle' data-toggle='dropdown'><span class='glyphicon glyphicon-wrench'></span> <span class='caret'></span></button><ul class='dropdown-menu' role='menu' aria-labelledby='dropdown'>";
							$list .= "<li role='presentation'><a role='menuitem' tabindex='-1' target='_blank' href='" . $url . "'>More Details</a></li>";
							$list .= "<li role='presentation'><a role='menuitem' tabindex='-1' href='mailto:?body=Check out this one!  " . $url . "&subject=A House I Like'>Send Email</a></li>";
							$list .= "<li" . ((isAddressRejected($address, $rejectedHouses)) ? "" : " class='hidden'") . " role='presentation'><a class='seeNotes' data-toggle='modal' role='menuitem' tabindex='-1' href='#modal_notes'>See Notes</a></li>";
							$list .= "<li" . ((isAddressRejected($address, $rejectedHouses)) ? "" : " class='hidden'") . " role='presentation'><a class='removeRejected' role='menuitem' tabindex='-1' href='#'>I like it!</a></li>";
							$list .= "<li" . ((isAddressRejected($address, $rejectedHouses)) ? " class='hidden'" : "") . " role='presentation'><a class='enterNotes' data-toggle='modal' role='menuitem' tabindex='-1' href='#modal_form'>Not For Us</a></li>";
							$list .= "</ul></div></td></tr>";
							
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
				if ($rejectedHouse['address'] == $address) return str_replace("'", "&#39;", $rejectedHouse['notes']);
		}
		
		return "";
		
	}

?>