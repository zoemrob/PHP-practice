<?php
// Testing script, loads in various data, runs tests on it for debugging. 
require('PersonClass.php');
$person = new BasePerson('5a82739fb505a6e0f0d67996');
//$person->getName();
//$person->getAge();
//$person->getSex();

//$result = MongoHelper::insertNoteDB($person->getDBInstance(), '5a82739fb505a6e0f0d67996', $person->setNewNote('Monte, Josh E., and I talked in the living room about how Josh is buying a house, and about the origin of the phrase "Numnuts", and then Monte went to bed.'));
//$result = MongoHelper::queryByName($person->getCollection(), "z");
//var_dump($result);

$db = MongoHelper::createDBInstance();
/*$db->insertOne(
	[
		'firstName' => 'Suzi',
		'lastName' => 'Austin',
		'age' => 31,
		'sex' => 'F',
		'notes' => [],
	]
	);
*/
$result = MongoHelper::queryByName($db, 'Suzi');
foreach ($result as $data) {
	var_dump($data);
};
//$result = MongoHelper::getNotesByName(MongoHelper::createDBInstance(), 'zo');
//$notes = MongoHelper::returnNoteInfo($result);
//var_dump($notes);
//var_dump($result);
//MongoHelper::getDocNotes($result);

/*$person->setNotesFromDB();
echo $person->displayNotes();*/