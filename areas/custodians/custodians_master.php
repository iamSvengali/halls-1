<?php

// Custodian info
$custodian = userData();

// =====================================================================================================================================
// FORM PROCESSING FOR CUSTODIANS ======================================================================================================
// =====================================================================================================================================

// Application ID submitted
if (isset($_POST["appId"])){
	// Fields
	$id = $_POST["appId"];

	// Update application status to 'processing'
	update($CONNECTION, "applications", ["status", "lastModified"], ["processing", date('Y-m-d H:i:s',time())], "id", $id);

}

// Room assigned
if (isset($_POST["assignRoom"])){
	// Fields
	$st = $_POST["student"]; // student ID
	$room = $_POST["roomId"]; // room ID
	$appId = $_POST["assignRoom"]; // application ID

	// Update the application status
	update($CONNECTION, "applications", ["status", "lastModified"], ["approved", date('Y-m-d H:i:s',time())], "id", $appId);
	
	// Update the room occupants
	$occupants = query($CONNECTION, "rooms", "id", $room)[0]["occupants"];
	$newOccupants; // new occupants string
	if (!$occupants){
		$newOccupants = $st; // New student becomes the only occupant
	} else {
		$ocSt = explode(", ", $occupants); // occupant array
		array_push($ocSt, $st); // add student to the array
		$newOccupants = implode(", ", $ocSt);
	}

	// Update the room occupants
	update($CONNECTION, "rooms", "occupants", $newOccupants, "id", $room);

	// Finished
	output("Student successfully assigned to room.");

}

// Application declined
if (isset($_POST["declineRoom"])){
	// Fields
	$appId = $_POST["declineRoom"]; // application ID
	$note = $_POST["note"]; // note to the student

	// Update the application status
	update($CONNECTION, "applications", ["status", "lastModified", "note"], ["denied", date('Y-m-d H:i:s',time()), $note], "id", $appId);

	// Finished
	output("Application was denied.");

}

// Appointment started
$appTitle; // appointment title
$appId; // application ID
$appType; // appointment type
$appStudent; // hold student info
$appRoom; // hold student room info
$inventoryList; // hold student inventory
$hasMissingItems = false; // holds missing item info
$hasDamages = false; // holds damaged item info
if (isset($_POST["apptype"])){
	// Fields
	$appId = $_POST["id"];
	$appType = $_POST["apptype"];
	$appTitle = ucwords($_POST["apptype"]);
	$appointment = query($CONNECTION, "schedule", "id", $appId)[0];
	$appStudent = query($CONNECTION, "students", "id", $appointment["student"])[0];

	// Find student room
	$allrooms = query($CONNECTION, "rooms"); // get all rooms
	if ($allrooms){
		foreach ($allrooms as $r) {
			$occupants = $r["occupants"]; // get room occupant(s)
			if ($occupants){ // if there are any
				if (strpos($occupants, ",") !== false){ // if there's more than one
					// If array contains user, then that is their room.
					$ocArray = explode(",", $occupants); // Occupant ID array
					if (in_array($appStudent["id"], $ocArray)){
						$appRoom = $r;
					}
				} else { // just one occupant
					if ($occupants == $appStudent["id"]){
						$appRoom = $r;
					}
				}
			}
		}
	}

	// Find damages
	$hasDamages = query($CONNECTION, "damages", ["room","resolved"], [$appRoom["id"], "0"]);
	// Find missing items
	$hasMissingItems = query($CONNECTION, "missing", "student", $appStudent["id"]);

	// Student inventory
	$inventoryData = query($CONNECTION, "inventory", "student", $appStudent["id"])[0];
	$inv;
	if ($inventoryData){
		$inv = explode(",", $inventoryData["items"]);
		$inventoryList = "<ul>"; // holds rendered list
		foreach ($inv as $item) {
			$itemData = explode(": ", $item); // Split data item into array
			$inventoryList .= "<li><b>" . ucwords(str_replace("-", " ", $itemData[0])) . "</b>: " . $itemData[1] . "</li>";
		}
		$inventoryList .= "</ul>";
	}

	// Find room rent
	$rg = query($CONNECTION, "roomgroups", "id", $appRoom["roomgroup"])[0];
	$rent = ceil($rg["rent"] / $rg["capacity"] / 12);

}

