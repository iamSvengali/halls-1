<?php $restricted = ["users"=>"workmen"]; ?>
<?php require '../../php/master.php'; ?>

<!-- Functions and variables local to students -->
<?php require 'workmen_master.php'; ?>

<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<title>Maintenance Issue | Workmen | Halls of Residence</title>
		<?php include '../../php/links.php'; ?> <!-- This is all the style you get -->
	</head>
	<body class="container">

		<h1>School Halls of Residence</h1>
		<h2>Maintenance Issue</h2>

		<p>This issue (Issue #<?=$issue["id"]?>) has been reported <?=$issue["count"]?> time(s).</p>

		<h5>Details</h5>
		<p>
			Room: <b><?=$room["id"]?></b><br>
			Location: <b><?=$room["location"]?></b><br>
			Reporting student: <b><?=$student["firstName"] . " " . $student["lastName"]?></b><br>
			Issue category: <b><?=$ISSUES[$issue["problemType"]]?></b><br>
			Description: <b><em><?=$issue["description"]?></em></b>
		</p>
		
		<form action="" method="post">
			<?php if ($issue["status"] == "acknowledged"){ ?>
				<h5>How to begin</h5>
				<p>Click the button below and then proceed to the student's room.</p>
				<button type="submit" name="start" value="<?=$issue['id']?>">Start Work</button>
			<?php } elseif ($issue["status"] == "complete") { ?>
				<p><b>This issue was already handled.</b></p>
			<?php } else { 
				if (!$issueReported) { ?>
					<h5>Inspection checklist</h5>
					<p>Use the checklist below to perform a preliminary inspection.</p>
					<ul>
						<li>Damage appears normal due to either a faulty item, or normal wear and tear</li>
						<li>Fundamental item parts are missing (like socket covers, window locks)</li>
						<li>Item is part of room provisions</li>
					</ul>
					<p>If any of the statements above does not apply to the current issue, please make a <a href="damagereport">student damage report</a></p>
					<p>Otherwise, click the button below when you complete work on the issue.</p>
					<button type="submit" name="complete" value="<?=$issue['id'];?>">Complete Work</button>
				<?php } else {
					if ($issueReported["resolved"] == "0") { ?>
						<p>* The student damage report for this issue has not been resolved.</p>
					<?php } else { ?>
						<p>* The student damage report for this issue has been resolved. Click the button below once completed work on this issue.</p>
						<button type="submit" name="complete" value="<?=$issue['id'];?>">Complete Work</button>
					<?php }
				} ?>
			<?php } ?>
		</form>

		<?php output(); ?>

		<p>
			<a href="reports">Back to reports</a>
			<a href="./">Back to dashboard</a>
			<a href="../../">Sign out</a>
		</p>

	</body>
</html>
