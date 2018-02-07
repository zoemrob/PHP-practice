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
	// if pageLoad is true
	public function getAge($pageLoad) {
		return $pageLoad ?$this->age: '' ;
	}

	// Methood will receive data from Javascript or form submission
	// Possibly from databse query
	public function setName($str) {
		$this->name = $str;
	}

	// Method will return name to front end PHP/Javascript to be appended to html
	// if page load is true
	public function getName($pageLoad) {
		return $pageLoad ? $this->name: '';
	}
	// Methood will receive data from Javascript or form submission
	// Possibly from database query
	// This will be a radio select or radio-liike buttons (where only one is an option)
	public function setSex($sexChar) {
		$sexChar === 'M' ? $this->sex = 'Male': 'Female';
	}

	// Method will return sex to front end PHP/Javascript to be appended to html
	// if pageLoad is true
	public function getSex($pageLoad) {
		return $pageLoad ? $this->sex: '';
	}

	// Method will add however many list items are submitted, with appropriate date tag information
	// On the front-end this will be a dynamic jQuery structure where a button 'New Note' will append a second text field to the DOM, which will be entered in the next index
 	public function setNote($note) {
		$lengthBefore = count($this->notes);
		$dateTag = date('l, F jS, Y') . ' at ' . date('g:ia');
		array_push($this->notes, ['date' => $dateTag, 'note' => $note]);
		// echo for testing, should set a return value or assign the string to a variable that can be displayed as an alert in the browser. Or a popup div.
		echo $lengthBefore + 1 === count($this->notes) ? "'" . $note . "' was added to the note log successfully. \n\n" : "Your message failed to post.\n\n";
	} 
	
	public function getNotes($pageLoad) {
		if ($pageLoad) {
			return !empty($this->notes) ? $this->notes : 'You have no notes in for this person!';
		}
	}

    // Method deletes a note from the $notes array, and echo's the note that was deleted. And re-keys the $notes array numerically.
    // On the front-end, $noteNumber will be the numerical order of the entry.
    // allows index or 'all' to be passed in, removes index or all elements accordingly.
    public function deleteNote($noteNumber) {
    	if ($noteNumber === 'all') {
    		foreach($this->notes as $key => $value) {
    			unset($this->notes[$key]);
			} 
			echo "Your notes on " . $this->name . "were successfully deleted.\n";
		} else {
	    	$deletedNote = $this->notes[$noteNumber - 1]['note']; 
	    	unset($this->notes[$noteNumber - 1]);
	    	$this->notes = array_values($this->notes);
	    	echo "Your note '" . $deletedNote . "' was successfully deleted.\n";

		}
    }

}