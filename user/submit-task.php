<?php
session_start();
include '../config/db_connect.php';

// Redirect if not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['task_id'])) {

    $task_id = intval($_POST['task_id']);
    $user_id = $_SESSION['user_id'];

    if (isset($_FILES['submission_file']) && $_FILES['submission_file']['error'] === 0) {

        $uploadDir = "uploads/";
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        $fileName = time() . "_" . basename($_FILES['submission_file']['name']);
        $targetFile = $uploadDir . $fileName;

        if (move_uploaded_file($_FILES['submission_file']['tmp_name'], $targetFile)) {
            
            // Insert submission record
            $stmt = $conn->prepare("INSERT INTO submissions (task_id, user_id, file_path) VALUES (?, ?, ?)");
            $stmt->bind_param("iis", $task_id, $user_id, $targetFile);
            $stmt->execute();
            $stmt->close();

            $_SESSION['message'] = "Task submitted successfully!";
        } else {
            $_SESSION['message'] = "Failed to upload file.";
        }
    } else {
        $_SESSION['message'] = "No file selected or file upload error.";
    }

    header("Location: user-task.php");
    exit;
}
?>
