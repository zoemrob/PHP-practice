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
		<div class="header standard-bkgd-color standard-shadow margin0 wth50">
			<p class="header-text center">Co-Worker Journal</p>
		</div>
		<div class="nav-div center standard-bkgd-color standard-shadow margin0 wth50" id="nav-div">
<!-- 			<button id="search" type="button" class="navigation">Search</button> -->
			<table class='search-bar' id='js-searches'>
				<tr id='js-append-searches'>
					<td>
						<input id="search" type="text" placeholder="Search" class="navigation pad10">
					</td>
					<button id="new-entry-button" type="button" class="floatR pad10">New Entry</button>
				</tr>
			</table>
		</div>
		<br />
		<div id="container">
			<div class="demographics center" id="demographics">
				<?php 
					echo $person->displayDemographics(); 
				?>
			</div>
			<div class="center">
				<button type="button" id="new-note-button">New Note</button>
			</div>
			<div class="margin0 wth50" id="notes">
				<?php echo $person->displayNotes(); ?>
			</div>
		</div>
		<div id="javascript">
			<script type="text/javascript" src="frontEnd.js"></script>
		</div>
	</body>
</html>