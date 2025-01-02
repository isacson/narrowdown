<?php 
//if not logged in redirect to login page
require('includes/config.php');

if(!$user->is_logged_in()){ header('Location: ' . $thispath); } 

$rows = [];

$blobs = $_POST['blobs'];

foreach ($blobs as $key => $blob) {

	if (count($blob) == 1) {
		$blob[1] = $blob[0];
	}

	// let's see if the lastname yields one result

	$stmt = $db->prepare("SELECT member_key, COUNT(*) AS count FROM member WHERE lastname LIKE :lastname ORDER BY firstname");
	$stmt->execute(array(':lastname' => "%".$blob[1]."%"));

	$row = $stmt->fetch(PDO::FETCH_ASSOC);

	if ($row["count"] > 1) {

		$stmt2 = $db->prepare("SELECT member_key FROM member WHERE lastname LIKE :lastname AND firstname LIKE :firstname ");

		$stmt2->execute(array(':lastname' => "%".$blob[1]."%", ':firstname' => "%".$blob[0]."%"));

		$row2 = $stmt2->fetch(PDO::FETCH_ASSOC);

		$member_key = $row2["member_key"];

		if ($member_key == "") {

			$stmt2 = $db->prepare("SELECT member_key FROM member WHERE lastname LIKE :lastname AND nickname LIKE :nickname ");

			$stmt2->execute(array(':lastname' => "%".$blob[1]."%", ':nickname' => "%".$blob[0]."%"));

			$row2 = $stmt2->fetch(PDO::FETCH_ASSOC);

			$member_key = $row2["member_key"];

			if ($member_key == "") {

				$member_key = $row["member_key"];
			}
		}
	}
	if ($row["count"] == 1) {
		$member_key = $row["member_key"];
	}

	if (isset($member_key)) {
		$query = " SELECT
			member_key
			, senrep.senrep AS senrep
			, firstname
			, lastname
			, nickname
			, party.party AS party
			, state.state AS state

			FROM member

			INNER JOIN party ON party.party_key = member.party
			INNER JOIN state ON state.state_key = member.state
			INNER JOIN senrep ON senrep.senrep_key = member.senrep 

			WHERE member_key = $member_key ";

		if(!$result = $db->query($query))	{
			die('There was an error running the items query [' . $db->error . ']');
		}

		$row = $result->fetch(PDO::FETCH_ASSOC);
		$rows[] = $row;
	}
}

$rows = array_map("unserialize", array_unique(array_map("serialize", $rows)));

echo json_encode($rows);

?>

