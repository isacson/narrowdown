<?php 
//if not logged in redirect to login page
require('includes/config.php');

if(!$user->is_logged_in()){ header('Location: ' . $thispath); } 

//define page title
$title = 'Add or Edit Groups';

if (isset($_POST['user_key']) && $_POST['user_key'] != "") {
	$user_key = $_POST['user_key'];
	echo "<div id='user_key' style='visibility:hidden;'>$user_key</div>";
}
else {

	echo "<p>An error has occurred. Please <a href='logout.php'>log out</a> and try to enter again.";
	exit;
} 

echo <<<_END

<!-- addgroup -->

<p class="welcome" align="right">Welcome, $_SESSION[username]. (<a class="tutolink" onclick="tutorial()">tutorial/about</a>) (<a href='logout.php'>log out</a>)</p>

_END;

if (isset($_POST['entity_key']) && $_POST['entity_key'] != "") {

	$entity_key = $_POST['entity_key'];

	echo "<div id='entity_key' style='visibility:hidden;'>$entity_key</div>";

	$query = "SELECT entity, url FROM entity WHERE entity_key = $entity_key";
	if(!$result = $db->query($query))	{
		die('There was an error running the entity query [' . $db->error . ']');
	}
	$row = $result->fetch(PDO::FETCH_ASSOC);
	$entity = $row['entity'];
	$url = $row['url'];
}

if(isset($entity)) {
	echo "<h3><span style='background-color: #dff0d8;'>&nbsp;1. Edit the group called:
	 <input type='text' id='entity' name='entity' maxlength='255' style='width: 43.5em' placeholder='Use a name you&rsquo;ll recognize later' value='$entity' tabindex='5' ";
}
else {
	echo "<h3><span style='background-color: #dff0d8;'>&nbsp;1. Give this group a name:</span>
	<input type='text' id='entity' name='entity' maxlength='255' style='width: 42.5em' placeholder='Use a name you&rsquo;ll easily recognize later' tabindex='5' ";
}

echo <<<_END

	></h3>
<div id="entity_msg"></div>

	<p style="padding-bottom:.25em;"><span style='background-color: #dff0d8; vertical-align: middle;'>&nbsp;2. Optional&mdash;add a URL (web address):</span> 
		<input type="text" id="url" name="url" tabindex='10' placeholder="Something online that provides context, like a committee membership page, letter text, or vote count" style="width: 46.5em"
_END;

if(isset($url)) {

	echo " value='$url'";
}

echo <<<_END

		>
	</p>

<div id="url_msg"></div>

<h3 style="padding-bottom:.25em;"><span style='background-color: #dff0d8; vertical-align: middle;'>&nbsp;3. This group should be:</span> 
	<input type="radio" id="makepublic" name="pubpri" value="1" tabindex="15" checked>
	<label for="makepublic">
		Public <span style="font-size:0.8em; font-weight: 300;">(other people can use it, but can&rsquo;t edit it)&nbsp;</span>
	</label>
	<input type="radio" id="makeprivate" name="pubpri" value="0" tabindex="20">
	<label for="makeprivate">
		Private <span style="font-size:0.8em; font-weight: 300;">(only I can see it)&nbsp;</span>
	</label>
</h3>

<div style="display:inline-block; width:100%; vertical-align:bottom;">
	<div class="findercolumn" style="width:36%; margin-right:1.5%; margin-left:0;">
		<h3><span style="background-color: #dff0d8;">&nbsp;4a. Add members of Congress to a group by writing or pasting them into this box:</span></h3>
	</div>
	<div class="findercolumn" style="width:2.5%;">
		<h3 id="or" align="center"><br>or</h3>
	</div>
	<div class="findercolumn" style="width:55.5%;">
		<h3 id="edit_or_add"><br><span style="background-color: #dff0d8;">4b. 

_END;

if (isset($entity)) {

	echo "Edit ";
}
else {

	echo "Add ";
}

echo <<<_END

		members one by one:</span></h3>
	</div>
</div>

<div id="pastebox_holder" class="findercolumn" style="width:25%;">
	<textarea name="pasted_members" id="pasted_members" placeholder="This box will try to find members of Congress within the text that you give it. But it isn&rsquo;t magic! If it can&rsquo;t recognize a name, it will either guess or skip it. So make sure that the blanks on the right match correctly. If not, correct the wrong ones manually.\n\nIt works best if you\n\n* Put each member of Congress on a separate line\n* List each one with the first name first -- though lastname comma firstname may also work\n\nExtra words (like &ldquo;D-California&rdquo;) may or may not mess things up. Give it a try, the box may still be able to read it." tabindex="25"></textarea>
</div>
<div id="pastebox_submit" class="findercolumn" style="width:10%;">
	<button id="submit_pastebox" class="addgroup_button" tabindex="30">
		Parse this list
	</button>
	<div id="pastebox_message">
	</div>
</div>
<div style="float:left;display:inline-block;width:3%;font-weight:bold;font-size:2em;">&rarr;</div>
<div id="name_inputs" class="findercolumn" style="width:39%; margin-left:0; padding-left:1%;"">
	<div id="groupmembers_box" class="findercolumn_box" align="center">
		<button id="deleteinputs">Delete all of these</button>
		<div id="groupmembers">
		</div>
	</div>
</div>
<div id="final_submit" class="findercolumn" style="width:15%;">
	<button id="submit_group" class="addgroup_button" style="background-color: #dff0d8;" tabindex="1000">
		Submit this group
	</button>
	<div>&nbsp;</div>
	<button id="clear_all" class="addgroup_button" tabindex="1005">
		Clear everything or make a new group
	</button>
	<div>&nbsp;</div>
	<button class="addgroup_button" onclick="memberPage()" tabindex="1010">Back to main page</button>

	<div id="submit_msg"></div>
</div>

_END;

echo "<script src='" . $thispath . "js/addgroup.js'></script>";
echo "<script src='" . $thispath . "js/jquery-ui.min.js'></script>";

?>