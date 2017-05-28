<?php

// Student info
$student = userData();

// Find application info
$appinfo = query($CONNECTION, "applications", "student", $student["id"])[0];

// =====================================================================================================================================
// FORM PROCESSING FOR STUDENTS ========================================================================================================
// =====================================================================================================================================

// Room application submitted
if (isset($_POST["appconfirm"])){
	if ($appinfo) {
		if ($appinfo["status"] != "denied"){ // has previous open application
			output("Your application has been submitted.");
		} else { // has previously denied application
			delete($CONNECTION, "applications", "student", $student["id"]); // delete previous records
		}
	}

	$rid = $_POST["room"]; // room id
	$data = insert($CONNECTION, "applications", ["room", "student"], [$rid, $student["id"]]); // Post to the database
	if ($data){ // successfully posted
		output("Your application has been submitted.");
	}
}

// Check in/out time and date submitted
$confirmDate;
$confirmTime;
$cAssigned = false;
if (isset($_POST["checktime"])){ // Date + time selected
	$confirmDate = $_POST["date"]; // Appointment date
	$confirmTime = $_POST["checktime"]; // Appointment time
	$c; // Custodian

	// Find free custodian
	// A custodian is free if (s)he does not have an appointment on set date and time
	$custodians = query($CONNECTION, "custodians"); // gets list of custodians
	$appointments = query($CONNECTION, "schedule", ["date", "time"], [$confirmDate, $confirmTime]); // gets list of appointments
	foreach ($custodians as $custodian) {
		if (!$appointments){ // no appointments scheduled
			$c = $custodian; // assign to first custodian
			$cAssigned = true;
			break;
		}
		foreach ($appointments as $appointment) {
			if ($appointment["custodian"] != $custodian["id"]){ // the first custodian whose id does not match the one on the appointment is assigned
				$c = $custodian;
				$cAssigned = true;
				break;
			}
		}
	}

	// Assign to the first custodian if none has been selected
	if (!$cAssigned) $c = $custodians[0];

}

// Check in date, time, and custodian submitted
if (isset($_POST["checkinconfirm"])) {

	$d = $_POST["xdate"]; // appointment date
	$t = $_POST["xtime"]; // appointment time
	$c = $_POST["custodian"]; // selected custodian

	insert($CONNECTION, "schedule", ["student", "type", "date", "time", "custodian"], [$student["id"], "checkin", $d, $t, $c]); // Post to the database
	output("Your check-in appointment has been scheduled.");

}

// Check out time, date and custodian submitted
if (isset($_POST["checkoutconfirm"])) {

	$d = $_POST["xdate"]; // appointment date
	$t = $_POST["xtime"]; // appointment time
	$c = $_POST["custodian"]; // selected custodian

	insert($CONNECTION, "schedule", ["student", "type", "date", "time", "custodian"], [$student["id"], "checkout", $d, $t, $c]); // Post to the database
	output("Your check-out appointment has been scheduled.");

}

// Inventory submitted
if (isset($_POST["inventory"])) {
	$submittedItems = array_splice($_POST, 0, -1); // remove the "inventory" element
	$itemList = array();
	foreach ($submittedItems as $name => $value) {
		$itemList[] = $name . ": " . $value; // create an array of item: value
	}
	$itemString = implode(", ", $itemList); // break the array into a string
	insert($CONNECTION, "inventory", ["student", "items"], [$student["id"], $itemString]);
	output("Your inventory was succesfully recorded.");

}

// Maintenance report submitted
if (isset($_POST["maintenance"])){
	// Fields
	$room = $_POST["room"];
	$problemType = $_POST["problemType"];
	$description = $_POST["description"];
	// See if there is an open report on a similar matter
	$reports = query($CONNECTION, "maintenance", "room", $room);
	$updated = false; // whether previous reports have been updated
	if ($reports){
		foreach ($reports as $report) {
			if ($report["status"] != "complete"){ // only open reports
				// Add to its counts
				update($CONNECTION, "maintenance", ["description", "count", "lastModified"], [$description, $report["count"] + 1, date('Y-m-d H:i:s',time())], "id", $report["id"]);
				$updated = true;
			}
		}
	}

	// If there are no reports, or no open reports, add the new report
	if (!$reports || ($reports && !$updated)){
		// Find an available workman
		$assignedWorkman; // to hold workman
		$wAssigned = false;
		$workmen = query($CONNECTION, "workmen"); // list all workmen
		$issues = query($CONNECTION, "maintenance"); // list all maintenance issues
		if (!$issues){ // no maintenance issues
			$assignedWorkman = $workmen[0]["id"];
			$wAssigned = true;
		} else {
			foreach ($workmen as $workman) {
				foreach ($issues as $issue) {
					// Find the last completed issue, or the last issue where the workman does not match
					if ($issue["status"] == "complete" || $issue["workman"] != $workman["id"]){
						$assignedWorkman = $workman["id"];
						$wAssigned = true;
					}
				}
			}
		}

		// Assign workman if nothing happened
		$assignedWorkman = $workmen[0]["id"];

		// Throw it to the database
		insert($CONNECTION, "maintenance", ["room", "problemType", "description", "student", "workman"], [$room, $problemType, $description, $student["id"], $assignedWorkman]);
		output("Your maintenance issue has been submitted.");

	}

}



