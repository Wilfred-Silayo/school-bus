<?php
include 'includes/main.php';

$routeId = '';
if (isset($_GET['routeId'])) {
    $routeId = $_GET['routeId'];
}


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $routeId = $_POST['routeId'];
    $latitude = $_POST['latitude'];
    $longitude = $_POST['longitude'];

    // Perform input validation here if needed

    $stmt = $conn->prepare("INSERT INTO stops (name, route_id, latitude, longitude) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssdd", $name, $routeId, $latitude, $longitude);

    if ($stmt->execute()) {
        $_SESSION['success_message'] = "Stop created successfully";
    } else {
        $_SESSION['error_message'] = "Failed to create stop";
    }
}
?>
<?php
if (isset($_SESSION['success_message'])) {
    echo '<div class="alert alert-success mt-2">' . $_SESSION['success_message'] . '</div>';

    unset($_SESSION['success_message']);
}
?>

<?php if (isset($_SESSION['error_message'])) {
    echo '<div class="alert alert-danger mt-2">' . $_SESSION['error_message'] . '</div>';

    unset($_SESSION['error_message']);
}
?>

<div class="container-fluid bg-white mt-3">
    <div class="row">
        <h4 class="mt-3 mb-4 col-md-4">Add stops</h4>
    </div>
    <div class="row">
        <div class="col-md-6 mt-3 mb-4">
            <form action="add_stop.php" method="post">
                <input type="hidden" class="form-control" name="routeId" value="<?php echo $routeId ?>">
                <div class="form-group mb-3">
                    <label for="name">Stop Name</label>
                    <input type="text" class="form-control" id="name" name="name" required>
                </div>
                <div class="form-group mb-3">
                    <label for="latitude">Latitude</label>
                    <input type="number" step="any" class="form-control" id="latitude" name="latitude" required>
                </div>
                <div class="form-group mb-3">
                    <label for="longitude">Longitude</label>
                    <input type="number" step="any" class="form-control" id="longitude" name="longitude" required>
                </div>
                <button type="submit" class="btn btn-primary">Create Stop</button>
                <a href="manage_stop.php?routeId=<?php echo $routeId ?>" class="btn btn-secondary">Cancel</a>
            </form>
        </div>
    </div>
</div>

</div>
</div>
</div>
<script src="../bootstrap/js/bootstrap.js"></script>
</body>

</html>