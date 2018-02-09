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
		<div class="notes">
			<?php 	
				$person->getNotes();
			?>
		</div>
	</body>
</html>