// Appointment complete
if (isset($_POST["appcomplete"])){
	// Fields
	$appId = $_POST["appcomplete"]; // appointment id
	$appointment = query($CONNECTION, "schedule", "id", $appId)[0]; // get holding appointment
	$appType = $appointment["type"]; // appointment type
	$student = query($CONNECTION, "students", "id", $appointment["student"])[0]; // gets student

	// Handle appointment by type
	if ($appType == "checkin"){
		// Update student record
		update($CONNECTION, "students", "checkedIn", "1", "id", $student["id"]);
		// Update schedule record to seen
		update($CONNECTION, "schedule", "seen", "1", "id", $appId);
		// Done
		output("Student successfully checked in.");

	} elseif ($appType == "checkout") {
		// Update student record
		update($CONNECTION, "students", "checkedIn", "0", "id", $student["id"]);
		$rooms = query($CONNECTION, "rooms"); // get rooms
		foreach ($rooms as $room) {
			$occupants = explode(",", $room["occupants"]); // get occupants
			if(($key = array_search($student["id"], $occupants)) !== false) {
				unset($occupants[$key]); // remove student from room
				$newOccupants = implode(",", $occupants);
				update($CONNECTION, "rooms", "occupants", $newOccupants, "id", $room["id"]); // update occupants
				// delete student check in appointments
				delete($CONNECTION, "schedule", ["type", "student"], ["checkin", $student["id"]]);
				// set current appointment to seen
				update($CONNECTION, "schedule", "seen", "1", "student", $student["id"]);
				// delete student inventory
				delete($CONNECTION, "inventory", "student", $student["id"]);
				// delete student application
				delete($CONNECTION, "applications", "student", $student["id"]);
				output("Student checked out.");
				break;
			}
		}
	}

}

// Missing item reporting
if (isset($_POST["missing"])){
	$st = $_POST["stdId"]; // issue ID
	$note = $_POST["note"]; // issue notes
	$cost = $_POST["cost"]; // damage repair costs

	// See if similar listing exists
	$rep = query($CONNECTION, "missing", "student", $st);

	if ($rep){
		// If it does, update it
		update($CONNECTION, "missing", ["items", "cost"], [$note, $cost], "student", $st);
	} else {
		// If it doesn't insert it
		insert($CONNECTION, "missing", ["student", "items", "cost"], [$st, $note, $cost]);
	}
	output("Missing/damage item report submitted.");
}

// Receiving missing item payment
if (isset($_POST["paid"])){
	// Fields
	$id = $_POST["pid"]; // gets record ID
	// Delete missing item record
	delete($CONNECTION, "missing", "id", $id);
	// Done
	output("Payment received. Missing/damaged item(s) compensated.");
}

// Damage report viewing
$report; // to hold damage report id
$reportStudents; // to hold student list
$reportIssue; // to hold attached issue
$reportWorkman; // to hold attached workman
if (isset($_POST["report"])){
	// Fields
	$id = $_POST["report"];
	$report = query($CONNECTION, "damages", "id", $id)[0]; // get report
	$reportIssue = query($CONNECTION, "maintenance", "id", $report["issue"])[0]; // get attached issue
	$reportWorkman = query($CONNECTION, "workmen", "id", $reportIssue["workman"])[0]; // get attached workman
	$rStudents = query($CONNECTION, "rooms", "id", $report["room"])[0]["occupants"]; // get occupants responsible
	$rSArray = explode(",", $rStudents); // holds student id array
	$rSt = []; // holds student name array
	foreach ($rSArray as $st) { // find student names
		$data = query($CONNECTION, "students", "id", $st)[0]; // get student data
		array_push($rSt, $data["firstName"] . " " . $data["lastName"]); 
	}
	$reportStudents = implode(", ", $rSt); // create student list 
}

