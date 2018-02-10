<?php
require('HelperClass.php');
/*
	This Class will be used to create a new instance of a person to enter into database.
	A new instance of this object will be created on the HTML page, and an API will scrape the information out of the instanced
	objecct to create a database entry.
	$age, $name, $sex, and $notes will be received from form submission.
*/

Class BasePerson {

	private $age;
	private $name;
	private $sex;
	private $notes = [];

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
		// $this->displayDemographics();
	}

	public function debugNotes() {
		var_dump($this->notes);
	}

	/* echos/returns demographic information to UI/console
	 * will have to call this function on page load or something like that, or I can adjust this to render if $loaded = true
	 */
	public function displayDemographics() {
		$this->getName(); echo "\n";
		$this->getAge(); echo "\n";
		$this->getSex(); echo "\n";
	}

	// Method will receive data from JavaScript or form submission
	// Possibly from database query
	public function setAge($num) {
		$this->age = $num . "\n";
	}

	// Method will return age to front end PHP/Javascript to be appended to html
	public function getAge() {
		echo "Age: " . $this->age . "\n";
		return $this->age . "\n";
	}

	// Method will receive data from Javascript or form submission
	// Possibly from databse query
	public function setName($str) {
		$this->name = $str . "\n";
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
		$sexChar === 'M' ? $this->sex = "Male\n": "Female\n";
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
		$dateTag = HelperClass::getTimeUI('dmyt');
		array_push($this->notes, ['date' => $dateTag, 'note' => $note]);
		// echo $lengthBefore + 1 === count($this->notes) ? "'" . $note . "' was added to the note log successfully. \n\n" : "Your message failed to post.\n\n";
	} 

   /** Method echos html elements on the initial load of the page.
    *  Uses $this->notes indexes to create unique ids and classes for every html element,
    *
    */
	public function getNotes() {
		foreach($this->notes as $key => $note) {
			echo "
				<div id='note-" . $key . "'> 
					<div class='note-date-div' id='note-date-div-" . $key . "'>
						<p class='note-date' id='note-date-" . $key . "'>" . $note['date'] . " <span class='inline-em'>you wrote</span>:</p>
					</div>
					<div class='note-text-div' id='note-text-div-" . $key ."'> 
						<p class='note-text' id='note-text-" . $key . "'>" . $note['note'] . "</p>
					</div>
				</div>\n";
		}
	}
	/* Method will receive an array from UI. After selecting "Delete messages" and checking the notes to delete.
	 * @param $notesToDelete, array.
	 * @param $deleteAll, optional bool to delete all notes.
	 * FUTURE PLANNING: Instead of deleting the notes permanently, store them in a seperate DB category, where "Deleted Notes can be viewed".
	 */
    public function deleteNotes(array $notesToDelete, $deleteAll = false) {
        try{
            $verifiedNotesToDelete = [];
            if ($deleteAll) {
                $notesToDelete = array_keys($this->notes);
                // if $deleteAll is true, fill $verifiedNotesToDelete with all of the indexes of $this->notes.
            }

            foreach($notesToDelete as $index) {
                if (isset($this->notes[$index])) {
                    $verifiedNotesToDelete[] = $this->notes[$index]; 
                } else {
                    throw new Exception("The note you are trying to delete does not exist.\n");
                	// throws this exception if the index is does not exist.
                }
                unset($this->notes[$index]);
            }
            $message = "Your Note" .
                    count($verifiedNotesToDelete) > 0 ? 's' : '' .
                    " about " . $this->name .
                    " for notes: " .
                    join(',', array_column($verifiedNotesToDelete, 'note'));
            echo json_encode(['message' => $message, 'notes' => $verifiedNotesToDelete]);
        } catch (Exception $ex) {
            echo $ex;
        }
    }
}
