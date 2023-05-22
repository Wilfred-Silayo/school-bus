<?php include 'includes/main.php'; ?>

<?php
if (isset($_GET['id'])) {
    $routeId = $_GET['id'];

    if (!empty($routeId)) {
        $stmt = $conn->prepare("DELETE FROM routes WHERE id = ?");
        $stmt->bind_param("s", $routeId);

        if ($stmt->execute()) {
            if ($stmt->affected_rows > 0) {
                $_SESSION['success_message'] = "Route deleted successfully";
            } else {
                $_SESSION['error_message'] = "Route not found";
                echo '<script>window.location.href = "manage_routes.php";</script>';
            }
        } else {
            $_SESSION['error_message'] = "Failed to delete route";
            echo '<script>window.location.href = "manage_routes.php";</script>';
        }
    } else {
        $_SESSION['error_message'] = "Invalid route ID";
    }
   
}
?>

<?php
$recordsPerPage = 10;
$page = isset($_GET['page']) ? $_GET['page'] : 1;
$offset = ($page - 1) * $recordsPerPage;

$search = isset($_GET['search']) ? $_GET['search'] : '';

$query = "SELECT * FROM routes";
if (!empty($search)) {
    $query .= " WHERE name LIKE '%$search%' OR start_point LIKE '%$search%' OR pass_through LIKE '%$search%' OR end_point LIKE '%$search%'";
}

$query .= " LIMIT $offset, $recordsPerPage";

$result = $conn->query($query);

$totalRoutesQuery = "SELECT COUNT(*) AS total FROM routes";
if (!empty($search)) {
    $totalRoutesQuery .= " WHERE name LIKE '%$search%' OR start_point LIKE '%$search%' OR pass_through LIKE '%$search%' OR end_point LIKE '%$search%'";
}

$totalRoutesResult = $conn->query($totalRoutesQuery);

if ($result && $result->num_rows > 0) {
    $routes = $result->fetch_all(MYSQLI_ASSOC);
} else {
    $routes = [];
}

if ($totalRoutesResult && $totalRoutesResult->num_rows > 0) {
    $totalRoutes = $totalRoutesResult->fetch_assoc()['total'];
} else {
    $totalRoutes = 0;
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
        <h4 class="mt-3 mb-4 col-md-4">Manage Routes</h4>
        <div class="col-md-6 mt-3 mb-4">
            <form action="manage_route.php" method="get" class="form-inline">
                <div class="input-group">
                    <input type="text" class="form-control" name="search"
                        placeholder="Search by Name, Start Point, Via, or End Point">
                    <div class="input-group-append">
                        <button class="btn btn-primary" type="submit">Search</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Display the route records in a table -->
    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>Name</th>
                <th>Start Point</th>
                <th>Via</th>
                <th>End Point</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if (!empty($routes)) {
                foreach ($routes as $route) {
                    echo '<tr>';
                    echo '<td>' . $route['name'] . '</td>';
                    echo '<td>' . $route['start_point'] . '</td>';
                    echo '<td>' . $route['pass_through'] . '</td>';
                    echo '<td>' . $route['end_point'] . '</td>';
                    echo '<td>';
                    echo '<a href="manage_stop.php?routeId=' . $route['id'] . '"><i class="fa-solid fa-eye mx-2"></i></a>';
                    echo '<a href="edit_route.php?id=' . $route['id'] . '"><i class="fa-solid fa-edit mx-2"></i></a>';
                    echo '<a href="" data-bs-toggle="modal" data-bs-target="#staticBackdrop"
                            data-routeId="' . $route['id'] . '" data-name="' . $route['name'] . '"
                            data-startpoint="' . $route['start_point'] . '" data-via="' . $route['pass_through'] . '"
                            data-endpoint="' . $route['end_point'] .'"><i class="fa-solid fa-trash mx-2 text-danger"></i></a> ';
                    echo '</td>';
                    echo '</tr>';
                }
            } else {
                echo '<tr><td colspan="5">No routes found</td></tr>';
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
                    <h5 class="modal-title" id="staticBackdropLabel">Delete Route</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p> Are you sure you want to delete: <strong><span id="delete-route-name"></span></strong>?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Close</button>
                    <form id="delete-route-form" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="get">
                        <input type="hidden" name="id" id="delete-route-id" value="">
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
                $totalPages = ceil($totalRoutes / $recordsPerPage);

                // Previous page link
                if ($page > 1) {
                    echo '<li class="page-item"><a class="page-link" href="manage_routes.php?page=' . ($page - 1) . '">Previous</a></li>';
                } else {
                    echo '<li class="page-item disabled"><span class="page-link">Previous</span></li>';
                }

                // Page links
                for ($i = 1; $i <= $totalPages; $i++) {
                    if ($i == $page) {
                        echo '<li class="page-item active" aria-current="page"><span class="page-link">' . $i . '</span></li>';
                    } else {
                        echo '<li class="page-item"><a class="page-link" href="manage_routes.php?page=' . $i . '">' . $i . '</a></li>';
                    }
                }

                // Next page link
                if ($page < $totalPages) {
                    echo '<li class="page-item"><a class="page-link" href="manage_routes.php?page=' . ($page + 1) . '">Next</a></li>';
                } else {
                    echo '<li class="page-item disabled"><span class="page-link">Next</span></li>';
                }
                ?>
            </ul>
        </nav>
    </div>
</div>
</di>
</div>
</di>

<script>
var deleteModal = document.getElementById('staticBackdrop');
deleteModal.addEventListener('show.bs.modal', function(event) {
    var button = event.relatedTarget;
    var routeId = button.getAttribute('data-routeId');
    var name = button.getAttribute('data-name');
    var startpoint = button.getAttribute('data-startpoint');
    var via = button.getAttribute('data-via');
    var endpoint = button.getAttribute('data-endpoint');

    var modalTitle = deleteModal.querySelector('.modal-title');
    var deleteRouteName = deleteModal.querySelector('#delete-route-name');
    var deleteRouteId = deleteModal.querySelector('#delete-route-id');

    modalTitle.innerHTML = 'Delete Route';
    deleteRouteName.innerHTML = name + ' (Start Point: ' + startpoint + ', Via: ' + via + ', End Point: ' +
        endpoint + ')';
    deleteRouteId.value = routeId;
});
</script>

<script src="../bootstrap/js/bootstrap.js"></script>
</body>

</html>