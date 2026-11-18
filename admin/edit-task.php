<?php
session_start();
include '../config/db_connect.php';

// Only admin can access
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../auth/login.php");
    exit;
}

// Validate ID
$id = $_GET['id'] ?? null;
if (!$id || !is_numeric($id)) {
    die("Invalid Task ID.");
}

// Fetch task safely
$stmt = $conn->prepare("SELECT * FROM tasks WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    die("Task not found.");
}

$task = $result->fetch_assoc();

// Handle form submission
if (isset($_POST['update_task'])) {
    $title = trim($_POST['title']);
    $description = trim($_POST['description']);
    $deadline = $_POST['deadline'];
    $priority = $_POST['priority'];

    $stmt_update = $conn->prepare("UPDATE tasks SET title=?, description=?, deadline=?, priority=? WHERE id=?");
    $stmt_update->bind_param("ssssi", $title, $description, $deadline, $priority, $id);

    if ($stmt_update->execute()) {
        header("Location: view-task.php");
        exit;
    } else {
        $error = "Failed to update task.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Task</title>
    <link rel="stylesheet" href="../assets/css/edit-task.css">
</head>
<body>
    <header>
        <h1>Simple Task Management System | Admin</h1>
        <a href="view-task.php" class="back-btn">Back</a>
    </header>

    <div class="main-content">
        <div class="form-box">
            <h2>Edit Task</h2>

            <?php if (isset($error)): ?>
                <p class="error-msg"><?= $error ?></p>
            <?php endif; ?>

            <form method="POST">
                <label>Task Title</label>
                <input type="text" name="title" value="<?= htmlspecialchars($task['title']) ?>" required>

                <label>Description / Instructions</label>
                <textarea name="description" required><?= htmlspecialchars($task['description']) ?></textarea>

                <label>Deadline</label>
                <input type="date" name="deadline" value="<?= $task['deadline'] ?>" required>

                <label>Priority Level</label>
                <select name="priority" required>
                    <option value="High" <?= $task['priority'] == 'High' ? 'selected' : '' ?>>High Priority</option>
                    <option value="Medium" <?= $task['priority'] == 'Medium' ? 'selected' : '' ?>>Medium Priority</option>
                    <option value="Low" <?= $task['priority'] == 'Low' ? 'selected' : '' ?>>Low Priority</option>
                </select>

                <button type="submit" name="update_task" class="create-btn">Update Task</button>
            </form>
        </div>
    </div>

    <footer>
        <p>© 2025 Simple Task Management System. All Rights Reserved.</p>
    </footer>
</body>
</html>
