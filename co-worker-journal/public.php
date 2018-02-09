<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<title>Co-Worker Journal</title>
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
					foreach($person->notes as $key => $note) {
						echo "<div class='note" . $key . "'> 
						<p>" . $note['date'] . ":</p>
						<p>You wrote '" . $note['note'] . "'</p>
						</div>";
				}
			?>
		</div>
	</body>
</html>