// =====================================================================================================================================
// DATA FINDING FOR STUDENTS ===========================================================================================================
// =====================================================================================================================================

// Find schedule info
$checkInScheduled = query($CONNECTION, "schedule", ["student", "type"], [$student["id"], "checkin"])[0]; // needs to be updated to see if student has checked in and out more than once
$checkOutScheduled = query($CONNECTION, "schedule", ["student", "type"], [$student["id"], "checkout"])[0]; // needs to be updated to see if student has checked in and out more than once

// Find inventory info for student
$inventoryEntered = query($CONNECTION, "inventory", "student", $student["id"]);

// Find room info if appinfo is approved
$assignedRoom = null;
$roomOccupants = null;
if ($appinfo["status"] == "approved"){
	$allrooms = query($CONNECTION, "rooms"); // get all rooms
	if ($allrooms){
		foreach ($allrooms as $r) {
			$occupants = $r["occupants"]; // get room occupant(s)
			if ($occupants){ // if there are any
				if (strpos($occupants, ",") !== false){ // if there's more than one
					// If array contains user, then that is their room.
					$ocArray = explode(",", $occupants); // Occupant ID array
					if (in_array($student["id"], $ocArray)){
						$assignedRoom = $r;
						for ($i = 0; $i < sizeof($ocArray); $i++) {
							$st = query($CONNECTION, "students", "id", $ocArray[$i])[0];
							$roomOccupants .= $st["firstName"] . " " . $st["lastName"];
							if ($i < sizeof($ocArray) - 1) $roomOccupants .= ", ";
						}
					}
				} else { // just one occupant
					if ($occupants == $student["id"]){
						$assignedRoom = $r;
						$roomOccupants = $student["firstName"] . " " . $student["lastName"];
					}
				}
			}
		}
	}
}

// Find damage info
$hasDamageReport = query($CONNECTION, "damages", ["room","resolved"], [$assignedRoom["id"],"0"]);



// =====================================================================================================================================
// LIST BUILDERS FOR STUDENTS ==========================================================================================================
// =====================================================================================================================================

// Maintenance issues list builder
$claimList; // to hold the claims
if ($assignedRoom){ // if the student has a room
	$claims = query($CONNECTION, "maintenance", "room", $assignedRoom["id"]); // get claims for said room
	if ($claims){ // if there are any claims for the room
		$claimList = "<table><thead><tr><th></th><th>Problem Type</th><th>Workman Assigned</th><th>Status</th><th>Last updated</th></tr></thead><tbody>";
		foreach ($claims as $claim) { // build the list of claims
			$workman = query($CONNECTION, "workmen", "id", $claim["workman"])[0]; // find the workman assigned
			$workman = $workman["firstName"] . " " . $workman["lastName"]; // get the workman's name
			$claimList .= "<tr><td><b>#".$claim["id"]."</b></td><td>".$ISSUES[$claim["problemType"]]."</td><td>".$workman."</td><td>".ucwords($claim["status"])."</td><td>".date_format(date_create($claim["lastModified"]), "h:i A, l F d, Y")."</td></tr>";
		}
		$claimList .= "</tbody></table>";
	}
}

// Inventory item list buider
$inventoryData = query($CONNECTION, "inventory", "student", $student["id"])[0];
$inv;
$inventoryList;
if ($inventoryData){
	$inv = explode(",", $inventoryData["items"]);
	$inventoryList = "<ul>"; // holds rendered list
	foreach ($inv as $item) {
		$itemData = explode(": ", $item); // Split data item into array
		$inventoryList .= "<li><b>" . ucwords(str_replace("-", " ", $itemData[0])) . "</b>: " . $itemData[1] . "</li>";
	}
	$inventoryList .= "</ul>";
}


