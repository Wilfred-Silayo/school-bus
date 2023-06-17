<?php
include 'parent/session.php';

$messageCount = count_all_unseen_message($_SESSION['parent'], $conn);


echo $messageCount;
?>
