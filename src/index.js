/****************************************************************************
	UTILITY FUNCTIONS	
****************************************************************************/

/** Formats data to send to server
 * @param {string}: Tells server data how to process it.
 * @param {mixed}: The data to be processed. 
 */
function formatServerData (dataType, data) {
	return JSON.stringify({'dataType': dataType, 'data' : data});
}

/** Inserts newNode after referenceNode 
 * @param {DOM node}: Node to be appended.
 * @param {DOM node}: Note to be appended after.
 */
function insertAfter(newNode, referenceNode) {
    referenceNode.parentNode.insertBefore(newNode, referenceNode.nextSibling);
}

/** Delays a function execution by a certain amount of time.
 * @param {function}: callback function to be executed.
 * @param {int}: amount of time to delay in milliseconds.
 */
const delay = (function(){
  let timer = 0;
  return function(callback, ms){
    clearTimeout (timer);
    timer = setTimeout(callback, ms);
  };
})();

/** Allows server data to be pre-processed if necessary
 * Obviously looks goofy because everything currently is just returned.
 * @param {JSON str}: A JSON string of server data.
 */
function parseResponse(response) {
	const formattedResponse = JSON.parse(response),
		dataType = formattedResponse.dataType; // get dataType
	switch (dataType) {
		case 'mongoId&Name':
			return formattedResponse.data;
		case 'error':
			return formattedResponse.data;
		case 'newNoteRequest':
			return formattedResponse.data;
		case 'newNoteSet':
			return formattedResponse.data;
		case 'person':
			return formattedResponse.data;
		case 'newEntryForm':
			return formattedResponse.data;
		case 'confirmModal':
			return formattedResponse.data;
		case 'homepage':
			return formattedResponse.data;
		case 'deleteEntryConfirmModal':
			return formattedResponse.data;
		case 'deletedEntry':
			return formattedResponse.data;
		case 'editEntryForm':
			return formattedResponse.data;
	}
}

/** Sends an asynchronous post request to the server.
 * @param {str}: URL to send data to. 
 * @param {JSON str}: Pre-formatted JSON string containing client data. 
 * @return Obj: Returns a promise to allow content to fully load.
 */
function postAjax(url, data) {
     return new Promise(function (resolve, reject) {
          const xhr = window.XMLHttpRequest ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
          xhr.open('POST', url, true);
          xhr.send(data);
          xhr.onload = () => {
               resolve(xhr.responseText)
          };
     });
}

/**
 * @return node: container div node.
 */
function getContainerDiv() {
	return document.getElementsByClassName('container')[0];
}

/****************************************************************************
	UI FUNCTIONS	
****************************************************************************/

/** Creates a delete button div and assigns event listener to button.
 * @return node: HTML node for button.
 */
function createDeleteButton () {
	const button = document.createElement('button'),
		buttonDiv = document.createElement('div'),
		targetNoteToDelete = document.getElementById('moused-over-note');
	button.innerHTML = 'DELETE NOTE';
	button.classList.add('montserrat-font');
	button.setAttribute('id', 'moused-over-delete-button');
	button.onclick = () => {
		const confirmModal = document.createElement('div');
		const mongoId = getContainerDiv().getAttribute('id');
		const confirmModalRequest = formatServerData('confirmModalRequest', mongoId);
		confirmModal.classList.add('modal-bkgd');
		document.body.append(confirmModal);
		postAjax('src/server/form-handler.php', confirmModalRequest).then(response => {
			confirmModal.innerHTML = parseResponse(response);
			const closeModal = document.getElementById('close-note-modal'),
				confirmDelete = document.getElementById('confirm-delete-button');
			// closes modal if close button is clicked.
			closeModal.onclick = () => {
				confirmModal.remove();	
			};
			// closes modal if outside of the text entry is clicked.
			confirmModal.onclick = event => {
				event.target == confirmModal ? confirmModal.remove() : '';
			}

			confirmDelete.onclick = () => {
				deleteNoteFromUI([targetNoteToDelete]);
				deleteNoteFromDB([targetNoteToDelete]);				
				confirmModal.remove();
			};
		});
	};
	buttonDiv.classList.add(
		'bottom-corner-radius',
		'standard-bkgd-color',
		'delete-button-div'
	);
	buttonDiv.setAttribute('id', 'js-delete-button-div')
	buttonDiv.appendChild(button);
	return buttonDiv;
}

