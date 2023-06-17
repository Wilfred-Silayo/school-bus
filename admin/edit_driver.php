<?php include 'includes/main.php'; ?>

<?php
include '../database/conn.php';
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $driverId = $_POST['driverId'];
    $firstName = $_POST['firstName'];
    $middleName = $_POST['middleName'];
    $lastName = $_POST['lastName'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];

    $stmt = $conn->prepare("UPDATE drivers SET firstName = ?, lastName = ?, middleName = ?,email = IFNULL(?, email), phone = ? WHERE licence = ?");
    $stmt->bind_param("ssssss", $firstName, $lastName, $middleName, $email, $phone, $driverId);

    if ($stmt->execute()) {
        $_SESSION['success_message'] = "Driver updated successfully";
        echo '<script>window.location.href = "manage_driver.php";</script>';
        exit();
    } else {
        $_SESSION['error_message'] = "Failed to update driver";
        echo '<script>window.location.href = "manage_driver.php";</script>';
        exit();
    }
} else {
    if (isset($_GET['licence'])) {
        $driverId = $_GET['licence'];

        $stmt = $conn->prepare("SELECT * FROM drivers WHERE licence = ?");
        $stmt->bind_param("s", $driverId);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows == 1) {
            $driver = $result->fetch_assoc();
        } else {
            $_SESSION['error_message'] = "Driver not found";
            echo '<script>window.location.href = "manage_driver.php";</script>';
            exit();
        }
    } else {
        $_SESSION['error_message'] = "Invalid request";
        echo '<script>window.location.href = "manage_driver.php";</script>';
        exit();
    }
}
?>

<div class="container-fluid bg-white my-3">
    <h4>Edit Driver</h4>
    <form method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>">
        <input type="hidden" name="driverId" value="<?php echo $driverId; ?>">
        <div class="form-group">
            <div class="row mb-3">
                <div class="col-md-4 col-sm-3">
                    <label for="firstName">First Name</label>
                </div>
                <div class="col-md-7 col-sm-5">
                    <input type="text" class="form-control" name="firstName" value="<?php echo $driver['firstName']; ?>"
                        required>
                </div>
            </div>
        </div>
        <div class="form-group">
            <div class="row mb-3">
                <div class="col-md-4 col-sm-3">
                    <label for="middleName">Middle Name</label>
                </div>
                <div class="col-md-7 col-sm-5">
                    <input type="text" class="form-control" name="middleName" value="<?php echo $driver['middleName']; ?>"
                        required>
                </div>
            </div>
        </div>
        <div class="form-group">
            <div class="row mb-3">
                <div class="col-md-4 col-sm-3">
                    <label for="lastName">Last Name</label>
                </div>
                <div class="col-md-7 col-sm-5">
                    <input type="text" class="form-control" name="lastName" value="<?php echo $driver['lastName']; ?>"
                        required>
                </div>
            </div>
        </div>
        <div class="form-group">
            <div class="row mb-3">
                <div class="col-md-4 col-sm-3">
                    <label for="email">Email</label>
                </div>
                <div class="col-md-7 col-sm-5">
                    <input type="text" class="form-control" name="email" value="<?php echo $driver['email']; ?>"
                        required>
                </div>
            </div>
        </div>
        <div class="form-group">
            <div class="row mb-3">
                <div class="col-md-4 col-sm-3">
                    <label for="phone">Phone</label>
                </div>
                <div class="col-md-7 col-sm-5">
                    <input type="text" class="form-control" name="phone" value="<?php echo $driver['phone']; ?>"
                        required>
                </div>
            </div>
        </div>
        <button type="submit" class="btn btn-primary mt-2">Update</button>
        <a href="manage_driver.php" class="btn btn-secondary mt-2">Cancel</a>
    </form>

</div>

</div>
</div>
</div>
<script src="../bootstrap/js/bootstrap.js"></script>
</body>

</html>