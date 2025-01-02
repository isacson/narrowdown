<?php
require('includes/config.php');

if (isset($_POST['sort']) && $_POST['sort'] != "") {
	if ($_POST['sort'] = "timestamp") {
		$orderby = " ORDER BY saved_search.timestamp DESC ";
	}
	else {
		$orderby = " ORDER BY saved_search.saved_search ASC ";
	}
}
else {
	$orderby = " ORDER BY saved_search.saved_search ASC ";
}

$query = " SELECT * FROM saved_search 
	INNER JOIN user_saved_search ON user_saved_search.saved_search_key = saved_search.saved_search_key
	INNER JOIN user ON user.user_key = user_saved_search.user_key 
	WHERE user.user_key = $_POST[user_key] $orderby ";

// echo $query;

if(!$result = $db->query($query))	{
	die('There was an error running the saved search query [' . $db->error . ']');
}
$row = $result->fetch(PDO::FETCH_ASSOC);
$rows = array();

do {
	$rows[] = $row;
}
while($row = $result->fetch(PDO::FETCH_ASSOC));

echo json_encode($rows);

?>