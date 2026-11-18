<?php
session_start();
include '../config/db_connect.php';

// Only admin can delete
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../auth/login.php");
    exit;
}

// Validate ID
$id = $_GET['id'] ?? null;
if (!$id || !is_numeric($id)) {
    die("Invalid announcement ID.");
}

// Use prepared statement for safety
$stmt = $conn->prepare("DELETE FROM announcements WHERE id = ?");
$stmt->bind_param("i", $id);

if ($stmt->execute()) {
    header("Location: view-announcement.php");
    exit;
} else {
    die("Failed to delete announcement.");
}
?>
