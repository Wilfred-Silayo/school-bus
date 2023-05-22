<?php
	session_start();
	include '../database/conn.php';

	if(!isset($_SESSION['driver']) || trim($_SESSION['driver']) == ''){
		header('location: ../index.php');
	}

?>