// Inventory field list builder
$inventoryItems = query($CONNECTION, "items"); // Gets list of fields
$inventoryFields = null; // holds rendered list
$count = 1; // counts through rows for grid purposes
foreach ($inventoryItems as $item) {
	if ($count == 1) $inventoryFields .= "<div class='row'>";
	$inventoryFields .= "<div class='three columns'>";
	$inventoryFields .= "<label>".$item["name"].":</label>";
	$inventoryFields .= "<input min='0' autocomplete='off' required type='".$item["type"]."' name='".strtolower(str_replace(" ", "-", $item["name"]))."'>";
	$inventoryFields .= "</div>";
	if ($count < 4) { // 4 is number of items per row
		$count++;
	} else {
		$inventoryFields .= "</div>";
		$count = 1;
	}
}
if ($count < 4) $inventoryFields .= "</div>";

// Room list builder
$rooms = query($CONNECTION, "roomgroups"); // list of room types
$room = null; // selected room
$roomList = "<table><thead><th></th><th>Room Name</th><th>Capacity</th><th>Description</th></thead><tbody>";
foreach ($rooms as $r) {
	$roomList .= "<tr>";
	$roomList .= "<td><input type='radio' required name='room' value='".$r["name"]."'></td>";
	$roomList .= "<td>".$r["name"]."</td>";
	$roomList .= "<td>".$r["capacity"]." student(s)</td>";
	$roomList .= "<td>".$r["description"]."</td>";
	$roomList .= "</tr>";
	if (isset($_POST["app"])){
		if ($r["name"] == $_POST["room"]) $room = $r;
	}
}
$roomList .= "</tbody></table>";

// Schedule calendar builder
$dateComponents = getdate(date("U", strtotime($CALENDAR)));
$month = $dateComponents['mon'];
$year = $dateComponents['year'];
$selectable = true; // provides radio buttons in the calendar
$pastSelections = false; // disables selections before the current date
$controls = true; // enable calendar controls
$dateList = buildCalendar($month, $year, $selectable, $controls, $pastSelections);

// Schedule time slot builder
$dateSelected;
$timeList;

// check for data backup
if (isset($_SESSION["backup"])){
	if (isset($_SESSION["backup"]["checkin"]) || isset($_SESSION["backup"]["checkout"])){
		$_POST = $_SESSION["backup"];
	}
}

if (isset($_POST["checkin"]) || isset($_POST["checkout"])){
	$custodians = query($CONNECTION, "custodians"); // Fetches custodian list
	$custodians = sizeof($custodians); // Gets number of custodians
	$today = date("Y-m-d") == $_POST["date"]; // Checks if the date selected is today
	$dateSelected = $today ? "Today" : date_format(date_create($_POST["date"]), "l F d, Y"); // Shows date selected

	// Creates a list of time slots
	$timeList = "<table><thead><tr><th colspan='".$SLOTS."'>Time slots</th></tr><thead><tbody>";
	for ($i = $STARTTIME; $i < $ENDTIME; $i++) {
		if ($i == $BREAKTIME){ // No time slots available during lunch break (defined in global)
			$timeList .= "<tr><td colspan='".$SLOTS."'></td></tr>";
			$i++; // Move to next hour
		}
		$timeList .= "<tr>";
		$hour = ($i <= 12) ? $i : ($i - 12); // hour
		$suffix = ($i < 12) ? "AM" : "PM"; // suffix
		for ($x = 0; $x < $SLOTS; $x++) {
			$duration = 60 / $SLOTS; // duration of each slot
			$min = $x * $duration; $min = ($min == "0") ? "00" : $min; // minutes part
			$time = $hour . ":" . $min . " " . $suffix; // time to put in button
			$appts = query($CONNECTION, "schedule", ["date", "time", "seen"], [$_POST["date"], $time, "0"]); // finds number of appointments
			$timeDisabled = ($appts) ? (sizeof($appts) == $custodians ? "disabled" : "") : ""; // disables time slot if there all custodians have appointments at that time

			if ($today){ // If today was selected
				$timeNow = date("h:i A"); // Current time string
				$past = strtotime($timeNow, strtotime($time)) >= strtotime($time); // If time slot is in the past (current time > time of slot start)
				$timeDisabled = $timeDisabled == "disabled" ? "disabled" : ($past ? "disabled" : ""); // disables time slot if in the past
			}
			$timeList .= "<td><button type='submit' name='checktime' ".$timeDisabled." value='".$time."'> " . $time . "</button></td>";
		}
		$timeList .= "</tr>";
	}
	$timeList .= "</tbody><table>";

	// Redirect to necessary page
	if (!isset($_SESSION["backup"])){
		$_SESSION["backup"] = $_POST; // back up data and redirect
		if ($_POST["checkin"]){
			header("location: checkintime");
			exit();
		} elseif ($_POST["checkout"]) {
			header("location: checkouttime");
			exit();
		}
	} else {
		// Clear backup
		unset($_SESSION["backup"]);
		// Clear calendar
		unset($_SESSION["calendar"]);
	}

}

?>
