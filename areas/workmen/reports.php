<?php $restricted = ["users"=>"workmen"]; ?>
<?php require '../../php/master.php'; ?>

<!-- Functions and variables local to students -->
<?php require 'workmen_master.php'; ?>

<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<title>Maintenance Reports | Workmen | Halls of Residence</title>
		<?php include '../../php/links.php'; ?> <!-- This is all the style you get -->
	</head>
	<body class="container">

		<h1>School Halls of Residence</h1>
		<h2>Maintenance Reports</h2>

		<p>All issues assigned to you are listed below.</p>
		
		<form action="issue" method="post">
			<?=isset($issueList) ? $issueList : "<p>No issues found.</p>" ?>
		</form>

		<?php output(); ?>

		<p>
			<a href="./">Back to dashboard</a>
			<a href="../../">Sign out</a>
		</p>

	</body>
</html>
