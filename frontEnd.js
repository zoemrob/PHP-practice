/*
	This function expression is called when the window is loaded.
	It creates an constant [notes] which is an array of all of the note divs.
	It then assigns an Event Listener to each one of the notes for mouse enter/mouse leave
	These Event Listeners call the create/removeDeleteButton functions.
*/
const load = () => {
	const notes = document.getElementById("notes"),
		newEntryButton = document.getElementById("new-entry-button"),
		containerDiv = document.getElementById("container"),
		javascriptFiles = document.getElementById("javascript"),
		searchBar = document.getElementById("search"),
		navBar = document.getElementById("nav-div");

	for (let i = 0; i < notes.children.length; i++ ) {
		const mousedOverNote = notes.children[i],
			mousedOverNoteText = mousedOverNote.children[1].children[0],
			mousedOverNoteId = mousedOverNote.getAttribute('id');

		// for each of these Event Listeners, I could in the future create them as functions themselves, cleaning the code even more.
		// createDeleteButton
		mousedOverNote.addEventListener('mouseenter', () => {
			mousedOverNote.setAttribute('id', 'moused-over-note')
			mousedOverNoteText.setAttribute('class', 'moused-over-note-text')
			mousedOverNote.appendChild(createDeleteButton());
		});

		// removeDeleteButton
		mousedOverNote.addEventListener('mouseleave', () => {
			mousedOverNote.setAttribute('id', mousedOverNoteId);
			mousedOverNote.children[1].removeAttribute('id');
			mousedOverNoteText.setAttribute('class', 'note-text');
			removeDeleteButton(mousedOverNote);
		});
	}

	// Event Listener which when clicked appends the new entry form to the public.php page.
	newEntryButton.addEventListener('click', () => {
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
	});

	/*
		************************************************************************************************************************************
		Add a client-side data parser function. One that checks all incoming data, and checkes 'dataType', and knows how to handle the data.	
		************************************************************************************************************************************
	*/


	searchBar.addEventListener('keyup', () => {
		const nameData = JSON.stringify({'dataType': 'name', 'data' : searchBar.value}); // set dataType for form handler to process
		postAjax('SearchHandler.php', nameData, response => { // response will be a JSON string formatted [{'firstName': 'some name', 'lastName': 'some name', 'mongoId': 'some id'}, {...}]
			console.log(response);
/*			response.forEach(element => {
				const displayName = element['firstName'] + ' ' + element['lastName']; 
				const searchResult = document.createElement('div');
				searchResult.setAttribute('id', element['mongoId']);
				searchResult.setAttribute('class', 'search-results');
				searchResult.innerHTML = displayName;
				searchResult.addEventListener('click', () => {
					const mongoIdData = JSON.stringify(['dataType': 'mongoId', 'data' : searchResult.getAttribute('id')]);
					postAjax('SearchHandler.php', data, function(response) {
						// This will be the PersonInstance page of the person who was searched for.
					});
				})
				navBar.appendChild(searchResult);

			});*/
		});
	});

} 

// calls load function expression
window.onload = load;

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
	button.setAttribute('id', 'moused-over-delete-button');
	button.addEventListener('click', () => {
		deleteNoteFromUI([targetNoteToDelete]);
		// here will be a call to the deleteNoteFromDB function;
	})

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

