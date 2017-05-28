<?php $restricted = ["users"=>"custodians"]; ?>
<?php require '../../php/master.php'; ?>

<!-- Functions and variables local to students -->
<?php require 'custodians_master.php'; ?>

<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<title>Missing/Damaged Items Payment | Custodians | Halls of Residence</title>
		<?php include '../../php/links.php'; ?> <!-- This is all the style you get -->
	</head>
	<body class="container">

		<h1>School Halls of Residence</h1>
		<h2>Missing/Damaged Items Payment</h2>

		<p>All students with missing/damaged items payments due are listed below.</p>
		
		<form action="./" method="post">
			<?=isset($paymentList) ? $paymentList : "<p>No students found.</p>" ?>
			<?php if (isset($paymentList)) { ?>
				<button type="submit" name='paid'>Receive payment</button>
			<?php } ?>
		</form>
		
		<?php output(); ?>

		<p>
			<a href="./">Back to dashboard</a>
			<a href="../../">Sign out</a>
		</p>

	</body>
</html>
