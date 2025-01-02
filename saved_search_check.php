<?php
require('includes/config.php');

$saved_search_key = $_POST["saved_search_key"];

$db->beginTransaction();

$query = "SELECT * FROM entity_saved_search WHERE saved_search_key = $saved_search_key";
if(!$result = $db->query($query))	{
	die('There was an error running the saved search check query [' . $db->error . ']');
}
$row = $result->fetch(PDO::FETCH_ASSOC);

$old_entities = [];

do {
	$old_entities[] = $row["entity_key"];
}
while($row = $result->fetch(PDO::FETCH_ASSOC));

$entities = $_POST['searchOn']['entity'];

$old_entities = array_unique($old_entities);
$entities = array_unique($entities);

sort($old_entities);
sort($entities);

$old_entities = implode(",", $old_entities);
$entities = implode(",", $entities);

/* 
echo "old entities is " . $old_entities;
echo "entities is " . $entities;
*/

if ($old_entities == $entities) {

	echo "same";
}

else {

	echo "different";
}

?>