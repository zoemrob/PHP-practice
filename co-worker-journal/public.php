<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<title>Co-Worker Journal</title>
		<link href="public-page-style.css" rel="stylesheet">
		<link href="https://fonts.googleapis.com/css?family=Montserrat" rel="stylesheet">
		<!--<script src="frontEnd.js"></script>-->

		<?php 
		require("baseClass.php");
		$person = new BasePerson("Zoe", 22, "M"); 
		$person->setNote("I met up with some of my peers in the Udacity Grow-with-Google Scholarship.\nWe had a great time, we learned a lot. I arrived first at Case Study Coffee Roasters. Ginny arrived shortly after. We finished studying there, then we went to Starbucks, and studied a bit more. Andrew showed up a bit later, and now we are getting ready to go over a group project");
		$person->setNote("This is your 2nd test note.");
		$person->setNote("This is your 3rd test note.");
		?>
	</head>
	<body>
		<div class="header">
			<h1 class="header-text">Co-Worker Journal</h1>
		</div>
		<div class="demographics">
			<p class="demographics-data">
				<?php echo $person->displayDemographics(); ?>
			</p>
		</div>
		<div class="notes"><?php 	// loops over BasePerson->notes array, renders list of notes and assigns each element a numbered class, corresponding to the appropriate index of the note.
					foreach($person->notes as $key => $note) {
						echo "
						<div id='note-" . $key . "'> 
							<p class='note-date-" . $key . "' id='note-date-" . $key ."'>" . $note['date'] . " <em>you wrote</em>:</p>
							<p class='note-text-" . $key . "' id='note-text-" . $key ."'>'" . $note['note'] . "'</p>
						</div>\n";
				}
			?>
		</div>
	</body>
</html>