<?php
// Testing script, loads in various data, runs tests on it for debugging. 
require('PersonClass.php');
$person = new BasePerson('5a7fe017e662dc7ec495262d');
$person->getName();
$person->getAge();
$person->getSex();

$result = MongoHelper::insertNoteDB($person->getDBInstance(), '5a7fe017e662dc7ec495262d', $person->setNote('This is an additional test note.'));
echo $result . "\n";

//$person->setNote('Here is the first note that should be loaded.');
//var_dump($person->getNotes());

/*$person->debugNotes();
$person->setNote('Here is the second note that should be loaded.');
$person->setNote('Here is the third note that should be loaded.');
$person->setNote('Here is the fourth note that should be loaded.');
$person->setNote('Here is the fifth note that should be loaded.');
$person->setNote('Here is the sixth note that should be loaded.');
$person->debugNotes();
$person->deleteNotes([1, 3]);
$person->debugNotes();
$person->deleteNotes([0]);
$person->debugNotes();
*/
