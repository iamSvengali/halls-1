<?php require 'php/master.php'; ?>
<?php session_destroy(); ?>

<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<title>Register | <?=ucwords($_GET['c'])?> | Halls of Residence</title>
		<?php include 'php/links.php'; ?> <!-- This is all the style you get -->
	</head>
	<body class="container">

		<h1>School Halls of Residence</h1>
		<h2><?=ucwords($_GET['c'])?> Registration</h2>

		<p>Enter your details to create your account.</p>
		<form action="" method="post">

			<div class="row">
				<div class="three columns">
					<label>First Name: </label>
					<input autocomplete="off" type="text" required name="firstName" value=""/>
				</div>
				<div class="three columns">
					<label>Last Name: </label>
					<input autocomplete="off" type="text" required name="lastName" value=""/>
				</div>
			</div>

			<div class="row">
				<div class="six columns">
					<label>Email Address: </label>
					<input autocomplete="off" class="u-full-width" type="email" required name="email" value=""/>
				</div>
			</div>

			<div class="row">
				<div class="three columns">
					<label>Username: </label>
					<input autocomplete="off" type="text" required name="username" value=""/>
				</div>
				<div class="three columns">
					<label>Password:</label>
					<input type="password" required name="password" value=""/>
				</div>
			</div>

			<button class="button-primary" type="submit" name="register" value="<?=$_GET['c']?>">Register</button>
			<a href="sign-in?c=<?=$_GET['c']?>" class="button">Sign In</a>
		</form>

		<?php output(); ?>

		<p><a href="./">Homepage</a></p>

	</body>
</html>
