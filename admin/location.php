<?php include 'includes/main.php'; ?>

<?php
$recordsPerPage = 10;
$page = isset($_GET['page']) ? $_GET['page'] : 1;
$offset = ($page - 1) * $recordsPerPage;

$searchQuery = '';
if (isset($_GET['search'])) {
    $search = $_GET['search'];
    $searchQuery = "WHERE buses.plate_number LIKE '%$search%'
                    OR drivers.firstName LIKE '%$search%'
                    OR drivers.lastName LIKE '%$search%'
                    OR routes.name LIKE '%$search%'";
}

$query = "SELECT buses.plate_number, drivers.firstName, drivers.lastName, routes.name AS route_name 
          FROM buses
          LEFT JOIN drivers ON buses.driver_id = drivers.licence
          LEFT JOIN routes ON buses.route_id = routes.id";
          
if (!empty($searchQuery)) {
    $query .= " $searchQuery";
}

$query .= " LIMIT $offset, $recordsPerPage";

$result = $conn->query($query);

$totalBusesQuery = "SELECT COUNT(*) AS total FROM buses
                    LEFT JOIN drivers ON buses.driver_id = drivers.licence
                    LEFT JOIN routes ON buses.route_id = routes.id";

if (!empty($searchQuery)) {
    $totalBusesQuery .= " $searchQuery";
}

$totalBusesResult = $conn->query($totalBusesQuery);

if ($result && $result->num_rows > 0) {
    $buses = $result->fetch_all(MYSQLI_ASSOC);
} else {
    $buses = [];
}

if ($totalBusesResult && $totalBusesResult->num_rows > 0) {
    $totalBuses = $totalBusesResult->fetch_assoc()['total'];
} else {
    $totalBuses = 0;
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
        <h4 class="mt-3 mb-4 col-md-4">Buses locations</h4>
        <div class="col-md-6 mt-3 mb-4">
            <!-- Search form -->
            <form action="location.php" method="get" class="form-inline">
                <div class="input-group">
                    <input type="text" class="form-control" name="search" placeholder="Search by Plate Number">
                    <div class="input-group-append">
                        <button class="btn btn-primary" type="submit">Search</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Display the bus records in a table -->
    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>Plate Number</th>
                <th>Driver</th>
                <th>Route</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if (!empty($buses)) {
                foreach ($buses as $bus) {
                    echo '<tr>';
                    echo '<td>' . $bus['plate_number'] . '</td>';
                    echo '<td>' . $bus['firstName'] . ' ' . $bus['lastName'] . '</td>';
                    echo '<td>' . $bus['route_name'] . '</td>';
                    echo '<td>';
                    echo '<a href="map.php?plate_number=' . $bus['plate_number'] . '"><i class="fa-solid fa-map mx-2"></i></a>';
                    echo '</td';
                    echo '</tr>';
                }
            } else {
                echo '<tr><td colspan="4">No buses found</td></tr>';
            }
            ?>
        </tbody>
    </table>
 <!-- Display pagination links -->
 <div class="pagination">
        <nav aria-label="...">
            <ul class="pagination">
                <?php
                $totalPages = ceil($totalBuses / $recordsPerPage);

                // Previous page link
                if ($page > 1) {
                    echo '<li class="page-item"><a class="page-link" href="location.php?page=' . ($page - 1) . '">Previous</a></li>';
                } else {
                    echo '<li class="page-item disabled"><span class="page-link">Previous</span></li>';
                }

                // Page links
                for ($i = 1; $i <= $totalPages; $i++) {
                    if ($i == $page) {
                        echo '<li class="page-item active" aria-current="page"><span class="page-link">' . $i . '</span></li>';
                    } else {
                        echo '<li class="page-item"><a class="page-link" href="location.php?page=' . $i . '">' . $i . '</a></li>';
                    }
                }

                // Next page link
                if ($page < $totalPages) {
                    echo '<li class="page-item"><a class="page-link" href="location.php?page=' . ($page + 1) . '">Next</a></li>';
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

</html>