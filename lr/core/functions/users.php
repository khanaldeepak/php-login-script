<?php
function mail_users($subject, $body){
	$query = mysql_query("SELECT `email`, `first_name` FROM `user` WHERE `allow_email` = 1");
	while (($row = mysql_fetch_assoc($query)) !== false){
	
		email($row['email'], $subject, "Hello " . $row['first_name'] . ",\n\n" . $body);
	}
}

function has_access($user_id, $type) {
	$user_id = (int)$user_id;
	$type= (int)$type;
	
	return (mysql_result(mysql_query("SELECT COUNT(`user_id`) FROM `user` WHERE `user_id` = $user_id AND `type` = $type"), 0) == 1) ? true : false;
}

function recover($mode, $email){
	$mode = sanitize($mode);
	$email = sanitize($email);
	
	$user_data = user_data(user_id_from_email($email), 'first_name', 'username');
	
	if ($mode == 'username'){
		email($email, 'Your username', "Hello " . $user_data['first_name'] . ",\n\nYour username is: " . $user_data['username'] . "\n\n-thetimesofeverest");
		
	}else if ($mode == 'password'){
		$generated_password = substr(md5(rand(999,999999)), 0, 8);
		change_password($user_data['user_id'], $generated_password);
		
		update_user($user_data['user_id'], array('password_recover' => 1));
		
		email($email, 'Your password recovery', "Hello " . $user_data['first_name'] . ",\n\nYour new temporary password is: " . $generated_password . "\n\nPlease change your password for your account security after you log in with this temporary password.\n\n-thetimesofeverest");
		
	}	
}

function update_user($user_id, $update_data){
	$update = array(); 
	array_walk($update_data, 'array_sanitize');
	
	foreach($update_data as $field=>$data){
		$update[] = '`' . $field . '` = \'' . $data . '\'';
	}
	
	mysql_query("UPDATE `user` SET " . implode(', ', $update) . " WHERE `user_id` = $user_id");
}


function activate($email,$email_code){
	$email = mysql_real_escape_string($email);
	$email_code = mysql_real_escape_string($email_code);
	
	if (mysql_result(mysql_query("SELECT COUNT(`user_id`) FROM `user` WHERE `email` = '$email' AND `email_code` = '$email_code' AND `active` = 0"), 0) == 1) {
		mysql_query("UPDATE `user` SET `active` = 1 WHERE `email` = '$email'");
		return true;
	}else {
		return false;
	}
}

function change_password($user_id, $password){
	$user_id = (int)$user_id;
	$password = md5($password);
	
	mysql_query("UPDATE `user` SET `password` = '$password', `password_recover` = 0 WHERE `user_id` = $user_id");
}

function register_user($register_data){
	array_walk($register_data, 'array_sanitize');
	$register_data['password'] = md5($register_data['password']);
	
	$fields = '`' . implode('`, `', array_keys($register_data)) . '`';
	$data = '\'' . implode('\', \'', $register_data) . '\'';
	
	mysql_query("INSERT INTO `user` ($fields) VALUES ($data)");
	email($register_data['email'],'Activate your account',"Hello " . $register_data['first_name'] . ",\n\nClick the link below to activate your account: \n\n<a href='http://localhost/lr/activate.php?email=" . $register_data['email'] . "&email_code=" . $register_data['email_code'] . "'>Activate</a>\n\n-thetimesofeverest");
}

function user_count(){
	return mysql_result(mysql_query("SELECT COUNT(`user_id`) FROM `user` WHERE `active` = 1"), 0);
}

function user_data($user_id){
	$data = array();
	$user_id = (int)$user_id;
	
	$func_num_args = func_num_args();
	$func_get_args = func_get_args();
	
	if ($func_num_args > 1){
		unset($func_get_args[0]);
		
		$fields = '`' . implode('`, `', $func_get_args) . '`';
		$data =mysql_fetch_assoc(mysql_query("SELECT $fields FROM `user` WHERE `user_id` = $user_id"));
		
		return $data;
	}
	
}

function logged_in(){
	return (isset($_SESSION['user_id'])) ? true : false;
}

function user_exists($username){
	$username= sanitize($username);
	$query= mysql_query("SELECT COUNT(`user_id`) FROM `user` WHERE `username` = '$username'");
	return(mysql_result($query,0) == 1) ? true : false;
}

function email_exists($email){
	$username= sanitize($email);
	$query= mysql_query("SELECT COUNT(`user_id`) FROM `user` WHERE `email` = '$email'");
	return(mysql_result($query,0) == 1) ? true : false;
}

function user_active($username){
	$username= sanitize($username);
	$query= mysql_query("SELECT COUNT(`user_id`) FROM `user` WHERE `username` = '$username' AND `active` = 1");
	return(mysql_result($query,0) == 1) ? true : false;
}

function user_id_from_username($username){
	$username= sanitize($username);
	return mysql_result(mysql_query("SELECT `user_id` FROM `user` WHERE `username` ='$username'"), 0, 'user_id');
}

function user_id_from_email($email){
	$username= sanitize($email);
	return mysql_result(mysql_query("SELECT `user_id` FROM `user` WHERE `email` ='$email'"), 0, 'user_id');
}

function login($username, $password){
	$user_id = user_id_from_username($username);
	$username = sanitize($username);
	$password = md5($password);
	
	return (mysql_result(mysql_query("SELECT COUNT(`user_id`) FROM `user` WHERE `username` = '$username' AND `password` = '$password'"),0) == 1) ? $user_id : false;
}
?>