<?php
  	session_start();
  	if(isset($_SESSION['driver'])){
    	header('location:home.php');
  	}
?>
<?php include 'includes/header.php'; ?>

<body>
    <?php include 'includes/navbar.php'; ?>
    <div class="container">
        <div class="row justify-content-center mt-5">
            <div class="col-md-6">
                <div class="card bg-light">
                    <div class="card-header bg-success text-white">
                        Login as Driver
                    </div>
                    <div class="card-body">
                        <form action="login.php" method="post">
                        <?php
                                if(isset($_SESSION['error'])){
                                    echo "
                                        <div class='alert alert-danger'>
                                            <span>".$_SESSION['error']."</span> 
                                        </div>
                                    ";
                                    unset($_SESSION['error']);
                                }
                            ?>
                            <div class="mb-3"> 
                                <label for="username" class="form-label">Username</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fa fa-user"></i></span>
                                    <input type="text" class="form-control" id="username" name="username"
                                        placeholder="Enter username">
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="password" class="form-label">Password</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fa fa-lock"></i></span>
                                    <input type="password" class="form-control" id="password" name="password"
                                        placeholder="Enter password">
                                    <div class="input-group-append">
                                        <button type="button" class="btn btn-light rounded-left rounded-right btn-eye"
                                            id="togglePassword"><i class="fa fa-eye"></i></button>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="d-flex justify-content-between">
                                    <button type="submit" class="btn btn-success" name="login">Login</button>
                                    <a href="#" class="align-self-center">Forgot Password?</a>
                                </div>
                            </div>
                        </form>
                        <div class="mt-4"><hr></div>
                        <h5 class="text-center my-3">or</h5>
                        <div class="row">
                            <div class="col-6 text-center">
                                <a href="../admin/index.php" class="btn btn-success">Login as Admin</a>
                            </div>
                            <div class="col-6 text-center">
                                <a href="../index.php" class="btn btn-success">Login as Parent</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

<?php include 'includes/scripts.php' ?>

</html>