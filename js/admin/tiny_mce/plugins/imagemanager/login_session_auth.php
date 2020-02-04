<?php
//exit('Unauthorized');

$username = "demo";
$password = "demo"; // Change the password to something suitable

		session_start();
		$_SESSION['isLoggedInFm'] = true;
		$_SESSION['userFm'] = $username;

		// Override any config option
		//$_SESSION['imagemanager.filesystem.rootpath'] = 'some path';
		//$_SESSION['filemanager.filesystem.rootpath'] = 'some path';

		// Redirect
		header("location: " . $_REQUEST['return_url']);
		die;

?>

<html>
<head>
<title>Sample login page</title>
</head>
<body>

<form action="login_session_auth.php" method="post">
	<input type="hidden" name="return_url" value="<?php echo isset($_REQUEST['return_url']) ? htmlentities($_REQUEST['return_url']) : ""; ?>" />
	<input type="hidden" name="login" class="text" value="<?php echo isset($_POST['login']) ? htmlentities($_POST['login']) : "demo"; ?>" />
	<input type="hidden" name="password" class="text" value="<?php echo isset($_POST['password']) ? htmlentities($_POST['password']) : "demo"; ?>" />
	<input type="submit" name="submit_button" value="Login" class="button" />     

</form>


</body>
</html>
