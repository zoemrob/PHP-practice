<?php
require('PersonClass.php');
require('NewEntryClass.php');

$clientData = json_decode(file_get_contents('php://input'), true);

if (isset($clientData['data']) && !empty($clientData['data'])) {

	$data = $clientData['data'];

	if (is_array($data)) {
        array_walk($testdata, function(&$item){
		    $item = strip_tags($item);
		});
    } else {
    	$data = strip_tags($data);
    }

	switch ($clientData['dataType']) {
		case 'name':
			$cursor = MongoHelper::queryByNameSearch(MongoHelper::createDBInstance(), $data);
			$readyToSend = HelperClass::formatClientData('mongoId&Name', HelperClass::formatSearchResults($cursor));
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
		case 'newEntryData':
			$firstName = $data['firstName'];
			$lastName = $data['lastName'];
			$newEntry = new NewEntry(json_encode($data));
			$acknowledged = $newEntry->insertEntryIntoDB();
			if ($acknowledged == 1) {
				$data = MongoHelper::queryOneByName(MongoHelper::createDBInstance(), $firstName, $lastName);
			}
		case 'mongoId':
			$mongoId = $data;
			$person = new BasePerson($mongoId);
			$readyToSend = HelperClass::formatClientData('person', ['render' => $person->render(), 'mongoId' => $person->getMongoId()]);
			echo json_encode($readyToSend);
			break;
		case 'newEntryRequest':
			echo $data ? 
			json_encode(HelperClass::formatClientData('newEntryForm', HelperClass::generateNewEntryForm())) : 
			json_encode(HelperClass::formatClientData('error', 'Something went wrong. Unable to request new entry.'));
			break;
		case 'deleteNote':
			$mongoId = $data['mongoId'];
			$noteIndexes = $data['noteIndexes'];
			$person = new BasePerson($mongoId);
			$response = $person->deleteNotes($noteIndexes);
			echo json_encode($response);
			break;
		case 'confirmModalRequest':
			$mongoId = $data;
			$person = new BasePerson($mongoId);
			$readyToSend = HelperClass::formatClientData('confirmModal', $person->createNoteDeleteConfirm());
			echo json_encode($readyToSend);
			break;
		case 'homepage':
			echo $data ?
			json_encode(HelperClass::formatClientData('homepage', HelperClass::generateHomepage())) :
			json_encode(HelperClass::formatClientData('error', 'Something went wrong. Unable to go to homepage.'));
			break;
		case 'deleteEntryConfirmModalRequest':
			echo $data ?
			json_encode(HelperClass::formatClientData('deleteEntryConfirmModal', HelperClass::generateEntryConfirmModal())) :
			json_encode(HelperClass::formatClientData('error', 'Something went wrong.'));
			break;
		case 'deleteEntry':
			$mongoId = $data;
			$deleteMessage = MongoHelper::deleteDBEntry(MongoHelper::createDBInstance(), $mongoId);
			$readyToSend = HelperClass::generateEntryDeleteConfirmation($deleteMessage);
			echo json_encode(HelperClass::formatClientData('deletedEntry', $readyToSend));
			break;
		case 'editRequestForm':
			$mongoId = $data;
			$person = new BasePerson($mongoId);
			$form = $person->updateDemographicsForm();
			$readyToSend = HelperClass::formatClientData('editEntryForm', $form);
			echo json_encode($readyToSend);
			break;
		case 'updatedData':
			$elementsToUpdate = array();
			foreach($data as $key => $value) {
				if (!empty($value) && isset($value)) {
					$elementsToUpdate[$key] = $value;
				}
			}
			$elementsToUpdate = MongoHelper::convertToMongoFields($elementsToUpdate);
			echo json_encode($elementsToUpdate);
			break;
	}	

} else {
	echo json_encode(HelperClass::formatClientData('error', 'Server couldn\'t process data.')); // will create a list of constants on the server side, and communicate a constant which will be received on the client.
	// 1 = 'no value given'
}
