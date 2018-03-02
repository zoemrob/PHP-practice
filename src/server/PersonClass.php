<?php
require('AbstractPerson.php');
/*
	This Class is used to render an entry from the database.
*/

Class BasePerson extends AbstractPerson {

	protected $personDocument;
	protected $mongoId;
	protected $notes = [];

   /**
	*  __construct method queries by mongoId, returns cursor, sets properties.
	* @param String: MongoId
	*/
	public function __construct($mongoId) {
		//this is where the Person requested is queried.
		$this->setCollection();
		$this->setMongoId($mongoId);
		$this->setPersonDocument();
		$this->setAge();
		$this->setName();
		$this->setSex();
		$this->setNotesFromDB();
	}
 	
 	/** Method queries by MongoId passed into constructor.
 	 * sets Mongo cursor.
 	 */
	public function setPersonDocument() {
		$this->personDocument = MongoHelper::queryById($this->getCollection(), $this->getMongoId());		
	}

	/**
	 * Method sets mongoId for person instance.
	 * @param String: MongoId
	 */
	public function setMongoId($mongoId) {
		$this->mongoId = $mongoId;
	}

	/**
	 * Method returns ObjectId 
	 * @return String: person objectId
	 */
	public function getMongoId() {
		return $this->mongoId;
	}

	/**
	 *  Method returns the BSON DB document from the object instance.
	 * @return obj: Mongo Cursor
	 */
	public function getPersonDocument() {
		return $this->personDocument;
	}

	/**
	 * Method takes text from post request and inserts into DB.
	 * @param String: note text from $_POST
	 *
	 * @return Int: confirmation of updateOne method.
	 */
	public function setNoteFromUI($note) {
		$confirmation = MongoHelper::insertNoteDB($this->getCollection(), $this->getMongoId(), HelperClass::makeNote($note));
		$confirmation == 1 ? $this->setNotesFromDB() : '';
		return $confirmation;
	}

	/**
	 * Gets notes
	 * @return Array: formatted list of notes from DB.
	 */
	public function getNotes() {
		return $this->notes;
	}


	/* Method sets age for Person instance based on $this->personDocument.
	 */
	public function setAge() {
		$this->age = MongoHelper::getDocAge($this->personDocument);
	}

	/* Method sets $this-name for Person instance based on $this->personDocument.
	 */
	public function setName() {
		$this->name = MongoHelper::getDocName($this->personDocument);
	}

	/* Method sets $this->sex for Person instance based on $this->personDocument.
	 */
	public function setSex() {
		$sexChar = MongoHelper::getDocSex($this->personDocument);
		$sexChar === 'M' ? $this->sex = "Male": $this->sex = "Female";
	}

	/* Called on construct, fetches notes from the database.
	 *
	 */
	public function setNotesFromDB() {
		$this->setPersonDocument();
		$this->notes = MongoHelper::getDocNotes($this->personDocument);
	}

	/**
	 * Method renders person instance to the DOM.
	 * @return String: formatted HTML.
	 */
	public function render() {
		return 
		'<div class="demographics center" id="demographics">' .
			$this->displayDemographics() .
			"<div class='floatL'>
				<button class='pad10' type='button' id='new-note-button'>New Note</button>
			</div>
			<div class='right'>
				<button class='pad10' type='button' id='delete-entry-button'>Delete Entry</button>
			</div>" .
			"</div>" .
		'</div>

		<div class="margin0 wth50" id="notes">'.
			$this->displayNotes() .
		'</div>';
	}

	/**
	 * echos/returns demographic information to UI/console
	 * @return String: formatted HTML
	 */
	public function displayDemographics() {
		return "<div class='wth25 standard-bkgd-color standard-shadow top-corner-radius bottom-corner-radius margin0 pad10 montserrat-font default-border left'>" . 
			"<p class='id-holder subheader margin0' id='" . $this->getMongoId() . "'>Name: " . $this->getName() . "</p>\n" .
			"<p class='subheader margin0'>Age: " . $this->getAge() . "</p>\n" .
			"<p class='subheader margin0'>Sex: " . $this->getSex() . "</p>\n";
	}

   /**
    * Method echos html elements on the initial load of the page.
    * Uses $this->notes indexes to create unique ids and classes for every html element,
    * @return String: formatted HTML
    */
	public function displayNotes() {
		$formattedNotes = "";
		$notesToDisplay = array_reverse($this->notes, true);
		foreach($notesToDisplay as $key => $note) {
			$formattedNotes .= "
				<div id='note-" . $key . "' class='bottom-corner-radius standard-shadow'> 
					<div class='note-date-div' id='note-date-div-" . $key . "'>
						<p class='note-date margin-btm-0 pad10' id='note-date-" . $key . "'>" . $note['date'] . " <span class='inline-em'>you wrote</span>:</p>
					</div>
					<div id='note-text-div-" . $key ."'> 
						<p class='note-text montserrat-font bottom-corner-radius standard-bkgd-color' id='note-text-" . $key . "'>" . $note['note'] . "</p>
					</div>
				</div>";
		}
		return $formattedNotes;
	}

	/**
	 * Method delete notes from the database.
	 * @param $notesToDelete, array.
	 * @param $deleteAll, optional bool to delete all notes.
	 *
	 * @return String: confirmation message on how many notes were deleted.
	 * FUTURE PLANNING: Instead of deleting the notes permanently, store them in a seperate DB category, where "Deleted Notes can be viewed".
	 */
    public function deleteNotes(array $notesToDelete, $deleteAll = false) {
        try{
            if ($deleteAll) {
                $notesToDelete = array_keys($this->notes);
                // if $deleteAll is true, fill $verifiedNotesToDelete with all of the indexes of $this->notes.
            }

            foreach($notesToDelete as $index) {
            	$deletedNoteCount = 0;
                if (isset($this->notes[$index])) {
	                unset($this->notes[$index]);
	                $success = MongoHelper::deleteNoteFromDB($this->getCollection(), $this->getMongoId(), $index);
	                if ($success == 1) {
	                	$deletedNoteCount++;
	                }
                } else {
                    throw new Exception("The note you are trying to delete does not exist.\n");
                	// throws this exception if the index is does not exist.
                }
            }
            $message = "You successfully deleted " . $deletedNoteCount . " note" . ($deletedNoteCount > 1 ? "s" : "") . " about " . $this->getName() . ".";
            return $message;
        } catch (Exception $ex) {
            return $ex;
        }
    }

    /**
     * Creates the modal for a new note entry.
     * @return String: formatted HTML.
     */
	public function createNewNoteForm() {
		return 
		"<div class='modal standard-bkgd-color standard-shadow montserrat-font margin0' id='new-note-modal-content'>" .
			"<span id='close-note-modal'>&times;</span>" .
			"<span>New note about " . $this->getName() . ":</span>" . 
			"<br />" .
			"<textarea cols='50' rows='10' class='fix-size' id='new-note-entry' maxlength='500' minlength='1' placeholder='Write your new note here...'></textarea>" .
			"<br />" .
			"<button id='submit-new-note' type='button' class='modal-submit-button'>Enter Note</button>" .
		"</div>";
	}

	/** 
	 * Creates delete note modal confirmation.
	 * @return String: formatted HTML.
	 */
	public function createNoteDeleteConfirm() {
		return
		"<div class='modal standard-bkgd-color standard-shadow montserrat-font margin0 center' id='confirm-delete-modal'>" .
			"<span id='close-note-modal'>&times;</span>" .
			"<span>Are you sure you want to delete this note about " . $this->getName() . "?</span>" .
			"<br />" .
			"<button id='confirm-delete-button' type='button' class='modal-submit-button'>Confirm Delete</button>" .	
		"</div>";
	}

}