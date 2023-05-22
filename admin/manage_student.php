<?php include 'includes/main.php'; ?>

<?php
if (isset($_GET['studentId'])) {
    $studentId = $_GET['studentId'];

    if ($studentId > 0) {
        $stmt = $conn->prepare("DELETE FROM students WHERE id = ?");
        $stmt->bind_param("s", $studentId);

        if ($stmt->execute()) {
            if ($stmt->affected_rows > 0) {
                $_SESSION['success_message'] = "Student deleted successfully";
            } else {
                $_SESSION['error_message'] = "Student not found";
                echo '<script>window.location.href = "manage_student.php";</script>';
            }
        } else {
            $_SESSION['error_message'] = "Failed to delete student";
            echo '<script>window.location.href = "manage_student.php";</script>';
        }
    } else {
        $_SESSION['error_message'] = "Invalid studentId";
    }
   
}
?>

<?php
$recordsPerPage = 10;
$page = isset($_GET['page']) ? $_GET['page'] : 1;
$offset = ($page - 1) * $recordsPerPage;

$search = isset($_GET['search']) ? $_GET['search'] : '';

$query = "SELECT * FROM students";
if (!empty($search)) {
    $query .= " WHERE firstName LIKE '%$search%' OR id LIKE '%$search%' OR grade_level LIKE '%$search%' OR lastName LIKE '%$search%' OR bus_assigned LIKE '%$search%' ";
}

$query .= " LIMIT $offset, $recordsPerPage";

$result = $conn->query($query);

$totalStudentsQuery = "SELECT COUNT(*) AS total FROM students";
if (!empty($search)) {
    $totalStudentsQuery .= " WHERE firstName LIKE '%$search%' OR id LIKE '%$search%' OR grade_level LIKE '%$search%' OR lastName LIKE '%$search%' OR bus_assigned LIKE '%$search%'";
}

$totalStudentsResult = $conn->query($totalStudentsQuery);

if ($result && $result->num_rows > 0) {
    $students = $result->fetch_all(MYSQLI_ASSOC);
} else {
    $students = [];
}

if ($totalStudentsResult && $totalStudentsResult->num_rows > 0) {
    $totalStudents = $totalStudentsResult->fetch_assoc()['total'];
} else {
    $totalStudents = 0;
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
        <h4 class="mt-3 mb-4 col-md-4">Manage Students</h4>
        <div class="col-md-6 mt-3 mb-4">
            <form action="manage_student.php" method="get" class="form-inline">
                <div class="input-group">
                    <input type="text" class="form-control" name="search" placeholder="Search by Id, name, or email">
                    <div class="input-group-append">
                        <button class="btn btn-primary" type="submit">Search</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Display the student records in a table -->
    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Bus Assigned</th>
                <th>Grade Level</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if (!empty($students)) {
                foreach ($students as $student) {
                    echo '<tr>';
                    echo '<td>' . $student['id'] . '</td>';
                    echo '<td>' . $student['firstName'] . ' ' . $student['middleName'] . ' ' . $student['lastName'] . '</td>';
                    echo '<td>' . $student['bus_assigned'] . '</td>';
                    echo '<td>' . $student['grade_level'] . '</td>';
                    echo '<td>';
                    echo '<a href="edit_student.php?studentId=' . $student['id'] . '"><i class="fa-solid fa-edit mx-2"></i></a>';
                    echo '<a href="" data-bs-toggle="modal" data-bs-target="#staticBackdrop"
                            data-id="' . $student['id'] . '" data-firstName="' . $student['firstName'] . '"
                            data-lastName="' . $student['lastName'] .'"><i class="fa-solid fa-trash mx-2 text-danger"></i></a> ';
                    echo '</td>';
                    echo '</tr>';
                }
            } else {
                echo '<tr><td colspan="5">No students found</td></tr>';
            }
            ?>
        </tbody>
    </table>

    <!-- delete modal -->
    <div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
        aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="staticBackdropLabel">Delete Student</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p> Are you sure you want to delete: <strong><span id="delete-student-name"></span></strong>?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Close</button>
                    <form id="delete-student-form" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="get">
                        <input type="hidden" name="studentId" id="delete-student-id" value="">
                        <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Display pagination links -->
    <div class="pagination">
        <nav aria-label="...">
            <ul class="pagination">
                <?php
                $totalPages = ceil($totalStudents / $recordsPerPage);

                // Previous page link
                if ($page > 1) {
                    echo '<li class="page-item"><a class="page-link" href="manage_student.php?page=' . ($page - 1) . '">Previous</a></li>';
                } else {
                    echo '<li class="page-item disabled"><span class="page-link">Previous</span></li>';
                }

                // Page links
                for ($i = 1; $i <= $totalPages; $i++) {
                    if ($i == $page) {
                        echo '<li class="page-item active" aria-current="page"><span class="page-link">' . $i . '</span></li>';
                    } else {
                        echo '<li class="page-item"><a class="page-link" href="manage_student.php?page=' . $i . '">' . $i . '</a></li>';
                    }
                }

                // Next page link
                if ($page < $totalPages) {
                    echo '<li class="page-item"><a class="page-link" href="manage_student.php?page=' . ($page + 1) . '">Next</a></li>';
                } else {
                    echo '<li class="page-item disabled"><span class="page-link">Next</span></li>';
                }
                ?>
            </ul>
        </nav>
    </div>

</div>

</div>
</div>
</div>

<script>
    const deleteButtons = document.querySelectorAll('[data-bs-target="#staticBackdrop"]');

    deleteButtons.forEach(button => {
        button.addEventListener('click', () => {
            const id = button.getAttribute('data-id');
            const firstName = button.getAttribute('data-firstName');
            const lastName = button.getAttribute('data-lastName');
            const deleteStudentForm = document.querySelector('#delete-student-form');
            const deleteStudentName = document.querySelector('#delete-student-name');
            const deleteStudentId = document.querySelector('#delete-student-id');
            const deleteStudentAction = deleteStudentForm.getAttribute('action').replace('__studentId__', id);

            deleteStudentForm.setAttribute('action', deleteStudentAction);
            deleteStudentId.value = id;
            deleteStudentName.textContent = `${firstName} ${lastName}`;
        });
    });
</script>


<script src="../bootstrap/js/bootstrap.js"></script>
</body>

</html>
