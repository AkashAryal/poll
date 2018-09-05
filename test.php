<?php
ini_set('session.gc_maxlifetime', 3600);
session_set_cookie_params(3600);
session_start();

if(!isset($_SESSION['user_id'])){
	header("Location: login.html");
}
else{
	header("Location: user.php");
}
exit;
?>