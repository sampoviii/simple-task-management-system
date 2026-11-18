<?php
session_start();
include '../config/db_connect.php';

// Only admin can access
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../auth/login.php");
    exit;
}

// Fetch all announcements
$announcements = $conn->query("SELECT * FROM announcements ORDER BY created_at DESC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Announcements</title>
    <link rel="stylesheet" href="../assets/css/view-announcement.css">
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
                <li><a href="admin-announcement.php">Create Announcement</a></li>
                <li><a href="view-task.php">View Tasks</a></li>
                <li><a class="active" href="view-announcement.php">View Announcements</a></li>
                <li><a href="view-users.php">View Users</a></li>
                <li><a href="view-submitted.php">View Submissions</a></li>
                <li><a href="../auth/logout.php">Logout</a></li>
            </ul>
        </div>

        <!-- MAIN DASHBOARD CONTENT -->
        <main class="dashboard">
            <div class="announcement-container">
                <h2 class="announcement-title">Created Announcements</h2>

                <table class="announcement-table">
                    <thead>
                        <tr>
                            <th>Title</th>
                            <th>Subject</th>
                            <th>Description</th>
                            <th>Date Created</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($announcements->num_rows > 0): ?>
                            <?php while ($row = $announcements->fetch_assoc()): ?>
                                <tr>
                                    <td><?= htmlspecialchars($row['title']) ?></td>
                                    <td><?= htmlspecialchars($row['subject']) ?></td>
                                    <td><?= htmlspecialchars($row['description']) ?></td>
                                    <td><?= $row['created_at'] ?></td>
                                    <td>
                                        <a href="edit-announcement.php?id=<?= $row['id'] ?>">
                                            <button class="edit-btn">Edit</button>
                                        </a>
                                        <a href="delete-announcement.php?id=<?= $row['id'] ?>" onclick="return confirm('Delete this announcement?')">
                                            <button class="delete-btn">Delete</button>
                                        </a>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="5" style="text-align:center;">No announcements found.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </main>
    </div>

    <footer>
        <p>© 2025 Simple Task Management System. All Rights Reserved.</p>
    </footer>
</body>
</html>
