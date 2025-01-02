<?php 
//if not logged in redirect to login page
require('includes/config.php');

if(!$user->is_logged_in()){ header('Location: ' . $thispath); } 

$t = date('Y-m-d H:i:s',time());

$stmt = $db->prepare(" INSERT INTO admin_error (admin_error, user_key, ts) VALUES (:admin_error, :user_key, :ts) ");
$stmt->execute(array(':admin_error' => $_POST['admin_error'], ':user_key' => $_POST['user_key'], ':ts' => $t));

echo "success";

?>

