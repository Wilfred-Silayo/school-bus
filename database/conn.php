<?php
	$conn = new mysqli('localhost', 'root', '', 'school_bus');

	if ($conn->connect_error) {
	    die("Connection failed: " . $conn->connect_error);
	}
	
?>