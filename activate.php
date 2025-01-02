<?php

require('includes/config.php');

//collect values from the url
$user_key = trim($_GET['x']);
$active = trim($_GET['y']);

//if id is number and the active token is not empty carry on
if(is_numeric($user_key) && !empty($active)){

	//update users record set the active column to Yes where the user_key and active value match the ones provided in the array
	$stmt = $db->prepare("UPDATE user SET active = 'Yes' WHERE user_key = :user_key AND active = :active");
	$stmt->execute(array(
		':user_key' => $user_key,
		':active' => $active
	));

	//if the row was updated redirect the user
	if($stmt->rowCount() == 1){

		//redirect to login page
		header('Location: ' . $thispath . 'index.php?action=active');
		exit;

	} else {
		echo "Your account could not be activated."; 
	}
	
}
?>