/** Removes delete button from UI
 */
function removeDeleteButton () {
	const buttonDiv = document.getElementById('js-delete-button-div');
	buttonDiv.remove();
}

/** Function is called on new person load or note refresh, assigns event listeners.
 */
function setNoteMouseoverEvents() {
	let notes = document.getElementById("notes");
	for (let i = notes.children.length - 1; i >= 0; i-- ) {
		const mousedOverNote = notes.children[i],
			mousedOverNoteText = mousedOverNote.children[1].children[0],
			noteId = mousedOverNote.getAttribute('id');
		// createDeleteButton
		mousedOverNote.addEventListener('mouseenter', () => {
			mousedOverNote.setAttribute('id', 'moused-over-note');
			mousedOverNoteText.classList.add('margin-btm-0');
			mousedOverNoteText.classList.toggle('bottom-corner-radius');
			mousedOverNote.appendChild(createDeleteButton());
		});

		// removeDeleteButton
		mousedOverNote.addEventListener('mouseleave', () => {
			mousedOverNote.setAttribute('id', noteId);
			mousedOverNote.children[1].removeAttribute('id');
			mousedOverNoteText.classList.remove('margin-btm-0');
			mousedOverNoteText.classList.remove('bottom-corner-radius');
			mousedOverNoteText.classList.toggle('bottom-corner-radius');
			removeDeleteButton();
		});
	}
}

/** Deletes notes from UI. Logs error if array is not given.
 * @param {array}: Array of indexes.
 */
function deleteNoteFromUI (elements) {
	try {
		if (Array.isArray(elements)) {
			elements.forEach(noteToDelete => {
				noteToDelete.remove();
			});
		}
	} catch(error) {
		console.log(`Expecting Array. Received ${typeof elements} instead.`);
	}
}

/**
 * Deletes notes from database for Entry
 * @param {array}: Array of indexes
 *
 */
function deleteNoteFromDB(elements) {
	const containerDiv = getContainerDiv();
		personId = containerDiv.getAttribute('id'),
		notes = [];
	elements.forEach(noteToDelete => {
		const targetNoteToDelete = noteToDelete.children[0],
			idOfNoteToDelete = targetNoteToDelete.getAttribute('id'),
			noteIndex = idOfNoteToDelete.slice(14); // to get the index
		notes.push(noteIndex);
	});
	formattedNotes = notes.join(','); // join into string with comma delimiter
	const notesAndId = {
		'noteIndexes': formattedNotes,
		'mongoId' : personId
	};
	const data = formatServerData('deleteNote', notesAndId);
	postAjax('src/server/form-handler.php', data).then(response => {
		window.alert(response);
	});
}

/** Fetches form data, verifies the values are valid.
 */
function getFormEvents () {
	const containerDiv = getContainerDiv(),
		form = document.getElementById('submit-button');
	form.onclick = () => {
		let completeMessage = true,
			sex;
		const firstName = document.getElementById('first-name').value,
			lastName = document.getElementById('last-name').value,
			age = document.getElementById('age').value;
		try {
			if (age > 110 || age < 1) {
				throw "Please enter an age between 1-110";
			}
			if (firstName === '' || lastName === '' || age === '') {
				throw "You must fill out all the fields.";
			}
		} catch (e) {
			window.alert(e);
			completeMessage = false;
		}
		// assigns sex and verifies a gender was selected.
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
		// submits new entry data and displays new person page of new entry.
		if (completeMessage) {
			const newEntryData = {
				"firstName" : firstName,
				"lastName" : lastName,
				"age" : Number.parseInt(age),
				"sex" : sex,
			};
			const readyToSend = formatServerData('newEntryData', newEntryData);
			postAjax('src/server/form-handler.php', readyToSend).then(response => {
				const personData = parseResponse(response);
				containerDiv.setAttribute('id', personData.mongoId);
				containerDiv.innerHTML = personData.render;
				setNewNoteEvent();
				setNoteMouseoverEvents();
				deleteEntryEvent();
				editEntryDemographicEvent();
			});
		}
	};
}

/** Assigns event listener to "New Note" button, appends modal for adding data.
 */
