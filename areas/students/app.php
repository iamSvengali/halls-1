<?php $restricted = ["users"=>"students"]; ?>
<?php require '../../php/master.php'; ?>

<!-- Functions and variables local to students -->
<?php require 'students_master.php'; ?>

<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<title>Apply for Accomodation | Students | Halls of Residence</title>
		<?php include '../../php/links.php'; ?> <!-- This is all the style you get -->
	</head>
	<body class="container">

		<h1>School Halls of Residence</h1>
		<h2>Apply for Accomodation</h2>

		<p>We offer various types of rooms. Please make a selection and click Next</p>

		<form action="appconfirm" method="post">
			<?=$appinfo && $appinfo["status"] != "denied" ? "<p><b>You already applied for a room.</b></p>" : $roomList?>
			<?php if (!$appinfo || $appinfo["status"] == "denied") { ?>
				<button type="submit" name="app" value="selected">Next</button>
			<?php } ?>
		</form>



		<p>
			<a href="./">Back to dashboard</a>
			<a href="../../">Sign out</a>
		</p>

		<?php output(); ?>

	</body>
</html>
