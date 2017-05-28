<?php $restricted = ["users"=>"custodians"]; ?>
<?php require '../../php/master.php'; ?>

<!-- Functions and variables local to students -->
<?php require 'custodians_master.php'; ?>

<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<title><?=$appTitle?> | Custodians | Halls of Residence</title>
		<?php include '../../php/links.php'; ?> <!-- This is all the style you get -->
	</head>
	<body class="container">

		<h1>School Halls of Residence</h1>
		<h2>Student <?= isset($appTitle) ? $appTitle : "Appointment"?></h2>

		<p>Once you are ready to complete, click the Complete button.</p>

		<h5>Student Information</h5>
		<p>
			Name: <b><?=isset($appStudent) ? $appStudent["firstName"] . " " . $appStudent["lastName"] : "--"?></b><br>
			Room: <b><?=isset($appRoom) ? $appRoom["id"] : "--"?></b><br>
			Location: <b><?=isset($appRoom) ? $appRoom["location"] : "--"?></b>
		</p>
		
		<form action="./" method="post">
			<?php if ($appType == "checkin") { ?>
				<h5>Requirements</h5>
				<p>Student needs to provide a bank deposit slip with an amount of at least <b>KES <?=number_format($rent, 2)?></b> towards rent. Student also needs to have brought along a portion/all of their personal belongings.</p>

				<p>Once you have verified this information and received the bank deposit slip, click the Complete button below.</p>
				<button type="submit" name="appcomplete" value="<?=$appId?>">Complete <?=isset($appType) ? $appType : "--"?></button>
			<?php } else {
				if ($hasDamages) { ?> <!-- has unresolved damage report -->
					<h5>Damage Reports</h5>
					<p>The student has some unresolved damage reports. To resolve them now, go to <a href="damages">Damage reports</a></p>
				<?php } else { ?>
					<h5>Student Inventory</h5>
					<?php if ($hasMissingItems) { ?> <!-- has missing items, duh -->
						<p>The student has some missing/damaged items that have not been paid for. If the student is ready to make a payment, go to <a href="payment">Make a payment</a></p>
					<?php } else { ?> <!-- all good (ish) -->
						<p>The student's room should have/meet the following requirements.</p>
						<?=isset($inventoryList) ? $inventoryList : "--"?>
						<p>If any of the items above is missing/damaged, please file a <a href="missing">Missing/Damaged items report</a></p>
						<p>Otherwise, click the button below to check out the student.</p>
						<input type="hidden" name="appointmentType" value='<?=isset($appType) ? $appType : "--"?>'>
						<button type="submit" name="appcomplete" value="<?=$appId?>">Complete <?=isset($appType) ? $appType : "--"?></button>
						
					<?php }
				}
			} ?>
		
		</form>

		<h3></h3> <!-- space -->

		<?php output(); ?>

		<p>
			<a href="schedule">Back to schedule</a>
			<a href="./">Back to dashboard</a>
			<a href="../../">Sign out</a>
		</p>

	</body>
</html>
