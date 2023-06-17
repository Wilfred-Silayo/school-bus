<?php



include('../database/conn.php');

session_start();

echo fetch_user_chat_history($_SESSION['driver'], $_POST['recipient_id'], $conn);

?>