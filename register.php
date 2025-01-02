<?php require('includes/config.php'); 

$query = " SELECT COUNT(*) AS count FROM entity WHERE public = 1 ";

// echo $query;

if(!$result = $db->query($query))	{
	die('There was an error running the saved search query [' . $db->error . ']');
}
$row = $result->fetch(PDO::FETCH_ASSOC);

$count = $row["count"]-3;

?>

<p style="float: right; margin-top:-2.75em; margin-right:0.5em;" align="right">(<a class="tutolink" onclick="tutorial()">tutorial/about</a>)</p>

<div id="welcome_stranger">

	<div id="this_is_better">

		<h2 style="padding-top:0.25em;margin-bottom:0.25em;">Welcome, Guest!</h2>

		<h3>This is an unattractive but simple website that does one thing: pinpoint members of Congress who meet chosen criteria. (<a class="tutolink" onclick="tutorial()">Here&rsquo;s a quick tutorial.</a>) To work properly, it requires that you create a unique identity. (It&rsquo;s free, and I don&rsquo;t ask for your real name.)<br><br></h3>

		<h3>Until you register, you can only access the 10 most-shared groups of legislators below. (Users are currently sharing <?php echo $count; ?> groups.) And you can&rsquo;t create your own, or save your searches.</h3>

		<p id="orp"><br><button id="open_register" onclick="openRegister()">Sign up or sign in now.</button><p>

		<h3><br>In the meantime, scroll down and give it a try. &mdash;Cheers, <a style="color:white; text-decoration:underline;" href="https://www.wola.org/people/adam-isacson/">Adam Isacson</a></h3>

	</div>

	<div id="quick_login">

		Have an account? Log in:<br>
		<input type="text" name="user" tabindex="5" id="usera" placeholder="User Name"><br>
		<input type="password" name="password" id="passworda" tabindex="10" placeholder="Password"><br>
		<button name="login" onclick="loginButton()" tabindex="15" id="login" value="login">Log In</button>

	</div>

</div>

<!-- register -->

<div id="registry_form" class="row">

    <div class="col-xs-12 col-sm-8 col-md-6 col-sm-offset-2 col-md-offset-3">
		<h2>Please Sign Up or <a onclick='login()'>Sign In</a></h2>
		<p>Welcome! I hope you find this useful. In order to save your searches and groups, you need to create an identity.&mdash;Cheers, Adam Isacson</p>
		<p>Already a member? <a onclick='login()'>Log In</a></p>
		<hr>

		<?php
		//check for any errors
		$title = "Congress Finder";
		if(isset($error)){
			foreach($error as $error){
				echo '<p class="bg-danger">'.$error.'</p>';
			}
		}
		?>

		<div class="form-group">
			<input type="text" name="user" id="user" class="form-control input-lg" placeholder="User Name" value="<?php if(isset($error)){ echo $_POST['user']; } ?>" tabindex="30">
		</div>
		<div class="row">
			<div class="col-xs-6 col-sm-6 col-md-6">
				<div class="form-group">
					<input type="password" name="password" id="password" class="form-control input-lg" placeholder="Password" tabindex="50">
				</div>
			</div>
			<div class="col-xs-6 col-sm-6 col-md-6">
				<div class="form-group">
					<input type="password" name="passwordConfirm" id="passwordConfirm" class="form-control input-lg" placeholder="Confirm Password" tabindex="60">
				</div>
			</div>
		</div>

		<div class="row">
			<div class="col-xs-6 col-md-6"><button id="register" onclick="register()" class="btn btn-primary btn-block btn-lg" tabindex="70">Register</button></div>
		</div>
	</div>
</div>

<!-- guestpage -->

<div id="memberpagebody">

<div id="saved_searches_head" class="findercolumn">
	<h3>Your Saved Searches</h3>
	<div id="saved_searches" class="findercolumn_box">
		(Only registered users can have saved searches.)
	</div>
</div>

<div id="entities_head" class="findercolumn">
	<h3 class="bg-success">Start Here &rarr; 10 Most-Used Groups of Legislators</h3>
	<div id="guest_entities" class="guest_findercolumn_box">
	</div>
</div>

<div id="members_head" class="findercolumn">
	<h3>Matched Legislators</h3>

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
	</p>
	<div id="members" class="findercolumn_box">
	</div>
</div>

</div>


<?php 

echo "<script src='" . $thispath . "js/guestpage.js'></script>";
echo "<script src='" . $thispath . "js/login.js'></script>";
?>