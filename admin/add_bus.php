<?php
include 'includes/main.php';

$successMessage = '';
$errorMessage = '';

$plate_number = '';
$driver_id = '';
$route_id = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $plate_number = $_POST['plate_number'];
    $driver_id = $_POST['driver_id'];
    $route_id = $_POST['route_id'];

    // Check if plate_number already exists in buses table
    $checkPlateQuery = "SELECT * FROM buses WHERE plate_number = '$plate_number'";
    $checkPlateResult = $conn->query($checkPlateQuery);

    // Check if driver_id already exists in buses table
    $checkDriverQuery = "SELECT * FROM buses WHERE driver_id = '$driver_id'";
    $checkDriverResult = $conn->query($checkDriverQuery);

    if ($checkPlateResult->num_rows > 0) {
        $errorMessage = "Plate number already exists.";
    } elseif ($checkDriverResult->num_rows > 0) {
        $errorMessage = "Driver is already assigned to a bus.";
    } else {
        // Insert data into the buses table
        $insertQuery = "INSERT INTO buses (plate_number, driver_id, route_id) VALUES ('$plate_number', '$driver_id', '$route_id')";

        if ($conn->query($insertQuery)) {
            $successMessage = "Bus registered successfully.";
            $plate_number = '';
            $driver_id = '';
            $route_id = '';
        } else {
            $errorMessage = "Error: " . $conn->error;
        }
    }
}

$driverQuery = "SELECT * FROM drivers";
$driverResult = $conn->query($driverQuery);

$routeQuery = "SELECT * FROM routes";
$routeResult = $conn->query($routeQuery);
?>

<div class="container-fluid bg-white mt-3">
    <div class="row">
        <h2 class="text-dark mb-2 mt-2">Register Bus</h2>
    </div>
    <?php if (!empty($successMessage)) { ?>
    <div class="alert alert-success"><?php echo $successMessage; ?></div>
    <?php } ?>
    <?php if (!empty($errorMessage)) { ?>
    <div class="alert alert-danger"><?php echo $errorMessage; ?></div>
    <?php } ?>
    <form method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>">
        <div class="form-group col-md-6 mb-3">
            <label for="plate_number">Plate Number:</label>
            <input type="text" class="form-control" name="plate_number" id="plate_number" value="<?php echo $plate_number; ?>" required>
        </div>
        <div class="form-group col-md-6 mb-3">
            <label for="driver_id">Driver:</label>
            <select class="form-control" name="driver_id" id="driver_id" required>
                <option value="">Select Driver</option>
                <?php while ($driverRow = $driverResult->fetch_assoc()) { ?>
                <option value="<?php echo $driverRow['licence']; ?>" <?php echo ($driverRow['licence'] == $driver_id) ? 'selected' : ''; ?>>
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
                <option value="<?php echo $routeRow['id']; ?>" <?php echo ($routeRow['id'] == $route_id) ? 'selected' : ''; ?>><?php echo $routeRow['name']; ?></option>
                <?php } ?>
            </select>
        </div>
        <button type="submit" class="btn btn-primary">Submit</button>
        <a href="home.php" class="btn btn-secondary">Cancel</a>
    </form>
</div>

</div>
</div>
</div>

<script src="../bootstrap/js/bootstrap.js"></script>
</body>

</html>