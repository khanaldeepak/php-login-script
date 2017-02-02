<?php 
include 'core/init.php';
include 'includes/overall/header.php'; ?>

    <div id="content">
    
    <h1>Home</h1>
     
    <section>
        	<p>Template Content</p>   

    </section>
</div> <!-- /end #content-->
<?php
if (has_access($session_user_id, 1) === true){
	echo 'Admin';
} else if (has_access($session_user_id, 2) === true) {
	echo 'Moderator';
}
?>
      
<?php include 'includes/overall/footer.php'; ?>