<?php
// Testing script, loads in various data, runs tests on it for debugging. 
require('PersonClass.php');
$person = new BasePerson('5a82739fb505a6e0f0d67996');
//$person->getName();
//$person->getAge();
//$person->getSex();

//$result = MongoHelper::insertNoteDB($person->getDBInstance(), '5a82739fb505a6e0f0d67996', $person->setNewNote('Monte, Josh E., and I talked in the living room about how Josh is buying a house, and about the origin of the phrase "Numnuts", and then Monte went to bed.'));
$cursor = MongoHelper::queryByName(MongoHelper::createDBInstance(), "c");
echo HelperClass::formatSearchResults($cursor);

//var_dump($result);

// var_dump(HelperClass::generateNewEntryForm());
//$result = MongoHelper::getNotesByName(MongoHelper::createDBInstance(), 'zo');
//$notes = MongoHelper::returnNoteInfo($result);
//var_dump($notes);
//var_dump($result);
//MongoHelper::getDocNotes($result);

/*$person->setNotesFromDB();
echo $person->displayNotes();*/

// echo $_POST['first-name'];