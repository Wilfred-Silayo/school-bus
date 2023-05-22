<?php
include 'includes/session.php'; 
include 'includes/header.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $currentPassword = $_POST['current_password'];
    $newPassword = $_POST['new_password'];
    $confirmPassword = $_POST['confirm_password'];

    $driverId = $_SESSION['driver'];
    $stmt = $conn->prepare("SELECT password FROM drivers WHERE licence = ?");
    $stmt->bind_param("s", $driverId);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $hashedPassword = $row['password'];

    if (password_verify($currentPassword, $hashedPassword)) {
        if ($newPassword !== $confirmPassword) {
            $errorMessage = "New password and confirm password do not match";
        } else {
            $stmt = $conn->prepare("UPDATE drivers SET password = ? WHERE licence = ?");
            $hashedNewPassword = password_hash($newPassword, PASSWORD_DEFAULT);
            $stmt->bind_param("ss", $hashedNewPassword, $driverId);
            
            if ($stmt->execute()) {
                $successMessage = "Password changed successfully";
            } else {
                $errorMessage = "Failed to change password";
            }

            $currentPassword = "";
            $newPassword = "";
            $confirmPassword = "";
        }
    } else {
        $errorMessage = "Invalid current password";
    }
}
?>

<body class="bg-light" style=" min-height:90vh;">
    <?php include 'includes/navbar.php'; ?>
    <div class="container-fluid">
        <div class="row flex-nowrap">
            <?php include 'includes/menubar.php'; ?>
            <!-- Content Wrapper. Contains page content -->
            <div class="col-md-9 col-xl-10">
                <div class="container-fluid bg-white my-3">
                    <h4>Password Change</h4>
                    <div class="col-md-6">
                        <?php if (isset($errorMessage)) : ?>
                        <div class="alert alert-danger"><?php echo $errorMessage; ?></div>
                        <?php endif; ?>
                        <?php if (isset($successMessage)) : ?>
                        <div class="alert alert-success"><?php echo $successMessage; ?></div>
                        <?php endif; ?>
                    </div>

                    <form method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                        <div class="form-row mb-3">
                            <div class="form-group col-md-6">
                                <label for="current_password">Current Password:</label>
                                <input type="password" id="current_password" name="current_password"
                                    class="form-control" required>
                            </div>
                        </div>
                        <div class="form-row mb-3">
                            <div class="form-group col-md-6">
                                <label for="new_password">New Password:</label>
                                <input type="password" id="new_password" name="new_password" class="form-control"
                                    required>
                            </div>
                        </div>
                        <div class="form-row mb-3">
                            <div class="form-group col-md-6">
                                <label for="confirm_password">Confirm Password:</label>
                                <input type="password" id="confirm_password" name="confirm_password"
                                    class="form-control" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <input type="submit" value="Change Password" class="btn btn-primary">
                        </div>
                    </form>
                </div>


            </div>
        </div>
    </div>
    <script src="bootstrap/js/bootstrap.js"></script>
</body>

</html>