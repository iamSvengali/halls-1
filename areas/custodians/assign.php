<?php $restricted = ["users"=>"custodians"]; ?>
<?php require '../../php/master.php'; ?>

<!-- Functions and variables local to students -->
<?php require 'custodians_master.php'; ?>

<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<title>Room Assignment | Custodians | Halls of Residence</title>
		<?php include '../../php/links.php'; ?> <!-- This is all the style you get -->
	</head>
	<body class="container">

		<h1>School Halls of Residence</h1>
		<h2>Room Assignment</h2>

		<p>All available rooms of the student's preference are listed below. Please make a selection.</p>
		
		<form action="./" method="post">
			<input type="hidden" name="student" value="<?= $student; ?>">
			<?=isset($roomList) && $roomAvailable ? $roomList : "<p>No rooms available.</p>"?>
			<?php if (isset($roomList) && $roomAvailable) { ?>
				<button type="submit" name="assignRoom" value="<?=$_POST["appId"]?>">Assign Room</button>
			<?php } else { ?>
				<label>Note to student:</label>
				<textarea name="note" maxlength="250">Your application was declined because the room type you selected was not available.</textarea><br>
				<button type="submit" name="declineRoom" value="<?=$_POST["appId"]?>">Decline Application</button>
				<h3></h3> <!-- spacer -->
			<?php } ?>
		</form>

		<?php output(); ?>

		<p>
			<a href="applications">Start over</a>
			<a href="./">Back to dashboard</a>
			<a href="../../">Sign out</a>
		</p>

	</body>
</html>
