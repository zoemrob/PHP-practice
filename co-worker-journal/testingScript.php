<?php
// Testing script, loads in various data, runs tests on it for debugging. 
require('baseClass.php');
$person = new BasePerson();
$person->setNote('Here is the first note that should be loaded.');
var_dump($person->notes);
$person->setNote('Here is the second note that should be loaded.');
var_dump($person->notes);
foreach($person->notes as $key => $value) {
	echo $key."\n";
}
$person->deleteNote('all');
var_dump($person->notes);
