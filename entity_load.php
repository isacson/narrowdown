<?php
require('includes/config.php');

$sorter = " ORDER BY entity.ts desc ";

$search = " WHERE user.user_key = $_POST[user_key] ";

if (isset($_POST['search']) && $_POST['search'] != "") {
	$search .= " AND entity.entity LIKE '%$_POST[search]%' ";
}

if (isset($_POST['sorter'])) {

	if ($_POST['sorter'] == "name") {
		$sorter = " ORDER BY entity.entity ASC ";
	}

	if ($_POST['sorter'] == "date") {
		$sorter = " ORDER BY entity.ts desc ";
	}
}

$query = "SELECT entity.entity_key, entity.entity, entity.url, entity.ts FROM entity
		INNER JOIN user_entity ON user_entity.entity_key = entity.entity_key
		INNER JOIN user ON user.user_key = user_entity.user_key
		$search
		$sorter ";

if(!$result = $db->query($query))	{
	die('There was an error running the saved search query [' . $db->error . ']');
}
$row = $result->fetch(PDO::FETCH_ASSOC);
$rows = array();

do {
	$rows[] = $row;
}
while($row = $result->fetch(PDO::FETCH_ASSOC));

foreach ($rows as $key => $value) {
	$rows[$key]['ts'] = date("M j Y", strtotime(substr($value['ts'], 0, 10)));
}

echo json_encode($rows);

?>