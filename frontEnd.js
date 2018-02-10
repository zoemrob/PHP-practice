const load = () => {
	const notes = document.getElementById("notes");

	for (let i = 0; i < notes.children.length; i++ ) {
		let mousedOverNote = notes.children[i];

		mousedOverNote.addEventListener('mouseenter', () => {
			mousedOverNote.children[1].appendChild(createDeleteButton());
			mousedOverNote.children[1].setAttribute('id', 'moused-over-note')
		});

		mousedOverNote.addEventListener('mouseleave', () => {
			mousedOverNote.children[1].removeAttribute('id');	
			removeDeleteButton(mousedOverNote.children[1]);
		});
	}
} 

window.onload = load; 

function createDeleteButton () {

	const button = document.createElement('button');
	button.innerHTML = 'DELETE';
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