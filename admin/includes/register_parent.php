<?php
session_start();
include '../../database/conn.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Define variables to hold the form field values and error messages
    $firstName = $middleName = $lastName = $email = $phone =$password = "";
    $fnameError = $mnameError = $lnameError = $emailError = $phoneError = "";

    // Validate the first name field
    if (empty($_POST["firstName"])) {
        $fnameError = "Please enter first name";
    } else {
        $firstName = test_input($_POST["firstName"]);
    }

    // Validate the middle name field
    if (empty($_POST["middleName"])) {
        $mnameError = "Please enter middle name";
    } else {
        $middleName = test_input($_POST["middleName"]);
    }

    // Validate the last name field
    if (empty($_POST["lastName"])) {
        $lnameError = "Please enter last name";
    } else {
        $lastName = test_input($_POST["lastName"]);
        $password = password_hash(strtoupper($lastName), PASSWORD_DEFAULT);
    }

    // Validate the email field
    if (empty($_POST["email"])) {
        $emailError = "Please enter email";
    } else {
        $email = test_input($_POST["email"]);
    }

    // Validate the phone field
    if (empty($_POST["phone"])) {
        $phoneError = "Please enter phone number";
    } else {
        $phone = test_input($_POST["phone"]);
    }

    if (empty($fnameError) && empty($mnameError) && empty($lnameError) && empty($emailError) && empty($phoneError)) {
        
        $year = date('Y'); 
        $number = 1; 

        $sql = "SELECT id FROM parents ORDER BY id DESC LIMIT 1";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $lastId = $row['id'];

            // Extract the number from the last inserted ID
            $lastNumber = explode('-', $lastId)[2];

            // Increment the number by 1
            $number = intval($lastNumber) + 1;
        }
            
            $numberFormatted = str_pad($number, 4, '0', STR_PAD_LEFT);

            $id = 'SBTS-' . $year . '-' . $numberFormatted;

            $sql = "INSERT INTO parents (id, firstName, middleName, lastName, email, phone, password) VALUES (?, ?, ?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);

            $stmt->bind_param("sssssss", $id, $firstName, $middleName, $lastName, $email, $phone, $password);

            try {
                $stmt->execute();
            
                if ($stmt->affected_rows > 0) {
                    $_SESSION['success_message']=" Parent registered successfully";
                    header("Location: ../add_parent.php");
                    exit();
                } else {
                    // Failed to register parent
                    header("Location: ../add_parent.php?error=registration_failed");
                    exit();
                }
            } catch (mysqli_sql_exception $e) {
                // Handle duplicate entry error
                if ($e->getCode() === 1062) {
                    $emailError = "Email already exists. Please choose a different email.";
                    header("Location: ../add_parent.php?emailError=" . urlencode($emailError));
                    exit();
                } else {
                    // Other database error occurred
                    header("Location: ../add_parent.php?error=database_error");
                    exit();
                }
            }
            
            $stmt->close();
            $conn->close();

    }
    else{
        $query = http_build_query([
            'fnameError' => $fnameError,
            'mnameError' => $mnameError,
            'lnameError' => $lnameError,
            'emailError' => $emailError,
            'phoneError' => $phoneError
        ]);
        header("Location: ../add_parent.php?" . $query);
        exit();
    }

}
    

// Function to clean input data
function test_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}
?>