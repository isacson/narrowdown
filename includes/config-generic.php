<?php
if (!isset($thispath)) {
	ob_start();
	session_start();

	//set timezone
	date_default_timezone_set('America/New_York');

	//database credentials
	define('DBHOST','localhost');
	define('DBUSER','<<USERNAME HERE>');
	define('DBPASS','<<PASSWORD HERE>>');
	define('DBNAME','<<I’M USING adamisac_narrowdown>>');

	//application address
	define('DIR','https://<<YOUR SITE HERE>>');
	define('SITEEMAIL','<<YOUR EMAIL HERE>>');

	$thispath = "<<PATH IF A SUBFOLDER>>";
	$sitename = "<<GIVE A NAME LIKE Legislative Haystack Needle-Finder>>";

	try {

		//create PDO connection
		$db = new PDO("mysql:host=".DBHOST.";dbname=".DBNAME, DBUSER, DBPASS);
		$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

	} catch(PDOException $e) {
		//show error
	    echo '<p class="bg-danger">'.$e->getMessage().'</p>';
	    exit;
	}

	//include the user class, pass in the database connection
	include('classes/user.php');
	include('classes/phpmailer/mail.php');
	$user = new User($db);
}
?>
