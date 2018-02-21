/*
	This function expression is called when the window is loaded.
	It creates an constant [notes] which is an array of all of the note divs.
	It then assigns an Event Listener to each one of the notes for mouse enter/mouse leave
	These Event Listeners call the create/removeDeleteButton functions.
*/
const load = () => {
	const notes = document.getElementById("notes"),
		mongoIdContainer = document.getElementsByClassName('id-holder')[0],
		newEntryButton = document.getElementById("new-entry-button"),
		containerDiv = document.getElementById("container"),
		javascriptFiles = document.getElementById("javascript"),
		searchField = document.getElementById("search"),
		navBar = document.getElementById("nav-div"),
		newNoteButton = document.getElementById("new-note-button"),
		demographicsDiv = document.getElementById('demographics');

	for (let i = 0; i < notes.children.length; i++ ) {
		const mousedOverNote = notes.children[i],
			mousedOverNoteText = mousedOverNote.children[1].children[0],
			mousedOverNoteId = mousedOverNote.getAttribute('id');

		// for each of these Event Listeners, I could in the future create them as functions themselves, cleaning the code even more.
		// createDeleteButton
		mousedOverNote.addEventListener('mouseenter', () => {
			mousedOverNote.setAttribute('id', 'moused-over-note');
			mousedOverNoteText.classList.toggle('margin-btm-0');
			mousedOverNoteText.classList.toggle('bottom-corner-radius');
			mousedOverNote.appendChild(createDeleteButton());
		});

		// removeDeleteButton
		mousedOverNote.addEventListener('mouseleave', () => {
			mousedOverNote.setAttribute('id', mousedOverNoteId);
			mousedOverNote.children[1].removeAttribute('id');
			mousedOverNoteText.classList.toggle('margin-btm-0');
			mousedOverNoteText.classList.toggle('moused-over-note-text');
			mousedOverNoteText.classList.toggle('bottom-corner-radius');
			removeDeleteButton(mousedOverNote);
		});
	}
 // MUST REWORK WITH THE COMMUNICATION CONTRACT AND parseServerData()
	// Event Listener which when clicked appends the new entry form to the public.php page.
	newEntryButton.onclick = () => {
		postAjax('personInstanceFormHandler.php', 1, response => {
			const data = JSON.parse(response);
			containerDiv.innerHTML = (data['displayData']);
			// appends an additional javascript script when the element is clicked
			(tag => {
		    const scriptTag = document.createElement(tag), // create a script tag
			    firstScriptTag = document.getElementsByTagName(tag)[0]; // find the first script tag in the document
		    scriptTag.src = 'newEntryHandler.js';
		    firstScriptTag.parentNode.insertBefore(scriptTag, firstScriptTag); // append the script to the DOM
			})('script');
		});
	}


	// *** Search Bar Function ***
	searchField.onkeyup = () => {
		if (searchField.value !== '') {
			const nameData = formatServerData('name', searchField.value);
			postAjax('SearchHandler.php', nameData, response => { // response will be a JSON string formatted [{'firstName': 'some name', 'lastName': 'some name', 'mongoId': 'some id'}, {...}]	
				const results = parseResponse(response),
					searchBar = document.getElementById('js-append-searches');

				//searchBar.innerHTML = results;
				results.forEach(result => {
					const tr = document.createElement('tr');
					tr.innerHTML = result;
					insertAfter(tr, searchBar);
					tr.onclick = () => {
						const child = tr.firstChild,
							mongoIdData = formatServerData('mongoId', child.getAttribute('id'));
						postAjax('SearchHandler.php', mongoIdData, response => {
							console.log(parseResponse(response));
							mongoIdContainer.setAttribute('id', parseResponse(response).mongoId);
							demographicsDiv.innerHTML = parseResponse(response).demographics;
							notes.innerHTML = parseResponse(response).notes;
						});
					};
				});
			});
		} else {

		}
	};


			// Add new note modal
	newNoteButton.onclick = () => {
		const mongoId = mongoIdContainer.getAttribute('id'),
			newNoteRequest = formatServerData('newNoteRequest', mongoId), // gets the mongoId of the person, stored in the Name Div
			noteModal = document.createElement('div');
		noteModal.classList.toggle('modal-bkgd');
		document.body.appendChild(noteModal);
		
		postAjax('SearchHandler.php', newNoteRequest, response => {
			noteModal.innerHTML = parseResponse(response); // get new note modal format.
		});

		// Modal events, set a 50 millisecond timeout to have time to fetch the elements
		setTimeout(() => {
			const closeModal = document.getElementById('close-note-modal'),
				submitNote = document.getElementById('submit-new-note');
			// closes modal if close button is clicked.
			closeModal.onclick = () => {
				noteModal.remove();	
			};
			// closes modal if outside of the text entry is clicked.
			noteModal.onclick = event => {
				event.target == noteModal ? noteModal.remove() : '';
			}

			submitNote.onclick = () => {
				const entry = document.getElementById('new-note-entry').value;
				if (entry == null || entry == undefined || entry == '') {
					window.alert('You have to enter a note!');
				} else {
					const data = formatServerData('newNote', {'mongoId': mongoId, 'note': entry});
					// add note to person instance, return new person instance data with appended note.
					postAjax('SearchHandler.php', data, response => {
						const newInfo = parseResponse(response);
						if (newInfo) {
							notes.innerHTML = newInfo;
						}
					})
				}
				noteModal.remove();
			};
		},
		50);
		
	};

} 

