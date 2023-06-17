<?php
include 'includes/main.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $stopId = $_POST['stopId'];
    $routeId = $_POST['routeId'];
    $latitude = $_POST['latitude'];
    $longitude = $_POST['longitude'];

    // Perform input validation here if needed

    $stmt = $conn->prepare("UPDATE stops SET name = ?, latitude = ?, longitude = ? WHERE id = ?");
    $stmt->bind_param("sddi", $name, $latitude, $longitude, $stopId);

    if ($stmt->execute()) {
        $_SESSION['success_message'] = "Stop updated successfully";
    } else {
        $_SESSION['error_message'] = "Failed to update stop";
    }
}


if (isset($_POST['routeId']) && isset($_POST['stopId'])) {
    $stopId = $_POST['stopId'];
    $routeId = $_POST['routeId'];
}

else{
    $stopId = $_GET['stopId'];
    $routeId = $_GET['routeId'];
}

$originalStop = [];
if (!empty($stopId)) {
    $stmt = $conn->prepare("SELECT * FROM stops WHERE id = ?");
    $stmt->bind_param("i", $stopId);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result && $result->num_rows > 0) {
        $originalStop = $result->fetch_assoc();
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
        <h4 class="mt-3 mb-4 col-md-4">Edit Stop</h4>
    </div>
    <div class="row">
        <div class="col-md-6 mt-3 mb-4">
            <form action="edit_stop.php" method="post">
            <input type="hidden" name="stopId" value="<?php echo htmlentities($stopId); ?>">
            <input type="hidden" name="routeId" value="<?php echo htmlentities($routeId); ?>">
                <div class="form-group mb-3">
                    <label for="name">Stop Name</label>
                    <input type="text" class="form-control" id="name" name="name" value="<?php echo isset($originalStop['name']) ? htmlentities($originalStop['name']) : ''; ?>" required>
                </div>
                <div class="form-group mb-3">
                    <label for="latitude">Latitude</label>
                    <input type="number" step="any" class="form-control" id="latitude" name="latitude" value="<?php echo isset($originalStop['latitude']) ? htmlentities($originalStop['latitude']) : ''; ?>" required>
                </div>
                <div class="form-group mb-3">
                    <label for="longitude">Longitude</label>
                    <input type="number" step="any" class="form-control" id="longitude" name="longitude" value="<?php echo isset($originalStop['longitude']) ? htmlentities($originalStop['longitude']) : ''; ?>" required>
                </div>
                <button type="submit" class="btn btn-primary">Update Stop</button>
                <a href="manage_stop.php?routeId=<?php echo $routeId ?>" class="btn btn-secondary">Cancel</a>
            </form>
        </div>
    </div>
</div>

<script src="../bootstrap/js/bootstrap.js"></script>
</body>

</html>
