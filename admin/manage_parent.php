<?php include 'includes/main.php'; ?>

<?php
if (isset($_GET['parentId'])) {
    $parentId = $_GET['parentId'];

    if ($parentId > 0) {
        $stmt = $conn->prepare("DELETE FROM parents WHERE id = ?");
        $stmt->bind_param("s", $parentId);

        if ($stmt->execute()) {
            if ($stmt->affected_rows > 0) {
                $_SESSION['success_message'] = "Parent deleted successfully";
            } else {
                $_SESSION['error_message'] = "Parent not found";
                echo '<script>window.location.href = "manage_parent.php";</script>';
            }
        } else {
            $_SESSION['error_message'] = "Failed to delete parent";
            echo '<script>window.location.href = "manage_parent.php";</script>';
        }
    } else {
        $_SESSION['error_message'] = "Invalid parentId";
    }
   
}
?>

<?php
$recordsPerPage = 10;
$page = isset($_GET['page']) ? $_GET['page'] : 1;
$offset = ($page - 1) * $recordsPerPage;

$search = isset($_GET['search']) ? $_GET['search'] : '';

$query = "SELECT * FROM parents";
if (!empty($search)) {
    $query .= " WHERE firstName LIKE '%$search%' OR id LIKE '%$search%' OR lastName LIKE '%$search%' OR email LIKE '%$search%'";
}

$query .= " LIMIT $offset, $recordsPerPage";

$result = $conn->query($query);

$totalParentsQuery = "SELECT COUNT(*) AS total FROM parents";
if (!empty($search)) {
    $totalParentsQuery .= " WHERE firstName LIKE '%$search%' OR id LIKE '%$search%' OR lastName LIKE '%$search%' OR email LIKE '%$search%'";
}

$totalParentsResult = $conn->query($totalParentsQuery);

if ($result && $result->num_rows > 0) {
    $parents = $result->fetch_all(MYSQLI_ASSOC);
} else {
    $parents = [];
}

if ($totalParentsResult && $totalParentsResult->num_rows > 0) {
    $totalParents = $totalParentsResult->fetch_assoc()['total'];
} else {
    $totalParents = 0;
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
        <h4 class="mt-3 mb-4 col-md-4">Manage Parents</h4>
        <div class="col-md-6 mt-3 mb-4">
            <form action="manage_parent.php" method="get" class="form-inline">
                <div class="input-group">
                    <input type="text" class="form-control" name="search" placeholder="Search by Id, name, or email">
                    <div class="input-group-append">
                        <button class="btn btn-primary" type="submit">Search</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Display the parent records in a table -->
    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Email</th>
                <th>Phone</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if (!empty($parents)) {
                foreach ($parents as $parent) {
                    echo '<tr>';
                    echo '<td>' . $parent['id'] . '</td>';
                    echo '<td>' . $parent['firstName'] . ' ' . $parent['middleName'] . ' ' . $parent['lastName'] . '</td>';
                    echo '<td>' . $parent['email'] . '</td>';
                    echo '<td>' . $parent['phone'] . '</td>';
                    echo '<td>';
                    echo '<a href="view_parent.php?id=' . $parent['id'] . '"><i class="fa-solid fa-eye mx-2"></i></a>';
                    echo '<a href="edit_parent.php?id=' . $parent['id'] . '"><i class="fa-solid fa-edit mx-2"></i></a>';
                    echo '<a href="" data-bs-toggle="modal" data-bs-target="#staticBackdrop"
                            data-id="' . $parent['id'] . '" data-firstName="' . $parent['firstName'] . '"
                            data-lastName="' . $parent['lastName'] .'"><i class="fa-solid fa-trash mx-2 text-danger"></i></a> ';
                    echo '</td>';
                    echo '</tr>';
                }
            } else {
                echo '<tr><td colspan="5">No parents found</td></tr>';
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
                    <h5 class="modal-title" id="staticBackdropLabel">Delete Parent</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p> Are you sure you want to delete: <strong><span id="delete-parent-name"></span></strong>?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Close</button>
                    <form id="delete-parent-form" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="get">
                        <input type="hidden" name="parentId" id="delete-parent-id" value="">
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
                $totalPages = ceil($totalParents / $recordsPerPage);

                // Previous page link
                if ($page > 1) {
                    echo '<li class="page-item"><a class="page-link" href="manage_parent.php?page=' . ($page - 1) . '">Previous</a></li>';
                } else {
                    echo '<li class="page-item disabled"><span class="page-link">Previous</span></li>';
                }

                // Page links
                for ($i = 1; $i <= $totalPages; $i++) {
                    if ($i == $page) {
                        echo '<li class="page-item active" aria-current="page"><span class="page-link">' . $i . '</span></li>';
                    } else {
                        echo '<li class="page-item"><a class="page-link" href="manage_parent.php?page=' . $i . '">' . $i . '</a></li>';
                    }
                }

                // Next page link
                if ($page < $totalPages) {
                    echo '<li class="page-item"><a class="page-link" href="manage_parent.php?page=' . ($page + 1) . '">Next</a></li>';
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
            const deleteParentForm = document.querySelector('#delete-parent-form');
            const deleteParentName = document.querySelector('#delete-parent-name');
            const deleteParentId = document.querySelector('#delete-parent-id');
            const deleteParentAction = deleteParentForm.getAttribute('action').replace('__parentId__', id);

            deleteParentForm.setAttribute('action', deleteParentAction);
            deleteParentId.value = id;
            deleteParentName.textContent = `${firstName} ${lastName}`;
        });
    });
</script>


<script src="../bootstrap/js/bootstrap.js"></script>
</body>

</html>