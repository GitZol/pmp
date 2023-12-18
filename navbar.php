<nav class="navbar navbar-expand-lg bg-body-secondary">
    <div class="container-fluid">
        <a class="navbar-brand" href="index.php">Project Management Platform</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse justify-content-end" id="navbarSupportedContent">
            <ul class="navbar-nav mb-2 mb-lg-0">
                <?php
                $loggedIn = isset($_SESSION["UserID"]);
                if ($loggedIn) {
                    $hostname = "127.0.0.1";
                    $username = "root";
                    $password = "";
                    $db_name = "project_management_platform";
        
                    $mysqli = new mysqli($hostname, $username, $password, $db_name);
                    if ($mysqli->connect_error) {
                        die("Connection failed: " . $mysqli->connect_error);
                    }
        
                    $userID = $_SESSION["UserID"]; 
                    $query = "SELECT Username, PFPName FROM user WHERE UserID = ?";
                    $stmt = $mysqli->prepare($query);

                    $stmt->bind_param("i", $userID);

                    $stmt->execute();
                    $result = $stmt->get_result();

                    if (!$result) {
                        echo "Error: " . $mysqli->error;
                        exit();
                    }

                    $row = $result->fetch_assoc();

                    $mysqli->close();

                    echo '
                    <li class="nav-item">
                        <a class="nav-link" href="home.php">Dashboard</a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" role="button" data-bs-toggle="dropdown" data-bs-display="static" aria-expanded="false">
                            '. $row["Username"] .'
                            <img class="rounded-circle" height="25" width="25" alt="Avatar" loading="lazy" style="object-fit: cover;" src="img/pfp/' . $row["PFPName"] . '" />
                        </a>
                    <ul class="dropdown-menu dropdown-menu-lg-end" style="min-width:inherit;">
                        <li><a class="dropdown-item" href="account.php">Details</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item" href="logout.php">Logout</a></li>
                    </ul>
                    </li>
                    ';
                } else {
                    echo '
                    <li class="nav-item">
                        <a class="nav-link" href="index.php">Home</a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" role="button" data-bs-toggle="dropdown" data-bs-display="static" aria-expanded="false">Account</a>
                    <ul class="dropdown-menu dropdown-menu-lg-end" style="min-width:inherit;">
                        <li><a class="dropdown-item" href="login.php">Login</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item" href="register.php">Register</a></li>
                    </ul>
                    </li>
                    ';
                }
                ?>
            </ul>
        </div>
    </div>
</nav>
