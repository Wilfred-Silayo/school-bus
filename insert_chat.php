<?php
include('database/conn.php');
session_start();

$recipient_id = $_POST['recipient_id'];
$sender_id = $_SESSION['parent'];
$content = $_POST['content'];
$status = '1';

$query = "INSERT INTO messages (recipient_id, sender_id, content, status) VALUES (?, ?, ?, ?)";
$statement = $conn->prepare($query);
$statement->bind_param("ssss", $recipient_id, $sender_id, $content, $status);

if ($statement->execute()) {
	echo fetch_user_chat_history($_SESSION['parent'], $_POST['recipient_id'], $conn);
} else {
	echo "Error: " . $statement->error;
}

$statement->close();
$conn->close();
?>
