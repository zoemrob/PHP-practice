/*
	This function expression is called when the window is loaded.
	It creates an constant [notes] which is an array of all of the note divs.
	It then assigns an Event Listener to each one of the notes for mouse enter/mouse leave
	These Event Listeners call the create/removeDeleteButton functions.
*/
const load = () => {
	const newEntryButton = document.getElementById("new-entry-button"),
		containerDiv = document.getElementsByClassName("container")[0],
		javascriptFiles = document.getElementById("javascript"),
		searchField = document.getElementById("search"),
		navBar = document.getElementById("nav-div"),
		searchBar = document.getElementById('js-searches');

	//setNewNoteEvent();
	//setNoteMouseoverEvents();


	// Event Listener which when clicked appends the new entry form to the public.php page.
	newEntryButton.onclick = () => {
		const newEntryRequest = formatServerData('newEntryRequest', true);
		postAjax('SearchHandler.php', newEntryRequest, response => {
			const data = parseResponse(response);
			containerDiv.innerHTML = data;
			getFormData();
		});
	}


	// *** Search Bar Function ***
	setTimeout(() => {
		searchField.onkeyup = () => {
			if (searchField.value !== '') {
				delay(() => {
					const nameData = formatServerData('name', searchField.value);
					postAjax('SearchHandler.php', nameData, response => { // response will be a JSON string formatted [{'firstName': 'some name', 'lastName': 'some name', 'mongoId': 'some id'}, {...}]	
						const results = parseResponse(response),
							searchResultLocation = document.getElementById('js-append-searches');

						//searchBar.innerHTML = results;
						results.forEach(result => {
							const tr = document.createElement('tr');
							tr.classList.add('search-result');
							tr.innerHTML = result;
							insertAfter(tr, searchResultLocation);
							tr.onclick = () => {
								const child = tr.firstChild,
									mongoIdData = formatServerData('mongoId', child.getAttribute('id'));
								postAjax('SearchHandler.php', mongoIdData, response => {
									containerDiv.setAttribute('id', parseResponse(response).mongoId);
									containerDiv.innerHTML = parseResponse(response).render;
									setNewNoteEvent();
									setNoteMouseoverEvents();
								});
								searchField.value = '';
								const searchResults = document.getElementsByClassName('search-result');
								console.log(searchResults);
								Array.prototype.forEach.call(searchResults, result => {
									result.remove();
								});
							};
						});

						navBar.onmouseleave = () => {
							const visibleSearchResults = searchBar.getElementsByClassName('search-result');
							for (let i = 0, l = visibleSearchResults.length; i < l; i++) {
								visibleSearchResults[i].classList.add('hide');
							}	
						}

						navBar.onmouseenter = () => {
							const hiddenSearchResults = searchBar.getElementsByClassName('search-result');
							for (let i = 0, l = hiddenSearchResults.length; i < l; i++) {
								hiddenSearchResults[i].classList.remove('hide');
							}	
						}
					});

					searchField.onkeydown = () => {
						const previousSearchResults = searchBar.getElementsByClassName('search-result');
						for (let i = 0, l = previousSearchResults.length; i < l; i++) {
							previousSearchResults[0].remove();
						}
					}
				}, 200);

			} else {
				const previousSearchResults = searchBar.getElementsByClassName('search-result');
				for (let i = 0, l = previousSearchResults.length; i < l; i++) {
						previousSearchResults[0].remove();
					}
			}
		};

	}, 
	50);


}

const delay = (function(){
  let timer = 0;
  return function(callback, ms){
    clearTimeout (timer);
    timer = setTimeout(callback, ms);
  };
})();

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
			break;
		case 'newEntryForm':
			return formattedResponse.data;
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
		targetNoteToDelete = document.getElementById('moused-over-note')
		previousId = targetNoteToDelete.getAttribute('id');
	
	button.innerHTML = 'DELETE NOTE';
	button.classList.add('montserrat-font');
	button.setAttribute('id', 'moused-over-delete-button');
	console.log(button);
	button.onclick = () => {
		deleteNoteFromUI([targetNoteToDelete]);

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
				noteToDelete.
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


function getFormData () {
	const containerDiv = document.getElementsByClassName('container')[0];

	const form = document.getElementById('submit-button');
	form.addEventListener('click', () => {
		let completeMessage = true;
		const firstName = document.getElementById('first-name').value;
		const lastName = document.getElementById('last-name').value;
		const age = document.getElementById('age').value;
		try {
			if (firstName === '' || lastName === '' || age === '') {
				throw "You must fill out all the fields."
			}
		} catch (e) {
			window.alert(e);
			completeMessage = false;
		}
		// assigns sex and verifies a gender was selected.
		let sex;
		try {
			if (document.getElementById('male').checked) {
				sex = document.getElementById('male').value;
			} else if (document.getElementById('female').checked) {
				sex = document.getElementById('female').value;
			} else {
				throw "You must select a gender.";
			}
		} catch (e) {
			window.alert(e);
			completeMessage = false;
		}

		if (completeMessage) {
			const newEntryData = {
				"firstName" : firstName,
				"lastName" : lastName,
				"age" : Number.parseInt(age),
				"sex" : sex,
			};
			const readyToSend = formatServerData('newEntryData', newEntryData);
			postAjax('SearchHandler.php', readyToSend, response => {
				containerDiv.setAttribute('id', parseResponse(response).mongoId);
				containerDiv.innerHTML = parseResponse(response).render;
				setNewNoteEvent();
				setNoteMouseoverEvents();
			});
		}
	});
}

	// Add new note modal
function setNewNoteEvent() {
	const newNoteButton = document.getElementById("new-note-button"),
		containerDiv = document.getElementsByClassName('container')[0];

	newNoteButton.onclick = () => {
		const mongoId = containerDiv.getAttribute('id'),
			newNoteRequest = formatServerData('newNoteRequest', mongoId), // gets the mongoId of the person, stored in the Name Div
			noteModal = document.createElement('div');
		console.log(mongoId);
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
							setNoteMouseoverEvents();
						}
					})
				}
				noteModal.remove();
			};
		},
		50);	
	};
}

// BROKEN :(
function setNoteMouseoverEvents() {
		const notes = document.getElementById("notes"); console.log(notes);
	
	for (let i = 0, l = notes.children.length; i < l; i++ ) {
		const mousedOverNote = notes.children[i],
			mousedOverNoteText = mousedOverNote.children[1].children[0],
			mousedOverNoteId = mousedOverNote.getAttribute('id');

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
}