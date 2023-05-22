<?php include 'includes/main.php'; ?>

<?php
if (isset($_GET['licence'])) {
    $licence = $_GET['licence'];

    if (!empty($licence)) {
        $stmt = $conn->prepare("DELETE FROM drivers WHERE licence = ?");
        $stmt->bind_param("s", $licence);

        if ($stmt->execute()) {
            if ($stmt->affected_rows > 0) {
                $_SESSION['success_message'] = "Driver deleted successfully";
            } else {
                $_SESSION['error_message'] = "Driver not found";
                echo '<script>window.location.href = "manage_driver.php";</script>';
            }
        } else {
            $_SESSION['error_message'] = "Failed to delete driver";
            echo '<script>window.location.href = "manage_driver.php";</script>';
        }
    } else {
        $_SESSION['error_message'] = "Invalid driver licence";
    }
   
}
?>

<?php
$recordsPerPage = 10;
$page = isset($_GET['page']) ? $_GET['page'] : 1;
$offset = ($page - 1) * $recordsPerPage;

$search = isset($_GET['search']) ? $_GET['search'] : '';

$query = "SELECT * FROM drivers";
if (!empty($search)) {
    $query .= " WHERE licence LIKE '%$search%' OR firstName LIKE '%$search%' OR lastName LIKE '%$search%' OR email LIKE '%$search%'";
}

$query .= " LIMIT $offset, $recordsPerPage";

$result = $conn->query($query);

$totalDriversQuery = "SELECT COUNT(*) AS total FROM drivers";
if (!empty($search)) {
    $totalDriversQuery .= " WHERE licence LIKE '%$search%' OR firstName LIKE '%$search%' OR lastName LIKE '%$search%' OR email LIKE '%$search%'";
}

$totalDriversResult = $conn->query($totalDriversQuery);

if ($result && $result->num_rows > 0) {
    $drivers = $result->fetch_all(MYSQLI_ASSOC);
} else {
    $drivers = [];
}

if ($totalDriversResult && $totalDriversResult->num_rows > 0) {
    $totalDrivers = $totalDriversResult->fetch_assoc()['total'];
} else {
    $totalDrivers = 0;
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
        <h4 class="mt-3 mb-4 col-md-4">Manage Drivers</h4>
        <div class="col-md-6 mt-3 mb-4">
            <form action="manage_driver.php" method="get" class="form-inline">
                <div class="input-group">
                    <input type="text" class="form-control" name="search" placeholder="Search by Licence, name, or email">
                    <div class="input-group-append">
                        <button class="btn btn-primary" type="submit">Search</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Display the driver records in a table -->
    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>Licence</th>
                <th>Name</th>
                <th>Email</th>
                <th>Phone</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if (!empty($drivers)) {
                foreach ($drivers as $driver) {
                    echo '<tr>';
                    echo '<td>' . $driver['licence'] . '</td>';
                    echo '<td>' . $driver['firstName'] . ' ' . $driver['middleName'] . ' ' . $driver['lastName'] . '</td>';
                    echo '<td>' . $driver['email'] . '</td>';
                    echo '<td>' . $driver['phone'] . '</td>';
                    echo '<td>';
                    echo '<a href="edit_driver.php?licence=' . $driver['licence'] . '"><i class="fa-solid fa-edit mx-2"></i></a>';
                    echo '<a href="" data-bs-toggle="modal" data-bs-target="#staticBackdrop"
                            data-licence="' . $driver['licence'] . '" data-firstName="' . $driver['firstName'] . '"
                            data-lastName="' . $driver['lastName'] .'"><i class="fa-solid fa-trash mx-2 text-danger"></i></a> ';
                    echo '</td>';
                    echo '</tr>';
                }
            } else {
                echo '<tr><td colspan="5">No drivers found</td></tr>';
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
                    <h5 class="modal-title" id="staticBackdropLabel">Delete Driver</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p> Are you sure you want to delete: <strong><span id="delete-driver-name"></span></strong>?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Close</button>
                    <form id="delete-driver-form" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="get">
                        <input type="hidden" name="licence" id="delete-driver-licence" value="">
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
                $totalPages = ceil($totalDrivers / $recordsPerPage);

                // Previous page link
                if ($page > 1) {
                    echo '<li class="page-item"><a class="page-link" href="manage_driver.php?page=' . ($page - 1) . '">Previous</a></li>';
                } else {
                    echo '<li class="page-item disabled"><span class="page-link">Previous</span></li>';
                }

                // Page links
                for ($i = 1; $i <= $totalPages; $i++) {
                    if ($i == $page) {
                        echo '<li class="page-item active" aria-current="page"><span class="page-link">' . $i . '</span></li>';
                    } else {
                        echo '<li class="page-item"><a class="page-link" href="manage_driver.php?page=' . $i . '">' . $i . '</a></li>';
                    }
                }

                // Next page link
                if ($page < $totalPages) {
                    echo '<li class="page-item"><a class="page-link" href="manage_driver.php?page=' . ($page + 1) . '">Next</a></li>';
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
    // JavaScript code to set values in the delete driver modal
    var deleteDriverModal = document.getElementById('staticBackdrop');
    deleteDriverModal.addEventListener('show.bs.modal', function (event) {
        var button = event.relatedTarget;
        var licence = button.getAttribute('data-licence');
        var firstName = button.getAttribute('data-firstName');
        var lastName = button.getAttribute('data-lastName');
        var driverName = firstName + ' ' + lastName;
        var deleteDriverForm = document.getElementById('delete-driver-form');
        var deleteDriverLicence = document.getElementById('delete-driver-licence');

        document.getElementById('delete-driver-name').textContent = driverName;
        deleteDriverForm.action = deleteDriverForm.action + '?licence=' + licence;
        deleteDriverLicence.value = licence;
    });
</script>

<script src="../bootstrap/js/bootstrap.js"></script>
</body>

</html>
