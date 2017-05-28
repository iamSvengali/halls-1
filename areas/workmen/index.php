<?php $restricted = ["users"=>"workmen"]; ?>
<?php require '../../php/master.php'; ?>

<!-- Functions and variables local to students -->
<?php require 'workmen_master.php'; ?>

<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<title>Dashboard | Workmen | Halls of Residence</title>
		<?php include '../../php/links.php'; ?> <!-- This is all the style you get -->
	</head>
	<body class="container">

		<h1>School Halls of Residence</h1>
		<h2>Workmen Dashboard</h2>

		<p>Hi, <?=$workman["firstName"]?>!</p>

		<?php output(); ?>

		<h5>Tasks:</h5>
		<ul>
			<li><a href="reports">View maintenance reports</a></li>
		</ul>
		<ul>
			
		</ul>

		<p><a href="/hallsreboot/">Sign out</a></p>

	</body>
</html>
