<?php
require('includes/config.php');

if ($_POST['otherUsers'] == "unknown") {
	$query = "SELECT user_key, entity.creator FROM user_entity
			INNER JOIN entity on user_entity.entity_key = entity.entity_key
			WHERE user_entity.entity_key = $_POST[entity_key] ";

	if(!$result = $db->query($query))	{
		die('There was an error running the saved search query [' . $db->error . ']');
	}
	$row = $result->fetchAll(PDO::FETCH_ASSOC);

	if (count($row) > 1) {
		$created = "";

		for ($i=0; $i < count($row); $i++){
			if ($row[$i]["creator"] == $_POST['user_key']) {
				
				$created = "yes";
			}
		}

		if ($created == "yes") {

			echo "first";
		}
		else {
			echo "not first";
		}
	}
	else {
		echo "only";
	}
}

if ($_POST['otherUsers'] == "everyone") {
	$query = "DELETE FROM entity
			WHERE entity_key = $_POST[entity_key] ";

	if(!$result = $db->query($query))	{
		die('There was an error running the saved search query [' . $db->error . ']');
	}

	echo "deleted all";

}

if ($_POST['otherUsers'] == "just me") {
	$query = "DELETE FROM user_entity
			WHERE entity_key = $_POST[entity_key] AND user_key = $_POST[user_key]";

	if(!$result = $db->query($query))	{
		die('There was an error running the saved search query [' . $db->error . ']');
	}

	$query = "DELETE e FROM entity_saved_search e
		INNER JOIN user_saved_search u ON e.saved_search_key = u.saved_search_key 
		WHERE e.entity_key = $_POST[entity_key] AND u.user_key = $_POST[user_key]";

	if(!$result = $db->query($query))	{
		die('There was an error running the saved search query [' . $db->error . ']');
	}

	$query = "DELETE s FROM saved_search s LEFT JOIN entity_saved_search e ON s.saved_search_key = e.saved_search_key WHERE e.saved_search_key IS NULL ";

	if(!$result = $db->query($query))	{
		die('There was an error running the saved search query [' . $db->error . ']');
	}

	echo "deleted one";

}

?>