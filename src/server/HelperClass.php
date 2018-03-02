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
		$data = 
			'<div class="wth25 standard-bkgd-color standard-shadow top-corner-radius bottom-corner-radius margin0 pad10 montserrat-font default-border">' .
					'<p class="subheader margin0 bold">New Entry</p>
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
					</div>' .
			'</div>';
		return $data;
	}

	public static function generateHomepage() {
		$data = 
			"<div class='wth50 standard-bkgd-color standard-shadow top-corner-radius bottom-corner-radius margin0 pad10 montserrat-font default-border'>
				<span class='subheader bold'>This application allows you to keep a record of the various things your co-workers do throughout the day.</span>
				<span class='inline-em pad10'>(This is a joke.)</span>
				<br />
				<br />
				<span class='subheader'>It's for recording useful tidbits such as:</span>
				<ul>
					<li class='pad10'>How many cups of coffee do they drink a day?</li>
					<li class='pad10'>A funny joke they told. <span class='inline-em'>Matt, you kidder!</span></li>
					<li class='pad10'>A valuable contribution to standup.</li>
					<li class='pad10'>Are they wearing a new ring today? Is it cool?</li>
					<li class='pad10 inline-em'>And much more...</li>
				</ul>
				<p class='bold right pad10'>
					To get started, click \"New Entry\", or type in the search bar to retrieve an existing entry!
				</p>
			</div>";
		return $data;
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