<?php
// Testing script, loads in various data, runs tests on it for debugging. 
require('PersonClass.php');
$person = new BasePerson('5a7fe017e662dc7ec495262d');
$person->getName();
$person->getAge();
$person->getSex();

$result = MongoHelper::insertNoteDB($person->getDBInstance(), '5a7fe017e662dc7ec495262d', $person->setNote('This is an additional test note.'));
echo $result . "\n";

