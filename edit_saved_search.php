<?php
require('includes/config.php');

$saved_search_key = $_POST["saved_search_key"];

$db->beginTransaction();

try {
// delete all old entities from entity_saved_search
	$query = "DELETE FROM entity_saved_search WHERE saved_search_key = $saved_search_key";
	if(!$result = $db->query($query))	{
		die('There was an error running the saved search delete query [' . $db->error . ']');
	}
// insert new entities into entity_saved_search
	$entities = $_POST['searchOn']['entity'];
	$t = date('Y-m-d H:i:s',time());

	foreach ($entities as $key => $entity) {
		$entry_query = "INSERT INTO entity_saved_search (entity_key, saved_search_key, ts) VALUES (?, ?, ?)";
		$stmt = $db->prepare($entry_query);
		$stmt->execute(array(
			$entity, $saved_search_key, $t));
	}
	$db->commit();
	echo "success";
}
catch(Exception $e) {

    echo $e->getMessage();
    $db->rollBack();
}

?>