// Damage report resolving
if (isset($_POST["resolved"])){
	// Fields
	$id = $_POST["resolved"];

	// Update damage report
	update($CONNECTION, "damages", "resolved", "1", "id", $id);

	// Done
	output("Damage report succesfully resolved.");

}



// =====================================================================================================================================
// LIST BUILDERS FOR CUSTODIANS ========================================================================================================
// =====================================================================================================================================

// Student list builder
$studentSelection; // holds list of students for a select box
$students = query($CONNECTION, "students");
if ($students){
	$studentSelection = "";
	foreach ($students as $st) {
		$studentSelection .= "<option value='".$st["id"]."'>".$st["firstName"]." ".$st["lastName"]."</option>";
	}
}

// Payment list builder
$paymentList; // holds list of students with payments due
$payments = query($CONNECTION, "missing"); // get missing item list
if ($payments){
	$paymentList = "<table><thead><tr><th></th><th>Student Name</th><th>Amount due</th></tr></thead><tbody>";
	foreach ($payments as $payment) {
		$std = query($CONNECTION, "students", "id", $payment["student"])[0]; // fetch attached student object
		$paymentList .= "<tr><td><input required type='radio' name='pid' value='".$payment["id"]."'</td><td>".$std["firstName"]." ".$std["lastName"]."</td><td>KES ".number_format($payment["cost"],2)."</td></tr>";
	}
	$paymentList .= "</tbody></table>";
}

// Maintenance issues list builder
$issueList; // to hold the issues
$issues = query($CONNECTION, "maintenance"); // get issues
if ($issues){ // if there are any issues
	$issueList = "<table><thead><tr><th></th><th>Room</th><th>Problem Type</th><th>Workman Assigned</th><th>Status</th><th>Last updated</th></tr></thead><tbody>";
	foreach ($issues as $issue) { // build the list of issues
		$workman = query($CONNECTION, "workmen", "id", $issue["workman"])[0]; // find the workman assigned
		$workman = $workman["firstName"] . " " . $workman["lastName"]; // get the workman's name
		$issueList .= "<tr><td><b>#".$issue["id"]."</b></td><td>".$issue["room"]."</td><td>".$ISSUES[$issue["problemType"]]."</td><td>".$workman."</td><td>".ucwords($issue["status"])."</td><td>".date_format(date_create($issue["lastModified"]), "h:i A, l F d, Y")."</td></tr>";
	}
	$issueList .= "</tbody></table>";
}

// Damage report list builder
$damageList; // to hold damage reports
$damages = query($CONNECTION, "damages"); // get em
if ($damages){ // if there are any damages
	$damageList = "<table><thead><tr><th></th><th>Room</th><th>Issue</th><th></th></tr></thead><tbody>";
	foreach ($damages as $report) {
		$iss = query($CONNECTION, "maintenance", "id", $report["issue"])[0]; // find attached issue 
		$damageList .= "<tr><td><b>#".$report["id"]."</b></td><td>".$report["room"]."</td><td>".$report["issue"]." - ".$ISSUES[$iss["problemType"]]."</td><td>";
		if ($report["resolved"] != "1"){
			$damageList .= "<button type='submit' name='report' value='".$report["id"]."'>View Report</button>";
		} else {
			$damageList .= "Resolved.";
		}
		$damageList .= "</td></tr>";
	}
	$damageList .= "</tbody></table>";
}

