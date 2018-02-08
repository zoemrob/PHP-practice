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

   /*  __construct method allows for the optional parameters to be created upon initialization
	* @param $name = string, name of person creating entry
	* 
	*
	*/
	public function __construct($name = null, $age = null, $sex = null) {
		// add control flow essentially, if this is a new person, do this, if this is an existingperson do this, 
		// POSSIBLY BREAK THIS INTO TWO DIFFERENT CLASSES, ONE FOR GETTING AND SETTING A PERSON WHO EXISTS, ANOTHER FOR CREATING A NEW PERSON ENTRY
		// SET SOME SORT OF LIVE BACKUP TO DATABASE. IF IT IS NEWLY CREATED, IT WILL STORE IN DB, IF NOT, THE CONSTRUCT ARGUMENTS WILL BE PASSED BASED ON THE RESULT OF QUERY.
		!is_null($name) ? $this->setName($name): '';
		!is_null($age) ? $this->setAge($age): '';
		!is_null($sex) ? $this->setSex($sex): '';
		$this->displayDemographics();
	}

	/* echos/returns demographic information to UI/console
	 * will have to call this function on page load or something like that, or I can adjust this to render if $loaded = true
	 */
	public function displayDemographics() {
		$this->getName();
		$this->getAge();
		$this->getSex();
	}

	// Method will receive data from JavaScript or form submission
	// Possibly from database query
	public function setAge($num) {
		$this->age = $num;
	}

	// Method will return age to front end PHP/Javascript to be appended to html
	public function getAge() {
		echo "Age: " . $this->age . "\n";
		return $this->age . "\n";
	}

	// Method will receive data from Javascript or form submission
	// Possibly from databse query
	public function setName($str) {
		$this->name = $str;
	}

	// Method will return name to front end PHP/Javascript to be appended to html
	public function getName() {
		echo "Name: " . $this->name . "\n";
		return $this->name . "\n";
	}
	// Methood will receive data from Javascript or form submission
	// Possibly from database query
	// This will be a radio select or radio-liike buttons (where only one is an option)
	public function setSex($sexChar) {
		$sexChar === 'M' ? $this->sex = 'Male': 'Female';
	}

	// Method will return sex to front end PHP/Javascript to be appended to html
	public function getSex() {
		echo "Sex: " . $this->sex . "\n";
		return $this->sex . "\n";
	}

   /* Method will add however many list items are submitted, with appropriate date tag information
	* On the front-end this will be a dynamic jQuery structure where a button 'New Note' will append a * second text field to the DOM, which will be entered in the next index
	* @param $note string that will be added to note log
	*/
 	public function setNote($note) {
		$lengthBefore = count($this->notes);
		$dateTag = date('l, F jS, Y') . ' at ' . date('g:ia');
		array_push($this->notes, ['date' => $dateTag, 'note' => $note]);
		echo $lengthBefore + 1 === count($this->notes) ? "'" . $note . "' was added to the note log successfully. \n\n" : "Your message failed to post.\n\n";
	} 

	/* Method will receive an array from UI. After selecting "Delete messages" and checking the notes to delete.
	 * @param $notesToDelete, array or boolean.
	 * if $notesToDelete = true, all notes will be deleted.
	 * if $notesToDelete = array of indexes, notes at indexes will be removed.
	 * FUTURE PLANNING: Instead of deleting the notes permanently, store them in a seperate DB category, where "Deleted Notes can be viewed".
	 */
	public function deleteMultipleNotes($notesToDelete) {
		if (is_array($notesToDelete)) {
			$verifiedNotesToDelete = [];
			foreach($notesToDelete as $index) {
				isset($this->notes[$index]) ? $verifiedNotesToDelete[] = $index : ''; //echo "The note number " . $index . " does not exist."; commented out to replan logic later.
			}
			if (count($notesToDelete) === 1) {
				$deletedNote = array_splice($this->notes, $notesToDelete[0], 1);
				echo "Your note '" . $deletedNote[0]['note'] . "' was deleted.\n";
			} else {
				foreach($notesToDelete as $key => $value) {
					unset($this->notes[$value]);
				}
				echo "Selected notes deleted.\n";
			}
			$this->notes = array_values($this->notes);
		} else if ($notesToDelete === true) {
			if ($this->notes === []) {
				echo "You don't have any notes on this person!\n";
			} else {
				$this->notes = [];
				echo "All of your notes about " . $this->name . " were deleted.\n";
			}
		} else { echo "Your delete was not successful."; }
	}

}