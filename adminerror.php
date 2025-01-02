<?php 
//if not logged in redirect to login page
require('includes/config.php');

if($_SESSION['username'] != "admin"){ header('Location: ' . $thispath); } 

//define page title
$title = 'Admin errors';

echo <<<_END

<!-- adminerror -->

<p>Hey, admin: here are some possible errors to the database. <a onclick='memberPage()'>Or just go back to the Congress Finder page</a>.</p>

_END;

$query = "SELECT * FROM admin_error";

if(!$result = $db->query($query))	{
	die('There was an error running the admin error query [' . $db->error . ']');
}

$row = $result->fetch(PDO::FETCH_ASSOC);

if ($row["admin_error"] != "") {
	echo "<ul>";
	do {
		$userquery = "SELECT user from user WHERE user_key = $row[user_key]";
		if(!$userresult = $db->query($userquery))	{
			die('There was an error running the error username query [' . $db->error . ']');
		}
		$userrow = $userresult->fetch(PDO::FETCH_ASSOC);
		echo "<li>From $userrow[user]: \"$row[admin_error]\"</li>";
	}
	while ($row = $result->fetch(PDO::FETCH_ASSOC));
	echo "</ul>";
}

?>