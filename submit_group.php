<?php 
//if not logged in redirect to login page
require('includes/config.php');

if(!$user->is_logged_in()){ header('Location: ' . $thispath); } 

$edcreate = "";

$user_key = $_POST['user_key'];

$memkeys = $_POST['memkeys'];

$memkeys = array_map("unserialize", array_unique(array_map("serialize", $memkeys)));

foreach ($memkeys as $key => $value) {
  if($value["member"] == "") {
    unset($memkeys[$key]);
  }
}

if (isset($_POST['entity_key']) && $_POST['entity_key'] != "") {

	$entity_key = $_POST['entity_key'];

	$stmt = $db->prepare("SELECT creator FROM entity WHERE entity_key = $entity_key");
	$stmt->execute();
	$row = $stmt->fetch(PDO::FETCH_ASSOC);
	$creator = $row['creator'];
}
else {
	$creator = "";
}

try {
    $db->beginTransaction();

	// if there is an entity_key:

    if (isset($_POST['entity_key']) && $_POST['entity_key'] != "") {
		
		// if the user_key matches the entity_key's creator field:
     	if ($creator == $user_key) {

    		// if someone is editig a group s/he created:
   			// update all the variables in entity (name etc)

    		$t = date('Y-m-d H:i:s',time());

   			$stmt = $db->prepare("UPDATE entity SET entity = :entity, public = :public, url = :url, ts = :ts WHERE entity_key = $entity_key ");
   			$stmt->execute(array(':entity' => $_POST['entity'], ':public' => $_POST['public'], ':url' => $_POST['url'], ':ts' => $t));

   			// remove all the entries in member_entity
   			$stmt = $db->prepare("DELETE FROM member_entity WHERE entity_key = $entity_key ");
   			$stmt->execute();

   			// insert each member into member_entity (remember the timestamp)
   			foreach ($memkeys as $key => $value) {
   				$stmt = $db->prepare(" INSERT INTO member_entity (member_key, entity_key, leader, ts) VALUES (:member_key, :entity_key, :leader, :ts) ");
   				$stmt->execute(array(':member_key' => $value["member"], ':entity_key' => $entity_key, ':leader' => $value["leader"], ':ts' => $t));	
   			}

   			$edcreate = "edited";
    	}
    	else {
    		// if the user_key is not the creator
    		makenew($db, $user_key, $_POST['entity'], $_POST['public'], $memkeys);
    		$edcreate = "created a copy of";
    	}
    }
    else {
    	// if there is no entity_key (it's brand new)
   		makenew($db, $user_key, $_POST['entity'], $_POST['public'], $memkeys);
   		$edcreate = "created";
    }

    $db->commit();

    if ($_POST['public'] == 1) {
    	$pubpriv = "public";
    }
    else {
    	$pubpriv = "private";
    }
    $num = count($memkeys);

    echo "You have $edcreate a new $pubpriv group called $_POST[entity], with $num members.";

} catch (Exception $e) {
	echo "An error happened: " . $e;

    $db->rollback();
}



function makenew($db, $user_key, $entity, $public, $memkeys) {

	// insert a new entity (don't forget timestamp), make user the creator, get last entry id
	$t = date('Y-m-d H:i:s',time());

	$stmt = $db->prepare(" INSERT INTO entity (entity, public, creator, url, ts) VALUES (:entity, :public, :creator, :url, :ts) ");
	$stmt->execute(array(':entity' => $entity, ':public' => $public, ':creator' => $user_key, ':url' => $_POST['url'], ':ts' => $t));
	$id = $db->lastInsertId('entity_key');

	// insert it into user_entity, making user the creator (don't forget timestamp)
	$stmt = $db->prepare(" INSERT INTO user_entity (user_key, entity_key, ts) VALUES (:user_key, :entity_key, :ts) ");
	$stmt->execute(array(':user_key' => $user_key, ':entity_key' => $id, ':ts' => $t));

	// insert each member into member_entity (remember the timestamp)
	foreach ($memkeys as $key => $value) {
		$stmt = $db->prepare(" INSERT INTO member_entity (member_key, entity_key, leader, ts) VALUES (:member_key, :entity_key, :leader, :ts) ");
		$stmt->execute(array(':member_key' => $value["member"], ':entity_key' => $id, ':leader' => $value["leader"], ':ts' => $t));	
	}
}

?>

