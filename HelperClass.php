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

	/* Method creates new entry form to allow a new database entry to be added.
	 */
	public static function generateNewEntryForm() {
		$data = ['displayData' => '
			<div class="center">
				<form id="new-entry-form">
					<p class="subheader margin0">New Entry</p>
					<div class="pad10">
						<div class="pad10">
							<label>First Name:</label>
							<input id="first-name" name="first-name" type="text" placeholder="Enter first name" required>
						</div>
						<div class="pad10">
							<label>Last Name:</label>
							<input id="last-name" name="last-name" type="text" placeholder="Enter last name" required>
						</div>
						<div class="pad10">
							<label>Age:</label>
							<input id="age" name="age" type="number" placeholder="Age" required>
						</div>
						<div class="pad10">
							<label>Gender:</label>
							<input type="radio" class="gender-input" name="gender-input" id="male" value="M" required>
							<label for="male">Male</label>
							<input type="radio" class="gender-input" name="gender-input" id="female" value="F" required>
							<label for="female">Female</label>
						</div>
					</div>
					<div class="pad10">
						<button type="click" id="submit-button">SUBMIT</button>
					</div>
				</form>
			</div>
		'];
		return json_encode($data);
	}
	/* This method formats the data into JSON in the agreeable format and sends it.
	 * @param $dataType str, lets client know how to process data.
	 * @param $data, mixed, data to send to client
	 */
	public static function formatClientData($dataType, $data) {
		$agreeableData = array(
			'dataType' => $dataType,
			'data' => $data
			);
		return $agreeableData;
	}

	/* Method formats the results of Search Bar function from UI into table data elements which are appended to the DOM.
	 * @param $cursor, cursor turned array
	 */
	public static function formatSearchResults($cursor){
		$htmlStringSearchResults = array();
		$data = MongoHelper::getNameAndMongoId($cursor);
		foreach($data as $person) {
			$htmlStringSearchResults[] =
				"<td id='" . $person['mongoId'] . "'>" . $person['firstName'] . " " . $person['lastName'] . "</td>";
		}
		return $htmlStringSearchResults;
	}
}