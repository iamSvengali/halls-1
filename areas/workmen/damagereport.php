<?php $restricted = ["users"=>"workmen"]; ?>
<?php require '../../php/master.php'; ?>

<!-- Functions and variables local to students -->
<?php require 'workmen_master.php'; ?>

<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<title>Student Damage Report | Workmen | Halls of Residence</title>
		<?php include '../../php/links.php'; ?> <!-- This is all the style you get -->
	</head>
	<body class="container">

		<h1>School Halls of Residence</h1>
		<h2>Student Damage Report</h2>

		<p>Please fill out the form below below.</p>
		
		<form action="./" method="post">
			<div class="row">
				<div class="four columns">
					<label>Issue:</label>
					<select name="issId" class="u-full-width">
						<?=isset($issueSelection) ? $issueSelection : "<option>No issues found</option>"?>
					</select>
				</div>
				<div class="four columns">
					<label>Damage costs (KES):</label>
					<input required type="number" name="cost" class="u-full-width">
				</div>
			</div>
			<div class="row">
				<div class="eight columns">
					<label>Notes:</label>
					<textarea name="note" required class="u-full-width" placeholder="Explain damages being reported here."></textarea>
				</div>
			</div>
			<button type="submit" name="damagereport">Submit Report</button>
		</form>

		<?php output(); ?>

		<p>
			<a href="./">Back to dashboard</a>
			<a href="../../">Sign out</a>
		</p>

	</body>
</html>
