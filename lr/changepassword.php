<?php 
include 'core/init.php';
protect_page();
if (empty($_POST) === false){
	$required_fields = array('current_password','password','password_again');
	foreach ($_POST as $key=>$value){
		if (empty($value) && in_array($key, $required_fields) === true){
			$errors[] = 'Fields marked with * are required';
			break 1; 
		}
	}
	
	if (md5($_POST['current_password']) === $user_data['password']) {
		if (trim($_POST['password']) !== trim($_POST['password_again'])){
		    $errors[] = 'Your new password do not match';
		} else if (strlen($_POST['password']) < 6) {
			$errors[] = 'Your password must be at least 6 characters';
		}
	} else {
		$errors[] = 'Your current password is incorrect';
	}
	
	
}
include 'includes/overall/header.php'
 ?> 

    <div id="content">
    
    <h1>Change Password</h1>
<?php
if (isset($_GET['success']) === true && empty($_GET['success']) === true){
	echo 'You\'ve successfully changed your password!';

} else {
	if(isset($_GET['force']) === true && empty($_GET['force']) === true){
	?>
	<p>You must change your password now that you've requested.</p>
	<?php
	}
	
	
	if (empty($_POST) === false && empty($errors) === true) {
		//posted no errors with form
		change_password($session_user_id, $_POST['password']);
		header('Location: changepassword.php?success');
		
		exit();
		
	} else if (empty($errors) === false){
		echo output_errors($errors);
	}
?>
     
    <section>
	<form action="" method="post">
		<ul>      	
			<li>
				Current Password*:<br/>
				<input type="password" name="current_password">
			</li>
			<li>
				New Password*:<br/>
				<input type="password" name="password">
			</li>
			<li>
				New Password Again*:<br/>
				<input type="password" name="password_again">
			</li>
			<br/>
			<li>
				<input type="submit" value="Change Password">
			</li>
		</ul>			
	</form>
    </section>
</div> <!-- /end #content-->
      
<?php 
}
include 'includes/overall/footer.php' 
?>