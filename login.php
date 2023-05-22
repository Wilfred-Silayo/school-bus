<?php
session_start();
include './database/conn.php';

if (isset($_POST['login'])) {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    if (empty($username) || empty($password)) {
        $_SESSION['error'] = 'Input parent credentials first';
    } else {
        $username = htmlspecialchars($username);
        $password = htmlspecialchars($password);

        $sql = "SELECT * FROM parents WHERE id='$username'";
        $query = $conn->query($sql);

        if ($query->num_rows < 1) {
            $_SESSION['error'] = 'Incorrect credentials';
        } else {
            $row = $query->fetch_assoc();
            if (password_verify($password, $row['password'])) {
                $_SESSION['parent'] = $row['id'];
            } else {
                $_SESSION['error'] = 'Incorrect credentials password';
            }
        }
    }
}

header('location: index.php');
?>
