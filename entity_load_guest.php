<?php
require('includes/config.php');

$search = " WHERE entity.public = 1 AND entity.entity_key != 38 AND entity.entity_key != 39 AND entity.entity_key != 40 ";

$query = "SELECT entity.entity_key, entity.entity, entity.url, COUNT(user_entity.entity_key) AS popcount, entity.ts FROM entity
		INNER JOIN user_entity ON user_entity.entity_key = entity.entity_key

		$search
		GROUP BY user_entity.entity_key ORDER BY popcount DESC, entity LIMIT 10 ";

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