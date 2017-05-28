<?php $restricted = ["users"=>"students"]; ?>
<?php require '../../php/master.php'; ?>

<!-- Functions and variables local to students -->
<?php require 'students_master.php'; ?>

<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<title>Application Status | Students | Halls of Residence</title>
		<?php include '../../php/links.php'; ?> <!-- This is all the style you get -->
	</head>
	<body class="container">

		<h1>School Halls of Residence</h1>
		<h2>Application Status</h2>

		<?php
		if ($student["checkedIn"]){
			echo "<p><b>You are already checked in.</b><p>";
		} else {
			if ($appinfo) {
				if ($appinfo["status"] != "approved" && $appinfo["status"] != "denied") {?>
					<p>Your application was <b><?=$appinfo["status"]?></b> as of <b><?=date_format(date_create($appinfo["lastModified"]),"h:i A, l F d")?></b>. Please check back later for changes.</p><?php
				} else {
					if ($appinfo["status"] == "approved"){?>
						<p><b>Congratulations!</b> Your application was approved! You have been assigned to <b>Room <?=$assignedRoom["id"]?></b> located on the <?=$assignedRoom["location"]?>.</p>
						<p>Schedule your check-in <a href="checkin">here.</a></p><?php
					} else {?>
						<p>Sorry, your application was denied. This does not mean you have been denied residence at the halls. A custodian left this note:<br><b><em><?=$appinfo["note"]?></em></b></p>
						<p>If you would like to apply again, click <a href="app">here.</a></p><?php
					}
				}
			} else {
				?>
				<p>You do not have a current application. Please make one <a href="app">here.</a></p>
				<?php
			}
		}
		?>

		<h2></h2> <!-- spacer -->

		<p>
			<a href="./">Back to dashboard</a>
			<a href="../../">Sign out</a>
		</p>

		<?php output(); ?>

	</body>
</html>
