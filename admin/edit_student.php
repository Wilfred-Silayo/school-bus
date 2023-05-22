<?php include 'includes/main.php'; ?>
<?php

$studentId = '';

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['studentId'])) {
    $studentId = $_GET['studentId'];
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['studentId'])) {
    $studentId = $_POST['studentId'];
}

// Fetch the original student data
$studentQuery = $conn->prepare("SELECT * FROM students WHERE id = ?");
$studentQuery->bind_param("s", $studentId);
$studentQuery->execute();
$studentResult = $studentQuery->get_result();
$studentData = $studentResult->fetch_assoc();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $firstName = $_POST['firstName'];
    $middleName = $_POST['middleName'];
    $lastName = $_POST['lastName'];
    $gradeLevel = $_POST['gradeLevel'];
    $parentId = $_POST['parentId'];
    $busAssigned = $_POST['busAssigned'];
    $stopId = $_POST['stopId'];

    $id=$studentId;
    $stmt = $conn->prepare("UPDATE students SET firstName = ?, middleName = ?, lastName = ?, grade_level = ?, parent_id = ?, bus_assigned = ?, stop_id = ? WHERE id = ?");
    $stmt->bind_param("sssssssi", $firstName, $middleName, $lastName, $gradeLevel, $parentId, $busAssigned, $stopId, $studentId);

    if ($stmt->execute()) {
        $_SESSION['success_message'] = "Student updated successfully";
    } else {
        $_SESSION['error_message'] = "Failed to update student";
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
        <h4 class="mt-2">Edit Student</h4>
    </div>
    <form method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>">
        <input type="hidden" name="studentId" value="<?php echo htmlentities($studentId); ?>">
        <div class="form-group">
            <div class="row mb-3">
                <div class="col-md-4 col-sm-3">
                    <label for="firstName">First Name</label>
                </div>
                <div class="col-md-7 col-sm-5">
                    <input type="text" class="form-control" name="firstName" value="<?php echo htmlentities($studentData['firstName']); ?>" required>
                </div>
            </div>
        </div>
        <div class="form-group">
            <div class="row mb-3">
                <div class="col-md-4 col-sm-3">
                    <label for="middleName">Middle Name</label>
                </div>
                <div class="col-md-7 col-sm-5">
                    <input type="text" class="form-control" name="middleName" value="<?php echo htmlentities($studentData['middleName']); ?>">
                </div>
            </div>
        </div>
        <div class="form-group">
            <div class="row mb-3">
                <div class="col-md-4 col-sm-3">
                    <label for="lastName">Last Name</label>
                </div>
                <div class="col-md-7 col-sm-5">
                    <input type="text" class="form-control" name="lastName" value="<?php echo htmlentities($studentData['lastName']); ?>" required>
                </div>
            </div>
        </div>
        <div class="form-group">
            <div class="row mb-3">
                <div class="col-md-4 col-sm-3">
                    <label for="gradeLevel">Grade Level</label>
                </div>
                <div class="col-md-7 col-sm-5">
                    <input type="text" class="form-control" name="gradeLevel" value="<?php echo htmlentities($studentData['grade_level']); ?>" required>
                </div>
            </div>
        </div>
        <div class="form-group">
            <div class="row mb-3">
                <div class="col-md-4 col-sm-3">
                    <label for="parentId">Parent</label>
                </div>
                <div class="col-md-7 col-sm-5">
                    <select class="form-control" name="parentId">
                        <?php
                        // Fetch parent data from the database
                        $parentQuery = $conn->query("SELECT * FROM parents");
                        while ($parent = $parentQuery->fetch_assoc()) {
                            $selected = ($parent['id'] == $studentData['parent_id']) ? 'selected' : '';
                            echo "<option value='" . $parent['id'] . "' $selected>" . $parent['firstName'] . " " . $parent['lastName'] . "</option>";
                        }
                        ?>
                    </select>
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
                        <option value="">Select bus</option>
                        <?php
                        // Fetch bus data from the database
                        $busQuery = $conn->query("SELECT plate_number FROM buses");
                        while ($bus = $busQuery->fetch_assoc()) {
                            $selected = ($bus['plate_number'] == $studentData['bus_assigned']) ? 'selected' : '';
                            echo "<option value='" . $bus['plate_number'] . "' $selected>" . $bus['plate_number'] . "</option>";
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
                        <option value="">Select stop</option>
                        <?php
                        // Fetch stop data from the database
                        $stopQuery = $conn->query("SELECT * FROM stops");
                        while ($stop = $stopQuery->fetch_assoc()) {
                            $selected = ($stop['id'] == $studentData['stop_id']) ? 'selected' : '';
                            echo "<option value='" . $stop['id'] . "' $selected>" . $stop['name'] . "</option>";
                        }
                        ?>
                    </select>
                </div>
            </div>
        </div>

        <button type="submit" class="btn btn-primary">Update Student</button>
        <a href="manage_student.php?id" class="btn btn-secondary">Go Back</a>
    </form>
</div>

</div>
</div>
</div>
<script src="../bootstrap/js/bootstrap.js"></script>
</body>

</html>