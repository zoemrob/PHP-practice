<?php
require('AbstractPerson.php');
require('HelperClass.php');
require('MongoHelper.php');

Class NewEntry extends AbstractPerson {

	private $receivedData;
	private $firstName;
	private $lastName;

	public function __construct($JSONString) {
		$this->setReceivedData($JSONString);
		$this->setName();
		$this->setAge();
		$this->setSex();
		$this->setCollection();
	}

	public function setMongoId($mongoId) {
		return $mongoId;
	}

	// this method sets the $firstName, $lastName, and $name properties from the $JSONString arg passed to the constructor.
	public function setName() {
		$this->firstName = $this->receivedData['firstName'];
		$this->lastName = $this->receivedData['lastName'];
		$this->name = $this->firstName . " " . $this->lastName;
	}

	// this method sets the $age property from the $JSONString arg passed to the constructor.
	public function setAge() {
		$this->age = $this->receivedData['age'];
	}

	// this method sets the $sex property from the $JSONString arg passed to the constructor.
	public function setSex() {
		$this->sex = $this->receivedData['sex'];
	}

	// this method formats the $JSONString into a usable array for the object, and sets the $receivedData property.
	private function setReceivedData($JSONString) {
		$this->receivedData = HelperClass::JSONtoArray($JSONString);
	}

	// this method allows for a method value/name to be passed into the constructor. For future use after sanitizing input.
	private function executeMethod($method) {
		$this->$method();
	}

	// this method allows for the $JSONString data to be set used to add a new entry to the database collection.
	public function insertEntryIntoDB($firstName, $lastName, $sex, $age) {
		echo MongoHelper::insertNewEntryIntoDB($this->getCollection(), $this->firstName, $this->lastName, $this->sex, $this->age);
	}
}