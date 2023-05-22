<?php
	session_start();
	include 'database/conn.php';

	if(!isset($_SESSION['parent']) || trim($_SESSION['parent']) == ''){
		header('location: index.php');
	}

?>

