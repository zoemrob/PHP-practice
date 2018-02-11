/*
	This function expression is called when the window is loaded.
	It creates an constant [notes] which is an array of all of the note divs.
	It then assigns an Event Listener to each one of the notes for mouse enter/mouse leave
	These Event Listeners call the create/removeDeleteButton functions.
*/
const load = () => {
	const notes = document.getElementById("notes");

	for (let i = 0; i < notes.children.length; i++ ) {
		const mousedOverNote = notes.children[i];
		const mousedOverNoteText = mousedOverNote.children[1].children[0];
		const mousedOverNoteId = mousedOverNote.getAttribute('id');

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
} 

// calls load function expression
document.addEventListener('DOMContentLoaded', () => {
	load
});

/*
	This function is used to create the delete button when a note is moused over.
	When the button is clicked, it will delete the note [#moused-over-note] from the UI and DB
	The button is wrapped in a div which matches the styling over the [.note-text] div
*/
function createDeleteButton () {

	const button = document.createElement('button');
	const buttonDiv = document.createElement('div');
	const targetNoteToDelete = document.getElementById('moused-over-note');
	
	button.innerHTML = 'DELETE NOTE';
	button.setAttribute('id', 'moused-over-delete-button');
	button.addEventListener('click', () => {
		deleteNoteFromUI([targetNoteToDelete]);
		// here will be a call to the deleteNoteFromDB function;
	})

	buttonDiv.setAttribute('id', 'moused-over-delete-button-div');

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