// calls load function expression
window.onload = load;
	/*
		************************************************************************************************************************************
		Add a client-side data parser function. One that checks all incoming data, and checkes 'dataType', and knows how to handle the data.	
		************************************************************************************************************************************
	*/
// formats server-send data
function formatServerData (dataType, data) {
	return JSON.stringify({'dataType': dataType, 'data' : data});
}

function parseResponse(response) {
	const formattedResponse = JSON.parse(response),
		dataType = formattedResponse.dataType; // get dataType

	switch (dataType) {
		case 'mongoId&Name':
			return formattedResponse.data;
			break;
		case 'error':
			return formattedResponse.data;
			break;
		case 'newNoteRequest':
			return formattedResponse.data;
			break;
		case 'newNoteSet':
			return formattedResponse.data;
			break;
		case 'person':
			return formattedResponse.data;
			// return formattedResponse.data;
			break;
	}

}

/*
	This function is used to create the delete button when a note is moused over.
	When the button is clicked, it will delete the note [#moused-over-note] from the UI and DB
	The button is wrapped in a div which matches the styling over the [.note-text] div
*/
function createDeleteButton () {

	const button = document.createElement('button'),
		buttonDiv = document.createElement('div'),
		targetNoteToDelete = document.getElementById('moused-over-note');
	
	button.innerHTML = 'DELETE NOTE';
	button.classList.add('montserrat-font')
	button.setAttribute('id', 'moused-over-delete-button');
	button.addEventListener.onclick = () => {
		deleteNoteFromUI([targetNoteToDelete]);
		// here will be a call to the deleteNoteFromDB function;
	};

	buttonDiv.classList.add('bottom-corner-radius', 'standard-bkgd-color');
	buttonDiv.setAttribute('id', 'moused-over-delete-button-div'); // changes id to allow CSS selection.

	buttonDiv.appendChild(button);
	return buttonDiv
}

/*
	This function is used to remove the delete button when the mouse exits the note div.
*/
function removeDeleteButton (node) {
	const button = document.getElementById('moused-over-delete-button');
	if (node.contains(button)) {
		button.remove();
	}
	const buttonDiv = document.getElementById('moused-over-delete-button-div');
	if (node.contains(buttonDiv)) {
		buttonDiv.remove();
	}
}

/*
	This function will be called when the deleteButton is clicked.
	An event listener will be appended to deleteButton when it is created.
	This function will allow for multiple notes to be deleted in the future.
	@param elements = array of DOM elements to delete.

	FUTURE PLANNING: This method will popup a floating div which will confirm deletion.
*/
function deleteNoteFromUI (elements) {
	// THIS METHOD WILL NEED TO HAVE AN AJAX REQUEST TO RETURN AND REKEY THE ELEMENTS USING PersonClass::deleteNotes()
	
	try {
		if (Array.isArray(elements)) {
			elements.forEach(noteToDelete => {
				noteToDelete.remove();
			});
		}
	} catch(error) {
		console.log(`Expecting Array. Received ${typeof elements} instead.`);
		console.log(error);
	}
}

/*
	This function will be called when the deleteButton is clicked.
	An event listener will be appended to deleteButton when it is created.
	This function will allow for multiple notes to be deleted in the future.
*/
function deleteNoteFromDB () {

}

function postAjax(url, data, onSuccess) {
	const xhr = window.XMLHttpRequest ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
	xhr.open('POST', url, true);
	xhr.send(data);
	xhr.onload = () => {
		onSuccess(xhr.responseText); // executes the callback function on the server response.
	};
}

function insertAfter(newNode, referenceNode) {
    referenceNode.parentNode.insertBefore(newNode, referenceNode.nextSibling);
}
