<?php
include 'parent/session.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $message = $_POST['content'];

    if (!empty($message)) {
        $currentUserId = $_SESSION['parent'];
        $receiverId = 'Admin';

        $sql = "INSERT INTO messages (sender_id, recipient_id, content, sent_at, received_at) 
                VALUES (?, ?, ?, NOW(), NULL)";
        $stmt = $conn->prepare($sql);

        if ($stmt) {
            $stmt->bind_param("sss", $currentUserId, $receiverId, $message);
            if ($stmt->execute()) {
                $stmt->close();
                $conn->close();
                header('Location: messages.php');
                exit();
            } else {
                echo "Error: " . $stmt->error;
            }
        } else {
            echo "Error: " . $conn->error;
        }
    }
}
?>

