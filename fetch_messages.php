<?php
include 'parent/session.php'; 

$current_user_id=$_SESSION['parent'];

$query = "SELECT * FROM messages WHERE sender_id = '$current_user_id' 
OR recipient_id = '$current_user_id' ORDER BY created_at DESC";
$result = $conn->query($query);

$messages = array();
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $messages[] = array(
            'sender_id' => $row['sender_id'],
            'recipient_id' => $row['recipient_id'],
            'content' => $row['content'],
            'sent_at' => $row['sent_at'],
            'received_at' => $row['received_at']
        );
    }
}

header('Content-Type: application/json');
echo json_encode($messages);
?>
