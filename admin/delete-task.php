<?php
session_start();
include '../config/db_connect.php';

if ($_SESSION['role'] !== 'admin') {
    header("Location: ../auth/login.php");
    exit;
}

$id = $_GET['id'];

$conn->query("DELETE FROM tasks WHERE id = $id");

header("Location: view-task.php");
exit;
?>
