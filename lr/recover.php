<?php 
include 'core/init.php';
logged_in_redirect();
include 'includes/overall/header.php' ?>

    <div id="content">
    
    <h1>Recover</h1>
     
    <section>
	<?php
	if(isset($_GET['success']) === true && empty($_GET['success']) === true){
		echo '<p>Thank you,We have emailed you. Please check your inbox</p>';
	}else{	
			$mode_allowed = array('username', 'password');
			if(isset($_GET['mode']) === true && in_array($_GET['mode'], $mode_allowed) === true){
				if (isset($_POST['email']) === true && empty($_POST['email']) === false){
					if (email_exists($_POST['email']) === true){
						recover($_GET['mode'], $_POST['email']);
						header('Location: recover.php?success');
					} else {
						echo '<p>Oops, we couldn\'t find that email address!</p>';
					}
				}
		?>
				<form action="" method="POST">
						<ul>
							<li>
								Please enter your email address:<br/>
								<input type="text" name="email">
							</li>
							<br/>
							<li>
								<input type="submit" value="Recover">
							</li>
						</ul>
				</form>
		<?php
			} else {
				header ('Location:index.php');
				exit();
			}
		}
	?>  

    </section>
</div> <!-- /end #content-->
      
<?php include 'includes/overall/footer.php' ?>