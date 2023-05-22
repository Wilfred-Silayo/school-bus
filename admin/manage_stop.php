<?php
include 'includes/main.php';

if (isset($_POST['stopId'])) {
    $stopId = $_POST['stopId'];
    $routeId=$_POST['routeId'];

    if (!empty($stopId)) {
        $stmt = $conn->prepare("DELETE FROM stops WHERE id = ?");
        $stmt->bind_param("i", $stopId);

        if ($stmt->execute()) {
            if ($stmt->affected_rows > 0) {
                $_SESSION['success_message'] = "Stop deleted successfully";
            } 
        } else {
            $_SESSION['error_message'] = "Failed to delete stop";
        }
    } else {
        $_SESSION['error_message'] = "Invalid stop ID";
    }
}

$recordsPerPage = 10;
$page = isset($_GET['page']) ? $_GET['page'] : 1;
$offset = ($page - 1) * $recordsPerPage;

$routeId = '';
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $routeId = isset($_GET['routeId']) ? $_GET['routeId'] : '';
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $routeId = isset($_POST['routeId']) ? $_POST['routeId'] : '';
} else {
    $routeId = '';
}

// Search functionality
$search = isset($_GET['search']) ? $_GET['search'] : '';

$query = "SELECT * FROM stops WHERE route_id = '$routeId'";
if (!empty($search)) {
    $query .= " AND name LIKE '%$search%'";
}

$query .= " LIMIT $offset, $recordsPerPage";

// Move the query inside the if condition
if (!empty($routeId)) {
    $result = $conn->query($query);
} else {
    $result = false;
}

$totalStopsQuery = "SELECT COUNT(*) AS total FROM stops WHERE route_id = '$routeId'";
if (!empty($search)) {
    $totalStopsQuery .= " AND name LIKE '%$search%'";
}

$totalStopsResult = $conn->query($totalStopsQuery);

if ($result && $result->num_rows > 0) {
    $stops = [];
    while ($row = $result->fetch_assoc()) {
        $stops[] = $row;
    }
} else {
    $stops = [];
}

if ($totalStopsResult && $totalStopsResult->num_rows > 0) {
    $totalStops = $totalStopsResult->fetch_assoc()['total'];
} else {
    $totalStops = 0;
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
    <div class="row align-items-baseline">
        <div class="col-md-3 col-sm-3 col-xl-3">
            <a href="add_stop.php?routeId=<?php echo $routeId ?>" class="btn btn-primary">
                <i class="fa-solid fa-plus"></i>New</a>
        </div>
        <div class="col-md-5 col-sm-6 mt-6 mb-4 col-xl-6">
            <form action="manage_stop.php" method="get" class="form-inline">
                <div class="input-group">
                    <input type="text" class="form-control" name="search" placeholder="Search by Name"
                        value="<?php echo htmlentities($search); ?>">
                    <input type="hidden" name="routeId" value="<?php echo $routeId; ?>">
                    <div class="input-group-append">
                        <button class="btn btn-primary" type="submit">Search</button>
                    </div>
                </div>
            </form>
        </div>
        <div class="col-md-3 col-sm-3 col-xl-3">
            <a href="manage_route.php" class="btn btn-secondary">Go back</a>
        </div>
    </div>

    <div class="row">
        <p class="fw-bold">Here is the list of stops in the route, you can add a new stop by clicking the plus button above</p>
        <p>
        <?php 
                $query = "SELECT * FROM routes WHERE id = ?";
                $stmt = $conn->prepare($query);
                $stmt->bind_param("i", $routeId);
                $stmt->execute();
                $result = $stmt->get_result();

                if ($result && $result->num_rows > 0) {
                $row = $result->fetch_assoc();
                echo 'Route Name: <span class="text-danger fw-bold me-4">' . $row['name'] . '</span> Start Point: <span class="text-danger fw-bold me-4">' . $row['start_point'] . '</span> End Point: <span class="text-danger fw-bold">' . $row['end_point'] . '</span>';
                }
                ?>
            
        
        </p>
    </div>
    <!-- Display the stop records in a table -->
    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>Name</th>
                <th>Latitude</th>
                <th>Longitude</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if (!empty($stops)) {
                foreach ($stops as $stop) {
                    echo '<tr>';
                    echo '<td>' . $stop['name'] . '</td>';
                    echo '<td>' . $stop['latitude'] . '</td>';
                    echo '<td>' . $stop['longitude'] . '</td>';
                    echo '<td>';
                    echo '<a href="edit_stop.php?stopId=' . $stop['id'] . '&routeId='. $routeId.'"><i class="fa-solid fa-edit mx-2"></i></a>';
                    echo '<a href="" data-bs-toggle="modal" data-bs-target="#staticBackdrop"
                            data-id="' . $stop['id'].' " data-name="' . $stop['name'].' "
                            data-routeId="' . $routeId.'"><i class="fa-solid fa-trash mx-2 text-danger"></i></a> ';
                    echo '</td>';
                    echo '</tr>';
                }
            } else {
                echo '<tr><td colspan="4">No stops found</td></tr>';
            }
            ?>
        </tbody>
    </table>

    <!-- Pagination links -->
    <ul class="pagination">
        <?php
        $totalPages = ceil($totalStops / $recordsPerPage);

        if ($page > 1) {
            echo '<li class="page-item"><a class="page-link" href="manage_stop.php?page=' . ($page - 1) . '&routeId=' . $routeId . '&search=' . htmlentities($search) . '">Previous</a></li>';
        } else {
            echo '<li class="page-item disabled"><span class="page-link">Previous</span></li>';
        }

        for ($i = 1; $i <= $totalPages; $i++) {
            if ($i == $page) {
                echo '<li class="page-item active" aria-current="page"><span class="page-link">' . $i . '</span></li>';
            } else {
                echo '<li class="page-item"><a class="page-link" href="manage_stop.php?page=' . $i . '&routeId=' . $routeId . '&search=' . htmlentities($search) . '">' . $i . '</a></li>';
            }
        }

        if ($page < $totalPages) {
            echo '<li class="page-item"><a class="page-link" href="manage_stop.php?page=' . ($page + 1) . '&routeId=' . $routeId . '&search=' . htmlentities($search) . '">Next</a></li>';
        } else {
            echo '<li class="page-item disabled"><span class="page-link">Next</span></li>';
        }
        ?>
    </ul>
</div>

<!-- delete modal -->
<div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
    aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="staticBackdropLabel">Delete stop</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p> Are you sure you want to delete: <strong><span id="delete-stop-name"></span></strong>?</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Close</button>
                <form id="delete-stop-form" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
                    <input type="hidden" name="stopId" id="delete-stop-id" value="">
                    <input type="hidden" name="routeId" id="delete-route-id" value="">
                    <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                </form>
            </div>
        </div>
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
            const routeId = button.getAttribute('data-routeId');
            const Name = button.getAttribute('data-name');
            const deleteStopForm = document.querySelector('#delete-stop-form');
            const deleteStopName = document.querySelector('#delete-stop-name');
            const deleteStopId = document.querySelector('#delete-stop-id');
            const deleteRouteId = document.querySelector('#delete-route-id');
            deleteStopId.value = id;
            deleteRouteId.value = routeId;
            deleteStopName.textContent = `${Name}`;
        });
    });
</script>


<script src="../bootstrap/js/bootstrap.js"></script>
</body>

</html>


