<?php
include 'includes/session.php';

$messageCount = count_all_unseen_message($_SESSION['admin'], $conn);


echo $messageCount;
?>