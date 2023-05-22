<?php
include 'includes/main.php'; ?>
<?php
if (isset($_GET['plate_number'])) {
    $plate_number = $_GET['plate_number'];

    if (!empty($plate_number)) {
        $stmt = $conn->prepare("DELETE FROM buses WHERE plate_number = ?");
        $stmt->bind_param("s", $plate_number);

        if ($stmt->execute()) {
            if ($stmt->affected_rows > 0) {
                $_SESSION['success_message'] = "Bus deleted successfully";
            } else {
                $_SESSION['error_message'] = "Bus not found";
                echo '<script>window.location.href = "manage_buse.php";</script>';
            }
        } else {
            $_SESSION['error_message'] = "Failed to delete bus";
            echo '<script>window.location.href = "manage_buse.php";</script>';
        }
    } else {
        $_SESSION['error_message'] = "Invalid bus plate_number";
    }
   
}
?>
<?php
$recordsPerPage = 10;
$page = isset($_GET['page']) ? $_GET['page'] : 1;
$offset = ($page - 1) * $recordsPerPage;

$search = isset($_GET['search']) ? $_GET['search'] : '';

$query = "SELECT b.plate_number, d.firstName,d.lastName, r.name
          FROM buses AS b
          LEFT JOIN drivers AS d ON b.driver_id = d.licence
          LEFT JOIN routes AS r ON b.route_id = r.id";

if (!empty($search)) {
    $query .= " WHERE b.plate_number LIKE '%$search%' OR d.firstName LIKE '%$search%' OR
     d.lastName LIKE '%$search%' OR r.name LIKE '%$search%'";
}

$query .= " LIMIT $offset, $recordsPerPage";

$result = $conn->query($query);

$totalBusesQuery = "SELECT COUNT(*) AS total FROM buses";
if (!empty($search)) {
    $totalBusesQuery .= " LEFT JOIN drivers AS d ON buses.driver_id = d.licence
                         LEFT JOIN routes AS r ON buses.route_id = r.id
                         WHERE buses.plate_number LIKE '%$search%' OR  d.firstName LIKE '%$search%' OR 
                         d.lastName LIKE '%$search%' OR r.name LIKE '%$search%'";
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
    // Display the success message
    echo '<div class="alert alert-success mt-2">' . $_SESSION['success_message'] . '</div>';

    // Clear the success message from the session
    unset($_SESSION['success_message']);
}
?>

<?php if (isset($_SESSION['error_message'])) {
    // Display the error message
    echo '<div class="alert alert-danger mt-2">' . $_SESSION['error_message'] . '</div>';

    // Clear the error message from the session
    unset($_SESSION['error_message']);
}
?>

<div class="ntainer-fluid bg-white mt-3">
    <div class="row">
        <h4 class="mt-3 mb-4 col-md-4 ms-2">Manage Buses</h4>
        <div class="col-md-6 mt-3 mb-4">
            <form action="manage_bus.php" method="get" class="form-inline">
                <div class="input-group">
                    <input type="text" class="form-control" name="search"
                        placeholder="Search by Plate Number, Driver Name, or Route Name">
                    <div class="input-group-append">
                        <button class="btn btn-primary" type="submit">Search</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Display the bus records in a table -->
    <table class="table table-bordered table-striped mx-2">
        <thead>
            <tr>
                <th>Plate Number</th>
                <th>Driver Name</th>
                <th>Route Name</th>
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
                    echo '<td>' . $bus['name'] . '</td>';
                    echo '<td>';
                    echo '<a href="view_bus.php?id=' . $bus['plate_number'] . '"><i class="fa-solid fa-eye mx-2"></i></a>';
                    echo '<a href="edit_bus.php?id=' . $bus['plate_number'] . '"><i class="fa-solid fa-edit mx-2"></i></a>';
                    echo '<a href="#" href="" data-bs-toggle="modal" data-bs-target="#staticBackdrop" data-plate-number="' . $bus['plate_number'] . '">';
                    echo '<i class="fa-solid fa-trash text-danger mx-2"></i></a>';
                    echo '</td>';
                    echo '</tr>';
                }
            } else {
                echo '<tr><td colspan="4">No buses found</td></tr>';
            }
            ?>
        </tbody>
    </table>

    <div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
        aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="staticBackdropLabel">Delete bus</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p> Are you sure you want to delete: <strong><span id="delete-bus-name"></span></strong>?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Close</button>
                    <form id="delete-bus-form" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="get">
                        <input type="hidden" name="plate_number" id="delete-bus-plate-number" value="">
                        <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
   

    <!-- Display pagination links -->
    <div class="pagination mx-2">
        <nav aria-label="...">
            <ul class="pagination">
                <?php
                $totalPages = ceil($totalBuses / $recordsPerPage);

                // Previous page link
                if ($page > 1) {
                    echo '<li class="page-item"><a class="page-link" href="manage_bus.php?page=' . ($page - 1) . '">Previous</a></li>';
                } else {
                    echo '<li class="page-item disabled"><span class="page-link">Previous</span></li>';
                }

                // Page links
                for ($i = 1; $i <= $totalPages; $i++) {
                    if ($i == $page) {
                        echo '<li class="page-item active" aria-current="page"><span class="page-link">' . $i . '</span></li>';
                    } else {
                        echo '<li class="page-item"><a class="page-link" href="manage_bus.php?page=' . $i . '">' . $i . '</a></li>';
                    }
                }

                // Next page link
                if ($page < $totalPages) {
                    echo '<li class="page-item"><a class="page-link" href="manage_bus.php?page=' . ($page + 1) . '">Next</a></li>';
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
    // JavaScript code to set values in the delete bus modal
    var deleteBusModal = document.getElementById('staticBackdrop');
    deleteBusModal.addEventListener('show.bs.modal', function (event) {
        var button = event.relatedTarget;
        var plate_number = button.getAttribute('data-plate-number');
        var deleteBusForm = document.getElementById('delete-bus-form');
        var deleteBusNumber = document.getElementById('delete-bus-plate-number');
        document.getElementById('delete-bus-name').textContent = plate_number;
        deleteBusForm.action = deleteBusForm.action + '?plate_number=' + plate_number;
        deleteBusNumber.value = plate_number;
    });
</script>

<script src="../bootstrap/js/bootstrap.js"></script>
</body>

</html>