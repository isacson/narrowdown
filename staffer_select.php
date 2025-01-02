<?php
require('includes/config.php');

// check that user is a wola staff member

$query = "SELECT wola_user FROM user WHERE user_key = $_POST[user_key]";
if(!$result = $db->query($query))	{
	die('There was an error running the members query [' . $db->error . ']');
}
$row = $result->fetch(PDO::FETCH_ASSOC);

if ($row["wola_user"] != 1) {
	exit();
}
else {

	$query = "SELECT * FROM staffer WHERE member_key = $_POST[member_key]";
	if(!$result = $db->query($query))	{
		die('There was an error running the members query [' . $db->error . ']');
	}
	$row = $result->fetchAll(PDO::FETCH_ASSOC);

	$text = "";

	do {
		$text .= stafferArray($row, "foreign_policy");
		$text .= stafferArray($row, "immigration");
		$text .= stafferArray($row, "defense");
	}
	while($row = $result->fetch(PDO::FETCH_ASSOC));
}

echo $text;

function stafferArray($row, $category) {
	$name = "";
	foreach ($row as $key => $value) {
		if ($value[$category] == 1) {
			switch ($category) {
				case "foreign_policy":
					if (!strpos($name, "Foreign Policy")) {
						$name .= "<br>Foreign Policy: ";
					}
					else {
						$name .= "; ";
					}
					$name .= $value["firstname"] . " ";
					if ($value["nickname"] != "") {
						$name .= "&ldquo;" . $value["nickname"] . "&rdquo; ";
					}
					$name .= $value["lastname"];
					if ($value["email"] != "") {
						$name .= " " . $value["email"];
					}
					break;
				case "immigration":
				if (!strpos($name, "Immigration")) {
					$name .= "<br>Immigration: ";
				}
				else {
					$name .= "; ";
				}
					$name .= $value["firstname"] . " ";
					if ($value["nickname"] != "") {
						$name .= "&ldquo;" . $value["nickname"] . "&rdquo; ";
					}
					$name .= $value["lastname"];
					if ($value["email"] != "") {
						$name .= " " . $value["email"];
					}
					break;
				case "defense":
					if (!strpos($name, "Defense")) {
						$name .= "<br>Defense: ";
					}
					else {
						$name .= "; ";
					}
					$name .= $value["firstname"] . " ";
					if ($value["nickname"] != "") {
						$name .= "&ldquo;" . $value["nickname"] . "&rdquo; ";
					}
					$name .= $value["lastname"];
					if ($value["email"] != "") {
						$name .= " " . $value["email"];
					}
					break;
			}
		}
	}
	return $name;
}

?>