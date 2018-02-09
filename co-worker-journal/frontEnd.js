(function () {
	let notesDiv = document.getElementsByClassName('notes');
	console.log(notesDiv);
	// let arrayOfNotes = notesDiv.hasChildNodes() ? notesDiv.childNodes : []; 
	if (notesDiv.hasChildNodes()) {
		let arrayOfNotes = notesDiv.childNodes();
	}
	console.log(notesDiv);
}());