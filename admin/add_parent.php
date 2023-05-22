<?php
include 'includes/main.php';
// Retrieve error messages from query parameters, if any
$fnameError = isset($_GET['fnameError']) ? $_GET['fnameError'] : "";
$mnameError = isset($_GET['mnameError']) ? $_GET['mnameError'] : "";
$lnameError = isset($_GET['lnameError']) ? $_GET['lnameError'] : "";
$emailError = isset($_GET['emailError']) ? $_GET['emailError'] : "";
$phoneError = isset($_GET['phoneError']) ? $_GET['phoneError'] : "";

// Check if there is a success message in the session
if (isset($_SESSION['success_message'])) {
    // Display the success message
    echo '<div class="alert alert-success mt-2">' . $_SESSION['success_message'] . '</div>';
    
    // Clear the success message from the session
    unset($_SESSION['success_message']);
}
?>


<div class="container-fluid bg-white mt-3">
    <div class="row mb-3 border-bottom border-2">
        <h3 class="text-dark mb-2 mt-2">Add New Parent</h3>
    </div>
    <form action="includes/register_parent.php" method="post">
        <!-- First Name -->
        <div class="form-group">
            <div class="row mb-3">
                <div class="col-md-3 col-sm-2">
                    <label for="firstName">First Name:</label>
                </div>
                <div class="col-md-7 col-sm-5">
                    <input type="text" class="form-control" id="firstName" name="firstName"
                        placeholder="Enter First Name">
                    <span id="fnameError" class="text-danger"><?php echo $fnameError; ?></span>
                </div>
            </div>
        </div>
        <!-- Middle Name -->
        <div class="form-group">
            <div class="row mb-3">
                <div class="col-md-3 col-sm-2">
                    <label for="middleName">Middle Name:</label>
                </div>
                <div class="col-md-7 col-sm-5">
                    <input type="text" class="form-control" id="middleName" name="middleName"
                        placeholder="Enter Middle Name">
                    <small id="mnameError" class="text-danger"><?php echo $mnameError; ?></small>
                </div>
            </div>
        </div>
        <!-- Last Name -->
        <div class="form-group">
            <div class="row mb-3">
                <div class="col-md-3 col-sm-2">
                    <label for="lastName">Last Name:</label>
                </div>
                <div class="col-md-7 col-sm-5">
                    <input type="text" class="form-control" id="lastName" name="lastName" placeholder="Enter Last Name">
                    <small id="lnameError" class="text-danger"><?php echo $lnameError; ?></small>
                </div>
            </div>
        </div>
        <!-- Email -->
        <div class="form-group">
            <div class="row mb-3">
                <div class="col-md-3 col-sm-2">
                    <label for="email">Email:</label>
                </div>
                <div class="col-md-7 col-sm-5">
                    <input type="email" class="form-control" id="email" name="email" placeholder="Enter your email">
                    <small id="emailError" class="text-danger"><?php echo $emailError; ?></small>
                </div>
            </div>
        </div>
        <!-- Phone -->
        <div class="form-group">
            <div class="row mb-3">
                <div class="col-md-3 col-sm-2">
                    <label for="phone">
                        Phone:</label>
                </div>
                <div class="col-md-7 col-sm-5">
                    <input type="text" class="form-control" id="phone" name="phone"
                        placeholder="Enter your phone number">
                    <small id="phoneError" class="text-danger"><?php echo $phoneError; ?></small>
                </div>
            </div>
        </div>
                <button type="submit" class="btn btn-primary">Register</button>
                <a href="home.php" class="btn btn-secondary">Cancel</a>
    </form>
</div>

</div>

</div>
</div>
</div>

<script src="../bootstrap/js/bootstrap.js"></script>
</body>

</html>