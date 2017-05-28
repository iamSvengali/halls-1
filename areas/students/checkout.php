<?php $restricted = ["users"=>"students"]; ?>
<?php require '../../php/master.php'; ?>

<!-- Functions and variables local to students -->
<?php require 'students_master.php'; ?>

<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<title>Schedule Check-out | Students | Halls of Residence</title>
		<?php include '../../php/links.php'; ?> <!-- This is all the style you get -->
	</head>
	<body class="container">

		<h1>School Halls of Residence</h1>
		<h2>Schedule Check-out</h2>

		<p>Please select a date in the calendar below and click Next</p>

		<form action="" method="post">
			<?=$student["checkedIn"] ? $dateList : "<p><b>You cannot schedule a check out before you check in.</b></p>"?>
			<?php if ($student["checkedIn"]) { ?>
				<button type="submit" name="checkout" value="selected">Next</button>
			<?php } ?>
		</form>

		<p>
			<a href="./">Back to dashboard</a>
			<a href="../../">Sign out</a>
		</p>

		<?php output(); ?>

	</body>
</html>
