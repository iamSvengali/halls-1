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
		<h2>Confirm your Check-out</h2>

		<p>Your appointment is to be scheduled on <b><?=isset($confirmDate) ? date_format(date_create($confirmDate), "l M d, Y") : "--"?></b> at <b><?=isset($confirmTime) ? $confirmTime : "--"?></b>.<br>
		At the time of your appointment, custodian, <b><?=isset($c) ? $c["firstName"] . " " . $c["lastName"] : "--"?></b>, will come to your room to perform a final inventory check before signing your check-out documents.</p>

		<?php if(isset($confirmDate)){ ?>
			<h5>Before you leave:</h5>
			<p>It is courteous to clean your room and fix any minor issues you caused. Also, make sure any damaged or missing items are replaced or paid for.</p>

			<h5>You're ready to submit!</h5>
			<p>All your information is already on file so all you have to do is click the button below.</p>

			<form action="./" method="post">
				<input type="hidden" name="custodian" value="<?=$c['id']?>">
				<input type="hidden" name="xdate" value="<?=$confirmDate?>">
				<input type="hidden" name="xtime" value="<?=$confirmTime?>">
				<button type="submit" name="checkoutconfirm">Confirm Appointment</button>
			</form>

		<?php } ?>

		<h2></h2> <!-- spacer -->

		<p>
			<a href="checkout">Start over</a>
			<a href="./">Back to dashboard</a>
			<a href="../../">Sign out</a>
		</p>

		<?php output(); ?>

	</body>
</html>
