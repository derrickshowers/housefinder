<?php

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

	for ($days=0; $days < $daysSelected; $days++) {
	
		$currentDate = new DateTime();
		$currentDate -> modify('-' . $days . ' day');
		$newDateDay = (int)$currentDate -> format('d');
		$newDateMonth = $currentDate -> format('F');
		
		foreach ($townships as $key => $value) {
			
			$content = file_get_contents('http://www.everyhome.com/Homes_For_Sale/Pennsylvania/'.$key.'/New_Listings/' . $newDateMonth . '_' . $newDateDay . '_2013.htm');
			
			foreach ($townships[$key] as $value) {
			
				preg_match_all('/<tr><td class=\'addr_pcct\'>.*'.$value.'.*<\/tr>/', $content, $matches);
				
				foreach ($matches as $val) {
					for ($x=0; $x < count($val); $x++) {
						$row -> loadHTML($val[$x]);
						$cols = $row -> getElementsByTagName('td');
						$url = $row -> getElementsByTagName('a');
						$price = ($cols -> item(4) -> nodeValue != "") ? $cols -> item(4) -> nodeValue : $cols -> item(5) -> nodeValue;
						$priceInt = (int)str_replace(array("$",","),"",$price);
						if (!$priceFilter || ($priceInt >= 200000 && $priceInt <= 400000)) {
							$list .= ($days == 0) ? "<tr class='success'>": "<tr>";
							if ($days == 0) 
								$list .= "<td>New Today!</td>";
							else if ($days == 1)
								$list .= "<td>" . $days . " day ago</td>";
							else
								$list .= "<td>" . $days . " days ago</td>";
							$list .= "<td>" . $cols -> item(1) -> nodeValue . "</td>";
							$list .= "<td>" . $cols -> item(2) -> nodeValue . "</td>";
							$list .= "<td>" . $price . "</td>";
							$list .= "<td><a target='_blank' href='" . $url -> item(1) -> getAttribute('href') . "'>Go</a></td>";
							$list .= "</tr>";
						}
					}
				}		
			}
		}	
	}

?>