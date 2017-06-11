<?php $restricted = ["users"=>"students"]; ?>
<?php require '../../php/master.php'; ?>

<!-- Functions and variables local to students -->
<?php require 'students_master.php'; ?>

<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<title>Submit Application | Students | Halls of Residence</title>
		<?php include '../../php/links.php'; ?> <!-- This is all the style you get -->
	</head>
	<body class="container">

		<h1>School Halls of Residence</h1>
		<h2>Submit your Application</h2>

		<p>You selected the <b><?=isset($room) ? ucwords($room["name"]) : "--"?></b> room.<br>This primarily costs <b>KES <?=isset($room) ? number_format($room["rent"], 2) : "--"?></b> each year. If you need any special aid with the rent, please contact a custodian.</p>

		<?php if (isset($room)) {?>
			<h5>You're ready to submit!</h5>
			<p>All your information is already on file so all you have to do is click the button below.</p>

			<form action="./" method="post">
				<input type="hidden" name="room" value="<?=$room['id']?>">
				<button type="submit" name="appconfirm">Submit Application</button>
			</form>
		<?php } ?>

		<h2></h2> <!-- spacer -->

		<p>
			<a href="app">Start over</a>
			<a href="./">Back to dashboard</a>
			<a href="../../">Sign out</a>
		</p>

		<?php output(); ?>

	</body>
</html>
