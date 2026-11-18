<?php
session_start();
include '../config/db_connect.php';

// Redirect if not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

// Handle form submission
if (isset($_POST['update_profile'])) {

    $first_name = trim($_POST['first_name']);
    $last_name  = trim($_POST['last_name']);
    $email      = trim($_POST['email']);

    // Optional: change password
    if (!empty($_POST['password'])) {
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
        $sql = "UPDATE users SET first_name=?, last_name=?, email=?, password=? WHERE id=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssssi", $first_name, $last_name, $email, $password, $user_id);
    } else {
        $sql = "UPDATE users SET first_name=?, last_name=?, email=? WHERE id=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssi", $first_name, $last_name, $email, $user_id);
    }

    if ($stmt->execute()) {
        $_SESSION['message'] = "Profile updated successfully!";
    } else {
        $_SESSION['message'] = "Error updating profile.";
    }

    // Handle profile picture upload
    if (isset($_FILES['profile_pic']) && $_FILES['profile_pic']['error'] == 0) {
        $ext = pathinfo($_FILES['profile_pic']['name'], PATHINFO_EXTENSION);
        $target_dir = "uploads/";
        if (!is_dir($target_dir)) mkdir($target_dir, 0777, true);
        $target_file = $target_dir . "user_" . $user_id . "." . $ext;
        move_uploaded_file($_FILES['profile_pic']['tmp_name'], $target_file);
    }

    header("Location: user-profile.php");
    exit;
}

// Fetch current user info
$sql = "SELECT * FROM users WHERE id=?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>User Profile</title>
<link rel="stylesheet" href="../assets/css/user-profile.css">
</head>
<body>

<header>
    <h1>Simple Task Management System</h1>
</header>

<div class="container">
    <div class="side-nav">
        <img class="logo" src="../assets/images/Logo.png" alt="STMS Logo">
        <ul class="nav-links">
            <li><a href="user-dashboard.php">Dashboard</a></li>
            <li><a href="user-task.php">Task</a></li>
            <li><a class="active" href="user-profile.php">Profile</a></li>
            <li><a href="about.php">About Us</a></li>
            <li><a href="../auth/logout.php">Log Out</a></li>
        </ul>
    </div>

    <main class="profile-wrapper">
        <div class="profile-container">
            <h2>Your Profile</h2>

            <?php if (isset($_SESSION['message'])) {
                echo "<p style='color: green; text-align:center;'>" . $_SESSION['message'] . "</p>";
                unset($_SESSION['message']);
            } ?>

            <form method="POST">
                <label>First Name</label>
                <input type="text" name="first_name" value="<?= htmlspecialchars($user['first_name']) ?>" required>

                <label>Last Name</label>
                <input type="text" name="last_name" value="<?= htmlspecialchars($user['last_name']) ?>" required>

                <label>Email</label>
                <input type="email" name="email" value="<?= htmlspecialchars($user['email']) ?>" required>

                <label>Password <small>(Leave blank to keep current)</small></label>
                <input type="password" name="password">

                <button type="submit" name="update_profile">Update Profile</button>
            </form>
        </div>
    </main>
</div>

<footer>
    <p>© 2025 Simple Task Management System. All Rights Reserved.</p>
</footer>

</body>
</html>
