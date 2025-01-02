<?php
require('includes/config.php');

$query = " SELECT *, user_entity.user_key FROM entity 
	INNER JOIN user_entity ON user_entity.entity_key = entity.entity_key
	WHERE entity.entity = '$_POST[entity]' ";

// echo $query;

if(!$result = $db->query($query))	{
	die('There was an error running the saved search query [' . $db->error . ']');
}
$row = $result->fetch(PDO::FETCH_ASSOC);

$rows = [];

$j = 0;
$k = 0;

do {
	// did the user create this entity?
	if ($row["creator"] == $_POST['user_key']) {
		$rows[$j]["creator"] = "creator";
		$rows[$j]["url"] = $row["url"];
		$rows[$j]["entity_key"] = $row["entity_key"];
		$j++;
	}
	// is the user copying another user's entity? (and if so is it public?)
	if ($row["creator"] != $_POST['user_key'] && $row["public"] == 1) {
		$rows[$k]["creator"] = "copycat";
		$rows[$j]["url"] = $row["url"];
		$rows[$k]["entity_key"] = $row["entity_key"];
		$k++;
	}
}
while($row = $result->fetch(PDO::FETCH_ASSOC));

echo json_encode($rows);

?>