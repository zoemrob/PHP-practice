<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<title>Co-Worker Journal</title>
		<link href="public-page-style.css" rel="stylesheet">
		<link href="https://fonts.googleapis.com/css?family=Montserrat" rel="stylesheet">

		<?php 
		 require("PersonClass.php");
		$person = new BasePerson("5a7fe017e662dc7ec495262d"); 
		?>
	</head>
	<body>
		<div class="header">
			<h1 class="header-text">Co-Worker Journal</h1>
		</div>
		<div class="nav-div" id="nav-div">
<!-- 			<button id="search" type="button" class="navigation">Search</button> -->
			<input id="search" type="text" placeholder="Search" class="navigation">
			<button id="new-entry-button" type="button" class="navigation">New Entry</button>
		</div>
		<br />
		<div id="container">
			<div class="demographics" id="demographics">
				<?php 
					echo $person->displayDemographics(); 
				?>
			</div>
			<div class="new-note">
				<button type="button" id="new-note-button">New Note</button>
			</div>
			<div class="notes" id="notes">
				<?php echo $person->displayNotes(); ?>
			</div>
		</div>
		<div id="javascript">
			<script type="text/javascript" src="frontEnd.js"></script>
		</div>
	</body>
</html>