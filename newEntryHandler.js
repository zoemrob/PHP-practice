		const form = document.getElementById('submit-button');

		form.addEventListener('click', () => {
			let completeMessage = true;
			try {
				const firstName = document.getElementById('first-name').value;
				const lastName = document.getElementById('last-name').value;
				const age = document.getElementById('age').value;
				if (firstName === '' || lastName === '' || age === '') {
					throw "You must fill out all the fields."
				}
			} catch (e) {
				window.alert(e);
				completeMessage = false;
			}
			// assigns sex and verifies a gender was selected.
			let sex;
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

			if (completeMessage) {
				const newEntryData = {
					"firstName" : firstName,
					"lastName" : lastName,
					"age" : age,
					"sex" : sex,
				};
				const readyToSend = formatServerData('newEntryData', newEntryData);
				/*postAjax('SearchHandler.php', parsedData, response => {
					console.log(response);
				})*/
			}
		});

function getAjax(url, onSuccess) {
	const xhr = window.XMLHttpRequest ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
	xhr.open('GET', url, true);
	xhr.onload = () => {
		onSuccess(xhr.responseText);
	};
	xhr.send();
}

function postAjax(url, data, onSuccess) {
	const xhr = window.XMLHttpRequest ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
	xhr.open('POST', url, true);
	xhr.send(data);
	xhr.onload = () => {
		onSuccess(xhr.responseText);
	};
}