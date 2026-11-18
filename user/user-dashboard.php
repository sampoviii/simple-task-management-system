<?php
session_start();
include '../config/db_connect.php';

// Redirect if not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

// GET ALL ANNOUNCEMENTS
$announcements = $conn->query("
    SELECT * FROM announcements ORDER BY id DESC
");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Dashboard</title>
    <link rel="stylesheet" href="../assets/css/user-dashboard.css">
</head>
<body>

<header>
    <h1>Simple Task Management System</h1>
</header>

<div class="container">

    <div class="side-nav">
        <img class="logo" src="../assets/images/Logo.png" alt="STMS Logo">

        <ul class="nav-links">
            <li><a class="active" href="user-dashboard.php">Dashboard</a></li>
            <li><a href="user-task.php">Task</a></li>
            <li><a href="user-profile.php">Profile</a></li>
            <li><a href="about.php">About Us</a></li>
            <li><a href="../auth/logout.php">Log Out</a></li>
        </ul>
    </div>

    <main class="dashboard">

        <div class="announcement-container">
            <div class="announcement-title">Announcements</div>

            <?php 
            if ($announcements->num_rows > 0) {
                while ($row = $announcements->fetch_assoc()) { ?>
                    
                    <div class="announcement-box">
                        <h3><?php echo htmlspecialchars($row['title']); ?></h3>
                        <p><strong><?php echo htmlspecialchars($row['subject']); ?></strong></p>
                        <p><?php echo nl2br(htmlspecialchars($row['description'])); ?></p>
                    </div>

            <?php }} else { ?>

                <p style="text-align:center;">No announcements at the moment.</p>

            <?php } ?>
        </div>

    </main>

</div>

<footer>
    <p>© 2025 Simple Task Management System. All Rights Reserved.</p>
</footer>

</body>
</html>
