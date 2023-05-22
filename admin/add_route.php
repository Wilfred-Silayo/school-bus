
<?php include 'includes/main.php';

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
        $sql = "INSERT INTO routes (name,start_point,pass_through,end_point) VALUES 
        ('$name','$start_point','$pass_through','$end_point')";

        if ($conn->query($sql) === TRUE) {
            $_SESSION['success_message'] = 'Route created successfully.';
        } 
        else{
            $_SESSION['error_message'] = 'Route registration failed'; 
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
        <h3 class="text-dark mb-2 mt-2">Create Route</h3>
    </div>
    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
        <div class="form-group">
            <div class="row mb-3">
                <div class="col-md-3 col-sm-2">
                    <label for="name">Route Name:</label>
                </div>
                <div class="col-md-7 col-sm-5">
                    <input type="text" class="form-control" id="name" name="name" placeholder="Enter Route Name">
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
                    <input type="text" class="form-control" id="start_point" name="start_point" placeholder="Enter Route start point">
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
                    <input type="text" class="form-control" id="pass_through" name="pass_through" placeholder="Enter Route pass through">
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
                    <input type="text" class="form-control" id="end_point" name="end_point" placeholder="Enter Route end point">
                    <span id="end_pointError" class="text-danger"><?php echo $end_pointError; ?></span>
                </div>
            </div>
        </div>
        <button type="submit" class="btn btn-primary">Create Route</button>
        <a href="home.php" class="btn btn-secondary">Cancel</a>
    </form>
</div>

</div>
</div>
</div>
<script src="../bootstrap/js/bootstrap.js"></script>
</body>

</html>