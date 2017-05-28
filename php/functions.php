<?php

// Any functions with names in all CAPS are deadly
// This means errors within them will terminate the program

// Checks if provided url is main index
function isHomePage($url, $root, $directory, $multiviews = false){

	if ($root[strlen($root) - 1] != "/") $root .= "/"; // adds a slash at the end of the root url
	if ($directory[strlen($directory) - 1] != "/") $directory .= "/"; // adds a slash at the end of the directory name

	$folder = $root . $directory; // childs the folder to the root
	$index = $directory . "index"; // childs the index to the folder

	if (!$multiviews) $index .= ".php";

	if ($url == $root || $url == $directory || $url == $index) return true; // url is the home page

	return false; // not home page

}

// Creates an SQL Database connection
function CONNECT($server, $user, $password, $database){

	$connection = new mysqli($server, $user, $password, $database); // establishes connection
	if ($connection->connect_error) die($cn->connect_error); // tests connection

	return $connection;

}

// Queries a table for provided data and retuns data
function query($connection, $tableName, $fields = null, $input = null, $sortField = null, $sortOrder = "DESC"){

	// First part of SQL Statement
	$sql = "SELECT * FROM " . $tableName;

	// If criteria is provided
	if ($fields && $input) $sql .=" WHERE ";

	if (is_array($fields)){
		// Loop through fields and add criteria to statement
		for ($i = 0; $i < sizeof($input); $i++) {
			$sql .= $fields[$i] . " = '" . $input[$i] . "'";
			if (sizeof($input) - $i > 1) $sql .= " AND "; // only adds AND until one from the last row
		}

	} else if ($fields != null && $input != null) {
		// Just add criteria to statement
		$sql .= $fields . " = '" . $input . "'";

	}

	if ($sortField) $sql .= " ORDER BY " . $sortField . " " . $sortOrder;

	$result = mysqli_query($connection, $sql); // Actually query the database
	$rows = []; // store our data

	if (mysqli_num_rows($result) == 0) return false; // no data found
	while($r = mysqli_fetch_assoc($result)) {
  	array_push($rows, $r); // add each row to our result
	}

	return $rows;

}

// Inserts data into a table
// Queries a table for provided data and retuns data
function insert($connection, $tableName, $fields, $values){

	// First part of SQL Statement
	$sql = "INSERT INTO " . $tableName;
	$f = " (";
	$v = " VALUES (";

	if (is_array($fields)){
		// Loop through fields and add criteria to statement
		for ($i = 0; $i < sizeof($fields); $i++) {
			$f .= $fields[$i];
			$v .= "'".$values[$i]."'";
			if (sizeof($fields) - $i > 1) $f .= ", "; // only adds , until one from the last row
			if (sizeof($fields) - $i > 1) $v .= ", "; // only adds , until one from the last row
		}

	} else if ($fields != null) {
		// Just add criteria to statement
		$f = $fields;
		$v = $values;
	}

	$f .= ")";
	$v .= ")";

	$sql .= $f . $v;

	$result = $connection->query($sql); // Insert the data into the table
	if ($result) return $result;

	output($connection->error);
	return false;

}

// Updates rows in a table for provided criteria
function update($connection, $tableName, $fields, $input, $queryFields, $queryValues){

	// First part of SQL statement
	$sql = "UPDATE " . $tableName . " SET ";

	// Fields and values
	if (is_array($fields)){
		// Loop through fields and add criteria to statement
		for ($i = 0; $i < sizeof($fields); $i++) {
			$sql .= $fields[$i] . " = '" . $input[$i] . "'";
			if (sizeof($input) - $i > 1) $sql .= ", "; // only adds ',' until one from the last row
		}

	} else if ($fields != null) {
		// Just add criteria to statement
		$sql .= $fields . " = '" . $input . "'";

	}

	// Criteria
	$sql .= " WHERE ";

	// Criteria and values
	if (is_array($queryFields)){
		// Loop through fields and add criteria to statement
		for ($i = 0; $i < sizeof($queryFields); $i++) {
			$sql .= $queryFields[$i] . " = '" . $queryValues[$i] . "'";
			if (sizeof($queryValues) - $i > 1) $sql .= " AND "; // only adds AND until one from the last row
		}

	} else {
		$sql .= $queryFields . " = '" . $queryValues . "'";
	}

	$result = $connection->query($sql); // Update the thing
	if ($result) return $result;

	output($connection->error);
	return false;

}

