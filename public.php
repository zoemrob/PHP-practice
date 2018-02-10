<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<title>Co-Worker Journal</title>
		<link href="public-page-style.css" rel="stylesheet">
		<link href="https://fonts.googleapis.com/css?family=Montserrat" rel="stylesheet">
		<script src="frontEnd.js"></script>

		<?php 
		require("PersonClass.php");
		$person = new BasePerson("Zoe", 22, "M"); 
		$person->setNote("I met up with some of my peers in the Udacity Grow-with-Google Scholarship. We had a great time, we learned a lot. I arrived first at Case Study Coffee Roasters. Ginny arrived shortly after. We finished studying there, then we went to Starbucks, and studied a bit more. Andrew showed up a bit later, and now we are getting ready to go over a group project");
		$person->setNote("This is your 2nd test note.");
		$person->setNote("This is your 3rd test note.");
		?>
	</head>
	<body>
		<div class="header">
			<h1 class="header-text">Co-Worker Journal</h1>
		</div>
		<div class="demographics">
			<?php 
				$person->displayDemographics(); 
			?>
		</div>
		<div class="notes" id="notes">
			<?php 	
				$person->displayNotes();
			?>
		</div>
	</body>
</html>