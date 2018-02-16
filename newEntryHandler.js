const form = document.getElementById('new-entry-form');

form.addEventListener('submit', () => {
	const firstName = document.getElementById('first-name').value;
	const lastName = document.getElementById('last-name').value;
	const age = document.getElementById('age').value;
	// assigns sex and verifies a gender was selected.
	const sexes = document.getElementsByName('gender-input');
	try {
		if (sexes[0].checked) {
			const sex = sexes[0].value;
		} else if (sexes[1].checked) {
			const sex = sexes[1].value
		} else {
			throw "You must select a gender.";
		}
	} catch (e) {
		window.alert(e);
		return false;
	}


});
/*const verify = function () {

	const firstName = document.getElementById('first-name').value;
	const lastName = document.getElementById('last-name').value;
	const age = document.getElementById('age').value;
	// assigns sex and verifies a gender was selected.
	const sexes = document.getElementsByName('gender-input');
	try {
		if (sexes[0].checked) {
			const sex = sexes[0].value;
		} else if (sexes[1].checked) {
			const sex = sexes[1].value
		} else {
			throw new Exception("You must select a gender.");
		}
	} catch (e) {
		window.alert(e);
		return false;
	}

}*/