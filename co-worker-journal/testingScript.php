<?php
// Testing script, loads in various data, runs tests on it for debugging. 
require('baseClass.php');
$person = new BasePerson('Zoe', 22, 'M');
$person->setNote('Here is the first note that should be loaded.');
var_dump($person->notes);
$person->setNote('Here is the second note that should be loaded.');
$person->setNote('Here is the third note that should be loaded.');
$person->setNote('Here is the fourth note that should be loaded.');
var_dump($person->notes);
$person->deleteMultipleNotes([1, 3]);
var_dump($person->notes);
$person->deleteAllNotes();
