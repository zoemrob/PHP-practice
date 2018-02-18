<?php
require('vendor/autoload.php');

Class MongoHelper {

    /*
	 *	This method is used to create an instance of the coworkerjournal db.
	 */
	public static function createDBInstance() {

		$db = new MongoDB\Client("mongodb://localhost:27017");
		return $db->coworkerjournal->coworkerjournal;
	}

	public static function getMongoIdString($cursor) {
		foreach($cursor as $mongoId) {
			return $mongoId->_id;
		}
	}

	/*
	 *	This method returns the BSON Document of a DB by ObjectId
	 *	@param $collection = instance of MongoDB->coworkerjournal->Collection that is being queried
	 *	@param $mongoId = the ObjectId of the Document being retrieved.
	 */
	public static function queryById($collection, $mongoId) {
		$document = $collection->findOne(
			[
				"_id" => new MongoDB\BSON\ObjectId($mongoId)
			]
		);
		return $document;
	}

	// used for search bar feature
	public static function queryByName($collection, $name) {
		$regexp = new MongoDB\BSON\Regex('^' . $name, 'i');
		$result = $collection->find(
			[
				'$or' => [
					[
					'firstName' => $regexp
					],
					[
					'lastName' => $regexp
					]
				]
			],
			[
				'projection' => ["_id" => 1]
			]
		)->toArray();
		return $result;
	}


	// CURTIS METHODS
	public static function getNotes($collection, $match) {
	    return $collection->findOne($match, ['notes.date' => 1, 'notes.note' => 1])['notes'] ?? [];
	}

	public static function getNotesById($collection, $id) {
	    return self::getNotes($collection, ['_id' => $id]);
	}

	public static function getNotesByName($collection, $name) {
	    return self::getNotes($collection, ['firstName' => new MongoDB\BSON\Regex('^' . $name, 'i')]);
	}


	/*
	 *	This method returns the "firstName" &/or "lastName" value from a Document.
	 *	@param $BSONDocument = BSON object Document
	 *	@param $lastName = boolean value that determines whether or not "lastName" value is returned.
	 */
	public static function getDocName($BSONDocument, $lastName = true) {
		return $lastName ? $BSONDocument->firstName . " " . $BSONDocument->lastName : $BSONDocument->firstName;
	}

	/*
	 *	This method returns the "age" value from the Document.
	 *	@param $BSONDocument = BSON object Document
	 */
	public static function getDocAge($BSONDocument) {
		return $BSONDocument->age;
	}

	/*
	 *	This method returns the "gender" value from the Document.
	 *	@param $BSONDocument = BSON object Document
	 */
	public static function getDocSex($BSONDocument) {
		return $BSONDocument->sex;
	}


	// Will be deprecated!!!
	public static function getDocNotes($BSONDocument) {
		$notes = array();
		foreach($BSONDocument->notes as $noteObj) {
			$note = ['date' => $noteObj['date'], 'note' => $noteObj['note']];
			$notes[] = $note;
		}
		return $notes;
	}

	public static function returnNoteInfo($notes, $person = null) {
		if ($person) {
			foreach($notes as $note) {
				$person->notes[] = ['date' => $note['date'], 'note' => $note['note']];
			}
		} else {
			$notesArray = array();
			foreach($notes as $note) {
				$notesArray[] = ['date' => $note['date'], 'note' => $note['note']];
			}
			return $notesArray;		
		}
	}

	/* This method will insert the note into the notes array in the Person's Document. 
	 * @param $collection = obj - Instance of database->collection
	 * @param $mongoId = str - Unique ObjectId
	 * @param $note = array - containing 'date' and 'note'
	 */
	public static function insertNoteDB($collection, $mongoId, $note) {
		$result = $collection->updateOne(
				[
					"_id" => new MongoDB\BSON\ObjectId($mongoId)
				],
				[
					'$push' => [
						"notes" => $note
					]
				]
			);
		return $result->isAcknowledged(); // returns 1 if success, returns 0 if failed. Should be used to respond to AJAX request.
	}
	/* Method will delete a note from a Document in an aray
	 * @param $collection = obj, db->collection to delete from
	 * @param $mongoId = string, of document to delete from
	 * @param $index = int, index of notes to delete from in document.
	 */
	public static function deleteNoteFromDB($collection, $mongoId, $index) {
		$collection->updateOne(
			[
				'_id' => new MongoDB\BSON\ObjectId($mongoId)
			],
			[
				'$unset' => [
					'notes.' . $index => 1
				]
			]
		);
		$result = $collection->updateOne(
			[
				'_id' => new MongoDB\BSON\ObjectId($mongoId)
			],
			[
				$pull => [
					'notes' => null
				]
			]
		);
	}

	public static function insertNewEntryIntoDB($collection, $firstName, $lastName, $age, $sex) {
		$result = $collection->insertOne(
			[
				'firstName' => $firstName,
				'lastName' => $lastName,
				'age' => (int)$age,
				'sex' => $sex,
				'notes' => []
			]
		);
		return $result->isAcknowledged();
	}
}