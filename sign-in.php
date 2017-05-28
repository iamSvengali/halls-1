<?php require 'php/master.php'; ?>
<?php session_destroy(); ?>

<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<title>Sign In | <?=ucwords($_GET['c'])?> | Halls of Residence</title>
		<?php include 'php/links.php'; ?> <!-- This is all the style you get -->
	</head>
	<body class="container">

		<h1>School Halls of Residence</h1>
		<h2><?=ucwords($_GET['c'])?> Sign In</h2>

		<p>Enter your details to log in to your dashboard.</p>
		<form action="" method="post">
			<div class="row">
				<div class="three columns">
					<label>Username: </label>
					<input autocomplete="off" type="text" required name="username" value=""/>
				</div>
				<div class="three columns">
					<label>Password:</label>
					<input type="password" required name="password" value=""/><br/>
				</div>
			</div>

			<button class="button-primary" type="submit" name="sign-in" value="<?=$_GET['c']?>">Sign In</button>
			<a href="register?c=<?=$_GET['c']?>" class="button">Register</a>

			<h3></h3>
		</form>

		<?php output(); ?>

		<p><a href="./">Homepage</a></p>

	</body>
</html>
