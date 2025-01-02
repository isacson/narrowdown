<?php
require('includes/config.php');

$wola_user = 0;

$query = "SELECT wola_user FROM user WHERE user_key = $_POST[user_key]";
if(!$result = $db->query($query))	{
	die('There was an error running the members query [' . $db->error . ']');
}
$row = $result->fetch(PDO::FETCH_ASSOC);

if ($row["wola_user"] == 1) {
	$wola_user = 1;
}

$selectclause = " SELECT
	COUNT(*) AS count
	, member.member_key
	, chamber.chamber as chamber
	, senrep.senrep AS senrep
	, firstname
	, middlename
	, lastname
	, name_suffix
	, nickname
	, party.party AS party
	, state.state AS state
	, district
	, gender.gender AS gender
	, phone
	, fax
	, website
	, congress_office
	, senate_class ";

$joinclause = "	INNER JOIN party ON party.party_key = member.party
	INNER JOIN state ON state.state_key = member.state
	INNER JOIN senrep ON senrep.senrep_key = member.senrep 
	INNER JOIN chamber ON chamber.chamber_key = member.chamber
	INNER JOIN gender ON
		gender.gender_key = 
		member.gender ";
$whereclause = "";
$havingclause = "";

if (isset($_POST['searchOn']['entity']) && $_POST['searchOn']['entity'][0] != "") {

	$entities = $_POST['searchOn']['entity'];

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

	if ($wola_user == 1) {
		$staffquery = "SELECT firstname, nickname, lastname, email, foreign_policy, immigration, defense FROM staffer WHERE member_key = $row[member_key] ";
		if(!$staffresult = $db->query($staffquery))	{
			die('There was an error running the staff query [' . $db->error . ']');
		}
		$staffrow = $staffresult->fetchAll(PDO::FETCH_ASSOC);

		$i = 1;

		foreach ($staffrow as $key => $value) {
			foreach ($value as $staffkey => $svalue) {					
				$row["staff_" . $staffkey . "_" . $i] = $svalue;
			}
			$i++;
		}
	}

	unset($row["member_key"]);

	$rows[] = $row;
}
while($row = $result->fetch(PDO::FETCH_ASSOC));

download_send_headers("member_export_" . date("Y-m-d") . ".csv");
$p = preg_replace("/\n/", "<br>", array2csv($rows));
echo $p;

exit();


function download_send_headers($filename) {
    // disable caching
    $now = gmdate("D, d M Y H:i:s");
    header("Expires: $now");
    header("Cache-Control: max-age=0, no-cache, must-revalidate, proxy-revalidate");
    header("Last-Modified: {$now} GMT");

    // force download  
    header("Content-Type: application/force-download");
    header("Content-Type: application/octet-stream");
    header("Content-Type: application/download");

    // disposition / encoding on response body
    header("Content-Disposition: attachment;filename={$filename}");
    header("Content-Transfer-Encoding: binary");
}

function array2csv(array &$array)
{
   if (count($array) == 0) {
     return null;
   }
   ob_start();
   $df = fopen("php://output", 'w');
   fputcsv($df, array_keys(reset($array)));
   foreach ($array as $row) {
      fputcsv($df, $row);
   }
   fclose($df);
   return ob_get_clean();
}

?>