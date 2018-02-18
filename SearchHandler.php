<?php
require('MongoHelper.php');
require('HelperClass.php');

$clientData = json_decode(file_get_contents('php://input'), true);

if (isset($clientData['data']) && !empty($clientData['data'])) {

	$data = $clientData['data'];

	switch ($clientData['dataType']) {
		case 'name':
//			$readyToSend = array();
			$cursor = MongoHelper::queryByName(MongoHelper::createDBInstance(), $data);
			$readyToSend = HelperClass::formatClientData('mongoId&Name', MongoHelper::getNameAndMongoId($cursor));
			echo json_encode($readyToSend);
			break;
	}	

} else {
	echo json_encode(HelperClass::formatClientData('error', 1)); // will create a list of constants on the server side, and communicate a constant which will be received on the client.
	// 1 = 'no value given'
}
