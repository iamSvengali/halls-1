<?php

// Custodian info
$workman = userData();

// =====================================================================================================================================
// FORM PROCESSING FOR WORKMEN =========================================================================================================
// =====================================================================================================================================

// View issue details
$issue; // to hold the issue
$issueReported = false;
$student; // to hold reporting student info
$room; // to hold room info
if (isset($_POST["issue"])){
	$id = $_POST["issue"]; // get issue id
	$issue = query($CONNECTION, "maintenance", "id", $id)[0]; // get issue object

	// Set issue to acknowledged if it was pending
	if ($issue["status"] == "pending"){
		update($CONNECTION, "maintenance", ["status", "lastModified"], ["acknowledged", date('Y-m-d H:i:s',time())], "id", $id);
	}

	// Requery issue
	$issue = query($CONNECTION, "maintenance", "id", $id)[0]; // get issue object
	$ir = query($CONNECTION, "damages", "issue", $issue["id"]);
	$issueReported = $ir ? $ir[0] : false;
	$student = query($CONNECTION, "students", "id", $issue["student"])[0]; // get student object
	$room = query($CONNECTION, "rooms", "id", $issue["room"])[0]; // get room object

}

// Issue work started
if (isset($_POST["start"])){
	$id = $_POST["start"]; // get the issue ID
	// Update issue status to say started
	update($CONNECTION, "maintenance", ["status", "lastModified"], ["started", date('Y-m-d H:i:s',time())], "id", $id);

	// Requery issue
	$issue = query($CONNECTION, "maintenance", "id", $id)[0]; // get issue object
	$student = query($CONNECTION, "students", "id", $issue["student"])[0]; // get student object
	$room = query($CONNECTION, "rooms", "id", $issue["room"])[0]; // get room object

}

// Damage report on issue
if (isset($_POST["damagereport"])){
	$id = $_POST["issId"]; // issue ID
	$rm = query($CONNECTION, "maintenance", "id", $id)[0]["room"];
	$note = $_POST["note"]; // issue notes
	$cost = $_POST["cost"]; // damage repair costs

	// See if similar report exists
	$rep = query($CONNECTION, "damages", "issue", $id);

	if ($rep){
		// If it does, update it
		update($CONNECTION, "damages", ["note", "cost"], [$note, $cost], "issue", $id);
	} else {
		// If it doesn't insert it
		insert($CONNECTION, "damages", ["issue", "room", "note", "cost"], [$id, $rm, $note, $cost]);
	}
	output("Damage report submitted.");

}

// Issue work completed
if (isset($_POST["complete"])) {
	$id = $_POST["complete"]; // get the issue ID
	// Update issue status to say started
	update($CONNECTION, "maintenance", ["status", "lastModified"], ["complete", date('Y-m-d H:i:s',time())], "id", $id);
	header("location: reports");
}



// =====================================================================================================================================
// LIST BUILDERS FOR WORKMEN ===========================================================================================================
// =====================================================================================================================================

// Maintenance reports list builder
$issueList; // to hold the reports
$reports = query($CONNECTION, "maintenance", "workman", $workman["id"]); // get reports for said room
if ($reports){ // if there are any reports for the room
	$issueList = "<table><thead><tr><th></th><th>Room</th><th>Problem Type</th><th>Status</th><th>Last updated</th><th></th></tr></thead><tbody>";
	foreach ($reports as $issue) { // build the list of reports
		$issueList .= "<tr><td><b>#".$issue["id"]."</b></td><td>".$issue["room"]."</td><td>".$ISSUES[$issue["problemType"]]."</td><td>".ucwords($issue["status"])."</td><td>".date_format(date_create($issue["lastModified"]), "h:i A, l F d, Y")."</td><td>";
		if ($issue["status"] != "complete"){ // work is not done
			$issueList .= "<button type='submit' name='issue' value='".$issue["id"]."'>View Issue</button>";
		} else {
			$issueList .= "Resolved.";
		}
		$issueList .="</td></tr>";
	}
	$issueList .= "</tbody></table>";
}

// Issue selection builder
$issueSelection; // to hold list of select options
if ($reports){
	$issueSelection = "";
	foreach ($reports as $issue) {
		if ($issue["status"] != "complete"){
			$issueSelection .= "<option value='".$issue["id"]."'>#".$issue["id"]." - ".$ISSUES[$issue["problemType"]]."</option>";
		}
	}
}

?>
