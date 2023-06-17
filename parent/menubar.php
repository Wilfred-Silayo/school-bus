<div class="col-auto col-md-3 col-xl-2 px-0" style="background-color: rgb(18, 24, 24); min-height:90vh;">
    <div class="d-flex flex-column pt-2 text-white" style="height:100%;">
        <ul class="nav nav-pills nav-sidebar flex-column" id="menu">
            <li class="nav-item">
                <a href="home.php" class="nav-link d-block align-middle ps-2 pe-4">
                    <i class="fa-solid text-white fa-tachometer-alt"></i>
                    <span class="ms-1 d-none d-sm-inline text-white">Dashboard</span>
                </a>
            </li>
            <li class="nav-item bg-dark">
                <a href="location.php" class="nav-link d-block align-middle ps-2">
                    <i class="fa-solid text-white fa-location"></i>
                    <span class="ms-1 d-none d-sm-inline text-white">Locations</span>
                </a>
            </li>
            <li class="nav-item bg-dark">
                <a href="messages.php" class="nav-link d-block align-middle ps-2">
                    <i class="fa-solid text-white fa-message"></i>
                    <span class="ms-1 d-none d-sm-inline text-white" id="message-count">Messages</span>
                </a>
            </li>
            <li class="nav-item bg-dark">
                <a href="password.php" class="nav-link ps-2 align-middle">
                    <i class="fa-solid text-white fa-lock"></i>
                    <span class="ms-1 d-none d-sm-inline text-white">Password</span>
                </a>
            </li>
            <li class="nav-item bg-dark">
                <a href="logout.php" class="nav-link ps-2 align-middle">
                    <i class="fa-solid text-white fa-sign-out"></i>
                    <span class="ms-1 d-none d-sm-inline text-white">Logout</span>
                </a>
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
