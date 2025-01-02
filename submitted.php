<?php 

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require('includes/config.php');

//very basic validation
if (strlen($_POST['user']) < 3) {
	$error[] = 'username is too short.';
} else {
	$stmt = $db->prepare('SELECT user FROM user WHERE user = :user');
	$stmt->execute(array(':user' => $_POST['user']));
	$row = $stmt->fetch(PDO::FETCH_ASSOC);

	if(!empty($row['user'])){
		$error[] = 'username provided is already in use.';
	}

}

if (strlen($_POST['password']) < 3) {
	$error[] = 'Password is too short.';
}

if (strlen($_POST['passwordConfirm']) < 3) {
	$error[] = 'Confirm password is too short.';
}

if ($_POST['password'] != $_POST['passwordConfirm']) {
	$error[] = 'Passwords do not match.';
}

//if no errors have been created carry on
if (!isset($error)) {

	//hash the password
	$hashedpassword = password_hash($_POST['password'], PASSWORD_BCRYPT);

	try {

		//insert into database with a prepared statement
		$stmt = $db->prepare('INSERT INTO user (user,password,active) VALUES (:user, :password, :active)');
		$stmt->execute(array(
			':user' => $_POST['user'],
			':password' => $hashedpassword,
			':active' => "yes"
		));
//			':active' => $activasion
		$id = $db->lastInsertId('user_key');

/*
		//give the new user a "sample search" (currently key # 72)
		$stmt2 = $db->prepare('INSERT INTO user_saved_search (user_key,saved_search_key) VALUES (:user_key, :saved_search_key)');
		$stmt2->execute(array(
			':user_key' => $id,
			':saved_search_key' => 1
		));

		// get the entities for that sample search
		$query = "SELECT entity.entity_key ek FROM entity 
				INNER JOIN entity_saved_search ON entity_saved_search.entity_key = entity.entity_key
				INNER JOIN saved_search ON entity_saved_search.saved_search_key = saved_search.saved_search_key
				WHERE saved_search.saved_search_key = 1";
		if (!$result = $db->query($query))	{
			die('There was an error running the saved search query [' . $db->error . ']');
		}
		$row = $result->fetch(PDO::FETCH_ASSOC);

		$t = date('Y-m-d H:i:s',time());

		do {
			$query2 = "INSERT INTO user_entity (user_key,entity_key, ts) VALUES ($id, $row[ek], '$t')";
			if (!$result2 = $db->query($query2))	{
				die('There was an error running the saved search query [' . $db->error . ']');
			}
		}
		while($row = $result->fetch(PDO::FETCH_ASSOC));

		// add one sample entity that's not part of the sample saved search -- makes clearer what the "saved search" means
		$query2 = "INSERT INTO user_entity (user_key,entity_key,ts) VALUES ($id, 40, '$t')";
		if (!$result2 = $db->query($query2))	{
			die('There was an error running the saved search query [' . $db->error . ']');
		}

*/
		echo "<h2>Registration was successful! <a onclick='login()'>You can log in now</a>.</h2>";
		exit;

	//else catch the exception and show the error.
	} 
	catch(PDOException $e) {
	    $error[] = $e->getMessage();
	}

}

?>
<!-- register -->

	<div class="row">

	    <div class="col-xs-12 col-sm-8 col-md-6 col-sm-offset-2 col-md-offset-3">
			<h2>Please <a onclick='login()'>Log in here</a></h2>
			<p>&nbsp;</p>
			<hr>
			<p>Not a member? Register here:</p>

			<?php
			//check for any errors
			if(isset($error)){
				foreach($error as $error){
					echo '<p class="bg-danger">'.$error.'</p>';
				}
			}
			?>

			<div class="form-group">
				<input type="text" name="user" id="user" class="form-control input-lg" placeholder="User Name" value="<?php if(isset($error)){ echo $_POST['user']; } ?>" tabindex="1">
			</div>
			<div class="row">
				<div class="col-xs-6 col-sm-6 col-md-6">
					<div class="form-group">
						<input type="password" name="password" id="password" class="form-control input-lg" placeholder="Password" tabindex="3">
					</div>
				</div>
				<div class="col-xs-6 col-sm-6 col-md-6">
					<div class="form-group">
						<input type="password" name="passwordConfirm" id="passwordConfirm" class="form-control input-lg" placeholder="Confirm Password" tabindex="4">
					</div>
				</div>
			</div>

			<div class="row">
				<div class="col-xs-6 col-md-6"><button id="register" onclick="register()" class="btn btn-primary btn-block btn-lg" tabindex="5">Register</button></div>
			</div>
		</div>
	</div>