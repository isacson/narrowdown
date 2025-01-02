<?php
require('includes/config.php');

$selectclause = " SELECT *, COUNT(*) AS count, party.party AS party, state.state_spelled AS state, senrep.senrep AS senrep ";
$joinclause = "	INNER JOIN party on party.party_key = member.party
	INNER JOIN state on state.state_key = member.state
	INNER JOIN senrep on senrep.senrep_key = member.senrep ";
$whereclause = "";
$havingclause = "";

if (isset($_POST['searchOn']['entity']) && $_POST['searchOn']['entity'][0] != "") {

	$entities = $_POST['searchOn']['entity'];

	$selectclause .= ", SUM(member_entity.leader) AS leads ";

	$joinclause .= " INNER JOIN member_entity ON member.member_key = member_entity.member_key 
	INNER JOIN entity ON entity.entity_key = member_entity.entity_key ";

	if ($whereclause == "") {
		$whereclause = " WHERE ";
	}
	else {
		$whereclause .= " AND ";
	}
	$a = " ( ";
	for ($i=0; $i < sizeof($entities); $i++) { 
		$a .= " entity.entity_key = $entities[$i] ";
		if ($i < sizeof($entities) -1) {
			$a .= " OR ";
		}
	}
	$a .= " ) ";
	$whereclause .= $a;
}

if (isset($_POST['searchOn']['chamber']) && $_POST['searchOn']['chamber'] != "" && $_POST['searchOn']['chamber'] != 3 && $_POST['searchOn']['chamber'] != 0) {

	$chamber = $_POST["searchOn"]["chamber"];

	if ($whereclause == "") {
		$whereclause = " WHERE ";
	}
	else {
		$whereclause .= " AND ";
	}
	$whereclause .= " member.chamber = $chamber ";
}

if (isset($_POST['searchOn']['searchMatch']) && $_POST['searchOn']['searchMatch'] != "") {

	$searchmatch = $_POST["searchOn"]["searchMatch"];

	if ($whereclause == "") {
		$whereclause = " WHERE ";
	}
	else {
		$whereclause .= " AND ";
	}
	$whereclause .= " ( member.firstname LIKE '%$searchmatch%' OR member.nickname LIKE '%$searchmatch%' OR member.lastname LIKE '%$searchmatch%' OR state.state_spelled LIKE '%$searchmatch%' ) ";
}

if (isset($_POST['searchOn']['party']) && $_POST['searchOn']['party'] != "" && $_POST['searchOn']['party'] != 0 && $_POST['searchOn']['party'] != 13) {

	$party = $_POST["searchOn"]["party"];

	if ($whereclause == "") {
		$whereclause = " WHERE ";
	}
	else {
		$whereclause .= " AND ";
	}

	switch ($party) {
		case 1:
			$whereclause .= " (member.party = 1) ";
			break;
		case 4:
			$whereclause .= " (member.party = 2) ";
			break;
		case 8:
			$whereclause .= " (member.party = 3) ";
			break;
		case 5:
			$whereclause .= " (member.party = 1 OR member.party = 2) ";
			break;
		case 9:
			$whereclause .= " (member.party = 1 OR member.party = 3) ";
			break;
		case 12:
			$whereclause .= " (member.party = 2 OR member.party = 3) ";
			break;		
	}
}

if (isset($_POST['searchOn']['matches']) && $_POST['searchOn']['matches'] != "" && $_POST['searchOn']['matches'] > 1) {
	$matches = $_POST['searchOn']['matches'];
	if ($havingclause == "") {
		$havingclause = " HAVING ";
	}
	else {
		$havingclause .= " AND ";
	}
	$havingclause .= " count >= $matches ";

}

$query = "$selectclause FROM member $joinclause $whereclause GROUP BY member.member_key $havingclause ORDER BY count DESC, lastname ";

// echo $query;

if(!$result = $db->query($query))	{
	die('There was an error running the members query [' . $db->error . ']');
}
$row = $result->fetch(PDO::FETCH_ASSOC);
$rows = array();

do {
	$rows[] = $row;
}
while($row = $result->fetch(PDO::FETCH_ASSOC));

echo json_encode($rows);
?>