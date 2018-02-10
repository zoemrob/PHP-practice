const load = () => {
	const notes = document.getElementById("notes");
	for (let i = 0; i < notes.children.length; i++ ) {
		notes.children[i].addEventListener('mouseenter', () => {
			notes.children[i].appendChild(createDeleteButton());
		});
		notes.children[i].addEventListener('mouseleave', () => {
			notes.children[i].appendChild(removeDeleteButton(notes.children[i]));
		});
	}
} 

window.onload = load; 

function createDeleteButton () {
	const button = document.createElement('button');
	button.innerHTML = 'DELETE';
	button.setAttribute('id', 'mouseoverDeleteButton');
	return button;
}

function removeDeleteButton (node) {
	const button = document.getElementById('mouseoverDeleteButton');

	if (node.contains(button)) {
		button.remove();
	}
}