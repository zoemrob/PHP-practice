<?php

/*
	This Class will be used to create a new instance of a person to enter into database.
	A new instance of this object will be created on the HTML page, and an API will scrape the information out of the instanced
	objecct to create a database entry.
	$age, $name, $sex, and $notes will be received from form submission.
*/

Class BasePerson {

	public $age;
	public $name;
	public $sex;
	public $notes = [];

	public function __construct() {

	}

	// Method will receive data from JavaScript or form submission
	// Possibly from database query
	public function setAge($num) {
		$this->age = $num;
	}

	// Method will return age to front end PHP/Javascript to be appended to html
	public function getAge() {
		return $this->age;
	}

	// Methood will receive data from Javascript or form submission
	// Possibly from databse query
	public function setName($str) {
		$this->name = $str;
	}

	// Method will return name to front end PHP/Javascript to be appended to html
	public function getName() {
		return $this->name;
	}
	// Methood will receive data from Javascript or form submission
	// Possibly from database query
	// This will be a radio select or radio-liike buttons (where only one is an option)
	public function setSex($sexChar) {
		$sexChar === 'M' ? $this->sex = 'Male': 'Female';
	}

	// Method will return sex to front end PHP/Javascript to be appended to html
	public function getSex() {
		return $this->sex;
	}

   /* Method will add however many list items are submitted, with appropriate date tag information
	* On the front-end this will be a dynamic jQuery structure where a button 'New Note' will append a * second text field to the DOM, which will be entered in the next index
	* @param $note string that will be added to note log
	*/
 	public function setNote($note) {
		$lengthBefore = count($this->notes);
		$dateTag = date('l, F jS, Y') . ' at ' . date('g:ia');
		array_push($this->notes, ['date' => $dateTag, 'note' => $note]);
		// echo for testing, should set a return value or assign the string to a variable that can be displayed as an alert in the browser. Or a popup div.
		echo $lengthBefore + 1 === count($this->notes) ? "'" . $note . "' was added to the note log successfully. \n\n" : "Your message failed to post.\n\n";
	} 

    // also add a method that can take an array of indexes, and delete multiples if necessary.ss

   /* Method deletes a note from the $notes array, and echo's the note that was deleted. And re-keys
    * the $notes array numerically.
    * On the front-end, $noteNumber will be the numerical order of the entry.
    * @param $noteNumber is index of $this->notes array
    * if index does not exist in $this->notes, echo alert.
	*/
    public function deleteSingleNote($noteNumber) {
    	if (isset($this->notes[$noteNumber])) {
	    	$deletedNote = $this->notes[$noteNumber]['note']; 
	    	unset($this->notes[$noteNumber]);
	    	$this->notes = array_values($this->notes);
	    	echo "Your note '" . $deletedNote . "' was successfully deleted.\n";    		
    	} else {
    		echo "That note does not exist!\n";
    	}
	}

	// Method deletes all notes from array. Clears log.
	public function deleteAllNotes() {
		if ($this->notes === []) {
			echo "You don't have any notes on this person!\n";
		} else {
			$this->notes = [];
			echo "All of your notes about " . $this->name . " were deleted.\n";
		}
	}

}