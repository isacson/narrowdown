<?php 
//if not logged in redirect to login page
require('includes/config.php');

if(!$user->is_logged_in()){ header('Location: ' . $thispath); } 

$userkey_query = "SELECT user_key FROM user WHERE user = '$_SESSION[username]'";
if(!$userkey_result = $db->query($userkey_query))	{
	die('There was an error running the userkey query [' . $db->error . ']');
}
$userkey_row = $userkey_result->fetch(PDO::FETCH_ASSOC);

$user_key = $userkey_row['user_key'];

echo "<div id='user_key' style='visibility:hidden;'>$user_key</div>";

if ($user_key == "") {

	echo "<p>An error has occurred. Please <a href='logout.php'>log out</a> and try to enter again.";
	exit;
} 

echo <<<_END

<!-- memberpage -->

<div id="memberpagebody">

<p class="welcome">Welcome, $_SESSION[username]. (<a class="tutolink" onclick="tutorial()">tutorial/about</a>) (<a href='logout.php'>log out</a>)</p>

<button style="float:right; right:2%; position:absolute; margin-top:-2.5em;" id="clearAll">Clear everything</button>

_END;

if ($_SESSION['username'] == "admin") {

	$admin_error_query = "SELECT COUNT(*) num FROM admin_error";
	if(!$admin_error_result = $db->query($admin_error_query))	{
		die('There was an error running the admin error query [' . $db->error . ']');
	}
	$admin_error_row = $admin_error_result->fetch(PDO::FETCH_ASSOC);

	if ($admin_error_row["num"] > 0) {
		echo "<p id='admin_error_warn'>Hey, admin: people have submitted some possible errors to the database. <a onclick='adminError()'>Check them out here</a>.</p>";
	}
}

echo <<<_END

<div id="saved_searches_head" class="findercolumn">
	<h3>Your Saved Searches</h3>
	<div id="saved_searches" class="findercolumn_box">
	</div>
</div>

<div id="entities_head" class="findercolumn">
	<h3 class="bg-success">Start Here &rarr; Groups of Legislators</h3>
	<div id="addgroupp" align="center"><button id="makenew" onclick="addGroup('$user_key')";>Make a new group</button><br><button id="addPubEntBut" onclick="addPubEnt()">Add a group created by the community</button></div>
	<div id="newhere"></div>
	<div id="entsortsearch">
	</div>
	<div id="entities" class="findercolumn_box">
	</div>
</div>

<div id="members_head" class="findercolumn">
	<h3>Matched Legislators
	<span style="font-weight:normal; font-size:0.6em; vertical-align:middle;">(with text <input id="matchtext" name="matchtext" type="text" maxlength="10" style="width:25%;" placeholder="A few letters">)</span>
	</h3>

	<input type="checkbox" id="chamber1" value="1" name="chamber" onclick="chamberSelect()">
	<label for="chamber1">House
	</label>
	<input type="checkbox" id="chamber2" value="2" name="chamber" onclick="chamberSelect()">
	<label for="chamber2">Senate&nbsp;
	</label>

	<input type="checkbox" id="partyr" value="1" name="party" onclick="partySelect()">
	<label for="partyr">R&nbsp;
	</label>
	<input type="checkbox" id="partyd" value="4" name="party" onclick="partySelect()">
	<label for="partyd">D&nbsp;
	</label>
	<input type="checkbox" id="partyi" value="8" name="party" onclick="partySelect()">
	<label for="partyi">I&nbsp;
	</label>

	<p>Show members with <input type="text" size="2" id="matches" name="matches"> or more matches</p>
	<p align="center">
		<button id="selectMem" onClick="selectMem()">
			Select all
		</button>
		<button id="boldlead">
			Boldface group leaders
		</button>
		<button id="exportMem">
			Export as CSV
		</button>
	</p>
	<div id="members" class="findercolumn_box">
	</div>
</div>

</div>

_END;

// echo "<script src='" . $thispath . "js/jquery.tinysort.min.js'></script>";
echo "<script src='" . $thispath . "js/memberpage.js'></script>";

?>

