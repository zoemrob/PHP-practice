<?php
//require('MongoHelper.php');
//require('HelperClass.php');
require('PersonClass.php');

$clientData = json_decode(file_get_contents('php://input'), true);

if (isset($clientData['data']) && !empty($clientData['data'])) {

	$data = $clientData['data'];

	switch ($clientData['dataType']) {
		case 'name':
			$cursor = MongoHelper::queryByName(MongoHelper::createDBInstance(), $data);
			$readyToSend = HelperClass::formatClientData('mongoId&Name', MongoHelper::getNameAndMongoId($cursor));
			echo json_encode($readyToSend);
			break;
		case 'newNoteRequest':
			$mongoId = $data;
			$person = new BasePerson($mongoId);
			$readyToSend = HelperClass::formatClientData('newNoteRequest', $person->createNewNoteForm());
			echo json_encode($readyToSend);
			break;
		case 'newNote':
			$mongoId = $data['mongoId'];
			$note = $data['note'];
			$person = new BasePerson($mongoId);
			$success = $person->setNoteFromUI($note);
						// if the note was successfully added, send the new person note data.
			if ($success == 1) {
				$readyToSend = HelperClass::formatClientData('newNoteSet', $person->displayNotes());
				echo json_encode($readyToSend);
			} else {
				$readyToSend = HelperClass::formatClientData('error', 'Something went wrong. Your note was not added successfully.');
				echo json_encode($readyToSend);
			}
			break;
		case 'mongoId':
			$mongoId = $data;
			$person = new BasePerson($mongoId);
			$readyToSend = HelperClass::formatClientData('person', ['demographics' => $person->displayDemographics(), 'notes' => $person->displayNotes()]);
			echo json_encode($readyToSend);
	}	

} else {
	echo json_encode(HelperClass::formatClientData('error', 1)); // will create a list of constants on the server side, and communicate a constant which will be received on the client.
	// 1 = 'no value given'
}
