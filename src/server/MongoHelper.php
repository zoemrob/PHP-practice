<?php
require('vendor/autoload.php');
/*
	Database static helper methods.
*/
Class MongoHelper {

    /** 
	 * Method creates instance of db.
	 * @return Obj: DB instance object.
	 */
	public static function createDBInstance() {

		$db = new MongoDB\Client("mongodb://localhost:27017");
		return $db->coworkerjournal->coworkerjournal;
	}

	/** 
	 *  Method is for internal use, converting ObjectId object into string.
	 * 
	 *  @param Obj: MongoId Cursor
	 *  @return String: ObjectId string from Cursor.
	 */
	private static function getSingleMongoIdString($cursor) {
		$oid = $cursor->_id;
		return $oid->__toString();
	}

	/**
	 *	This method returns the BSON Document of a DB by ObjectId
	 *	@param Obj: instance of MongoDB->coworkerjournal->Collection that is being queried
	 *	@param String:the ObjectId of the Document being retrieved.
	 *
	 *  @return Obj: Cursor for document.
	 */
	public static function queryById($collection, $mongoId) {
		$document = $collection->findOne(
			[
				"_id" => new MongoDB\BSON\ObjectId($mongoId)
			]
		);
		return $document;
	}

	/**
	 *  This method returns the MongoId string of a result of a name search.
	 *	@param Obj: instance of MongoDB->coworkerjournal->Collection that is being queried
	 *  @param String: first name of person to query
	 *  @param String: last name of person to query
	 *
	 *  @return String: MongoId string for person
	 */
	public static function queryOneByName($collection, $first, $last) {
		$result = $collection->findOne(
			[
				'firstName' => $first,
				'lastName' => $last
			]
		);
		$mongoId = self::getSingleMongoIdString($result);
		return $mongoId;
	}


	/**
	 *  This method searches for first/last name by regular expression.
	 *	@param Obj: instance of MongoDB->coworkerjournal->Collection that is being queried
	 *  @param String: name string from $_POST method in search bar.
	 *
	 *  @return Array: ObjectId, first name, last name
	 */
	public static function queryByNameSearch($collection, $name) {
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
				'projection' => [
					'_id' => 1,
					'firstName' => 1,
					'lastName' => 1
				]
			]
		)->toArray();
		return $result;
	}

	/**
	 *  Method returns an array of elements to be used for search bar results.
	 *  @param Obj: Mongo Cursor object
	 *
	 *  @return Array: array of results.
	 */
	public static function getNameAndMongoId($cursor) {
		$results = array();
		foreach($cursor as $document) {
			$results[] = array(
				'mongoId' => $document->_id,
				'firstName' => $document->firstName,
				'lastName' => $document->lastName		
			);
		}
		return $results;
	}

	/**
	 *	This method returns the "firstName" &/or "lastName" value from a Document.
	 *	@param Obj: Mongo Cursor
	 *	@param bool: determines whether or not "lastName" value is returned.
	 *
	 *  @return String: string of name.
	 */
	public static function getDocName($cursor, $lastName = true) {
		return $lastName ? $cursor->firstName . " " . $cursor->lastName : $cursor->firstName;
	}

	/**
	 *	This method returns the "age" value from the Document.
	 *	@param Obj: Mongo Cursor Object
	 *
	 *  @return Int: age of person
	 */
	public static function getDocAge($cursor) {
		return $cursor->age;
	}

	/**
	 *	This method returns the "gender" value from the Document.
	 *	@param Obj: Mongo Cursor object
	 *
	 *  @return String: gender value of person.
	 */
	public static function getDocSex($cursor) {
		return $cursor->sex;
	}


	/**
	 *  This method returns the notes from the cursor, formats into array.
	 *  @param Obj: Mongo Cursor Object.
	 *
	 *  @return Array: array of notes from cursor.
	 */
	public static function getDocNotes($cursor) {
		$notes = array();
		foreach($cursor->notes as $noteObj) {
			$note = ['date' => $noteObj['date'], 'note' => $noteObj['note']];
			$notes[] = $note;
		}
		return $notes;
	}

	/** 
	 *  This method will insert the note into the notes array in the Person's Document. 
	 *	@param Obj: instance of MongoDB->coworkerjournal->Collection that is being queried
	 *  @param String: ObjectId string.
	 *  @param Array: containing 'date' and 'note'
	 *
	 *  @return Int: success or fail message.
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
		return $result->isAcknowledged();
	}

	/** 
	 *  Method will delete a note from a Document in an aray
	 *	@param Obj: instance of MongoDB->coworkerjournal->Collection that is being queried
	 *  @param String: ObjectId string.
	 *  @param Int: index of notes to delete from in document.
 	 *
	 *  @return Int: success or fail message.
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
				'$pull' => [
					'notes' => null
				]
			]
		)->isAcknowledged();
		return $result;
	}

	/**
	 *  Method takes form data from $_POST request, creates a new document, returns confirmation.
	 *	@param Obj: instance of MongoDB->coworkerjournal->Collection that is being queried
	 *  @param String: First name
	 *  @param String: Last name
	 *  @param Int: age of person
	 *  @param String: Sex character.
	 *
	 *  @return Int: success or fail message.
	 */
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

	public static function deleteDBEntry($collection, $mongoId) {
		$name = self::getDocName(self::queryById($collection, $mongoId));
		$deletedDocument = $collection->deleteOne(
			[
				'_id' => new MongoDB\BSON\ObjectId($mongoId)
			]
		)->isAcknowledged();
		
		if ($deletedDocument == 1) {
			$message = "You have successfully deleted " . $name . " from the database.";
		} else {
			$message = "Something went wrong. We are working on it.";
		}
		return $message;
	}
	/**
	 *
	 * @param Obj: Mongo collection object
	 * @param Array: Array of fields to update.
	 */
	public static function updateEntryFields($collection, $mongoId, $fields) {
		$fields = self::convertToMongoFields($fields);
		$result = $collection->updateOne(
			[
				'_id': new MongoDB\BSON\ObjectId($mongoId)
			],
			[
				'$set' => $fields
			]
		)->getModifiedCount();
		return $result;
	}

	/**
	 *  Method converts $_POST elements to proper mongo field names
	 *  @param Array: array of elements received.
	 *  
	 *  @return Array: formatted correctly.
	 */
	private static function convertToMongoFields($fields) {
		$result = array();
		array_walk($fields, function(&$val, $key) use (&$result) {
			switch($key) {
				case 'first-name':
					$key = 'firstName';
					break;
				case 'last-name':
					$key = 'lastName';
					break;
			}
			$result[$key] = $val;
		});
		return $result;
	}
}