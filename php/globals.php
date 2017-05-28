<?php

// Variables global to the current project

// For server connection
$SV = "localhost"; // server name
$UN = "root"; // user name
$PW = ""; // password
$DB = "hallsreboot"; // database name

// File paths
$ROOT = "/";
$DIR = "hallsreboot"; // leave blank if hosting online in root of server
$PAGE = $_SERVER['REQUEST_URI']; // what page we're on
$MV = true; // a htaccess configuration that allows page access without specifying a file extension

// Timezone (Africa/Nairobi || America/Los_Angeles)
$TIMEZONE = "America/Los_Angeles";
date_default_timezone_set ( $TIMEZONE );

// Schedule nits
$STARTTIME = 8; // time office hours start (in 24H)
$ENDTIME = 16; // time office hours end (in 24H)
$SLOTS = 4; // number of slots per hour
$BREAKTIME = 18; // lunch time, assuming 1 hour break (no lunch)

// Maintenance nits
$ISSUES = [
	"lightbulb" => "Blown out light bulb",
	"socket" => "Faulty/broken electrical socket",
	"doorlock" => "Faulty/broken door lock",
	"windowlock" => "Faulty/broken window lock",
	"pane" => "Damaged window pane",
	"damp" => "Damp problem",
	"tiles" => "Dislodged floor tiles",
	"structure" => "Structurual problem",
	"superficial" => "Superficial damage (like plaster cracks)",
	"furniture" => "Broken furniture"
];

// Info, errors, and general output
$OUTPUT = [];

// adds to output
function output($data = null){

	if ($data){
		array_push($GLOBALS['OUTPUT'], $data); // add to output

	} else { // then print

		if ($GLOBALS['OUTPUT']){

			// prints output
			echo "<code>";
			for ($i = 0; $i < sizeof($GLOBALS['OUTPUT']); $i++) {
				echo $GLOBALS['OUTPUT'][$i] . "\n";
			}
			echo "</code>";

		}

	}

}

?>
