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
	}
}

/** Sends an asynchronous post request to the server.
 * @param {str}: URL to send data to. 
 * @param {JSON str}: Pre-formatted JSON string containing client data. 
 * @param {function}: callback function to be executed on server response.
 */
function postAjax(url, data, onSuccess) {
	const xhr = window.XMLHttpRequest ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
	xhr.open('POST', url, true);
	xhr.send(data);
	xhr.onload = () => {
		onSuccess(xhr.responseText); // executes the callback function on the server response.
	};
}

/****************************************************************************
	UI FUNCTIONS	
****************************************************************************/

/** Creates a delete button div and assigns event listener to button.
 */
function createDeleteButton () {
	const button = document.createElement('button'),
		buttonDiv = document.createElement('div'),
		targetNoteToDelete = document.getElementById('moused-over-note');
	button.innerHTML = 'DELETE NOTE';
	button.classList.add('montserrat-font');
	button.setAttribute('id', 'moused-over-delete-button');
	button.onclick = () => {
		deleteNoteFromUI([targetNoteToDelete]);
		deleteNoteFromDB([targetNoteToDelete]);
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
	const notes = document.getElementById("notes");
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

// 14 char
function deleteNoteFromDB(elements) {
	const personId = document.getElementsByClassName('id-holder')[0].getAttribute('id'),
		notes = [];
	elements.forEach(noteToDelete => {
	const targetNoteToDelete = noteToDelete.children[0],
		idOfNoteToDelete = targetNoteToDelete.getAttribute('id'),
		noteIndex = idOfNoteToDelete.slice(14);
		notes.push(noteIndex);
	});
	const notesAndId = {
		'noteIndexes': notes,
		'mongoId' : personId
	};
	const data = formatServerData('deleteNote', notesAndId);
	postAjax('SearchHandler.php', data, response => {
		console.log(response);
	});
}

/** Fetches form data, verifies the values are valid.
 */
function getFormData () {
	const containerDiv = document.getElementsByClassName('container')[0],
		form = document.getElementById('submit-button');
	form.onclick = () => {
		let completeMessage = true,
			sex;
		const firstName = document.getElementById('first-name').value,
			lastName = document.getElementById('last-name').value,
			age = document.getElementById('age').value;
		try {
			if (firstName === '' || lastName === '' || age === '') {
				throw "You must fill out all the fields."
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
			postAjax('SearchHandler.php', readyToSend, response => {
				const personData = parseResponse(response);
				containerDiv.setAttribute('id', personData.mongoId);
				containerDiv.innerHTML = personData.render;
				setNewNoteEvent();
				setNoteMouseoverEvents();
			});
		}
	};
}

/** Assigns event listener to "New Note" button, appends modal for adding data.
 */
function setNewNoteEvent() {
	const newNoteButton = document.getElementById("new-note-button");
		containerDiv = document.getElementsByClassName('container')[0];

	newNoteButton.onclick = () => {
		const mongoId = containerDiv.getAttribute('id'),
			newNoteRequest = formatServerData('newNoteRequest', mongoId),
			noteModal = document.createElement('div');

		noteModal.classList.add('modal-bkgd');
		document.body.appendChild(noteModal);	
		postAjax('SearchHandler.php', newNoteRequest, response => {
			noteModal.innerHTML = parseResponse(response);
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

			// add note to person instance, return new person instance data with appended note.
			submitNote.onclick = () => {
				const entry = document.getElementById('new-note-entry').value;
				if (entry == null || entry == undefined || entry == '') {
					window.alert('You have to enter a note!');
				} else {
					const data = formatServerData('newNote', {'mongoId': mongoId, 'note': entry});
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

/****************************************************************************
	CODE TO RUN ON PAGE LOAD
****************************************************************************/

const load = () => {
	const newEntryButton = document.getElementById("new-entry-button"),
		containerDiv = document.getElementsByClassName("container")[0],
		javascriptFiles = document.getElementById("javascript"),
		searchField = document.getElementById("search"),
		navBar = document.getElementById("nav-div"),
		searchBar = document.getElementById("js-searches");
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
					postAjax('SearchHandler.php', nameData, response => {
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
								postAjax('SearchHandler.php', mongoIdData, response => {
									const personData = parseResponse(response);
									containerDiv.setAttribute('id', personData.mongoId);
									containerDiv.innerHTML = personData.render;
									setNewNoteEvent();
									setNoteMouseoverEvents();
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
	}, 
	50);
}

window.onload = load;