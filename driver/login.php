<?php
session_start();
include '../database/conn.php';

if (isset($_POST['login'])) {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    if (empty($username) || empty($password)) {
        $_SESSION['error'] = 'Input driver credentials first';
    } else {
        $username = htmlspecialchars($username);
        $password = htmlspecialchars($password);

        $sql = "SELECT * FROM drivers WHERE licence='$username'";
        $query = $conn->query($sql);

        if ($query->num_rows < 1) {
            $_SESSION['error'] = 'Incorrect credentials';
        } else {
            $row = $query->fetch_assoc();
            if (password_verify($password, $row['password'])) {
                $_SESSION['driver'] = $row['licence'];
                $sub_query = "
				INSERT INTO login_details 
	     		(user_id) 
	     		VALUES ('".$row['licence']."')
				";
				$statement = $conn->prepare($sub_query);
				$statement->execute();
            } else {
                $_SESSION['error'] = 'Incorrect credentials password';
            }
        }
    }
}

header('location: index.php');
?>
