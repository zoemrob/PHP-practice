<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<title>Co-Worker Journal</title>
		<link href="public-page-style.css" rel="stylesheet">
		<link href="https://fonts.googleapis.com/css?family=Montserrat" rel="stylesheet">

		<?php 
		//require("PersonClass.php");
		//require("testingScript.php");
		//$person = new BasePerson("5a82739fb505a6e0f0d67996"); 
		?>
	</head>
	<body>		
		<div class="header">
			<h1 class="header-text">Co-Worker Journal</h1>
		</div>
		<div class="container">
<!-- 		Everything in this div will be the ultimate file, and will be the page dynamically appended.-->
			<div class="entry-fields">
				<form id="new-entry-form">
					<h2 class="subheader">New Entry</h2>
					<div class="form">
						<div class="form">
							<label>First Name:</label>
							<input id="first-name" name="first-name" type="text" placeholder="Enter first name" required>
						</div>
						<div class="form">
							<label>Last Name:</label>
							<input id="last-name" name="last-name" type="text" placeholder="Enter last name" required>
						</div>
						<div class="form">
							<label>Age:</label>
							<input id="age" name="age" type="number" placeholder="Age" required>
						</div>
						<div class="form">
							<label>Gender:</label>
							<input type="radio" class="gender-input" name="gender-input" id="male" value="M">
							<label for="male">Male</label>
							<input type="radio" class="gender-input" name="gender-input" id="female" value="F">
							<label for="female">Female</label>
						</div>
					</div>
					<div class="form">
						<button type="click" id="submit-button">SUBMIT</button>
					</div>
				</form>
			</div>
			<div class="submit-button">
				
			</div>
		</div>
		<script src="newEntryHandler.js"></script>
	</body>
</html>