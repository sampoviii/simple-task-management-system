<?php
session_start();
include '../config/db_connect.php';

// Only admin can access
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../auth/login.php");
    exit;
}

// Handle form submission
if (isset($_POST['create_task'])) {
    $title       = trim($_POST['title']);
    $description = trim($_POST['description']);
    $deadline    = $_POST['deadline'];
    $priority    = $_POST['priority'];
    $admin_id    = $_SESSION['user_id'];

    if (!empty($title) && !empty($description) && !empty($deadline)) {
        $stmt = $conn->prepare("INSERT INTO tasks (title, description, deadline, priority, created_by) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssi", $title, $description, $deadline, $priority, $admin_id);

        if ($stmt->execute()) {
            $success = "Task created successfully!";
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
    <title>Create Task</title>
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
                <li><a class="active" href="admin-task.php">Create Task</a></li>
                <li><a href="admin-announcement.php">Create Announcement</a></li>
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
                <h2 class="announcement-title">Create New Task</h2>

                <?php if (isset($success)) : ?>
                    <p style="color:green; text-align:center;"><?php echo $success; ?></p>
                <?php endif; ?>

                <?php if (isset($error)) : ?>
                    <p style="color:red; text-align:center;"><?php echo $error; ?></p>
                <?php endif; ?>

                <form method="POST">
                    <label>Task Title</label>
                    <input type="text" name="title" placeholder="Enter task title" required>

                    <label>Description / Instructions</label>
                    <textarea name="description" placeholder="Enter detailed instructions" required></textarea>

                    <label>Deadline</label>
                    <input type="date" name="deadline" required>

                    <label>Priority Level</label>
                    <select name="priority" required>
                        <option value="High">High Priority</option>
                        <option value="Medium">Medium Priority</option>
                        <option value="Low">Low Priority</option>
                    </select>

                    <button type="submit" name="create_task" class="create-btn">Create Task</button>
                </form>
            </div>
        </main>
    </div>

    <footer>
        <p>© 2025 Simple Task Management System. All Rights Reserved.</p>
    </footer>
</body>
</html>
