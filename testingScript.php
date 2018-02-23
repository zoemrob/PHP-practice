<?php
// Testing script, loads in various data, runs tests on it for debugging. 
require('PersonClass.php');
require('NewEntryClass.php');
$firstName  ='Zoe';
$lastName = 'Robertson';
$data = MongoHelper::queryOneByName(MongoHelper::createDBInstance(), $firstName, $lastName);
echo json_encode($data);