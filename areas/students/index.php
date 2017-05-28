<?php $restricted = ["users"=>"students"]; ?>
<?php require '../../php/master.php'; ?>

<!-- Functions and variables local to students -->
<?php require 'students_master.php'; ?>

<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<title>Dashboard | Students | Halls of Residence</title>
		<?php include '../../php/links.php'; ?> <!-- This is all the style you get -->
	</head>
	<body class="container">

		<h1>School Halls of Residence</h1>
		<h2>Student Dashboard</h2>

		<p>Hi, <?=$student["firstName"]?>!</p>

		<?php output(); ?>

		<!-- If they have an outstanding damage report -->
		<?= $hasDamageReport ? "<p><b>You have an oustanding damage report. Please see a custodian as soon as possible.</b></p>" : "" ?>

		<h5>Tasks:</h5>
		<ul>
			<!-- If they don't have an application -->
			<?php if (!$appinfo) {?><li><a href="app">Apply for accomodation</a></li><?php } ?>

			<!-- If they have an application and have not scheduled their check in  -->
			<?php if ($appinfo && !$checkInScheduled) {?><li><a href="appstatus">Check application status</a></li><?php } ?>

			<!-- If their application is approved and they have not scheduled their check in -->
			<?php if ($appinfo["status"]=="approved" && !$checkInScheduled) {?><li><a href="checkin">Schedule your check-in</a></li><?php } ?>

			<!-- If they are waiting for their check in date -->
			<?php if (!$student["checkedIn"] && $checkInScheduled) { ?> <li>Your check in is scheduled for <b><?=$checkInScheduled["time"] . " on " . date_format(date_create($checkInScheduled["date"]), "l F d, Y")?>.</b></li> <?php } ?>

			<!-- If they are checked in and have not scheduled their check out -->
			<?php if ($inventoryEntered && $student["checkedIn"] == 1 && !$hasDamageReport && !$checkOutScheduled) {?><li><a href="checkout">Schedule your check-out</a></li><?php } ?>

			<!-- If they are waiting for their check in date -->
			<?php if ($student["checkedIn"] && $checkOutScheduled) { ?> <li>Your check out is scheduled for <b><?=$checkOutScheduled["time"] . " on " . date_format(date_create($checkOutScheduled["date"]), "l F d, Y")?>.</b></li> <?php } ?>

			<!-- If they are checked in and have not entered their inventory -->
			<?php if ($student["checkedIn"] == 1 && !$inventoryEntered) {?><li><a href="inventory">Fill in your room inventory</a></li><?php } ?>

			<!-- If they are checked in and have entered their inventory -->
			<?php if ($student["checkedIn"] == 1 && $inventoryEntered) {?><li><a href="view-inventory">View your room inventory</a></li><?php } ?>

			<!-- If they are checked in -->
			<?php if ($inventoryEntered && $student["checkedIn"] == 1) {?><li><a href="maintenance">Report a maintenance issue</a></li><?php } ?>

			<!-- If they are checked in -->
			<?php if ($inventoryEntered && $student["checkedIn"] == 1) {?><li><a href="maintenancestatus">Check maintenance issue status</a></li><?php } ?>
		</ul>

		<p>
			<a href="../../">Sign out</a>
		</p>

	</body>
</html>
