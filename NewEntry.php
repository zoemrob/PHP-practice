<?php
require('AbstractPerson.php');
require('HelperClass.php');
require('MongoHelper.php');

Class NewEntry extends AbstractPerson {

	private $receivedData;

	__construct($JSONString) {
		$this->getReceivedData($JSONString);
		$this->setName();
		$this->setAge();
		$this->setSex();
	}

	public function setName() {
		$this->name = $this->receivedData['name'];
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

}