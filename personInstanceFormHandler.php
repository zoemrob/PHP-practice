<?php
require('NewEntryClass.php');
require('PersonClass.php');

$receivedData = file_get_contents('php://input');
HelperClass::generateNewEntryForm();