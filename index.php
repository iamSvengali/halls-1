<?php require 'php/master.php'; ?>
<?php session_destroy(); ?>

<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<title>Halls of Residence</title>
		<?php include 'php/links.php'; ?> <!-- This is all the style you get -->
	</head>
	<body class="container">

		<h1>School Halls of Residence</h1>
		<h2>Home</h2>

		<p>I am a:</p>
		<ul>
			<li><a href="sign-in?c=students">Student</a></li>
			<li><a href="sign-in?c=custodians">Custodian</a></li>
			<li><a href="sign-in?c=workmen">Workman</a></li>
		</ul>

		<?php output(); ?>

	</body>
</html>
