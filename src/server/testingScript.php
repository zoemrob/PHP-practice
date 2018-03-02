<?php
// Testing script, loads in various data, runs tests on it for debugging. 
require('PersonClass.php');
require('NewEntryClass.php');
$firstName  ='poop';
$data = MongoHelper::queryByNameSearch(MongoHelper::createDBInstance(), $firstName);
var_dump($data);
if (isset($data[0])) {
	echo "true";
}