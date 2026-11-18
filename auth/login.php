<?php
session_start();
include '../config/db_connect.php';

// Handle form submission
if (isset($_POST['login'])) {
    $email    = trim($_POST['email']);
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();

        if (password_verify($password, $user['password'])) {

            // Save session data
            $_SESSION['user_id']    = $user['id'];
            $_SESSION['first_name'] = $user['first_name'];
            $_SESSION['last_name']  = $user['last_name'];
            $_SESSION['email']      = $user['email'];
            $_SESSION['role']       = $user['role'];  // <-- IMPORTANT

            // Redirect based on role
            if ($user['role'] === 'admin') {
                header("Location: ../admin/admin-task.php");
            } else {
                header("Location: ../user/user-dashboard.php");
            }
            exit;

        } else {
            $error = "Incorrect password";
        }
    } else {
        $error = "Email not registered";
    }

    $stmt->close();
    $conn->close();
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Page</title>
    <link rel="stylesheet" href="../assets/css/auth.css">
</head>
<body>
    <img src="../assets/images/1764201814158.png" class="bg-img bg-blue" alt="Blue background">
    <img src="../assets/images/1764201845224.png" class="bg-img bg-green" alt="Green background">

    <div class="container">
        <div class="form-box">
            <form action="" method="POST">
                <img class="logo" src="../assets/images/Logo.png" alt="STMS Logo" width="100px" height="100px">
                <h2 class="login">LOGIN</h2>

                <?php
                if (isset($error)) {
                    echo "<p style='color:red; text-align:center;'>$error</p>";
                }
                ?>

                <input type="email" name="email" placeholder="Email" required>
                <input type="password" name="password" placeholder="Password" required>

                <button type="submit" name="login">Login</button>

                <p class="click">Don't have an account? 
                    <a href="sign-up.php">Sign Up here</a>
                </p>
            </form>
        </div>
    </div>
</body>
</html>
