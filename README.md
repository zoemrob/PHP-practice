# ******** Application Features **********

1) Allows you to create an entry, capturing Name, Age, and Gender
2) Allows you to edit those details after creating entry
3) Allows you add notes about the entry.
4) Allows you to delete notes about the entry.
5) Allows you to delete an entry.

# ******** Single Page Application *********

All html elements are received via ajax post request.
Elements are then appended dynamically using JavaScript.

# ******** Architecture *********

Database: MongoDB 3.6
Server-side Language: PHP 7.1
Server: Apache 2
Client: JavaScript (Vanilla, ES6)

# ******** Client Server Communication *********

All data transmitted between the Client and Server is via AJAX requests using a common JSON interchange:

`{'dataType': (string) , 'data', (mixed)}`

The form handler processes the dataType, and executes various methods based on the dataType received.
The server then sends back the appropriate dataType, which is parsed by the client.

# ******** Database Document Structure *********

```
{
	_id: ObjectId(''),
	firstName: 'Zoe' (string),
	lastName: 'Robertson' (string),
	age: 22 (int32),
	sex: 'M' (or 'F', string),
	notes: [
		{
			'date': 'Wednesday, March 7th, 2018 at 7:38pm' (string),
			'note': 'Some example text' (string)
		}
	]
}
```

# ******* Class Structure ********

## Parent Class: AbstractPerson
	-provides common properties, defines getter methods

## Child Class: BasePerson
	-child of AbstractPerson
	-the 'live' entry page that is viewed
	-setter methods are defined by stored Document fields

## Child Class: NewEntry
	-child of AbstractPerson
	-setter methods are defined by form submitted fields
	-creates database entry

## Utility Class: HelperClass
	-provides non-database related methods for use across backend
	-static methods

## Utility Class: MongoHelper
	-provides database related methods for use across backend
	-static methods

# ******* CSS Style ********

Attempted to create more modular, clear to read classes for styling.
One style feature = one class.
Example:

```
.bottom-corner-radius {
	border-bottom-left-radius: 10px;
	border-bottom-right-radius: 10px;
}
```

