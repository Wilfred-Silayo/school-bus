<?php include 'parent/session.php'; ?>
<?php include 'parent/header.php'; ?>

<body class="bg-light" style=" min-height:90vh;">

    <?php include 'parent/navbar.php'; ?>
    <div class="container-fluid">
        <div class="row flex-nowrap">
            <?php include 'parent/menubar.php'; ?>
            <!-- Content Wrapper. Contains page content -->
            <div class="col-md-9 mt-3">
                <div class="container bg-white my-3 mx-3">
                    <div class="row my-3 border-bottom border-2">

                        <h4 class="mt-3">Welcome, Parent! :
                            <?php 
                            $parentId = $_SESSION['parent'];
                            $query = "SELECT * FROM parents WHERE id = '$parentId'"; 
                            $result = $conn->query($query);
                            $row = $result->fetch_assoc();
                            echo '<span class="text-primary text-muted">'.$row['firstName'].' '.$row['lastName'].'</span>';
                            ?>
                        </h4>
                    </div>
                    <p>Here you can find information about your child's school bus location.</p>
                    <h4>Your Students:</h4>
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>Student Name</th>
                                <th>Grade</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $studentQuery = "SELECT * FROM students WHERE parent_id = '$parentId'";
                            $studentResult = $conn->query($studentQuery);

                            if ($studentResult && $studentResult->num_rows > 0) {
                                while ($studentRow = $studentResult->fetch_assoc()) {
                                    echo '<tr>';
                                    echo '<td>' . $studentRow['firstName'] .' '. $studentRow['lastName']. '</td>';
                                    echo '<td>' . $studentRow['grade_level'] . '</td>';
                                    echo '</tr>';
                                }
                            } else {
                                echo '<tr><td colspan="3" class="text-danger fw-bold">No students found.</td></tr>';
                            }
                            ?>
                        </tbody>
                    </table>
                    <div class="row mt-3">
                    </div>
                </div>
            </div>

        </div>
    </div>

    <script src="bootstrap/js/bootstrap.js"></script>
</body>

</html>


