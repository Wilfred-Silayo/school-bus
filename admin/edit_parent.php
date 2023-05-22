<?php include 'includes/main.php'; ?>
<?php
include '../database/conn.php';
// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get the parent ID from the form
    $parentId = $_POST['parentId'];

    // Retrieve the updated parent information from the form
    $firstName = $_POST['firstName'];
    $middleName = $_POST['middleName'];
    $lastName = $_POST['lastName'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];

    // Prepare the update statement
    $stmt = $conn->prepare("UPDATE parents SET firstName = ?, middleName = ?, lastName = ?,  email = IFNULL(?, email), phone = ? WHERE id = ?");
    $stmt->bind_param("ssssss", $firstName, $middleName, $lastName, $email, $phone, $parentId);

    // Execute the update statement
    if ($stmt->execute()) {
        $_SESSION['success_message'] = "Parent updated successfully";
        echo '<script>window.location.href = "manage_parent.php";</script>';
        exit();
    } else {
        $_SESSION['error_message'] = "Failed to update parent";
        echo '<script>window.location.href = "manage_parent.php";</script>';
        exit();
    }
} else {
    // Check if the parent ID is provided in the query string
    if (isset($_GET['id'])) {
        $parentId = $_GET['id'];

        // Retrieve the parent information from the database
        $stmt = $conn->prepare("SELECT * FROM parents WHERE id = ?");
        $stmt->bind_param("s", $parentId);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows == 1) {
            // Fetch the parent record
            $parent = $result->fetch_assoc();
        } else {
            $_SESSION['error_message'] = "Parent not found";
            echo '<script>window.location.href = "manage_parent.php";</script>';
            exit();
        }
    } else {
        $_SESSION['error_message'] = "Invalid request";
        echo '<script>window.location.href = "manage_parent.php";</script>';
        exit();
    }
}
?>

<div class="container-fluid bg-white my-3">
    <h4>Edit Parent</h4>
    <form method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>">
        <input type="hidden" name="parentId" value="<?php echo $parentId; ?>">
        <div class="form-group">
            <div class="row mb-3">
                <div class="col-md-4 col-sm-3">
                    <label for="firstName">First Name</label>
                </div>
                <div class="col-md-7 col-sm-5">
                    <input type="text" class="form-control" name="firstName" value="<?php echo $parent['firstName']; ?>"
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
                    <input type="text" class="form-control" name="middleName"
                        value="<?php echo $parent['middleName']; ?>">
                </div>
            </div>
        </div>
        <div class="form-group">
            <div class="row mb-3">
                <div class="col-md-4 col-sm-3">
                    <label for="lastName">Last Name</label>
                </div>
                <div class="col-md-7 col-sm-5">
                    <input type="text" class="form-control" name="lastName" value="<?php echo $parent['lastName']; ?>"
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
                    <input type="email" class="form-control" name="email" value="<?php echo $parent['email']; ?>"
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
                    <input type="text" class="form-control" name="phone" value="<?php echo $parent['phone']; ?>"
                        required>
                </div>
            </div>
        </div>
        <button type="submit" class="btn btn-primary mt-2">Update</button>
        <a href="manage_parent.php" class="btn btn-secondary mt-2">Cancel</a>
    </form>
</div>


</div>
</div>
</div>
<script src="../bootstrap/js/bootstrap.js"></script>
</body>

</html>