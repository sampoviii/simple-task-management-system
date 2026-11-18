<?php
session_start();
include '../config/db_connect.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../auth/login.php");
    exit;
}

$id = $_GET['id'];

// Example: Here you can set a `posted` flag if you want
// $conn->query("UPDATE announcements SET is_posted = 1 WHERE id = $id");

header("Location: view-announcement.php");
exit;
?>
