<?php 
include 'core/init.php';
protect_page();
admin_protect();
include 'includes/overall/header.php'; ?>

    <div id="content">
    
    <h1>Email All Users</h1>
	<?php
	if (isset($_GET['success']) === true && empty($_GET['success']) === true) {
		?>
		<p>Email has been sent</p>
		<?php
		
	}else{
		
			if (empty($_POST) === false) {
				if (empty($_POST['subject']) === true){
					$errors[] = 'Subject is required';
				}
				
				if (empty($_POST['body']) === true){
					$errors[] = 'Body is required';
				}
				
				if (empty($errors) === false){
					echo output_errors($errors);
				}else {
					//send email
					mail_users($_POST['subject'], $_POST['body']);
					header('Location: mail.php?success');
				}
			}
		
		?>
		
		
		 
		<section>
			<form action="" method="POST">
			<ul>
				<li>
					Subject*:<br/>
					<input type="text" name="subject">
				</li>
				<li>
					Body*:<br/>
					<textarea name="body"></textarea>
				</li>
				<li>
					<br/>
					<input type="submit" name="Send">
				</li>
			</ul>
			</form>		

		</section>
	</div> <!-- /end #content-->
		  
	<?php 
		}
include 'includes/overall/footer.php'; ?>