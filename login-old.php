<?php

require('includes/config.php');

if (!function_exists('random_int')) {
    function random_int($min, $max) {
        if (!function_exists('mcrypt_create_iv')) {
            trigger_error(
                'mcrypt must be loaded for random_int to work', 
                E_USER_WARNING
            );
            return null;
        }
        
        if (!is_int($min) || !is_int($max)) {
            trigger_error('$min and $max must be integer values', E_USER_NOTICE);
            $min = (int)$min;
            $max = (int)$max;
        }
        
        if ($min > $max) {
            trigger_error('$max can\'t be lesser than $min', E_USER_WARNING);
            return null;
        }
        
        $range = $counter = $max - $min;
        $bits = 1;
        
        while ($counter >>= 1) {
            ++$bits;
        }
        
        $bytes = (int)max(ceil($bits/8), 1);
        $bitmask = pow(2, $bits) - 1;

        if ($bitmask >= PHP_INT_MAX) {
            $bitmask = PHP_INT_MAX;
        }

        do {
            $result = hexdec(
                bin2hex(
                    mcrypt_create_iv($bytes, MCRYPT_DEV_URANDOM)
                )
            ) & $bitmask;
        } while ($result > $range);

        return $result + $min;
    }
}

//process login form if submitted
if(isset($_POST['logsubmit'])){

	$username = $_POST['user'];
	$password = $_POST['password'];

	if($user->login($username,$password)){ 
		$_SESSION['username'] = $username;

		if ($_POST['rememberme'] == 1) {
			onlogin($username, $db);
		}
		echo "success";
		exit;
	
	} else {
		$error[] = 'Wrong username or password or your account has not been activated.';
	}

}//end if submit

function onLogin($user, $db) {
    $token = random_str(255);
    storeTokenForUser($user, $token, $db);
    $cookie = $user . ':' . $token;
    $mac = hash_hmac('sha256', $cookie, "SECRET_KEY");
    $cookie .= ':' . $mac;
    setcookie('rememberme', $cookie);
}



function storeTokenForUser($username, $token, $db) {
	$stmt = $db->prepare("UPDATE user SET token = :token WHERE user = :user ");
	$stmt->execute(array(':token' => $token, ':user' => $username));
}

function random_str($length, $keyspace = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ')
{
    $str = '';
    $max = mb_strlen($keyspace, '8bit') - 1;
    for ($i = 0; $i < $length; ++$i) {
        $str .= $keyspace[random_int(0, $max)];
    }
    return $str;
}

?>

<!-- login -->

<div class="row">

    <div class="col-xs-12 col-sm-8 col-md-6 col-sm-offset-2 col-md-offset-3">
		<h2>Please Log In</h2>
		<p><a href='./'>Back to home page</a></p>
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
					echo "<h2 class='bg-success'>Your account is now active, you may now log in.</h2>";
					break;
				case 'reset':
					echo "<h2 class='bg-success'>Please check your inbox for a reset link.</h2>";
					break;
				case 'resetAccount':
					echo "<h2 class='bg-success'>Password changed, you may now login.</h2>";
					break;
			}

		}
		?>
		<div class="form-group">
			<input type="text" name="user" tabindex="5" id="usera" class="form-control input-lg" placeholder="User Name" value="<?php if(isset($error)){ echo $_POST['user']; } ?>">
		</div>

		<div class="form-group">
			<input type="password" name="password" id="passworda" tabindex="10" class="form-control input-lg" placeholder="Password">
		</div>

<!-- for later: <input type="checkbox" value="1" id="rememberme" name="rememberme"> <label for="rememberme">Remember me</label> / -->
				 
		<div class="row">
			<div class="col-xs-9 col-sm-9 col-md-9">

				<a onclick='reset()'>Forgot your Password?</a>
			</div>
		</div>
		
		<hr>
		<div class="row">
			<div class="col-xs-6 col-md-6"><button name="login" onclick="loginButton()" tabindex="15" id="login" value="login" class="btn btn-primary btn-block btn-lg" tabindex="5">Log In</button></div>
		</div>
	</div>
</div>

<?php echo "<script src='" . $thispath . "js/login.js'></script>"; ?>