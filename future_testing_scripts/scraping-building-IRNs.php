<?php
$formatted = '';
//for ($i = 1; $i < 88; $i++) {
	/*if (strlen($i) == 1) {
		$modifiedI = '0' . $i;
		$html = file_get_contents('http://ode000.ode.state.oh.us/htbin/ohio_educ_dir.com?dtype=01.+Any+Specific+District&dirn=043885&birn=&county=02+Allen');
	} else {	*/
	$html = file_get_contents('http://ode000.ode.state.oh.us/htbin/ohio_educ_dir.com?dtype=01.+Any+Specific+District&dirn=043885&birn=&county=02+Allen');
	//}
	$dom = new DOMDocument;
	$dom->loadHTML($html);
	foreach($dom->getElementsByTagName('FONT') as $fontTag) {
		if($fontTag->getAttribute('COLOR') == 'A62A2A') {
			$data = $fontTag->nodeValue;
		}
	}
	//$data = strip_tags($html);
	$formatted .= $data;//substr($data, 104);
	//echo "Fetching county " . $i . "\n";	
//}

echo $formatted;

//file_put_contents('codes.txt', $formatted);