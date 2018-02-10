<?php

Class HelperClass {

   /** returns a string of the current time formatted based on various parameters. To be used in UI.
	* @param $format, passes in a string to format time
	* @param optional integer, GMT timezone, default timezone set to GMT -8:00 PST
	*/
	public static function getTimeUI(string $format, $timezone = -8) {
		$dayLightSavingsCheck = time() + 3600*($timezone+date("I"));

		switch($format) {
			// example: Friday, February 9th, 2017 at 3:35pm
			case 'dmyt':
				return gmdate('l, F jS, Y', $dayLightSavingsCheck) . ' at ' . date('g:ia', $dayLightSavingsCheck);
				break;
			// example: Friday, February 9th
			case 'dmy':
				return gmdate('l, F jS, Y', $dayLightSavingsCheck);
				break;
			// example: February 9th, 2017
			case 'my':
				return gmdate('F jS, Y', $dayLightSavingsCheck);
				break;
			// example: Friday
			case 'd':
				return gmdate('l', $dayLightSavingsCheck);
				break;
		}

	}
}