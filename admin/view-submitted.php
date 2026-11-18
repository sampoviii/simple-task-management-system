<?php
session_start();
include '../config/db_connect.php';

// Only admin can access
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../auth/login.php");
    exit;
}

// Fetch all tasks
$task_sql = "SELECT * FROM tasks ORDER BY created_at DESC";
$task_result = $conn->query($task_sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Users Who Submitted Tasks</title>
    <link rel="stylesheet" href="../assets/css/view-submitted.css">
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
                <li><a href="view-announcement.php">View Announcements</a></li>
                <li><a href="view-users.php">View Users</a></li>
                <li><a class="active" href="view-submitted.php">View Submissions</a></li>
                <li><a href="../auth/logout.php">Logout</a></li>
            </ul>
        </div>

        <!-- MAIN DASHBOARD CONTENT -->
        <main class="dashboard">
            <div class="announcement-container">
                <h2 class="announcement-title">Users Who Submitted Tasks</h2>

                <?php if ($task_result && $task_result->num_rows > 0): ?>
                    <?php while ($task = $task_result->fetch_assoc()): ?>
                        <div class="task-box">
                            <h3>
                                <?= htmlspecialchars($task['title']) ?> 
                                (Deadline: <?= htmlspecialchars($task['deadline']) ?>)
                            </h3>
                            <p><?= htmlspecialchars($task['description']) ?></p>
                            <p>Priority: <?= htmlspecialchars($task['priority']) ?></p>

                            <?php
                            // Get all users who submitted this task
                            $sub_sql = "SELECT s.*, u.first_name, u.last_name 
                                        FROM submissions s
                                        JOIN users u ON s.user_id = u.id
                                        WHERE s.task_id = {$task['id']}";
                            $sub_result = $conn->query($sub_sql);
                            ?>

                            <?php if ($sub_result && $sub_result->num_rows > 0): ?>
                                <table class="submission-table">
                                    <thead>
                                        <tr>
                                            <th>User Name</th>
                                            <th>Submitted File</th>
                                            <th>Submitted At</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php while ($sub = $sub_result->fetch_assoc()): ?>
                                            <tr>
                                                <td><?= htmlspecialchars($sub['first_name'] . ' ' . $sub['last_name']) ?></td>
                                                <td>
                                                    <?php if (!empty($sub['submission_file'])): ?>
                                                        <?php
                                                        $submissionUrl = htmlspecialchars($sub['submission_file']);
                                                        if (strpos($submissionUrl, 'submissions/') === 0) {
                                                            $submissionUrl = '../' . $submissionUrl;
                                                        }
                                                        ?>
                                                        <a href="<?= $submissionUrl ?>" target="_blank">View File</a>
                                                    <?php else: ?>
                                                        No file
                                                    <?php endif; ?>
                                                </td>
                                                <td><?= htmlspecialchars($sub['submitted_at']) ?></td>
                                            </tr>
                                        <?php endwhile; ?>
                                    </tbody>
                                </table>
                            <?php else: ?>
                                <p style="color: gray;">No submissions for this task yet.</p>
                            <?php endif; ?>
                        </div>
                    <?php endwhile; ?>
                <?php else: ?>
                    <p>No tasks found.</p>
                <?php endif; ?>
            </div>
        </main>
    </div>

    <footer>
        <p>© 2025 Simple Task Management System. All Rights Reserved.</p>
    </footer>
</body>
</html>
