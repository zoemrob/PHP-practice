<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<title>Co-Worker Journal</title>
		<link href="public-page-style.css" rel="stylesheet">
		<!--<script src="frontEnd.js"></script>-->

		<?php 
		require("baseClass.php");
		$person = new BasePerson("Zoe", 22, "M"); 
		$person->setNote("This is your 1st test note.");
		$person->setNote("This is your 2nd test note.");
		$person->setNote("This is your 3rd test note.");
		?>
	</head>
	<body>
		<div class="header">
			<h1 class="header-text">Co-Worker Journal</h1>
		</div>
		<div class="demographics">
			<span class="demographics-data">
				<?php echo $person->displayDemographics(); ?>
			</span>
		</div>
		<div class="notes"><?php 	// loops over BasePerson->notes array, renders list of notes and assigns each element a numbered class, corresponding to the appropriate index of the note.
					foreach($person->notes as $key => $note) {
						echo "
						<div id='note-" . $key . "'> 
							<p class='note-date-" . $key . "'>" . $note['date'] . " <em>you wrote</em>:</p>
							<p class='note-text-" . $key . "'>'" . $note['note'] . "'</p>
						</div>\n";
				}
			?>
		</div>
	</body>
</html>