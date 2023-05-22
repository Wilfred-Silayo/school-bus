<?php
include 'includes/main.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $currentPassword = $_POST['current_password'];
    $newPassword = $_POST['new_password'];
    $confirmPassword = $_POST['confirm_password'];

    $adminId = $_SESSION['admin'];
    $stmt = $conn->prepare("SELECT password FROM admins WHERE id = ?");
    $stmt->bind_param("s", $adminId);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $hashedPassword = $row['password'];

    if (password_verify($currentPassword, $hashedPassword)) {
        if ($newPassword !== $confirmPassword) {
            $errorMessage = "New password and confirm password do not match";
        } else {
            $stmt = $conn->prepare("UPDATE admins SET password = ? WHERE id = ?");
            $hashedNewPassword = password_hash($newPassword, PASSWORD_DEFAULT);
            $stmt->bind_param("ss", $hashedNewPassword, $adminId);
            
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
                <input type="password" id="current_password" name="current_password" class="form-control" required>
            </div>
        </div>
        <div class="form-row mb-3">
            <div class="form-group col-md-6">
                <label for="new_password">New Password:</label>
                <input type="password" id="new_password" name="new_password" class="form-control" required>
            </div>
        </div>
        <div class="form-row mb-3">
            <div class="form-group col-md-6">
                <label for="confirm_password">Confirm Password:</label>
                <input type="password" id="confirm_password" name="confirm_password" class="form-control" required>
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
<script src="../bootstrap/js/bootstrap.js"></script>
</body>

</html>