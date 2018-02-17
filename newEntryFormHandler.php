<?php
require('NewEntryClass.php');
$receivedData = file_get_contents('php://input');
// I will sanitize the data as a stretch goal :) Right now it is only local, so
// https://stackoverflow.com/questions/37533162/sanitize-json-with-php 
// link to sanitize.

$newEntry = new NewEntry($receivedData);
$confirmation = $newEntry->insertEntryIntoDB();
echo $confirmation;
