<div class="col-auto col-md-3 col-xl-2  px-0 " style="background-color: rgb(18, 24, 24); min-height:90vh;">
    <div class="d-flex flex-column pt-2 text-white" style="min-height:100%;">
        <ul class="nav nav-pills nav-sidebar flex-column" id="menu">
            <li class="nav-item ">
                <a href="home.php" class="nav-link align-middle ps-2 pe-4 ">
                    <i class="fa-solid text-white fa-tachometer-alt"></i>
                    <span class="ms-3 d-none d-sm-inline text-white">Dashboard</span>
                </a>
            </li>

            <li class="nav-item bg-dark">
                <a class="nav-link align-middle ps-2" data-bs-toggle="collapse" href="#collapse2" role="button"
                    aria-expanded="false" aria-controls="collapse2">
                    <i class="fa-solid text-white fa-user"></i>
                    <span class="ms-3 d-none d-sm-inline text-white">Parents</span>
                </a>
            </li>
            <div class="collapse" id="collapse2">
                <ul class="nav flex-column">
                    <li class="nav-item ms-4 "><a class="nav-link text-white" href="add_parent.php"> <i
                                class="fa-solid fa-chevron-right"></i> Add Parents</a></li>
                    <li class="nav-item ms-4"><a class="nav-link text-white" href="manage_parent.php"> <i
                                class="fa-solid fa-chevron-right"></i> Manage Parents</a></li>
                </ul>
            </div>
            
            <li class="nav-item bg-dark">
                <a class="nav-link align-middle ps-2" data-bs-toggle="collapse" href="#collapse1" role="button"
                    aria-expanded="false" aria-controls="collapse1">
                    <i class="fa-solid text-white fa-user"></i>
                    <span class="ms-3 d-none d-sm-inline text-white">Students</span>
                </a>
            </li>
            <div class="collapse" id="collapse1">
                <ul class="nav flex-column">
                    <!-- <li class="nav-item ms-4 "><a class="nav-link text-white" href=""> <i
                                class="fa-solid fa-chevron-right"></i> Add student</a></li> -->
                    <li class="nav-item ms-4"><a class="nav-link text-white" href="manage_student.php"> <i
                                class="fa-solid fa-chevron-right"></i> Manage Students</a></li>
                </ul>
            </div>

            <li class="nav-item bg-dark">
                <a class="nav-link align-middle ps-2" data-bs-toggle="collapse" href="#collapse3" role="button"
                    aria-expanded="false" aria-controls="collapse3">
                    <i class="fa-solid text-white fa-user"></i>
                    <span class="ms-3 d-none d-sm-inline text-white">Drivers</span>
                </a>
            </li>
            <div class="collapse" id="collapse3">
                <ul class="nav flex-column">
                    <li class="nav-item ms-4 "><a class="nav-link text-white" href="add_driver.php"> <i
                                class="fa-solid fa-chevron-right"></i> Add Driver</a></li>
                    <li class="nav-item ms-4"><a class="nav-link text-white" href="manage_driver.php"> <i
                                class="fa-solid fa-chevron-right"></i> Manage Drivers</a></li>
                </ul>
            </div>

            <li class="nav-item bg-dark">
                <a class="nav-link align-middle ps-2" data-bs-toggle="collapse" href="#collapse4" role="button"
                    aria-expanded="false" aria-controls="collapse4">
                    <i class="fa-solid text-white fa-bus"></i>
                    <span class="ms-3 d-none d-sm-inline text-white">Buses</span>
                </a>
            </li>
            <div class="collapse" id="collapse4">
                <ul class="nav flex-column">
                    <li class="nav-item ms-4 "><a class="nav-link text-white" href="add_bus.php"> <i
                                class="fa-solid fa-chevron-right"></i> Add Bus</a></li>
                    <li class="nav-item ms-4"><a class="nav-link text-white" href="manage_bus.php"> <i
                                class="fa-solid fa-chevron-right"></i> Manage Buses</a></li>
                </ul>
            </div>

            <li class="nav-item bg-dark">
                <a class="nav-link align-middle ps-2" data-bs-toggle="collapse" href="#collapse5" role="button"
                    aria-expanded="false" aria-controls="collapse5">
                    <i class="fa-solid text-white fa-route"></i>
                    <span class="ms-3 d-none d-sm-inline text-white">Routes</span>
                </a>
            </li>
            <div class="collapse" id="collapse5">
                <ul class="nav flex-column">
                    <li class="nav-item ms-4 "><a class="nav-link text-white" href="add_route.php"> <i
                                class="fa-solid fa-chevron-right"></i> Add Route</a></li>
                    <li class="nav-item ms-4"><a class="nav-link text-white" href="manage_route.php"> <i
                                class="fa-solid fa-chevron-right"></i> Manage Routes</a></li>
                </ul>
            </div>

            <li class="nav-item bg-dark">
                <a href="location.php" class="nav-link align-middle ps-2">
                    <i class="fa-solid text-white fa-location"></i>
                    <span class="ms-3 d-none d-sm-inline text-white">Locations</span>
                </a>
            </li>
            <li class="nav-item bg-dark">
                <a class="nav-link align-middle ps-2" data-bs-toggle="collapse" href="#collapse6" role="button"
                    aria-expanded="false" aria-controls="collapse3">
                    <i class="fa-solid text-white fa-user"></i>
                    <span class="ms-3 d-none d-sm-inline text-white" id="message-count">Messages
                    </span>
                </a>
            </li>
            <div class="collapse" id="collapse6">
                <ul class="nav flex-column">
                    <li class="nav-item ms-4 "><a class="nav-link text-white" href="message_driver.php"> <i
                                class="fa-solid fa-chevron-right"></i>Message Drivers</a></li>
                    <li class="nav-item ms-4"><a class="nav-link text-white" href="message_parent.php"> <i
                                class="fa-solid fa-chevron-right"></i> Message Parents</a></li>
                </ul>
            </div>


            <li class="nav-item bg-dark">
                <a href="password.php" class="nav-link ps-2 align-middle">
                    <i class="fa-solid text-white fa-lock"></i>
                    <span class="ms-3 d-none d-sm-inline text-white">Password</span> </a>
            </li>
            <li class="nav-item bg-dark">
                <a href="includes/logout.php" class="nav-link ps-2 align-middle">
                    <i class="fa-solid text-white fa-sign-out"></i>
                    <span class="ms-3 d-none d-sm-inline text-white">Logout</span> </a>
            </li>

        </ul>
    </div>
</div>
<script>
    function updateMessageCount() {
        fetch('get_message_count.php')
            .then(response => response.text())
            .then(data => {
                document.getElementById('message-count').innerHTML = "Messages " + data;
            })
            .catch(error => console.log(error));
    }

    setInterval(updateMessageCount, 5000);
    updateMessageCount();
</script>