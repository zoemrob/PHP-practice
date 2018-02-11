<?php
// Testing script, loads in various data, runs tests on it for debugging. 
require('PersonClass.php');
require('vendor/autoload.php');
$person = new BasePerson('Zoe', 22, 'M');
/*$person->setNote('Here is the first note that should be loaded.');
$person->debugNotes();
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
*/

//database testing
$connection = new MongoDB\Client("mongodb://localhost:27017");
/*$connection->findOne('coworkerjournal', 'coworkerjournal', ['_id' => '5a7fe017e662dc7ec495262d']);
*/$db = $connection->coworkerjournal->coworkerjournal;
$resultOfQuery = $db->findOne([
	'_id' => new MongoDB\BSON\ObjectId('5a7fe017e662dc7ec495262d')
	],
	[
		'projection' => [
			'firstName' => 1,
		]
	]
);

var_dump($resultOfQuery);


