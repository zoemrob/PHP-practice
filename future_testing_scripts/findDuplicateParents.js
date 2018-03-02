var parentList = [];
db.getCollection('Parents').find({}, {_id: 1}).forEach(function(id) {parentList.push(id._id); });
var filteredParents = JSON.parse(
	db.getCollection('People').find({ _id: { $in: list } }, { FirstName: 1 , LastName: 1, PhoneNumbers: 1})
);

var getParentNumbers = function() {
	let parentNumbers = [];
	for (let i = 0, l = filteredParents.length; i < l; i++) {
		let phoneNumbers = filteredParents[i].PhoneNumbers;
		for (let j = 0, pl = phoneNumbers.length; j < pl; i++) {
			parentNumbers.push(phoneNumbers[j]);
		}
	}
	return parentNumbers;
}

var testNumbers = function (testNumber) {
	let parentNumbers = [];
	let dupParents = []
	for (let i = 0, l = filteredParents.length; i < l; i++) {
		let phoneNumbers = filteredParents[i].PhoneNumbers;
		for (let j = 0, pl = phoneNumbers.length; j < pl; i++) {
			if (testNumber === phoneNumbers[j]) {
				dupParents.push({filteredParents[i]});
			}
		}
	}
	return dupParents.
}


var parentNumbers = getParentNumbers();
var finalDupParents = [];
parentNumbers.forEach(number => {
	if(testNumbers(number) !== [] || testNumbers(number) !== undefined) {
		finalDupParents.push(testNumbers(number));
	}
});

finalDupParents;