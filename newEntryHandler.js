const form = document.getElementById('submit-button');

form.addEventListener('click', () => {
	let completeMessage;
	const firstName = document.getElementById('first-name').value;
	const lastName = document.getElementById('last-name').value;
	const age = document.getElementById('age').value;
	// assigns sex and verifies a gender was selected.
	var sex;
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
		return false;
	}

	const parsedData = JSON.stringify({
		"firstName" : firstName,
		"lastName" : lastName,
		"age" : age,
		"sex" : sex,
		// include php method for handling in the future
	});

	const request = new XMLHttpRequest();
	request.open('POST', 'formHandler.php');
	request.send(parsedData);
	request.onload = () => {
		const response = request.responseText;
		console.log(response);
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