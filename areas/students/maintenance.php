<?php $restricted = ["users"=>"students"]; ?>
<?php require '../../php/master.php'; ?>

<!-- Functions and variables local to students -->
<?php require 'students_master.php'; ?>

<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<title>Maintenance | Students | Halls of Residence</title>
		<?php include '../../php/links.php'; ?> <!-- This is all the style you get -->
	</head>
	<body class="container">

		<h1>School Halls of Residence</h1>
		<h2>Report Maintenance Issue</h2>

		<p>Please fill in the details of your issue.</p>

		<form action="./" method="post">
			<div class="row">
				<div class="four columns">
					<label>Room:</label>
					<input class="u-full-width" type="text" readonly title="You can only report an issue for your room." value="<?=$assignedRoom['id']?>">
				</div>
				<div class="four columns">
					<label>Occupant(s):</label>
					<input class="u-full-width" type="text" readonly value="<?=$roomOccupants?>">
				</div>
			</div>
			<div class="row">
				<div class="eight columns">
					<label>Nature of problem:</label>
					<select required class="u-full-width" name="problemType">
						<option value="lightbulb">Blown out light bulb</option>
						<option value="socket">Faulty/broken electrical socket</option>
						<option value="doorlock">Faulty/broken door lock</option>
						<option value="windowlock">Faulty/broken window lock</option>
						<option value="pane">Damaged window pane</option>
						<option value="damp">Damp problem</option>
						<option value="tiles">Dislodged floor tiles</option>
						<option value="structure">Structurual problem</option>
						<option value="superficial">Superficial damage (like plaster cracks)</option>
						<option value="furniture">Broken furniture</option>
					</select>
				</div>
			</div>
			<div class="row">
				<div class="eight columns">
					<label>Comments / Description:</label>
					<textarea required name="description" class="u-full-width" maxlength="250"></textarea>
				</div>
			</div>
			<h3></h3> <!-- spacer -->
			<h5>Before you submit...</h5>
			<p>If during inspection we find deliberate or malicious damage in your property we will investigate to ascertain who is responsible and arrange for them to be charged for the repair. We do not charge students for damage that is considered fair wear and tear.</p>
			<h3></h3> <!-- spacer -->

			<!-- Hidden inputs -->
			<input type="hidden" name="room" value="<?=$assignedRoom['id']?>">

			<button type="submit" name="maintenance">Submit Report</button>

		</form>
		<h3></h3> <!-- spacer -->

		<p>
			<a href="./">Back to dashboard</a>
			<a href="../../">Sign out</a>
		</p>

		<?php output(); ?>

	</body>
</html>
