<?php
require('AbstractPerson.php');
require('HelperClass.php');
require('MongoHelper.php');

Class NewEntry extends AbstractPerson {

	private $receivedData;
	private $firstName;
	private $lastName;

	public function __construct($JSONString) {
		$this->getReceivedData($JSONString);
		$this->setName();
		$this->setAge();
		$this->setSex();
		$this->setCollection();
	}

	public function setMongoId($mongoId) {
		return $mongoId;
	}

	public function setName() {
		$this->firstName = $this->receivedData['firstName'];
		$this->lastName = $this->receivedData['lastName'];
		$this->name = $this->firstName . " " . $this->lastName;
	}

	public function setAge() {
		$this->age = $this->receivedData['age'];
	}

	public function setSex() {
		$this->sex = $this->receivedData['sex'];
	}

	private function getReceivedData($JSONString) {
		$this->receivedData = HelperClass::JSONtoArray($JSONString);
	}

	private function executeMethod($method) {
		$this->$method();
	}

	public function insertNoteIntoDB($firstName, $lastName, $sex, $age) {
		echo MongoHelper::insertNewEntryIntoDB($this->getCollection(), $this->firstName, $this->lastName, $this->sex, $this->age);
	}
}