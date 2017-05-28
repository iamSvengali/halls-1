<?php $restricted = ["users"=>"custodians"]; ?>
<?php require '../../php/master.php'; ?>

<!-- Functions and variables local to students -->
<?php require 'custodians_master.php'; ?>

<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<title>Missing/Damaged Items | Custodians | Halls of Residence</title>
		<?php include '../../php/links.php'; ?> <!-- This is all the style you get -->
	</head>
	<body class="container">

		<h1>School Halls of Residence</h1>
		<h2>Missing/Damaged Items Report</h2>

		<p>Please fill out the form below below with a list of <em>all</em> missing items.</p>
		
		<form action="./" method="post">
			<div class="row">
				<div class="four columns">
					<label>Student:</label>
					<select name="stdId" class="u-full-width">
						<?=isset($studentSelection) ? $studentSelection : "<option>No students found</option>"?>
					</select>
				</div>
				<div class="four columns">
					<label>Repair/Replacement costs (KES):</label>
					<input required type="number" name="cost" class="u-full-width">
				</div>
			</div>
			<div class="row">
				<div class="eight columns">
					<label>Items:</label>
					<textarea name="note" required class="u-full-width" placeholder="List missing or damaged items here."></textarea>
				</div>
			</div>
			<button type="submit" name="missing">Submit Report</button>
		</form>

		<?php output(); ?>

		<p>
			<a href="schedule">Back to schedule</a>
			<a href="./">Back to dashboard</a>
			<a href="../../">Sign out</a>
		</p>

	</body>
</html>
