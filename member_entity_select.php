<?php
require('includes/config.php');

$query = " SELECT member_entity.entity_key, entity.entity FROM member_entity 
	INNER JOIN entity ON member_entity.entity_key = entity.entity_key
	INNER JOIN user_entity ON entity.entity_key = user_entity.entity_key 
	WHERE member_entity.member_key = $_POST[member_key] AND user_entity.user_key = $_POST[user_key] ORDER BY entity.entity DESC ";

// echo $query;

if(!$result = $db->query($query))	{
	die('There was an error running the saved search query [' . $db->error . ']');
}
$rows = $result->fetchAll(PDO::FETCH_ASSOC);

echo json_encode($rows);

?>