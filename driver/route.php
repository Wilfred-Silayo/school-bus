<?php
include 'includes/session.php'; 
include 'includes/header.php';
?>

<body class="bg-light" style="min-height:90vh;">
    <?php include 'includes/navbar.php'; ?>
    <div class="container-fluid">
        <div class="row flex-nowrap">
            <?php include 'includes/menubar.php'; ?>
            <!-- Content Wrapper. Contains page content -->
            <div class="col-md-9 col-xl-10">
                <div class="container-fluid bg-white my-3">
                    <div class="container">
                        <?php
                          // Pagination configuration
                        $stopsPerPage = 2; 
                        $currentPage = isset($_GET['page']) ? $_GET['page'] : 1; 

                        // Get the bus plate number for the current driver
                        $driverId = $_SESSION['driver'];
                        $busQuery = "SELECT b.plate_number, r.name
                                     FROM buses AS b
                                     JOIN routes AS r ON b.route_id = r.id
                                     WHERE b.driver_id = '$driverId'";
                        $busResult = $conn->query($busQuery);

                        if ($busResult && $busResult->num_rows > 0) {
                            $busRow = $busResult->fetch_assoc();
                            $busPlateNumber = $busRow['plate_number'];
                            $routeName = $busRow['name'];

                            echo "<div class='row mt-3'>";
                            echo "<p class='col'><strong>Bus Plate Number:</strong> $busPlateNumber</p>";
                            echo "<p class='col'><strong>Route Name:</strong> $routeName</p>";
                            echo "</div>";

                            // Fetch all the stops for the route
                            $stopsQuery = "SELECT name
                                           FROM stops
                                           WHERE route_id = (SELECT route_id FROM buses WHERE plate_number = '$busPlateNumber')
                                           LIMIT $stopsPerPage OFFSET " . ($currentPage - 1) * $stopsPerPage;
                            $stopsResult = $conn->query($stopsQuery);

                            if ($stopsResult && $stopsResult->num_rows > 0) {
                                echo "<div class='row mt-3 '>";
                                echo "<p class='col'><strong>Stop Lists:</strong></p>";
                                echo "</div>";
                                echo "<div class='col-md-6'>";
                                echo "<table class='table table-bordered table-sm'>";
                                echo "<thead>";
                                echo "<tr>";
                                echo "<th>Stop Name</th>";
                                echo "</tr>";
                                echo "</thead>";
                                echo "<tbody>";
                                while ($stopRow = $stopsResult->fetch_assoc()) {
                                    $stopName = $stopRow['name'];
                                    echo "<tr>";
                                    echo "<td>$stopName</td>";
                                    echo "</tr>";
                                }
                                echo "</tbody>";
                                echo "</table>";
                            } else {
                                echo "<p>No stops found for the route.</p>";
                            }
                            echo "</div>";

                            // Query to count the total number of stops
                            $totalStopsQuery = "SELECT COUNT(*) AS total FROM stops WHERE route_id = (SELECT route_id FROM buses WHERE plate_number = '$busPlateNumber')";
                            $totalStopsResult = $conn->query($totalStopsQuery);
                            $totalStopsRow = $totalStopsResult->fetch_assoc();
                            $totalStops = $totalStopsRow['total'];

                            // Calculate the total number of pages
                            $totalPages = ceil($totalStops / $stopsPerPage);
                        } else {
                            echo "<p class='text-danger'>Stops information not found.</p>";
                        }
                        ?>
                    </div>

                    <!-- Display pagination links -->
                    <div class="pagination mx-2">
                        <nav aria-label="...">
                            <ul class="pagination">
                                <?php
                                // Previous page link
                                if ($currentPage > 1) {
                                    echo '<li class="page-item"><a class="page-link" href="route.php?page=' . ($currentPage - 1) . '">Previous</a></li>';
                                } else {
                                    echo '<li class="page-item disabled"><span class="page-link">Previous</span></li>';
                                }

                                // Page links
                                for ($i = 1; $i <= $totalPages; $i++) {
                                    if ($i == $currentPage) {
                                        echo '<li class="page-item active" aria-current="page"><span class="page-link">' . $i . '</span></li>';
                                    } else {
                                        echo '<li class="page-item"><a class="page-link" href="route.php?page=' . $i . '">' . $i . '</a></li>';
                                    }
                                }

                                // Next page link
                                if ($currentPage < $totalPages) {
                                    echo '<li class="page-item"><a class="page-link" href="route.php?page=' . ($currentPage + 1) . '">Next</a></li>';
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
    <script src="bootstrap/js/bootstrap.js"></script>
</body>

</html>
