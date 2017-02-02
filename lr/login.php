<?php
include 'core/init.php'; 
logged_in_redirect();
if (empty($_POST)===false){
	$username= $_POST['username'];
	$password= $_POST['password'];
	
	if(empty($username)=== true || empty($password)=== true){
		$errors[]='You need to enter a username and password';
	} else if (user_exists($username)=== false){
		$errors[]='Username Not registered';
	} else if (user_active($username) === false){
		$errors[]='Username Not activated';
	} else {
		
		if(strlen($password) > 32){
			$errors[]='Password too Long';
		}
		
		$login = login($username, $password);
		if ($login === false){
			$errors[] = 'Username and Password are No match';
		} else{
			//set the user session
			$_SESSION['user_id'] = $login;
			header('Location:index.php');
			exit();
			
		}
	}
} else {
	$error[]= 'landed';
}
include 'includes/overall/header.php';

if (empty($errors) === false){
?>
	<h2>We tried to Log in, but..</h2>
	
<?php	
	echo output_errors($errors);
}
include 'includes/overall/footer.php'
?>