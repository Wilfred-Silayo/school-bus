<?php include 'parent/session.php'; ?>
<?php include 'parent/header.php'; ?>

<body class="bg-light" style=" min-height:90vh;">

    <?php include 'parent/navbar.php'; ?>
    <div class="container-fluid">
        <div class="row flex-nowrap">
            <?php include 'parent/menubar.php'; ?>
            <div class="col-md-9 mt-3">
                <div class="container bg-white my-3 mx-3">

                    <div class="alert alert-danger">
                        No GPS connection found <a href="location.php"> Go back</a>
                    </div>

                </div>
            </div>
        </div>
    </div>
    <script src="bootstrap/js/bootstrap.js"></script>
</body>

</html>