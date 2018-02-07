<?php
// Testing script, loads in various data, runs tests on it for debugging. 
require('baseClass.php');
$person = new BasePerson();
$person->setNote('Here is the first note that should be loaded.');
var_dump($person->notes);
$person->setNote('Here is the second note that should be loaded.');
var_dump($person->notes);
$person->deleteNote(1);
var_dump($person->notes);
