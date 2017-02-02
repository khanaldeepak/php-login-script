<?php
$connect_error='sorry we are experiencing downtime';
mysql_connect('localhost','root','') or die($connect_error);
mysql_select_db('everestnews') or die($connect_error);
?>