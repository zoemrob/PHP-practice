<?php
require('MongoHelper.php');
require('HelperClass.php');

$clientData = json_decode(file_get_contents('php://input'), true);

if (isset($clientData['data']) && !empty($clientData['data'])) {

	$data = $clientData['data'];

	switch ($clientData['dataType']) {
		case 'name':
			$cursor = MongoHelper::queryByName(MongoHelper::createDBInstance(), $data);
			$results = MongoHelper::getMongoIdString($cursor);
			HelperClass::sendClientData('mongoId', $results);
			break;
	}
	
} else {
	HelperClass::sendClientData('error', 1); // will create a list of constants on the server side, and communicate a constant which will be received on the client.
	// 1 = 'no value given'
}
