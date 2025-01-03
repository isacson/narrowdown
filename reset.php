<?php 

require('includes/config.php');

//if logged in redirect to members page
if($user->is_logged_in()){ header('Location: ' . $thispath); } 

//if form has been submitted process it
if(isset($_POST['submit'])){

	//email validation
	if(!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)){
	    $error[] = 'Please enter a valid email address';
	} else {
		$stmt = $db->prepare('SELECT email FROM user WHERE email = :email');
		$stmt->execute(array(':email' => $_POST['email']));
		$row = $stmt->fetch(PDO::FETCH_ASSOC);

		if(empty($row['email'])){
			$error[] = 'Email provided is not on recognised.';
		}

	}

	//if no errors have been created carry on
	if(!isset($error)){

		//create the activasion code
		$token = md5(uniqid(rand(),true));

		try {

			$stmt = $db->prepare("UPDATE user SET resetToken = :token, resetComplete='No' WHERE email = :email");
			$stmt->execute(array(
				':email' => $row['email'],
				':token' => $token
			));

			//send email
			$to = $row['email'];
			$subject = "Password Reset";
			$body = "<p>Someone requested that the password be reset.</p>
			<p>If this was a mistake, just ignore this email and nothing will happen.</p>
			<p>To reset your password, visit the following address: <a href='".DIR."index.php?key=$token'>".DIR."index.php?key=$token</a></p>";

			$mail = new Mail();
			$mail->setFrom(SITEEMAIL);
			$mail->addAddress($to);
			$mail->subject($subject);
			$mail->body($body);
			$mail->send();

			//redirect to index page

			header('Location: ' . $thispath . '?action=reset');
			exit;

		//else catch the exception and show the error.
		} catch(PDOException $e) {
		    $error[] = $e->getMessage();
		}

	}

}

//define page title
$title = 'Reset Account';

?>

<!-- reset -->

<div class="row">

    <div class="col-xs-12 col-sm-8 col-md-6 col-sm-offset-2 col-md-offset-3">
		<h2>Reset Password</h2>
		<p><a onclick="login()">Back to login page</a></p>
		<hr>

		<?php
		//check for any errors
		if(isset($error)){
			foreach($error as $error){
				echo '<p class="bg-danger">'.$error.'</p>';
			}
		}

		if(isset($_GET['action'])){

			//check the action
			switch ($_GET['action']) {
				case 'active':
					echo "<h2 class='bg-success'>Your account is now active you may now log in.</h2>";
					break;
				case 'reset':
					echo "<h2 class='bg-success'>Please check your inbox for a reset link.</h2>";
					break;
			}
		}
		?>

		<div class="form-group">
			<input type="email" name="email" id="email" class="form-control input-lg" placeholder="Email" value="" tabindex="1">
		</div>

		<hr>
		<div class="row">
			<div class="col-xs-6 col-md-6"><button name="reset" id="reset" onclick="reset()" value="Send Reset Link" class="btn btn-primary btn-block btn-lg" tabindex="2">Send Reset Link</button></div>
		</div>
	</div>
</div>