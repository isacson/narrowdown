<?php
require('includes/config.php');

$query = " SELECT saved_search.saved_search_key FROM saved_search 
	INNER JOIN user_saved_search ON user_saved_search.saved_search_key = saved_search.saved_search_key
	INNER JOIN user ON user.user_key = user_saved_search.user_key 
	WHERE user.user_key = $_POST[user_key] ";

// echo $query;

if(!$result = $db->query($query))	{
	die('There was an error running the saved search query [' . $db->error . ']');
}
$row = $result->fetch(PDO::FETCH_ASSOC);

do {
	if ($row["saved_search_key"] == $_POST["saved_search_key"]) {
		$deletequery = "DELETE FROM saved_search WHERE saved_search_key = $_POST[saved_search_key]";
		if(!$deleteresult = $db->query($deletequery))	{
			die('There was an error running the delete search query [' . $db->error . ']');
		}
		echo "success";
	}
}
while($row = $result->fetch(PDO::FETCH_ASSOC));
?>