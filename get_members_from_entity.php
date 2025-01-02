<?php 
//if not logged in redirect to login page
require('includes/config.php');

if(!$user->is_logged_in()){ header('Location: ' . $thispath); } 

$query = " SELECT
	member_entity.member_key
	, entity.public
	, senrep.senrep AS senrep
	, firstname
	, lastname
	, nickname
	, party.party AS party
	, state.state AS state
	, member_entity.leader AS leader

	FROM member

	INNER JOIN party ON party.party_key = member.party
	INNER JOIN state ON state.state_key = member.state
	INNER JOIN senrep ON senrep.senrep_key = member.senrep 
	INNER JOIN member_entity ON member.member_key = member_entity.member_key
	INNER JOIN entity ON entity.entity_key = member_entity.entity_key

	WHERE member_entity.entity_key = $_POST[entity_key] ";

if(!$result = $db->query($query))	{
	die('There was an error running the items query [' . $db->error . ']');
}

$row = $result->fetch(PDO::FETCH_ASSOC);
$rows = [];

do {
	$rows[] = $row;
}
while($row = $result->fetch(PDO::FETCH_ASSOC));

echo json_encode($rows);

?>

