<?php include 'includes/main.php'; ?>
<div class="container-fluid">
    <div class="row  mt-3 ">
        <h4 class="col">
            Welcome Back:
            <?php
            $user=$_SESSION['admin'];
            $sql = "SELECT * FROM admins WHERE id='$user'";
            $query = $conn->query($sql);
            $row = $query->fetch_assoc();
            echo "<span class='text-primary'>$row[firstName] $row[lastName]</span>";
            ?>
        </h4>
    </div>
    <div class="row mt-2 col-md-12 bg-white">
        <div class="col-md-12 mt-4 ">
            <p class="fw-bold fs-5 text-primary">Report Summary</p>
        </div>
        <div class="row mb-3 ms-2">
            <div class="card col-xl-3 col-md-4 mx-2 mb-1" style="width: 15rem;">
                <div class="card-body">
                    <h5 class="card-title">
                        Total students :
                        <?php $sql="select * from students";
                            $query = $conn->query($sql);
                            $row = $query->num_rows;
                            echo '<span class="text-primary">'.$row.'</span>';
                        ?>
                    </h5>
                    <p class="card-text"></p>
                    <a href="manage_student.php" class="text-primary">Go To Students</a>
                </div>
            </div>
            <div class="card col-xl-3 col-md-4 mx-2 mb-1" style="width: 15rem;">
                <div class="card-body">
                    <h5 class="card-title">
                        Total Parents :
                        <?php $sql="select * from parents";
                                         $query = $conn->query($sql);
                                         $row = $query->num_rows;
                                         echo '<span class="text-primary">'.$row.'</span>';
                                        ?>

                    </h5>
                    <p class="card-text"></p>
                    <a href="manage_parent.php" class="text-primary">Go To Parents</a>
                </div>
            </div>
            <div class="card col-xl-3 col-md-4 mx-2 mb-1" style="width: 15rem;">
                <div class="card-body">
                    <h5 class="card-title">
                        Total Drivers :
                        <?php $sql="select * from drivers";
                                         $query = $conn->query($sql);
                                         $row = $query->num_rows;
                                         echo '<span class="text-primary">'.$row.'</span>';
                                        ?>
                    </h5>
                    <p class="card-text"></p>
                    <a href="manage_driver.php" class="text-primary">Go To Drivers</a>
                </div>
            </div>
            <div class="card col-xl-3 col-md-4 mx-2 mb-1" style="width: 15rem;">
                <div class="card-body">
                    <h5 class="card-title">
                        Total Buses :
                        <?php $sql="select * from buses";
                                         $query = $conn->query($sql);
                                         $row = $query->num_rows;
                                         echo '<span class="text-primary">'.$row.'</span>';
                                        ?>
                    </h5>
                    <p class="card-text"></p>
                    <a href="manage_bus.php" class="text-primary">Go To Buses</a>
                </div>
            </div>
        </div>
        <div class="row mb-3 ms-2">
            <div class="card col-xl-3 col-md-4 mx-2 mb-1" style="width: 15rem;">
                <div class="card-body">
                    <h5 class="card-title">
                        Total Routes :
                        <?php $sql="select * from routes";
                                         $query = $conn->query($sql);
                                         $row = $query->num_rows;
                                         echo '<span class="text-primary">'.$row.'</span>';
                                        ?>
                    </h5>
                    <p class="card-text"></p>
                    <a href="manage_route.php" class="text-primary">Go To Routes</a>
                </div>
            </div>

            <div class="card col-xl-3 col-md-4 mx-2 mb-1" style="width: 15rem;">
                <div class="card-body">
                    <h5 class="card-title">
                        Total Stops :
                        <?php $sql="select * from stops";
                                         $query = $conn->query($sql);
                                         $row = $query->num_rows;
                                         echo '<span class="text-primary">'.$row.'</span>';
                                        ?>
                    </h5>
                    <p class="card-text"></p>
                </div>
            </div>
        </div>
    </div>
</div>
</div>
</div>
</div>
<script src="../bootstrap/js/bootstrap.js"></script>
</body>

</html>