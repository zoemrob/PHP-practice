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

$formatted = array();
$i = 1;
foreach($counties as $county) {
	if (strlen($i) == 1) {
		$alteredI = '0' . $i;
		$formatted[$alteredI] = $county;
		$i++;
	} else {
		$formatted["{$i}"] = $county;
		$i++;
	}
}

$formattedAgain =array();
foreach($formatted as $key => $val) {
	$formattedAgain[$key . $val] = array();
}

var_dump($formattedAgain);