<?php $restricted = ["users"=>"custodians"]; ?>
<?php require '../../php/master.php'; ?>

<!-- Functions and variables local to students -->
<?php require 'custodians_master.php'; ?>

<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<title>Dashboard | Custodians | Halls of Residence</title>
		<?php include '../../php/links.php'; ?> <!-- This is all the style you get -->
	</head>
	<body class="container">

		<h1>School Halls of Residence</h1>
		<h2>Custodian Dashboard</h2>

		<p>Hi, <?=$custodian["firstName"]?>!</p>

		<?php output(); ?>

		<h5>Tasks:</h5>
		<ul>
			<li><a href="applications">View student applications</a></li>
			<li><a href="schedule">View check-in/check-out schedule</a></li>
			<li><a href="maintenance">View maintenance issues</a></li>
			<li><a href="damages">View damage reports</a></li>
		</ul>

		<p>
			<a href="../../">Sign out</a>
		</p>

	</body>
</html>
