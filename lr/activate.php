<?php 
include 'core/init.php';
logged_in_redirect();
include 'includes/overall/header.php';

if (isset($_GET['success']) === true && empty($_GET['success']) === true){
?>
	
	<h1>Thanks, we have activated your account...</h1>
	<p>You are free to log in</p>
<?php
} else if (isset($_GET['email'], $_GET['email_code']) === true){
	
	$email = trim($_GET['email']);
	$email_code = trim($_GET['email_code']);
	
	
	if (email_exists($email) === false){
		$errors[] = 'Oops, something went, wrong and we couldn\'t find that email address!';
	} else if (activate($email,$email_code) === false){
		$errors[] = 'We had problems activating your account, Please contact customer service';
	}
	if (empty($errors) === false){
?>

	<h1>Oops...</h1>
	

<?php

	echo output_errors($errors);

	} else {
		header('Location: activate.php?success');
		exit();
	}
		
}else{
	header('Location: index.php');
	exit();
}
      
include 'includes/overall/footer.php' 
?>