function setNewNoteEvent() {
	const newNoteButton = document.getElementById("new-note-button");
		containerDiv = getContainerDiv();

	newNoteButton.onclick = () => {
		const mongoId = containerDiv.getAttribute('id'),
			newNoteRequest = formatServerData('newNoteRequest', mongoId),
			noteModal = document.createElement('div');

		noteModal.classList.add('modal-bkgd');
		document.body.appendChild(noteModal);	
		postAjax('src/server/form-handler.php', newNoteRequest).then(response => {
			noteModal.innerHTML = parseResponse(response);
		// Modal events, set a 50 millisecond timeout to have time to fetch the elements
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

			// add note to person instance, return new person instance data with appended note.
			submitNote.onclick = () => {
				const entry = document.getElementById('new-note-entry').value;
				if (entry == null || entry == undefined || entry == '') {
					window.alert('You have to enter a note!');
				} else {
					const data = formatServerData('newNote', {'mongoId': mongoId, 'note': entry});
					postAjax('src/server/form-handler.php', data).then(response => {
						const newInfo = parseResponse(response);
						if (newInfo) {
							let notes = document.getElementById('notes');
							notes.innerHTML = newInfo;
							setNoteMouseoverEvents();
						}
					});
				}
				noteModal.remove();
			};
		});
	};
}

function deleteEntryEvent() {
	const deleteEntryButton = document.getElementById('delete-entry-button'),
		containerDiv = getContainerDiv();
	deleteEntryButton.onclick = () => {
		const confirmEntryModal = document.createElement('div'),
			mongoId = containerDiv.getAttribute('id'),
			confirmModalRequest = formatServerData('deleteEntryConfirmModalRequest', true);
		postAjax('src/server/form-handler.php', confirmModalRequest).then(response => {
			const confirmEntryDeleteModal = parseResponse(response);
			confirmEntryModal.classList.add('modal-bkgd');
			confirmEntryModal.innerHTML = confirmEntryDeleteModal;
			document.body.appendChild(confirmEntryModal);
			const closeModal = document.getElementById('close-note-modal'),
				confirmDelete = document.getElementById('confirm-delete-button');
			// closes modal if close button is clicked.
			closeModal.onclick = () => {
				confirmEntryModal.remove();	
			};
			// closes modal if outside of the text entry is clicked.
			confirmEntryModal.onclick = event => {
				event.target == confirmEntryModal ? confirmEntryModal.remove() : '';
			}

			confirmDelete.onclick = () => {
				const deleteEntry = formatServerData('deleteEntry', mongoId);
				postAjax('src/server/form-handler.php', deleteEntry).then(response => {
					const deleteMessage = parseResponse(response);
					containerDiv.innerHTML = deleteMessage;
				});
				confirmEntryModal.remove();
			};
		})
	};
}

function editEntryDemographicEvent() {
	const editButton = document.getElementById('js-edit-button'),
		containerDiv = getContainerDiv(),
		mongoId = containerDiv.getAttribute('id');
		editButton.onclick = () => {
		const editRequestForm = formatServerData('editRequestForm', mongoId);
		postAjax('src/server/form-handler.php', editRequestForm).then(response => {
			const editEntryForm = parseResponse(response);
			containerDiv.innerHTML = editEntryForm;
			// sets delay for elements to be fetched.
			const form = document.getElementById('submit-button');
			form.onclick = () => {
				let fieldCheck = true;
				const fields = document.querySelectorAll('.edit-input'),
					fetchedFields = {};
				fields.forEach(field => {
					// sets the key for fetchedFields to the id of the field.
					const key = field.getAttribute('id');
					// assigns gender based on which radio option is checked.
					if (key == 'male' || key == 'female') {
						if (field.checked) {
							fetchedFields['sex'] = field.value;
						}
					} else {
						fetchedFields[key] = field.value;
					}
				});
				// checks if all of the fields are blank
				try {
					if (fetchedFields.sex == undefined && fetchedFields['first-name'] == '' && fetchedFields['last-name'] == '' && fetchedFields.age == '') {
						throw 'You must enter at least one field!';
					}
					if (fetchedFields.age != '' && (fetchedFields.age > 110 || fetchedFields.age < 1)) {
						throw "Please enter an age between 1-110";
					}					
				} catch (e) {
					fieldCheck = false;
					window.alert(e);
				}
				fetchedFields['mongoId'] = mongoId;
				const updatedData = formatServerData('updatedData', fetchedFields);
				if (fieldCheck) {
					postAjax('src/server/form-handler.php', updatedData).then(response => {
						const updatedEntry = parseResponse(response);
						if (updatedEntry != '' && updatedEntry != undefined && updatedEntry != null) {
							if (updatedEntry == 'You entered the same values!') {
								// alerts error if server returned error.
								window.alert(updatedEntry);
							} else {
								containerDiv.innerHTML = updatedEntry;
								setNewNoteEvent();
								setNoteMouseoverEvents();
								deleteEntryEvent();
								editEntryDemographicEvent();
							}
						}
					});
				}
			}
		})
	}
}

