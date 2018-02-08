<?php
// Testing script, loads in various data, runs tests on it for debugging. 
require('baseClass.php');
$person = new BasePerson();
$person->setNote('Here is the first note that should be loaded.');
var_dump($person->notes);
$person->setNote('Here is the second note that should be loaded.');
$person->setNote('Here is the third note that should be loaded.');
$person->setNote('Here is the fourth note that should be loaded.');
var_dump($person->notes);
$person->deleteSingleNote(0);
$person->deleteSingleNote(0);
$person->deleteSingleNote(5);
var_dump($person->notes);
$person->deleteAllNotes();
