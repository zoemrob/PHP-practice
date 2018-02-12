<?php
// Testing script, loads in various data, runs tests on it for debugging. 
require('PersonClass.php');
require('vendor/autoload.php');
require('MongoHelper.php');
$person = new BasePerson('5a7fe017e662dc7ec495262d');
$person->getName();
$person->getAge();
$person->getSex();
//$person->setNote('Here is the first note that should be loaded.');
//var_dump($person->getNotes());

/*$person->debugNotes();
>>>>>>> Stashed changes
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
$person->deleteNotes([], true);
<<<<<<< Updated upstream
=======
*/

//database testing 

		/*$db = MongoHelper::createDBInstance();
		$resultOfQuery = MongoHelper::queryById($db, "5a7fe017e662dc7ec495262d");
		$name = MongoHelper::getDocName($resultOfQuery); 
		echo $name . "\n";
*/	


/*
print_r(MongoDB\BSON\toJSON(MongoDB\BSON\fromPHP($resultOfQuery)));

$letsHope = $resultOfQuery->jsonSerialize();
foreach($letsHope as $element) {
	echo $element . "\n";
}
print_r($letsHope);

echo $letsHope->_id . "\n";
echo $letsHope->firstName . "\n";
*/