// Deletes rows from a table for provided data and retuns data
function delete($connection, $tableName, $fields, $input){

	// First part of SQL Statement
	$sql = "DELETE FROM " . $tableName . " WHERE ";

	if (is_array($fields)){
		// Loop through fields and add criteria to statement
		for ($i = 0; $i < sizeof($input); $i++) {
			$sql .= $fields[$i] . " = '" . $input[$i] . "'";
			if (sizeof($input) - $i > 1) $sql .= " AND "; // only adds AND until one from the last row
		}

	} else if ($fields != null && $input != null) {
		// Just add criteria to statement
		$sql .= $fields . " = '" . $input . "'";

	}

	$result = $connection->query($sql); // Actually delete records from the table

	if ($result) return $result;

	output($connection->error);
	return false;

}

// Ends an SQL Database connection
function disconnect($connection){

	$connection->close();

}

// Creates a calendar table
function buildCalendar($month, $year, $input = false, $past = false, $format = "Y-m-d") {
	// Create array containing abbreviations of days of week.
	$daysOfWeek = array('S','M','T','W','T','F','S');

	// What is the first day of the month in question?
	$firstDayOfMonth = mktime(0,0,0,$month,1,$year);

	// How many days does this month contain?
	$numberDays = date('t',$firstDayOfMonth);

	// Retrieve some information about the first day of the
	// month in question.
	$dateComponents = getdate($firstDayOfMonth);

	// What is the name of the month in question?
	$monthName = $dateComponents['month'];

	// What is the index value (0-6) of the first day of the
	// month in question.
	$dayOfWeek = $dateComponents['wday'];

	// Create the table tag opener and day headers
	$calendar = "<table>";
	$calendar .= "<caption>$monthName $year</caption>";
	$calendar .= "<tr>";

	// Create the calendar headers
	foreach($daysOfWeek as $day) {
    $calendar .= "<th>$day</th>";
	}

	// Create the rest of the calendar
	// Initiate the day counter, starting with the 1st.
	$currentDay = 1;
	$calendar .= "</tr><tr>";

	// The variable $dayOfWeek is used to
	// ensure that the calendar
	// display consists of exactly 7 columns.
	if ($dayOfWeek > 0) {
    $calendar .= "<td colspan='$dayOfWeek'>&nbsp;</td>";
	}
	$month = str_pad($month, 2, "0", STR_PAD_LEFT);
	while ($currentDay <= $numberDays) {
	  // Seventh column (Saturday) reached. Start a new row.
	  if ($dayOfWeek == 7) {
			$dayOfWeek = 0;
			$calendar .= "</tr><tr>";
	  }

	  $currentDayRel = str_pad($currentDay, 2, "0", STR_PAD_LEFT);
	  $date = "$year-$month-$currentDayRel";
	  $calendar .= "<td>";
		$calendar .= $currentDay;
		$disabled = (time()-(60*60*24)) > strtotime($date) ? 'disabled' : '';
		if ($input) $calendar .= "<br><input type='radio' required ".$disabled." name='date' value='".$date."'";
		$calendar .= "</td>";
	  // Increment counters
	  $currentDay++;
	  $dayOfWeek++;
	}

	// Complete the row of the last week in month, if necessary
	if ($dayOfWeek != 7) {
    $remainingDays = 7 - $dayOfWeek;
    $calendar .= "<td colspan='$remainingDays'>&nbsp;</td>";
	}

	$calendar .= "</tr>";
	$calendar .= "</table>";
	return $calendar;

}

?>
