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
		<h2>Confirm your Check-in</h2>

		<p>Your appointment is to be scheduled on <b><?=isset($confirmDate) ? date_format(date_create($confirmDate), "l M d, Y") : "--"?></b> at <b><?=isset($confirmTime) ? $confirmTime : "--"?></b>.<br>
		At the time of your appointment, you will meet with custodian, <b><?=isset($c) ? $c["firstName"] . " " . $c["lastName"] : "--"?></b>, in the SWA building.</p>

		<h5>What to bring:</h5>
		<p>Bring your official bank deposit slip for the initial rent payment as well as your student identification card. You should also have a portion/all of your required personal belongings to make the move in smooth.</p>

		<?php if (isset($confirmDate)){ ?>
			<h5>You're ready to submit!</h5>
			<p>All your information is already on file so all you have to do is click the button below.</p>

			<form action="./" method="post">
				<input type="hidden" name="custodian" value="<?=$c['id']?>">
				<input type="hidden" name="xdate" value="<?=$confirmDate?>">
				<input type="hidden" name="xtime" value="<?=$confirmTime?>">
				<button type="submit" name="checkinconfirm">Confirm Appointment</button>
			</form>
		<?php } ?>

		<h2></h2> <!-- spacer -->

		<p>
			<a href="checkin">Start over</a>
			<a href="./">Back to dashboard</a>
			<a href="../../">Sign out</a>
		</p>

		<?php output(); ?>

	</body>
</html>
