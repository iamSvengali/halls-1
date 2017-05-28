<?php $restricted = ["users"=>"custodians"]; ?>
<?php require '../../php/master.php'; ?>

<!-- Functions and variables local to students -->
<?php require 'custodians_master.php'; ?>

<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<title>Damage Reports | Custodians | Halls of Residence</title>
		<?php include '../../php/links.php'; ?> <!-- This is all the style you get -->
	</head>
	<body class="container">

		<h1>School Halls of Residence</h1>
		<h2>Damage Report #<?=$report["id"]?></h2>

		<p>Damage report details are listed below.</p>

		<h5>Details</h5>
		<p>
			Students involved: <b><?=$reportStudents?></b><br>
			Workman assigned: <b><?=$reportWorkman["firstName"] . " " . $reportWorkman["lastName"]?></b><br>
			Issue category: <b><?=$ISSUES[$reportIssue["problemType"]]?></b><br><br>
			
			Student report: <b><em><?=$reportIssue["description"]?></em></b><br>
			Workman inspection notes: <b><em><?=$report["note"]?></em></b><br><br>

			Damage cost: <b>KES <?=number_format($report["cost"], 2)?></b>
		</p>

		<h5>Problem resolution</h5>
		<p>
			Room occupants need to pay for all damages in full to resolve the report. If a successful dispute is made, the report is marked as resolved and the workman has to complete the repairs ASAP.
		</p>
		
		<form action="./" method="post">
			<button type="submit" name="resolved" value="<?=$report['id']?>">Resolve Report</button>
		</form>

		<?php output(); ?>

		<p>
			<a href="damages">Back to reports</a>
			<a href="./">Back to dashboard</a>
			<a href="../../">Sign out</a>
		</p>

	</body>
</html>
