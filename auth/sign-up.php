<?php
include '../config/db_connect.php';

if (isset($_POST['sign-up'])) {
    $first_name = trim($_POST['first_name']);
    $last_name  = trim($_POST['last_name']);
    $email      = trim($_POST['email']);
    $password   = $_POST['password'];

    // Password validation: 8+ characters, 1 uppercase, 1 special character
    $uppercase = preg_match('@[A-Z]@', $password);
    $special   = preg_match('@[^\w]@', $password);
    
    if (strlen($password) < 8 || !$uppercase || !$special) {
        echo "<script>alert('Password must be at least 8 characters with 1 uppercase and 1 special character'); window.history.back();</script>";
        exit;
    }

    // Hash the password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Check if email already exists
    $check = $conn->prepare("SELECT * FROM users WHERE email = ?");
    $check->bind_param("s", $email);
    $check->execute();
    $result = $check->get_result();

    if ($result->num_rows > 0) {
        echo "<script>alert('Email already registered'); window.history.back();</script>";
    } else {
        // Insert into database
        $stmt = $conn->prepare("INSERT INTO users (first_name, last_name, email, password) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $first_name, $last_name, $email, $hashed_password);

        if ($stmt->execute()) {
            echo "<script>alert('Sign up successful!'); window.location='login.php';</script>";
        } else {
            echo "<script>alert('Error: Please try again'); window.history.back();</script>";
        }
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
    <title>Sign Up Page</title>
    <link rel="stylesheet" href="../assets/css/auth.css">
</head>
<body>
    <img src="../assets/images/1764201814158.png" class="bg-img bg-blue" alt="Blue background">
    <img src="../assets/images/1764201845224.png" class="bg-img bg-green" alt="Green background">

    <div class="container">
        <div class="form-box">
            <form action="" method="POST">
                <img class="logo" src="../assets/images/Logo.png" alt="STMS Logo" width="100px" height="100px">

                <h2 class="signup">SIGN UP</h2>

                <input type="text" name="first_name" placeholder="First Name" required>
                <input type="text" name="last_name" placeholder="Last Name" required>
                <input type="email" name="email" placeholder="Email" required>
                <input type="password" name="password" placeholder="Password" required>

                <button type="submit" name="sign-up">Sign Up</button>

                <p class="click">Already have an account?
                    <a href="login.php">Login here</a>
                </p>
            </form>
        </div>
    </div>

</body>
</html>