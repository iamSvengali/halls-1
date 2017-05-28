<?php

// Check if any data is posted
if (isset($_POST)){

	// Connect to database
	$connection = CONNECT($SV, $UN, $PW, $DB);

	// Sign in request
	if (isset($_POST["sign-in"])){

		$category = $_POST["sign-in"]; // category of user. Tables are named to match.
		$username = $_POST["username"]; // posted username, duh
		$password = $_POST["password"]; // posted password, duh

		$encrypted = sha1(md5($password));

		// Logs in
		logIn($username, $encrypted, $category, $connection);

	} elseif (isset($_POST["register"])){

		$category = $_POST["register"]; // category of user. Tables are named to match.
		$firstname = $_POST["firstName"]; // posted firstname, duh
		$lastname = $_POST["lastName"]; // posted lastname, duh
		$email = $_POST["email"]; // posted email, duh
		$username = $_POST["username"]; // posted username, duh
		$password = $_POST["password"]; // posted password, duh

		$encrypted = sha1(md5($password));

		// Adds data to db
		insert($connection, $category, ["firstName", "lastName", "email", "username", "password"], [$firstname, $lastname, $email, $username, $encrypted]);

		// Logs in
		logIn($username, $encrypted, $category, $connection);

	}

	// Closes database connection
	disconnect($connection);

}

function logIn($username, $encrypted, $category, $connection){
	// Queries the database for the info
	$result = query($connection, $category,  ["username", "password"], [$username, $encrypted]);

	// Check our results
	if (!$result){
		// Incorrect or inexsitent credentials
		output("There was a problem with the username/password you entered.");

	} else {
		// Set session ID, then routing will happen after execution
		$_SESSION["id"] = $result[0]["id"];
		$_SESSION["cat"] = $category;

		$DIR = $GLOBALS['DIR'];

		header("location: /" . $DIR . "/areas/" . $_SESSION["cat"]);
		exit();

	}
}

?>
