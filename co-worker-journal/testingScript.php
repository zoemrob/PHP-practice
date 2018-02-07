<?php
// Testing script, loads in various data, runs tests on it for debugging. 
require('baseClass.php');
$person = new BasePerson();
$person->setNotes('Here is the first note that should be loaded.');
var_dump($person->notes);