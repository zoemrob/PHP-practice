const load = () => {
	const notes = document.getElementById("notes");

	for (let i = 0; i < notes.children.length; i++ ) {
		const mousedOverNote = notes.children[i];
		const mousedOverNoteText = mousedOverNote.children[1].children[0];
		const mousedOverNoteId = mousedOverNote.getAttribute('id');
		console.log(mousedOverNoteId);

		mousedOverNote.addEventListener('mouseenter', () => {
			mousedOverNote.appendChild(createDeleteButton());
			mousedOverNote.setAttribute('id', 'moused-over-note')
			mousedOverNoteText.setAttribute('class', 'moused-over-note-text')
		});

		mousedOverNote.addEventListener('mouseleave', () => {
			mousedOverNote.setAttribute('id', mousedOverNoteId);
			mousedOverNote.children[1].removeAttribute('id');
			mousedOverNoteText.setAttribute('class', 'note-text');
			removeDeleteButton(mousedOverNote);
		});
	}
} 

window.onload = load; 

function createDeleteButton () {

	const button = document.createElement('button');
	button.innerHTML = 'DELETE NOTE';
	button.setAttribute('id', 'moused-over-delete-button');

	const buttonDiv = document.createElement('div');
	buttonDiv.setAttribute('id', 'moused-over-delete-button-div');

	buttonDiv.appendChild(button);
	return buttonDiv
}

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