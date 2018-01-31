
<html>
	<head>
		<title>PHP Test</title>
		<?php include('ClassesPractice.php'); ?>
	</head>
	<body>
		<?php echo "<p>Here is a sample of some text.</p>\n"; ?>
		<?php 
			$Zoe = new Person("6'2", "140 lbs.", "male", "Zoe");
			$Zoe->echoPerson();
		?>
	</body>
</html>

