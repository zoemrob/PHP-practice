<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<?php require('provide-date.php'); ?>
		<link href="style.css" rel="stylesheet">
	</head>
	<body>
		<div class="header">
			<h1>Co-Worker Journal</h1>
		</div>
		<div class="name">
			<span>
			<?echo $Zoe->personName . ' Robertson'?>	
			 </span>
		</div>
		<div class="gender">
			<p>Gender: <?echo ucfirst($Zoe->sex); ?></p>
		</div>
		<div class="age">
			<p>Age: </p>
		</div>
		<div class="event-log">
			<ul>
				<li>Item 1</li>
				<li>Item 2</li>
				<li>Item 3</li>
				<li>Item 4</li>
				<li>Item 5</li>
			</ul>
		</div>
	</body>
</html>