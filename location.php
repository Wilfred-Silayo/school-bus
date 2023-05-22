<?php include 'parent/session.php'; ?>
<?php include 'parent/header.php'; ?>

<body class="bg-light" style=" min-height:90vh;">

    <?php include 'parent/navbar.php'; ?>
    <div class="container-fluid">
        <div class="row flex-nowrap">
            <?php include 'parent/menubar.php'; ?>
            <div class="col-md-9 mt-3">
                <div class="container bg-white my-3 mx-3">
                    <p class="fw-bold ">See were are my childrens</p>
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>Student Name</th>
                                <th>Location</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $parentId = $_SESSION['parent'];
                            $studentQuery = "SELECT * FROM students WHERE parent_id = '$parentId'";
                            $studentResult = $conn->query($studentQuery);

                            if ($studentResult && $studentResult->num_rows > 0) {
                                while ($studentRow = $studentResult->fetch_assoc()) {
                                    echo '<tr>';
                                    echo '<td>' . $studentRow['firstName'] .' '. $studentRow['lastName']. '</td>';
                                    echo '<td>';
                                    echo '<a href="map.php?plate_number=' . $studentRow['bus_assigned'] . '"><i class="fa-solid fa-map mx-2"></i></a>';
                                    echo '</td';
                                    echo '</tr>';
                                }
                            } else {
                                echo '<tr><td colspan="3" class="text-danger fw-bold">No students found.</td></tr>';
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <script src="bootstrap/js/bootstrap.js"></script>
</body>

</html>