<?php



include('database/conn.php');

session_start();

echo fetch_user_chat_history($_SESSION['parent'], $_POST['recipient_id'], $conn);

?>