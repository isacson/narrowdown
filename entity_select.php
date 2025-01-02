<?php
require('includes/config.php');

$query = " SELECT * FROM entity 
	INNER JOIN entity_saved_search ON entity_saved_search.entity_key = entity.entity_key
	INNER JOIN saved_search ON saved_search.saved_search_key = entity_saved_search.saved_search_key 
	WHERE saved_search.saved_search_key = $_POST[saved_search_key] ORDER BY entity.ts DESC ";

// echo $query;

if(!$result = $db->query($query))	{
	die('There was an error running the saved search query [' . $db->error . ']');
}
$row = $result->fetch(PDO::FETCH_ASSOC);
$rows = [];

do {
	$rows[] = $row;
}
while($row = $result->fetch(PDO::FETCH_ASSOC));

echo json_encode($rows);

?>