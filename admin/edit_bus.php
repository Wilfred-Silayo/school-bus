<?php
include 'includes/main.php';

$errorMessage = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $plateNumber = $_POST['plate_number'];
    $driverId = $_POST['driver_id'];
    $routeId = $_POST['route_id'];

    // Check if driver_id already exists in buses table
    $checkDriverQuery = "SELECT * FROM buses WHERE driver_id = '$driverId'";
    $checkDriverResult = $conn->query($checkDriverQuery);

    if ($checkDriverResult->num_rows > 0) {
        // Fetch the existing driver_id for the given plate_number
        $existingDriverQuery = "SELECT driver_id FROM buses WHERE plate_number = '$plateNumber'";
        $existingDriverResult = $conn->query($existingDriverQuery);
        $existingDriver = $existingDriverResult->fetch_assoc();

        if ($existingDriver['driver_id'] == $driverId) {
            // Driver ID is the same, allow update
            $stmt = $conn->prepare("UPDATE buses SET driver_id = IFNULL(?, driver_id), route_id = IFNULL(?, route_id)  WHERE plate_number = ?");
            $stmt->bind_param("sss", $driverId, $routeId, $plateNumber);

            if ($stmt->execute()) {
                $_SESSION['success_message'] = "Bus updated successfully";
                echo '<script>window.location.href = "manage_bus.php";</script>';
                exit();
            } else {
                $_SESSION['error_message'] = "Failed to update bus";
                echo '<script>window.location.href = "manage_bus.php";</script>';
                exit();
            }
        } else {
            // Driver ID is different, display error
            $errorMessage = "Driver is already assigned to a different bus.";
        }
    } else {
        $stmt = $conn->prepare("UPDATE buses SET driver_id = IFNULL(?, driver_id), route_id = IFNULL(?, route_id)  WHERE plate_number = ?");
        $stmt->bind_param("sss", $driverId, $routeId, $plateNumber);

        if ($stmt->execute()) {
            $_SESSION['success_message'] = "Bus updated successfully";
            echo '<script>window.location.href = "manage_bus.php?success=1";</script>';
            exit();
        } else {
            $_SESSION['error_message'] = "Failed to update bus";
            echo '<script>window.location.href = "manage_bus.php";</script>';
            exit();
        }
    }
} else {
    if (isset($_GET['id'])) {
        $plateNumber = $_GET['id'];

        $stmt = $conn->prepare("SELECT b.plate_number, b.driver_id, b.route_id, d.firstName, d.lastName, r.name
                               FROM buses AS b
                               LEFT JOIN drivers AS d ON b.driver_id = d.licence
                               LEFT JOIN routes AS r ON b.route_id = r.id
                               WHERE b.plate_number = ?");
        $stmt->bind_param("s", $plateNumber);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows == 1) {
            $bus = $result->fetch_assoc();
        } else {
            $_SESSION['error_message'] = "Bus not found";
            echo '<script>window.location.href = "manage_bus.php";</script>';
            exit();
        }
    } else {
        $_SESSION['error_message'] = "Invalid request";
        echo '<script>window.location.href = "manage_bus.php";</script>';
        exit();
    }
}

$driverQuery = "SELECT * FROM drivers";
$driverResult = $conn->query($driverQuery);

$routeQuery = "SELECT * FROM routes";
$routeResult = $conn->query($routeQuery);
?>

<?php if (!empty($errorMessage)) { ?>
    <div class="alert alert-danger"><?php echo $errorMessage; ?></div>
<?php } ?>

<div class="container-fluid bg-white my-3">
    <h4>Edit Bus</h4>
    <?php if (!empty($errorMessage)) { ?>
    <div class="alert alert-danger"><?php echo $errorMessage; ?></div>
    <?php } ?>
    <form method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>">
        <div class="form-group col-md-6 mb-3">
            <label for="plate_number">Plate Number:</label>
            <input type="text" class="form-control" name="plate_number" id="plate_number"
                value="<?php echo $bus['plate_number']; ?>" required readonly>
        </div>
        <div class="form-group col-md-6 mb-3">
            <label for="driver_id">Driver:</label>
            <select class="form-control" name="driver_id" id="driver_id" required>
                <option value="">Select Driver</option>
                <?php while ($driverRow = $driverResult->fetch_assoc()) { ?>
                <option value="<?php echo $driverRow['licence']; ?>"
                    <?php echo ($driverRow['licence'] == $bus['driver_id']) ? 'selected' : ''; ?>>
                    <?php echo $driverRow['firstName'] . ' ' . $driverRow['lastName']; ?>
                </option>
                <?php } ?>
            </select>
        </div>

        <div class="form-group col-md-6 mb-3">
            <label for="route_id">Route:</label>
            <select class="form-control" name="route_id" id="route_id" required>
                <option value="">Select Route</option>
                <?php while ($routeRow = $routeResult->fetch_assoc()) { ?>
                <option value="<?php echo $routeRow['id']; ?>"
                    <?php echo ($routeRow['id'] == $bus['route_id']) ? 'selected' : ''; ?>>
                    <?php echo $routeRow['name']; ?>
                </option>
                <?php } ?>
            </select>
        </div>
        <button type="submit" class="btn btn-primary mt-2">Update</button>
        <a href="manage_bus.php" class="btn btn-secondary mt-2">Cancel</a>
    </form>
</div>

</div>
</div>
</div>
<script src="../bootstrap/js/bootstrap.js"></script>
</body>

</html>