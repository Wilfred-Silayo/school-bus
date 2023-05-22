<?php
include 'includes/main.php';

$busId = null;
$driverName = '';

if (isset($_GET['id'])) {
    $busId = $_GET['id'];
    // Fetch the driver's full name
    $busQuery = "SELECT b.driver_id, CONCAT(d.firstName, ' ', d.lastName) AS driverName
                FROM buses AS b
                LEFT JOIN drivers AS d ON b.driver_id = d.licence
                WHERE b.plate_number = ?";
    $busPlateNumber = $busId;
    $stmt = $conn->prepare($busQuery);
    $stmt->bind_param("s", $busPlateNumber);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows == 1) {
        $bus = $result->fetch_assoc();
        $driverName = $bus['driverName'];
    } else {
        $driverName = "Unknown Driver";
    }
}

$recordsPerPage = 10;
$page = isset($_GET['page']) ? $_GET['page'] : 1;
$offset = ($page - 1) * $recordsPerPage;

$search = isset($_GET['search']) ? $_GET['search'] : '';

$query = "SELECT s.id, s.firstName, s.middleName, s.lastName
          FROM students AS s
          JOIN buses AS b ON s.bus_assigned = b.plate_number
          WHERE b.plate_number = ?";

if (!empty($search)) {
    $query .= " AND (s.firstName LIKE '%$search%' OR s.middleName LIKE '%$search%' OR  s.id LIKE '%$search%' OR
     s.lastName LIKE '%$search%')";
}

$query .= " LIMIT $offset, $recordsPerPage";

$stmt = $conn->prepare($query);
$stmt->bind_param("s", $busId);
$stmt->execute();
$result = $stmt->get_result();

$totalStudentsQuery = "SELECT COUNT(*) AS total
                       FROM students AS s
                       JOIN buses AS b ON s.bus_assigned = b.plate_number
                       WHERE b.plate_number = ?";

if (!empty($search)) {
    $totalStudentsQuery .= " AND (s.firstName LIKE '%$search%' OR s.middleName LIKE '%$search%' OR s.id LIKE '%$search%' OR
                             s.lastName LIKE '%$search%')";
}

$stmt = $conn->prepare($totalStudentsQuery);
$stmt->bind_param("s", $busId);
$stmt->execute();
$totalStudentsResult = $stmt->get_result();

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

<div class="container-fluid bg-white mt-3">
    <div class="row align-items-baseline">
        <h4 class="mt-3 mb-4 col-md-3 ms-2">Bus Details</h4>
        <div class="col-md-5 mt-3 mb-4">
            <form action="view_bus.php?id=<?php echo $busId; ?>" method="get" class="form-inline">
                <div class="input-group">
                    <input type="text" class="form-control" name="search" placeholder="Search by Student Name"
                        value="<?php echo $search; ?>">
                    <input type="hidden" name="id" value="<?php echo $busId; ?>">
                    <div class="input-group-append">
                        <button class="btn btn-primary" type="submit">Search</button>
                    </div>
                </div>
            </form>
        </div>
        <div class="col-md-2">
            <a href="manage_bus.php" class="btn btn-secondary">Go back</a>
        </div>
    </div>

    <!-- Display driver's full name -->
    <h5 class="mt-3">Driver: <?php echo $driverName; ?></h5>
    <p class="fw-bold">Below is a list of students of this bus: <span class="text-danger"><?php echo $busId?></span>
    </p>
    <!-- Display the student records in a table -->
    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if (!empty($students)) {
                foreach ($students as $student) {
                    echo '<tr>';
                    echo '<td>' . $student['id'] . '</td>';
                    echo '<td>' . $student['firstName'] . ' ' . $student['middleName'] . ' ' . $student['lastName'] . '</td>';
                    echo '</tr>';
                }
            } else {
                echo '<tr><td colspan="2">No students found</td></tr>';
            }
            ?>
        </tbody>
    </table>

    <!-- Display pagination links -->
    <div class="pagination">
        <nav aria-label="...">
            <ul class="pagination">
                <?php
                // Previous page link
                if ($page > 1) {
                    echo '<li class="page-item"><a class="page-link" href="?id=' . $busId . '&search=' . $search . '&page=' . ($page - 1) . '">Previous</a></li>';
                } else {
                    echo '<li class="page-item disabled"><span class="page-link">Previous</span></li>';
                }

                // Page links
                $totalPages = ceil($totalStudents / $recordsPerPage);
                for ($i = 1; $i <= $totalPages; $i++) {
                    echo '<li class="page-item' . ($i == $page ? ' active' : '') . '">';
                    echo '<a class="page-link" href="?id=' . $busId . '&search=' . $search . '&page=' . $i . '">' . $i . '</a>';
                    echo '</li>';
                }

                // Next page link
                if ($page < $totalPages) {
                    echo '<li class="page-item"><a class="page-link" href="?id=' . $busId . '&search=' . $search . '&page=' . ($page + 1) . '">Next</a></li>';
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


<script src="../bootstrap/js/bootstrap.js"></script>
</body>
</hmml>