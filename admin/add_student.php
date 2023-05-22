<?php
include 'includes/main.php';

$parentId = '';

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['parentId'])) {
    $parentId = $_GET['parentId'];
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['parentId'])) {
    $parentId = $_POST['parentId'];
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $firstName = $_POST['firstName'];
    $middleName = $_POST['middleName'];
    $lastName = $_POST['lastName'];
    $gradeLevel = $_POST['gradeLevel'];
    $parentId = $_POST['parentId'];
    $busAssigned = $_POST['busAssigned'];
    $stopId = $_POST['stopId'];

    $year = date('Y'); 
    $number = 1; 

    $sql = "SELECT id FROM students ORDER BY id DESC LIMIT 1";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $lastId = $row['id'];

        // Extract the number from the last inserted ID
        $lastNumber = explode('-', $lastId)[2];

        // Increment the number by 1
        $number = intval($lastNumber) + 1;
    }
    $numberFormatted = str_pad($number, 4, '0', STR_PAD_LEFT);

    $id = 'STU-' . $year . '-' . $numberFormatted;

    $stmt = $conn->prepare("INSERT INTO students (id, firstName, middleName, lastName, grade_level, parent_id, bus_assigned, stop_id) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssssssi", $id, $firstName, $middleName, $lastName, $gradeLevel, $parentId, $busAssigned, $stopId);

    if ($stmt->execute()) {
        $_SESSION['success_message'] = "Student added successfully";
    } else {
        $_SESSION['error_message'] = "Failed to add student";
       
    }
} 
?>
<?php
if (isset($_SESSION['success_message'])) {
    // Display the success message
    echo '<div class="alert alert-success mt-2">' . $_SESSION['success_message'] . '</div>';
    
    // Clear the success message from the session
    unset($_SESSION['success_message']);
}
?>


<div class="container-fluid bg-white my-3">
    <div class="row mb-3 border-bottom border-2">
        <h4 class="mt-2">Add Student</h4>
    </div>
    <form method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>">
    <input type="hidden" name="parentId" value="<?php echo htmlentities($parentId); ?>">
        <div class="form-group">
            <div class="row mb-3">
                <div class="col-md-4 col-sm-3">
                    <label for="firstName">First Name</label>
                </div>
                <div class="col-md-7 col-sm-5">
                    <input type="text" class="form-control" name="firstName" required>
                </div>
            </div>
        </div>
        <div class="form-group">
            <div class="row mb-3">
                <div class="col-md-4 col-sm-3">
                    <label for="middleName">Middle Name</label>
                </div>
                <div class="col-md-7 col-sm-5">
                    <input type="text" class="form-control" name="middleName">
                </div>
            </div>
        </div>
        <div class="form-group">
            <div class="row mb-3">
                <div class="col-md-4 col-sm-3">
                    <label for="lastName">Last Name</label>
                </div>
                <div class="col-md-7 col-sm-5">
                    <input type="text" class="form-control" name="lastName" required>
                </div>
            </div>
        </div>
        <div class="form-group">
            <div class="row mb-3">
                <div class="col-md-4 col-sm-3">
                    <label for="gradeLevel">Grade Level</label>
                </div>
                <div class="col-md-7 col-sm-5">
                    <input type="text" class="form-control" name="gradeLevel" required>
                </div>
            </div>
        </div>
        <div class="form-group">
            <div class="row mb-3">
                <div class="col-md-4 col-sm-3">
                    <label for="busAssigned">Bus Assigned</label>
                </div>
                <div class="col-md-7 col-sm-5">
                    <select class="form-control" name="busAssigned">
                    <option value="">select bus</option>
                        <?php
                // Fetch bus data from the database
                $busQuery = $conn->query("SELECT plate_number FROM buses");
                while ($bus = $busQuery->fetch_assoc()) {
                    echo "<option value='" . $bus['plate_number'] . "'>" . $bus['plate_number'] . "</option>";
                }
                ?>
                    </select>
                </div>
            </div>
        </div>

        <div class="form-group">
            <div class="row mb-3">
                <div class="col-md-4 col-sm-3">
                    <label for="stopId">Stop ID</label>
                </div>
                <div class="col-md-7 col-sm-5">
                    <select class="form-control" name="stopId">
                        <option value="">select stop</option>
                        <?php
                // Fetch stop data from the database
                $stopQuery = $conn->query("SELECT * FROM stops");
                while ($stop = $stopQuery->fetch_assoc()) {
                    echo "<option value='" . $stop['id'] . "'>" . $stop['name'] . "</option>";
                }
                ?>
                    </select>
                </div>
            </div>
        </div>

        <button type="submit" class="btn btn-primary">Add Student</button>
       <a href="view_parent.php?id=<?php echo $parentId; ?>" class="btn btn-secondary">Go Back</a>

</div>
</div>
</div>
</div>
</div>
<script src="../bootstrap/js/bootstrap.js"></script>
</body>

</html>