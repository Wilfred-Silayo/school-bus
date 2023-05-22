<?php
include 'includes/session.php';
include 'includes/header.php';
?>

<body class="bg-light" style="min-height: 90vh;">
    <?php include 'includes/navbar.php'; ?>
    <div class="container-fluid">
        <div class="row flex-nowrap">
            <?php include 'includes/menubar.php'; ?>
            <!-- Content Wrapper. Contains page content -->
            <div class="col-md-9">
                <?php
                // Pagination configuration
                $studentsPerPage = 10; 
                $currentPage = isset($_GET['page']) ? $_GET['page'] : 1; 

                // Get the bus plate number for the current driver
                $driverId = $_SESSION['driver'];
                $busQuery = "SELECT plate_number FROM buses WHERE driver_id = '$driverId'";
                $busResult = $conn->query($busQuery);

                if ($busResult && $busResult->num_rows > 0) {
                    $busRow = $busResult->fetch_assoc();
                    $busPlateNumber = $busRow['plate_number'];

                    // Query to fetch the students for the current page
                    $studentQuery = "SELECT * FROM students WHERE bus_assigned = '$busPlateNumber' LIMIT $studentsPerPage OFFSET " . ($currentPage - 1) * $studentsPerPage;
                    $studentResult = $conn->query($studentQuery);

                    // Query to count the total number of students
                    $totalStudentsQuery = "SELECT COUNT(*) AS total FROM students WHERE bus_assigned = '$busPlateNumber'";
                    $totalStudentsResult = $conn->query($totalStudentsQuery);
                    $totalStudentsRow = $totalStudentsResult->fetch_assoc();
                    $totalStudents = $totalStudentsRow['total'];

                    // Calculate the total number of pages
                    $totalPages = ceil($totalStudents / $studentsPerPage);
                } else {
                    $busPlateNumber = null;
                    $studentResult = false; 
                    $totalPages = 0; 
                }
                ?>
                <div class="container">
                    <div class="row mt-3">
                        <h4 class="col">
                            Welcome Back:
                            <?php
                                $user = $_SESSION['driver'];
                                $sql = "SELECT * FROM drivers WHERE licence='$user'";
                                $query = $conn->query($sql);
                                $row = $query->fetch_assoc();
                                echo "<span class='text-primary'>$row[firstName] $row[lastName]</span>";
                                ?>
                        </h4>
                        <p class="mb-4 text-danger fw-bold">Students list for your bus</p>
                    </div>

                    <!-- Fetch the route information of the bus -->
                    <?php
                            $busQuery = "SELECT b.plate_number, r.name
                                        FROM buses AS b
                                        JOIN routes AS r ON b.route_id = r.id
                                        WHERE b.driver_id = '$driverId'";
                            $busResult = $conn->query($busQuery);

                            if ($busResult && $busResult->num_rows > 0) {
                                $busRow = $busResult->fetch_assoc();
                                $busPlateNumber = $busRow['plate_number'];
                                $routeName = $busRow['name'];
                                echo '<div class="mb-2">';
                                echo "<span class='mb-2'><strong>Bus Plate Number:</strong> $busPlateNumber</span>";
                                echo "<span class='mb-2 ms-4'><strong>Route Name:</strong> $routeName</span>";
                                echo '</div';
                            } 
                            ?>
                    `
                </div>

                <!-- Students table -->
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Student Name</th>
                            <th>Grade</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($studentResult && $studentResult->num_rows > 0): ?>
                        <?php while ($studentRow = $studentResult->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo $studentRow['firstName'] . ' ' . $studentRow['lastName']; ?></td>
                            <td><?php echo $studentRow['grade_level']; ?></td>
                        </tr>
                        <?php endwhile; ?>
                        <?php else: ?>
                        <tr>
                            <td colspan="2">No students found.</td>
                        </tr>
                        <?php endif; ?>
                    </tbody>
                </table>

                <!-- Display pagination links -->
                <div class="pagination mx-2">
                    <nav aria-label="...">
                        <ul class="pagination">
                            <?php
                            // Previous page link
                            if ($currentPage > 1) {
                                echo '<li class="page-item"><a class="page-link" href="home.php?page=' . ($currentPage - 1) . '">Previous</a></li>';
                            } else {
                                echo '<li class="page-item disabled"><span class="page-link">Previous</span></li>';
                            }

                            // Page links
                            for ($i = 1; $i <= $totalPages; $i++) {
                                if ($i == $currentPage) {
                                    echo '<li class="page-item active" aria-current="page"><span class="page-link">' . $i . '</span></li>';
                                } else {
                                    echo '<li class="page-item"><a class="page-link" href="home.php?page=' . $i . '">' . $i . '</a></li>';
                                }
                            }

                            // Next page link
                            if ($currentPage < $totalPages) {
                                echo '<li class="page-item"><a class="page-link" href="home.php?page=' . ($currentPage + 1) . '">Next</a></li>';
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


    <script src="../bootstrap/js/bootstrap.js"></script>
</body>

</html>