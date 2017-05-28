<?php $restricted = ["users"=>"custodians"]; ?>
<?php require '../../php/master.php'; ?>

<!-- Functions and variables local to students -->
<?php require 'custodians_master.php'; ?>

<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<title>Damage Reports | Custodians | Halls of Residence</title>
		<?php include '../../php/links.php'; ?> <!-- This is all the style you get -->
	</head>
	<body class="container">

		<h1>School Halls of Residence</h1>
		<h2>Damage Reports</h2>

		<p>All damage reports are listed below from the newest to the oldest.</p>
		
		<form action="report" method="post">
			<?=isset($damageList) ? $damageList : "<p>No damage reports found.</p>" ?>
		</form>

		<?php output(); ?>

		<p>
			<a href="./">Back to dashboard</a>
			<a href="../../">Sign out</a>
		</p>

	</body>
</html>
