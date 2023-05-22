<?php
include 'includes/main.php';

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

        // Retrieve the associated students
        $stmt = $conn->prepare("SELECT * FROM students WHERE parent_id = ?");
        $stmt->bind_param("s", $parentId);
        $stmt->execute();
        $studentsResult = $stmt->get_result();

        if ($studentsResult->num_rows > 0) {
            $students = $studentsResult->fetch_all(MYSQLI_ASSOC);
        } else {
            $students = [];
        }
    } else {
        $_SESSION['error_message'] = "Parent not found";
        header("Location: manage_parent.php");
        exit();
    }
} 
?>

<div class="container-fluid bg-white my-3">
    <h3>Parent Information</h3>
    <table class="table table-bordered table-striped">
        <tr>
            <th>ID</th>
            <th>First Name</th>
            <th>Last Name</th>
            <th>Email</th>
            <th>Phone</th>
        </tr>
        <tr>
            <td><?php echo $parent['id']; ?></td>
            <td><?php echo $parent['firstName']; ?></td>
            <td><?php echo $parent['lastName']; ?></td>
            <td><?php echo $parent['email']; ?></td>
            <td><?php echo $parent['phone']; ?></td>
        </tr>
    </table>

    <div class="row my-2">
        <div class="col-md-6">
            <h5 class="text-primary fw-bold">Associated Students</h5>
        </div>
        <div class="col-md-6">
            <a href="add_student.php?parentId=<?php echo $parentId; ?>" class="btn btn-primary btn-sm">
            <i class="fa-solid fa-plus"></i>
            Add Student</a>
        </div>
    </div>
    <?php if (!empty($students)) { ?>
    <table class="table table-bordered table-striped">
        <tr>
            <th>ID</th>
            <th>First Name</th>
            <th>Last Name</th>
            <th>Grade</th>
        </tr>
        <?php foreach ($students as $student) { ?>
        <tr>
            <td><?php echo $student['id']; ?></td>
            <td><?php echo $student['firstName']; ?></td>
            <td><?php echo $student['lastName']; ?></td>
            <td><?php echo $student['grade_level']; ?></td>
        </tr>
        <?php } ?>
    </table>
    <?php } else { ?>
    <p class="text-danger fw-bold">No students associated with this parent.</p>
    <?php } ?>
    <a href="manage_parent.php" class="btn btn-primary">Go Back</a>
</div>
</div>
</div>
</div>
<script src="../bootstrap/js/bootstrap.js"></script>
</body>