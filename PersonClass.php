<?php
require('MongoHelper.php');
//require('vendor/autoload.php');
require('HelperClass.php');
/*
	This Class will be used to create a new instance of a person to enter into database.
	A new instance of this object will be created on the HTML page, and an API will scrape the information out of the instanced
	objecct to create a database entry.
	$age, $name, $sex, and $notes will be received from form submission.
*/

Class BasePerson {

	private $collection;
	private $mongoId;
	private $personDocument;
	private $age;
	private $name;
	private $sex;
	private $notes = [];

   /*  __construct method allows for the optional parameters to be created upon initialization
	* @param $mongoId
	* @param $name = string, name of person creating entry
	*
	*/
	public function __construct($mongoId, $name = null, $age = null, $sex = null) {
		//this is where the Person requested is queried.
		if ($mongoId) {
			$this->collection = MongoHelper::createDBInstance();
			$this->setMongoId($mongoId);
			$this->personDocument = MongoHelper::queryById($this->collection, $this->mongoId);
			$this->setAge();
			$this->setName();
			$this->setSex();
			$this->setNotesFromDB();
		} else {
		// POSSIBLY BREAK THIS INTO TWO DIFFERENT CLASSES, ONE FOR GETTING AND SETTING A PERSON WHO EXISTS, ANOTHER FOR CREATING A NEW PERSON ENTRY
		// SET SOME SORT OF LIVE BACKUP TO DATABASE. IF IT IS NEWLY CREATED, IT WILL STORE IN DB.
		!is_null($name) ? $this->setName($name): '';
		!is_null($age) ? $this->setAge($age): '';
		!is_null($sex) ? $this->setSex($sex): '';
		}
	}

	public function getCollection () {
		return $this->collection;
	}

	/* Method sets mongoId for person instance.
	 * @param $mongoId = str of mongoId
	 */
	public function setMongoId($mongoId) {
		$this->mongoId = $mongoId;
	}

	/* Method returns the mongoId for person instance.
	 */
	public function getMongoId() {
		return $this->mongoId;
	}

	/* Method returns the BSON DB document from the object instance.
	 * 
	 *
	 */
	public function getPersonDocument() {
		return $this->personDocument;
	}

	public function getNotes() {
		return $this->notes;
	}

	/* echos/returns demographic information to UI/console
	 * will have to call this function on page load or something like that, or I can adjust this to render if $loaded = true
	 */
	public function displayDemographics() {
		echo "<p class='demographics-data'>Name: " . $this->getName() . "</p>\n" .
			 "<p class='demographics-data'>Age: " . $this->getAge() . "</p>\n" .
			 "<p class='demographics-data'>Sex: " . $this->getSex() . "</p>\n";
	}

	/* Method sets age for Person instance based on $this->personDocument.
	 */
	public function setAge() {
		$this->age = MongoHelper::getDocAge($this->personDocument);
	}

	/* Method returns/echoes $this->age.
	 */
	public function getAge() {
		//echo "Age: " . $this->age . "\n";
		return $this->age;
	}

	/* Method sets $this-name for Person instance based on $this->personDocument.
	 */
	public function setName() {
		$this->name = MongoHelper::getDocName($this->personDocument);
	}

	/* Method returns/echoes $this->name.
	 */
	public function getName() {
		//echo "Name: " . $this->name . "\n";
		return $this->name;
	}

	/* Method sets $this->sex for Person instance based on $this->personDocument.
	 */
	public function setSex() {
		$sexChar = MongoHelper::getDocSex($this->personDocument);
		$sexChar === 'M' ? $this->sex = "Male": "Female";
	}

	/* Method returns/echoes $this->sex.
	 */
	public function getSex() {
		//echo "Sex: " . $this->sex . "\n";
		return $this->sex;
	}

	/* Called on construct, fetches notes from the database.
	 *
	 *
	 */
	public function setNotesFromDB() {
		$this->notes = MongoHelper::getDocNotes($this->personDocument);
	}


   /* Method will add however many list items are submitted, with appropriate date tag information
	* Method will return the note array created.
	* @param $noteText string that will be added to note log
	*/
	public function setNewNote($noteText) {
        $note = HelperClass::makeNote($noteText);
        $this->notes[] = $note;
        return $note;
    }

   /** Method echos html elements on the initial load of the page.
    *  Uses $this->notes indexes to create unique ids and classes for every html element,
    *
    */
	public function displayNotes() {
		$formattedNotes = "";
		foreach($this->notes as $key => $note) {
			$formattedNotes .= "
				<div id='note-" . $key . "'> 
					<div class='note-date-div' id='note-date-div-" . $key . "'>
						<p class='note-date' id='note-date-" . $key . "'>" . $note['date'] . " <span class='inline-em'>you wrote</span>:</p>
					</div>
					<div class='note-text-div' id='note-text-div-" . $key ."'> 
						<p class='note-text' id='note-text-" . $key . "'>" . $note['note'] . "</p>
					</div>
				</div>";
		}
		return $formattedNotes;
		var_dump($formattedNotes);
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

	/* Method used simply for debugging.
	 */
	public function debugNotes() {
		var_dump($this->notes);
	}

}
