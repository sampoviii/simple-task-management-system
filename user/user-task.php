<?php
session_start();
include '../config/db_connect.php';

// Redirect if not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit;
}

$user_id = $_SESSION['user_id'];


// ---------------------------------------------
// FILE SUBMISSION HANDLING
// ---------------------------------------------
if (isset($_POST['submit_file'])) {
    $task_id = intval($_POST['task_id']);
    $file = $_FILES['submission_file'];

    if ($file['error'] === 0) {
        $filename = basename($file['name']);
        $target_dir = __DIR__ . '/../submissions/';

        if (!is_dir($target_dir)) {
            mkdir($target_dir, 0777, true);
        }

        $saved_filename = time() . "_" . $filename;
        $target_file = $target_dir . $saved_filename;

        if (move_uploaded_file($file['tmp_name'], $target_file)) {
            $insert_sql = "
                INSERT INTO submissions (task_id, user_id, submission_file, submitted_at, status)
                VALUES ($task_id, $user_id, 'submissions/$saved_filename', NOW(), 'submitted')
            ";
            $conn->query($insert_sql);

            $_SESSION['message'] = "Task submitted successfully!";
        } else {
            $_SESSION['message'] = "Failed to upload file.";
        }
    } else {
        $_SESSION['message'] = "No file selected or upload error.";
    }

    header("Location: user-task.php");
    exit;
}


// ---------------------------------------------
// FETCH ALL POSTED TASKS
// ---------------------------------------------
$sql = "SELECT * FROM tasks WHERE is_posted = 1 ORDER BY deadline ASC";
$result = $conn->query($sql);


// ---------------------------------------------
// COUNT TASKS NOT YET SUBMITTED
// ---------------------------------------------
$task_count_sql = "
    SELECT COUNT(*) AS total 
    FROM tasks 
    WHERE is_posted = 1 
    AND id NOT IN (
        SELECT task_id FROM submissions WHERE user_id = $user_id
    )
";
$task_count_res = $conn->query($task_count_sql);
$task_count = $task_count_res->fetch_assoc()['total'];


// ---------------------------------------------
// COUNT SUBMITTED TASKS (DONE)
// ---------------------------------------------
$submission_count_sql = "
    SELECT COUNT(*) AS total 
    FROM submissions 
    WHERE user_id = $user_id
";
$submission_count_res = $conn->query($submission_count_sql);
$submission_count = $submission_count_res->fetch_assoc()['total'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Tasks</title>
    <link rel="stylesheet" href="../assets/css/user-task.css">
</head>
<body>

<header>
    <h1>Simple Task Management System</h1>
</header>

<div class="container">

    <!-- SIDE NAVIGATION -->
    <div class="side-nav">
        <img class="logo" src="../assets/images/Logo.png" alt="STMS Logo">

        <ul class="nav-links">
            <li><a href="user-dashboard.php">Dashboard</a></li>
            <li><a class="active" href="user-task.php">Task</a></li>
            <li><a href="user-profile.php">Profile</a></li>
            <li><a href="about.php">About Us</a></li>
            <li><a href="../auth/logout.php">Log Out</a></li>
        </ul>
    </div>


    <!-- MAIN CONTENT -->
    <main class="task-wrapper">
        <div class="task-container">

            <h2 class="page-title">Your Tasks</h2>

            <?php
            if (isset($_SESSION['message'])) {
                echo "<p style='color: green; text-align:center;'>" . $_SESSION['message'] . "</p>";
                unset($_SESSION['message']);
            }
            ?>

            <h4 style="text-align:center; color:#3b5c9d; margin-bottom:10px;">
                Total Tasks: <?= $task_count ?> | Done: <?= $submission_count ?>
            </h4>

            <?php
            if ($result && $result->num_rows > 0) {

                while ($task = $result->fetch_assoc()) {

                    // Check if task already submitted
                    $sub_check_sql = "
                        SELECT * 
                        FROM submissions 
                        WHERE task_id = {$task['id']} 
                        AND user_id = $user_id
                    ";
                    $sub_check_res = $conn->query($sub_check_sql);
                    $already_done = $sub_check_res->num_rows > 0;

                    // Skip tasks already submitted
                    if ($already_done) {
                        continue;
                    }

                    $priority = strtolower(trim($task['priority']));
                    $priorityClass = ($priority === "high") ? "high" :
                                     (($priority === "medium") ? "medium" : "low");
            ?>

            <div class="task-box">
                <div class="priority-indicator <?= $priorityClass ?>"></div>

                <div class="task-content">
                    <h3 class="task-title"><?= htmlspecialchars($task['title']) ?></h3>
                    <p class="task-desc"><?= htmlspecialchars($task['description']) ?></p>
                    <p class="task-deadline">Deadline: <?= htmlspecialchars($task['deadline']) ?></p>

                    <!-- SUBMISSION FORM -->
                    <form method="POST" enctype="multipart/form-data" class="task-submit">
                        <input type="hidden" name="task_id" value="<?= $task['id'] ?>">
                        <input type="file" name="submission_file" class="file-input" required>
                        <button type="submit" name="submit_file" class="submit-btn">Submit</button>
                    </form>
                </div>
            </div>

            <?php
                } // end while
            } else {
                echo "<p style=text-align:center; style='color: black;'>No tasks posted yet.</p>";
            }
            ?>

        </div>
    </main>
</div>

<footer>
    <p>© 2025 Simple Task Management System. All Rights Reserved.</p>
</footer>

</body>
</html>
