<?php 
$counties = array (
"Adams",
"Allen",
"Ashland",
"Ashtabula",
"Athens",
"Auglaize",
"Belmont",
"Brown",
"Butler",
"Carroll",
"Champaign",
"Clark",
"Clermont",
"Clinton",
"Columbiana",
"Coshocton",
"Crawford",
"Cuyahoga",
"Darke",
"Defiance",
"Delaware",
"Erie",
"Fairfield",
"Fayette",
"Franklin",
"Fulton",
"Gallia",
"Geauga",
"Greene",
"Guernsey",
"Hamilton",
"Hancock",
"Hardin",
"Harrison",
"Henry",
"Highland",
"Hocking",
"Holmes",
"Huron",
"Jackson",
"Jefferson",
"Knox",
"Lake",
"Lawrence",
"Licking",
"Logan",
"Lorain",
"Lucas",
"Madison",
"Mahoning",
"Marion",
"Medina",
"Meigs",
"Mercer",
"Miami",
"Monroe",
"Montgomery",
"Morgan",
"Morrow",
"Muskingum",
"Noble",
"Ottawa",
"Paulding",
"Perry",
"Pickaway",
"Pike",
"Portage",
"Preble",
"Putnam",
"Richland",
"Ross",
"Sandusky",
"Scioto",
"Seneca",
"Shelby",
"Stark",
"Summit",
"Trumbull",
"Tuscarawas",
"Union",
"Van",
"Wert",
"Vinton",
"Warren",
"Washington",
"Wayne",
"Williams",
"Wood");

$countyAndNumber = array();
$i = 1;
foreach($counties as $county) {
	if (strlen($i) == 1) {
		$alteredI = '0' . $i;
		$countyAndNumber[$alteredI] = $county;
		$i++;
	} else {
		$countyAndNumber["{$i}"] = $county;
		$i++;
	}
}

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
'Jefferson High School (9-12)                 IRN:   018184'

'<FONT COLOR="A62A2A">                                                                                                            
Jefferson High School (9-12)                 IRN:   018184' //188 characters
