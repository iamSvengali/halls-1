<?php $restricted = ["users"=>"students"]; ?>
<?php require '../../php/master.php'; ?>

<!-- Functions and variables local to students -->
<?php require 'students_master.php'; ?>

<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<title>Schedule Check-in | Students | Halls of Residence</title>
		<?php include '../../php/links.php'; ?> <!-- This is all the style you get -->
	</head>
	<body class="container">

		<h1>School Halls of Residence</h1>
		<h2>Schedule Check-in</h2>

		<p>Date selected: <b><?=isset($dateSelected) ? $dateSelected : "--"?></b>.<br>Please select a time slot below.</p>

		<form action="checkinconfirm" method="post">
			<input type="hidden" name="date" value="<?=$_POST['date']?>">
			<?=isset($timeList) ? $timeList : "--"?>
		</form>



		<p>
			<a href="checkin">Start over</a>
			<a href="./">Back to dashboard</a>
			<a href="../../">Sign out</a>
		</p>

		<?php output(); ?>

	</body>
</html>
