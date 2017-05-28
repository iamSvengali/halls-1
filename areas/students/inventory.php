<?php $restricted = ["users"=>"students"]; ?>
<?php require '../../php/master.php'; ?>

<!-- Functions and variables local to students -->
<?php require 'students_master.php'; ?>

<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<title>Room Inventory | Students | Halls of Residence</title>
		<?php include '../../php/links.php'; ?> <!-- This is all the style you get -->
	</head>
	<body class="container">

		<h1>School Halls of Residence</h1>
		<h2>Room Inventory</h2>

		<p>Please fill out the fields below letting us know the number of items assigned to you in your room.</p>

		<form action="./" method="post">
			
			<?=$inventoryItems ? $inventoryFields : "--"?>
			<h3></h3> <!-- spacer -->
			<h5>Cross-check your entries!</h5>
			<p>Please ensure all your entries are accurate to prevent future issues during your check-out. If you are sure all the information is correct, click the button below.</p>
			<?php if (!$inventoryEntered) { ?>
				<button type="submit" name="inventory">Submit Inventory</button>
			<?php } ?>
			<h3></h3> <!-- spacer -->

		</form>

		<p>
			<a href="./">Back to dashboard</a>
			<a href="../../">Sign out</a>
		</p>

		<?php output(); ?>

	</body>
</html>
