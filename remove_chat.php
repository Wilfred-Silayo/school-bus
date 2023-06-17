<?php

include('database/conn.php');

if(isset($_POST["id"]))
{
	$query = "
	DELETE from messages 
	WHERE id = '".$_POST["id"]."'
	";

	$statement = $conn->prepare($query);

	$statement->execute();
}

?>