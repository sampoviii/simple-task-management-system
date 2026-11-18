<?php
session_start();
include '../config/db_connect.php';

// Only admin can access
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../auth/login.php");
    exit;
}

// Get announcement ID from URL
$id = intval($_GET['id']);
$result = $conn->query("SELECT * FROM announcements WHERE id = $id");
$announcement = $result->fetch_assoc();

// Handle form submission
if (isset($_POST['update_announcement'])) {
    $title       = trim($_POST['title']);
    $subject     = trim($_POST['subject']);
    $description = trim($_POST['description']);

    $stmt = $conn->prepare("UPDATE announcements SET title = ?, subject = ?, description = ? WHERE id = ?");
    $stmt->bind_param("sssi", $title, $subject, $description, $id);
    $stmt->execute();
    $stmt->close();

    // Redirect back to view-announcement.php after update
    header("Location: view-announcement.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Announcement</title>
    <link rel="stylesheet" href="../assets/css/edit-announcement.css">
</head>
<body>
    <header>
        <h1>Simple Task Management System | Admin</h1>
        <a href="view-announcement.php" class="back-btn">Back</a>
    </header>

    <div class="main-content">
        <div class="form-box">
            <h2>Edit Announcement</h2>

            <form method="POST">
                <label>Title</label>
                <input type="text" name="title" value="<?= htmlspecialchars($announcement['title']) ?>" required>

                <label>Subject</label>
                <input type="text" name="subject" value="<?= htmlspecialchars($announcement['subject']) ?>" required>

                <label>Description</label>
                <textarea name="description" required><?= htmlspecialchars($announcement['description']) ?></textarea>

                <button type="submit" name="update_announcement" class="update-btn">Update Announcement</button>
            </form>
        </div>
    </div>

    <footer>
        <p>© 2025 Simple Task Management System. All Rights Reserved.</p>
    </footer>
</body>
</html>

