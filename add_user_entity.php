<?php
require('includes/config.php');

$t = date('Y-m-d H:i:s',time());

foreach ($_POST["checked_ents"] as $key => $value) {

	$stmt = $db->prepare('INSERT INTO user_entity (user_key, entity_key, ts) VALUES (:user_key, :entity_key, :ts)');
	$stmt->execute(array(
		':user_key' => $_POST['user_key'],
		':entity_key' => $value,
		':ts' => $t
	));
}

echo "success";

?>