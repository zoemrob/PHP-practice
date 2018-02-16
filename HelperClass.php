<?php

Class HelperClass {

   /** returns a string of the current time formatted based on various parameters. To be used in UI.
	* @param $format, passes in a string to format time
	* @param optional integer, GMT timezone, default timezone set to GMT -8:00 PST
	*/
	public static function getTimeUI(string $format) {
		switch($format) {
			// example: Friday, February 9th, 2017 at 3:35pm
			case 'dmyt':
				return date('l, F jS, Y') . ' at ' . date('g:ia');
				break;
			// example: Friday, February 9th
			case 'dmy':
				return date('l, F jS, Y');
				break;
			// example: February 9th, 2017
			case 'my':
				return date('F jS, Y');
				break;
			// example: Friday
			case 'd':
				return date('l');
				break;
		}

	}

	public static function makeNote($text) {
		$note = [
			'date' => self::getTimeUI('dmyt'),
			'note' => $text
		];
		return $note;
	}

	public static function JSONtoArray($JSONString) {
		return json_decode($JSONString, true);
	}
}