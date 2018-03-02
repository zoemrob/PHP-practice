<?php
$formatted = '';
for ($i = 1; $i < 88; $i++) {
	if (strlen($i) == 1) {
		$modifiedI = '0' . $i;
		$html = file_get_contents('http://odevax.ode.state.oh.us/htbin/www_getirn.com?SELECTION=COUNTY&county='. $modifiedI .'&district=');
	} else {	
		$html = file_get_contents('http://odevax.ode.state.oh.us/htbin/www_getirn.com?SELECTION=COUNTY&county='. $i .'&district=');
	}
	$data = strip_tags($html);
	$formatted .= substr($data, 104);
	echo "Fetching county " . $i . "\n";	
}

file_put_contents('codes.txt', $formatted);