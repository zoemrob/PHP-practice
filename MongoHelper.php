<?php
require('vendor/autoload.php');

Class MongoHelper {

    /*
	 *	This method is used to create an instance of the coworkerjournal instance.
	 */
	public static function createDBInstance() {

		$db = new MongoDB\Client("mongodb://localhost:27017");
		return $db->coworkerjournal->coworkerjournal;
	}

	/*
	 *	This method returns the BSON Document of a DB by ObjectId
	 *	@param $dbInstance = instance of MongoDB->coworkerjournal->Collection that is being queried
	 *	@param $mongoId = the ObjectId of the Document being retrieved.
	 */
	public static function queryById($dbInstance, $mongoId) {
		$document = $dbInstance->findOne(
			[
				"_id" => new MongoDB\BSON\ObjectId($mongoId)
			]
		);
		return $document;
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

	/* This method will insert the note into the notes array in the Person's Document. 
	 * @param $dbInstance = obj - Instance of database->collection
	 * @param $mongoId = str - Unique ObjectId
	 * @param $note = array - containing 'date' and 'note'
	 */
	public static function insertNoteDB($dbInstance, $mongoId, $note) {
		$result = $dbInstance->updateOne(
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

}