<?php

//update_last_activity.php

include('../database/conn.php');

session_start();

$query = "
UPDATE login_details 
SET last_activity = now() 
WHERE user_id = '".$_SESSION["driver"]."'
";

$statement = $conn->prepare($query);

$statement->execute();

?>

