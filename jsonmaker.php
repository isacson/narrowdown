<?php

require('includes/config.php');


$query = " SELECT
	member_key
	, senrep.senrep AS senrep
	, firstname
	, lastname
	, nickname
	, party.party AS party
	, state.state AS state

	FROM member

	INNER JOIN party ON party.party_key = member.party
	INNER JOIN state ON state.state_key = member.state
	INNER JOIN senrep ON senrep.senrep_key = member.senrep ";

$stmt = $db->prepare($query);
$result = $stmt->execute();

if(!$result = $db->query($query))	{
	die('There was an error running the items query [' . $db->error . ']');
}

$row = $result->fetch(PDO::FETCH_ASSOC);
$rows = $stmt->fetchAll();
$cnt = count($rows);
$j=1;

echo "[\n";

do { 

	$formblank = "$row[senrep] ";

	if ($row["nickname"] != "") {
		$formblank .= "$row[nickname] ";
	}
	else {
		$formblank .= "$row[firstname] ";
	}

	$formblank .= "$row[lastname] ($row[party]-$row[state]) id#$row[member_key]";

	$aformblank = stripslashes($formblank);
	$aformblank = str_replace("\"", "\\\"", $aformblank);

	echo "\"$aformblank\"";

	if ($j < $cnt) {
		echo ",\n";
	}
	$j++;
}
while($row = $result->fetch(PDO::FETCH_ASSOC));

echo "\n]";

$db = null;

?>