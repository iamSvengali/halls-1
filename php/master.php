<?php

// Init session
session_start();

// A bunch of useful PHP functions
require 'functions.php';

// Project global variables
require 'globals.php';

// Form processing logic
require 'form-processing.php';

// GLobal SQL connection to be used in contextual queries
$CONNECTION = CONNECT($SV, $UN, $PW, $DB);

// Area privacy and hotlink prevention
if (isset($restricted)){
	if (!isset($_SESSION["id"])){ // not logged in
		header("location: /" . $DIR); // redirect to index
	} else {
		if ($_SESSION["cat"] != $restricted["users"]){ // not in same user group
			header("location: /" . $DIR . "/areas/" . $_SESSION["cat"]); // redirect to user-group's index
		}
	}
}

// Return user data array
function userData(){
	$connection = $GLOBALS['CONNECTION'];
	$result = query($connection, $_SESSION["cat"],  "id", $_SESSION["id"]);
	return $result[0];
}

?>
