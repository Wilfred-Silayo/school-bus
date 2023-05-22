<?php
include 'includes/main.php';

$licenceError = $fnameError = $mnameError = $lnameError = $emailError = $phoneError = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $licence = trim($_POST['licence']);
    $firstName = trim($_POST['firstName']);
    $middleName = trim($_POST['middleName']);
    $lastName = trim($_POST['lastName']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);

    if (empty($licence)) {
        $licenceError = 'Please enter the licence number';
    }

    if (empty($firstName)) {
        $fnameError = 'Please enter the first name';
    }
    if (empty($middleName)) {
        $lnameError = 'Please enter the last name';
    }

    if (empty($lastName)) {
        $lnameError = 'Please enter the last name';
    }

    if (empty($email)) {
        $emailError = 'Please enter your email';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $emailError = 'Invalid email format';
    }

    if (empty($phone)) {
        $phoneError = 'Please enter your phone number';
    }

    if (empty($licenceError) && empty($fnameError) &&empty($mnameError) && empty($lnameError) && empty($emailError) && empty($phoneError)) {

        $checkQuery = "SELECT * FROM drivers WHERE licence = '$licence'";
        $checkEmailQuery = "SELECT * FROM drivers WHERE email = '$email'";
        $checkResult = $conn->query($checkQuery);
        $checkEmailResult = $conn->query($checkEmailQuery);
        if ($checkResult->num_rows > 0) {
            $licenceError = 'The license number is already taken';
        } 
        else if($checkEmailResult->num_rows > 0){
            $emailError = 'The email is already taken';
        }
        else {
            $password = password_hash(strtoupper($lastName), PASSWORD_DEFAULT);
            $sql = "INSERT INTO drivers (licence, firstName, middleName, lastName, email, phone,password)
                    VALUES ('$licence', '$firstName', '$middleName', '$lastName', '$email', '$phone','$password')";

            if ($conn->query($sql) === TRUE) {
                $_SESSION['success_message'] = 'Driver registered successfully.';
            } else {
                echo 'An error occurred while registering the driver. Please try again.';
            }
        }

        $conn->close();
    }
}


if (isset($_SESSION['success_message'])) {
    echo '<div class="alert alert-success mt-2">' . $_SESSION['success_message'] . '</div>';
    
    unset($_SESSION['success_message']);
}
?>

<div class="container-fluid bg-white mt-3">
    <div class="row mb-3 border-bottom border-2">
        <h3 class="text-dark mb-2 mt-2">Add New Driver</h3>
    </div>
    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
        <!-- First Name -->
        <div class="form-group">
            <div class="row mb-3">
                <div class="col-md-3 col-sm-2">
                    <label for="licence">Licence Number:</label>
                </div>
                <div class="col-md-7 col-sm-5">
                    <input type="text" class="form-control" id="licence" name="licence" placeholder="Enter First Name">
                    <span id="licenceError" class="text-danger"><?php echo $licenceError; ?></span>
                </div>
            </div>
        </div>
        <!-- First Name -->
        <div class="form-group">
            <div class="row mb-3">
                <div class="col-md-3 col-sm-2">
                    <label for="firstName">First Name:</label>
                </div>
                <div class="col-md-7 col-sm-5">
                    <input type="text" class="form-control" id="firstName" name="firstName"
                        placeholder="Enter First Name">
                    <span id="fnameError" class="text-danger"><?php echo $fnameError; ?></span>
                </div>
            </div>
        </div>
        <!-- Middle Name -->
        <div class="form-group">
            <div class="row mb-3">
                <div class="col-md-3 col-sm-2">
                    <label for="middleName">Middle Name:</label>
                </div>
                <div class="col-md-7 col-sm-5">
                    <input type="text" class="form-control" id="middleName" name="middleName"
                        placeholder="Enter Middle Name">
                    <small id="mnameError" class="text-danger"><?php echo $mnameError; ?></small>
                </div>
            </div>
        </div>
        <!-- Last Name -->
        <div class="form-group">
            <div class="row mb-3">
                <div class="col-md-3 col-sm-2">
                    <label for="lastName">Last Name:</label>
                </div>
                <div class="col-md-7 col-sm-5">
                    <input type="text" class="form-control" id="lastName" name="lastName" placeholder="Enter Last Name">
                    <small id="lnameError" class="text-danger"><?php echo $lnameError; ?></small>
                </div>
            </div>
        </div>
        <!-- Email -->
        <div class="form-group">
            <div class="row mb-3">
                <div class="col-md-3 col-sm-2">
                    <label for="email">Email:</label>
                </div>
                <div class="col-md-7 col-sm-5">
                    <input type="email" class="form-control" id="email" name="email" placeholder="Enter your email">
                    <small id="emailError" class="text-danger"><?php echo $emailError; ?></small>
                </div>
            </div>
        </div>
        <!-- Phone -->
        <div class="form-group">
            <div class="row mb-3">
                <div class="col-md-3 col-sm-2">
                    <label for="phone">
                        Phone:</label>
                </div>
                <div class="col-md-7 col-sm-5">
                    <input type="text" class="form-control" id="phone" name="phone"
                        placeholder="Enter your phone number">
                    <small id="phoneError" class="text-danger"><?php echo $phoneError; ?></small>
                </div>
            </div>
        </div>
        <button type="submit" class="btn btn-primary">Register</button>
        <a href="home.php" class="btn btn-secondary">Cancel</a>
    </form>
</div>

</div>

</div>
</div>
</div>

<script src="../bootstrap/js/bootstrap.js"></script>
</body>

</html>