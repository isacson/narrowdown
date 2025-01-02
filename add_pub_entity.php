<?php
require('includes/config.php');

$sorter = "";
$search = " WHERE entity.public = 1 AND entity.entity_key != 38 AND entity.entity_key != 39 AND entity.entity_key != 40 ";

if ($_POST['search'] != "") {
	$search .= " AND entity.entity LIKE '%$_POST[search]%' ";
}

if ($_POST['sorter'] == "name") {
	$sorter = " ORDER BY entity.entity ";
}

if ($_POST['sorter'] == "date") {
	$sorter = " ORDER BY entity.ts desc ";
}

$query = "SELECT DISTINCT entity.entity_key, entity.entity, entity.url, user_entity.user_key, user.user, entity.ts
	FROM entity 
	INNER JOIN user_entity ON user_entity.entity_key = entity.entity_key 
	INNER JOIN user ON user_entity.user_key = user.user_key
	$search GROUP BY entity.entity_key $sorter ";

if(!$result = $db->query($query))	{
	die('There was an error running the saved search query [' . $db->error . ']');
}
$row = $result->fetch(PDO::FETCH_ASSOC);

// from this, we need to make an array, 
// then, from that array, make an array of entity_keys that match the user's key,
// then eliminate all array elements that whose entity_key matches the array of "banned" entity_keys

$rows = array();
$nonentities = array();

do {
	$rows[] = $row;

	if ($row["user_key"] == $_POST['user_key']) {
		$nonentities[] = $row["entity_key"];
	}
}
while($row = $result->fetch(PDO::FETCH_ASSOC));

foreach ($nonentities as $key => $value) {
	foreach ($rows as $rowkey => $rowvalue) {
		if ($rowvalue["entity_key"] == $value) {
			unset($rows[$rowkey]);
		}
	}
}

$rows = array_values($rows);

foreach ($rows as $key => $value) {
	$rows[$key]['ts'] = date("M j Y", strtotime(substr($value['ts'], 0, 10)));
}

echo json_encode($rows);

?>