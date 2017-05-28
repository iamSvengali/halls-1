<?php $restricted = ["users"=>"students"]; ?>
<?php require '../../php/master.php'; ?>

<!-- Functions and variables local to students -->
<?php require 'students_master.php'; ?>

<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<title>Maintenance | Students | Halls of Residence</title>
		<?php include '../../php/links.php'; ?> <!-- This is all the style you get -->
	</head>
	<body class="container">

		<h1>School Halls of Residence</h1>
		<h2>Maintenance Status</h2>

		<?= isset($claimList) ? $claimList : "No claims found." ?>

		<h2></h2> <!-- spacer -->

		<p>
			<a href="./">Back to dashboard</a>
			<a href="../../">Sign out</a>
		</p>

		<?php output(); ?>

	</body>
</html>