// Application list builder
$apps = query($CONNECTION, "applications", null, null, "id"); // gets all applications in descending order of ID
$appList; // holds list of applications
if ($apps){ // if there are any
	$appList = "<table><thead><tr><th></th><th>Student Name</th><th>Room Type</th><th></th></tr></thead><tbody>";
	foreach ($apps as $application) { // builds list
		$student = query($CONNECTION, "students", "id", $application["student"])[0]; // gets student info
		$student = $student["firstName"] . " " . $student["lastName"]; // gets student name
		$room = query($CONNECTION, "roomgroups", "id", $application["room"])[0]; // gets room type info
		$room = $room["name"] . " - " . $room["capacity"] . " occupant(s)"; // gets room name
		$action = $application["status"] == "approved" ? "Approved." : ($application["status"] == "denied" ? "Denied." : "<button type='submit' name='appId' value='".$application["id"]."'>Assign room</button>" ); // determines current status and actions
		$appList .= "<tr><td><b>#".$application["id"]."</b></td><td>".$student."</td><td>".$room."</td><td>".$action."</td></tr>";
	}
	$appList .= "</tbody></table>";
}

// Schedule builder (only unseen)
$schedule = query($CONNECTION, "schedule", ["custodian", "seen"], [$custodian["id"], "0"], "id"); // gets schedule data sorted by descending Id
$scheduleList = false; // holds the schedule list
if ($schedule){
	$scheduleList = "<table><thead><tr><th></th><th>Date</th><th>Time</th><th>Student</th><th>Type</th><th></th></tr></thead><tbody>";
	foreach ($schedule as $appointment) {
		$st = query($CONNECTION, "students", "id", $appointment["student"])[0]; // gets student info
		$scheduleList .= "<tr><td><b>#".$appointment["id"]."</b></td><td>".date_format(date_create($appointment["date"]), "l F d, Y")."</td><td>".$appointment["time"]."</td><td>".$st["firstName"]." ".$st["lastName"]."</td><td>".ucwords($appointment["type"])."</td><td><input type='hidden' name='apptype' value='".$appointment["type"]."'><button type='submit' name='id' value='".$appointment["id"]."'>View</button></td></tr>";
	}
	$scheduleList .= "</tbody></table>";
}

// Room list builder
$rg;
$roomGroup;
$roomAvailable = false; // state of room availability
$rooms;
$roomList; // to hold the room list, duh
$student; // to hold student id
if (isset($_POST["appId"])){
	$data = query($CONNECTION, "applications", "id", $_POST["appId"])[0];
	$rg = $data["room"]; // gets applicant's desired room group
	$student = $data["student"]; // get applicant Id
	$rooms = query($CONNECTION, "rooms", "roomgroup", $rg);
	$roomGroup = query($CONNECTION, "roomgroups", "id", $rg)[0]; // gets types of rooms
}
if (isset($rooms)){
	$roomList = "<table><thead><tr><th></th><th></th><th>Location</th><th>Occupancy</th></tr></thead><tbody>";
	foreach ($rooms as $room) {
		$occupants = 0; // number of current occupants in each room
		if (isset($roomGroup) && $room["roomgroup"] == $roomGroup["id"]){
			if ($room["occupants"]){
				$occupants = sizeof(explode(", ", $room["occupants"])); // expand to find number of occupants
				if ($occupants < $roomGroup["capacity"]){
					$roomAvailable = true; // room is available if occupants are less than capacity
					$roomList .= "<tr><td><input type='radio' name='roomId' value='".$room["id"]."'><td><b>#".$room["id"]."</b></td><td>".$room["location"]."</td><td>".$occupants."/".$roomGroup["capacity"]."</td></tr>";
				}
			} else {
				$roomAvailable = true; // room is available if there are no occupants
				$roomList .= "<tr><td><input type='radio' name='roomId' value='".$room["id"]."'><td><b>#".$room["id"]."</b></td><td>".$room["location"]."</td><td>".$occupants."/".$roomGroup["capacity"]."</td></tr>";
			}
		}
	}
	$roomList .= "</tbody></table>";
}

?>
