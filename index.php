<?php 
require('includes/config.php');
require('layout/header.php');


//if logged in redirect to members page
// if( $user->is_logged_in() ){ 

//	echo "<div id='intro' style='visibility:hidden;'> </div>";
// }

if(isset($_GET['action']) && $_GET['action'] == 'reset' ){ 

	echo "<div id='reset' style='visibility:hidden;'> </div>";
}

if(isset($_GET['action']) && $_GET['action'] == 'active' ){ 

	echo "<div id='active' style='visibility:hidden;'> </div>";
}

if(isset($_GET['action']) && $_GET['action'] == 'resetAccount' ){ 

	echo "<div id='resetAccount' style='visibility:hidden;'> </div>";
}

if(isset($_GET['x'])){ 

	echo "<div id='x' style='visibility:hidden;'>$_GET[x]</div>";
}

if(isset($_GET['y'])){ 

	echo "<div id='y' style='visibility:hidden;'>$_GET[y]</div>";
}

if(isset($_GET['g'])){ 

	echo "<div id='g' style='visibility:hidden;'>$_GET[g]</div>";
}

if(isset($_GET['key'])){ 

	echo "<div id='key' style='visibility:hidden;'>$_GET[key]</div>";
}

//if form has been submitted process it
if(isset($_POST['submit'])){

	require('submitted.php');

}

?>

<div class="container" id="container">

</div>

<?php
//include header template
require('layout/footer.php');
?>
