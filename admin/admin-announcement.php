<?php
session_start();
include '../config/db_connect.php';

// Only admin can access
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../auth/login.php");
    exit;
}

// Handle form submission
if (isset($_POST['create_announcement'])) {
    $title       = trim($_POST['title']);
    $subject     = trim($_POST['subject']);
    $description = trim($_POST['description']);
    $admin_id    = $_SESSION['user_id'];

    if (!empty($title) && !empty($subject) && !empty($description)) {
        $stmt = $conn->prepare("INSERT INTO announcements (title, subject, description, created_by) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("sssi", $title, $subject, $description, $admin_id);

        if ($stmt->execute()) {
            $success = "Announcement created successfully!";
        } else {
            $error = "Something went wrong.";
        }

        $stmt->close();
    } else {
        $error = "All fields are required.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Announcement</title>
    <link rel="stylesheet" href="../assets/css/create.css">
</head>
<body>
    <header>
        <h1>Simple Task Management System | Admin</h1>
    </header>

    <div class="container">
        <!-- SIDEBAR -->
        <div class="side-nav">
            <img class="logo" src="../assets/images/Logo.png" alt="STMS Logo">
            <ul class="nav-links">
                <li><a href="admin-task.php">Create Task</a></li>
                <li><a class="active" href="admin-announcement.php">Create Announcement</a></li>
                <li><a href="view-task.php">View Tasks</a></li>
                <li><a href="view-announcement.php">View Announcements</a></li>
                <li><a href="view-users.php">View Users</a></li>
                <li><a href="view-submitted.php">View Submissions</a></li>
                <li><a href="../auth/logout.php">Logout</a></li>
            </ul>
        </div>

        <!-- MAIN CONTENT -->
        <main class="dashboard">
            <div class="announcement-container">
                <h2 class="announcement-title">Create New Announcement</h2>

                <?php if (isset($success)) : ?>
                    <p style="color:green; text-align:center;"><?php echo $success; ?></p>
                <?php endif; ?>

                <?php if (isset($error)) : ?>
                    <p style="color:red; text-align:center;"><?php echo $error; ?></p>
                <?php endif; ?>

                <form method="POST">
                    <label>Title</label>
                    <input type="text" name="title" placeholder="Enter announcement title" required>

                    <label>Subject</label>
                    <input type="text" name="subject" placeholder="Enter subject" required>

                    <label>Description</label>
                    <textarea name="description" placeholder="Enter detailed description" required></textarea>

                    <button type="submit" name="create_announcement" class="create-btn">Create Announcement</button>
                </form>
            </div>
        </main>
    </div>

    <footer>
        <p>© 2025 Simple Task Management System. All Rights Reserved.</p>
    </footer>
</body>
</html>
