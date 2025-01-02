<?php
require('includes/config.php');

// See if saved search with that name exists

$query = "SELECT saved_search, saved_search.saved_search_key from saved_search 
INNER JOIN user_saved_search ON user_saved_search.saved_search_key = saved_search.saved_search_key
INNER JOIN user ON user_saved_search.user_key = user.user_key
WHERE saved_search = '$_POST[saved_search]' AND user.user_key = $_POST[user_key] ";
if(!$result = $db->query($query))	{
	die('There was an error running the saved search query [' . $db->error . ']');
}
$row = $result->fetch(PDO::FETCH_ASSOC);

// if it exists and overwrite is 'no', return 'exists' and exit. Otherwise, these will be "update" queries

if ($row['saved_search'] == $_POST['saved_search']) {

	if($_POST['overwrite'] == "no") {
		echo "exists";
		exit;
	}
	else {
// get key

		$saved_search_key = $row["saved_search_key"];

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
			echo $saved_search_key;
		}
		catch(Exception $e) {

		    echo $e->getMessage();
		    $db->rollBack();
		}
	}
}
else {

//let's insert a brand-new saved search
	$db->beginTransaction();

	$t = date('Y-m-d H:i:s',time());

	try {
//first, in saved_search table -- and get the key number
		$query = "INSERT INTO saved_search (saved_search, ts) VALUES (?,?)";
		$stmt = $db->prepare($query);
		$stmt->execute(array($_POST['saved_search'], $t));
		$saved_search_key = $db->lastInsertId();

// next, in the user_saved_search table
		$query = "INSERT INTO user_saved_search (saved_search_key, user_key, ts) VALUES (?,?,?)";
		$stmt = $db->prepare($query);
		$stmt->execute(array($saved_search_key, $_POST['user_key'], $t));

// then, the entity_saved_search table
		$entities = $_POST['searchOn']['entity'];

		foreach ($entities as $key => $entity) {
		$entry_query = "INSERT INTO entity_saved_search (entity_key, saved_search_key, ts) VALUES (?, ?, ?)";
		$stmt = $db->prepare($entry_query);
		$stmt->execute(array(
			$entity, $saved_search_key, $t));
		}

		$db->commit();
		echo $saved_search_key;
	}
	catch(Exception $e) {

	    echo $e->getMessage();
	    $db->rollBack();
	}
}
?>