/****************************************************************************
	CODE TO RUN ON PAGE LOAD
****************************************************************************/

const load = () => {
	const newEntryButton = document.getElementById("new-entry-button"),
		containerDiv = getContainerDiv(),
		javascriptFiles = document.getElementById("javascript"),
		searchField = document.getElementById("search"),
		navBar = document.getElementById("nav-div"),
		searchBar = document.getElementById("js-searches")
		homepageButton = document.getElementById('home-button');
	// Event Listener which when clicked appends the new entry form to the public.php page.
	newEntryButton.onclick = () => {
		const newEntryRequest = formatServerData('newEntryRequest', true);
		postAjax('src/server/form-handler.php', newEntryRequest).then(response => {
			const data = parseResponse(response);
			containerDiv.innerHTML = data;
			getFormEvents();
		});
	}

	homepageButton.onclick = () => {
		const homepageRequest = formatServerData('homepage', true);
		postAjax('src/server/form-handler.php', homepageRequest).then(response => {
			const data = parseResponse(response);
			containerDiv.innerHTML = data;
		});
	}

	// *** Search Bar Function ***
	searchField.onkeyup = () => {
		if (searchField.value !== '') {
			delay(() => {
				const nameData = formatServerData('name', searchField.value);
				postAjax('src/server/form-handler.php', nameData).then(response => {
					const results = parseResponse(response),
						searchResultLocation = document.getElementById('js-append-searches');
					results.forEach(result => {
						const tr = document.createElement('tr');
						let searchResults = null;
						tr.classList.add('search-result');
						tr.innerHTML = result;
						insertAfter(tr, searchResultLocation);
						searchResults = document.getElementsByClassName('search-result');
						tr.onclick = () => {
							const child = tr.firstChild,
								// where the ObjectId value is stored.
								mongoIdData = formatServerData('mongoId', child.getAttribute('id'));
							postAjax('src/server/form-handler.php', mongoIdData).then(response => {
								const personData = parseResponse(response);
								containerDiv.setAttribute('id', personData.mongoId);
								containerDiv.innerHTML = personData.render;
								setNewNoteEvent();
								setNoteMouseoverEvents();
								deleteEntryEvent();
								editEntryDemographicEvent();
							});
							// reset value of search field when a result is clicked.
							searchField.value = '';
							for (let i = searchResults.length - 1; i >= 0; i--) {
								searchResults[0].remove();
							}
						};
					});

					navBar.onmouseleave = () => {
						const visibleSearchResults = document.getElementsByClassName('search-result');
						for (let i = 0, l = visibleSearchResults.length; i < l; i++) {
							visibleSearchResults[i].classList.add('hide');
						}	
					}

					navBar.onmouseenter = () => {
						const hiddenSearchResults = document.getElementsByClassName('search-result');
						for (let i = 0, l = hiddenSearchResults.length; i < l; i++) {
							hiddenSearchResults[i].classList.remove('hide');
						}	
					}
				});
			}, 200); // sets 200ms after stopped typing to query.

		} else {
			const previousSearchResults = document.getElementsByClassName('search-result');
			for (let i = 0, l = previousSearchResults.length; i < l; i++) {
					previousSearchResults[0].remove();
				}
		}
	};
	searchField.onkeydown = () => {
		const previousSearchResults = document.getElementsByClassName('search-result');
		if (previousSearchResults) {			
			for (let i = previousSearchResults.length - 1; i >= 0; i--) {
			previousSearchResults[0].remove();
			}
		}
	}
}

window.onload = load;