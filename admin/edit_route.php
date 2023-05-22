<?php include 'includes/main.php'; ?>
<?php
$nameError = '';
$start_pointError = '';
$pass_throughError = '';
$end_pointError = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);
    $start_point = trim($_POST['start_point']);
    $pass_through = trim($_POST['pass_through']);
    $end_point = trim($_POST['end_point']);

    if (empty($name)) {
        $nameError = 'Please enter the route name';
    }
    if (empty($start_point)) {
        $start_pointError = 'Please enter the route start point';
    }
    if (empty($pass_through)) {
        $pass_throughError = 'Please enter the route via';
    }
    if (empty($end_point)) {
        $end_pointError = 'Please enter the route end point';
    }

    if (empty($nameError) && empty($start_pointError) && empty($pass_throughError) && empty($end_pointError)) {
        $routeId = $_POST['route_id'];
        $sql = "UPDATE routes SET name='$name', start_point='$start_point', pass_through='$pass_through', end_point='$end_point' WHERE id='$routeId'";

        if ($conn->query($sql) === TRUE) {
            $_SESSION['success_message'] = 'Route updated successfully.';
        } else {
            $_SESSION['error_message'] = 'Route update failed';
        }

        $conn->close();
    }
} else {
    // Retrieve the route information from the database based on the provided route ID
    if (isset($_GET['id'])) {
        $routeId = $_GET['id'];
        $sql = "SELECT * FROM routes WHERE id='$routeId'";
        $result = $conn->query($sql);

        if ($result->num_rows == 1) {
            $route = $result->fetch_assoc();
            $name = $route['name'];
            $start_point = $route['start_point'];
            $pass_through = $route['pass_through'];
            $end_point = $route['end_point'];
        } else {
            $_SESSION['error_message'] = 'Route not found';
           
        }
    } else {
        $_SESSION['error_message'] = 'Invalid route ID';
       
    }
}

if (isset($_SESSION['success_message'])) {
    echo '<div class="alert alert-success mt-2">' . $_SESSION['success_message'] . '</div>';

    unset($_SESSION['success_message']);
}

?>

<div class="container-fluid bg-white mt-3">
    <div class="row mb-3 border-bottom border-2">
        <h3 class="text-dark mb-2 mt-2">Edit Route</h3>
    </div>
    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
        <input type="hidden" name="route_id" value="<?php echo $routeId; ?>">
        <div class="form-group">
            <div class="row mb-3">
                <div class="col-md-3 col-sm-2">
                    <label for="name">Route Name:</label>
                </div>
                <div class="col-md-7 col-sm-5">
                    <input type="text" class="form-control" id="name" name="name" placeholder="Enter Route Name" value="<?php echo $name; ?>">
                    <span id="nameError" class="text-danger"><?php echo $nameError; ?></span>
                </div>
            </div>
        </div>
        <div class="form-group">
            <div class="row mb-3">
                <div class="col-md-3 col-sm-2">
                    <label for="start_point">Route start point:</label>
                </div>
                <div class="col-md-7 col-sm-5">
                    <input type="text" class="form-control" id="start_point" name="start_point" placeholder="Enter Route start point" value="<?php echo $start_point; ?>">
                    <span id="start_pointError" class="text-danger"><?php echo $start_pointError; ?></span>
                </div>
            </div>
        </div>
        <div class="form-group">
            <div class="row mb-3">
                <div class="col-md-3 col-sm-2">
                    <label for="pass_through">Via:</label>
                </div>
                <div class="col-md-7 col-sm-5">
                    <input type="text" class="form-control" id="pass_through" name="pass_through" placeholder="Enter Route pass through" value="<?php echo $pass_through; ?>">
                    <span id="pass_throughError" class="text-danger"><?php echo $pass_throughError; ?></span>
                </div>
            </div>
        </div>
        <div class="form-group">
            <div class="row mb-3">
                <div class="col-md-3 col-sm-2">
                    <label for="end_point">Route end point:</label>
                </div>
                <div class="col-md-7 col-sm-5">
                    <input type="text" class="form-control" id="end_point" name="end_point" placeholder="Enter Route end point" value="<?php echo $end_point; ?>">
                    <span id="end_pointError" class="text-danger"><?php echo $end_pointError; ?></span>
                </div>
            </div>
        </div>
        <button type="submit" class="btn btn-primary">Update Route</button>
        <a href="manage_route.php" class="btn btn-secondary">Cancel</a>
    </form>
</div>


</div>
</div>
</div>
<script src="../bootstrap/js/bootstrap.js"></script>
</body>

</html>