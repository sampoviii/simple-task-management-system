<?php
session_start();

// Redirect if user not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About Us</title>
    <link rel="stylesheet" href="../assets/css/about.css">
</head>
<body>

    <!-- HEADER -->
    <header>
        <h1>Simple Task Management System</h1>
    </header>

    <div class="container">
        <div class="side-nav">
            <img class="logo" src="../assets/images/Logo.png" alt="STMS Logo">
            <ul class="nav-links">
                <li><a href="user-dashboard.php">Dashboard</a></li>
                <li><a href="user-task.php">Task</a></li>
                <li><a href="user-profile.php">Profile</a></li>
                <li><a class="active" href="about.php">About Us</a></li>
                <li><a href="../auth/logout.php">Log Out</a></li>
            </ul>
        </div>

        <!-- MAIN CONTENT -->
        <div class="about-wrapper">

            <!-- SYSTEM DESCRIPTION -->
            <section class="system-info">
                <h2>Simple Task Management System (STMS)</h2>
                <p class="tagline">"Organize, Track, and Complete Your Tasks Efficiently"</p>
                <p class="description">
                    Our Simple Task Management System (STMS) is designed to help students, teachers, and administrators
                    manage tasks, track progress, and ensure productivity. With a user-friendly interface and real-time updates,
                    staying organized has never been easier.
                </p>
            </section>

            <!-- ADMIN TEAM -->
            <section class="admin-team">
                <h2>Meet Our Team</h2>
                <div class="team-grid">

                    <!-- Admin 1 -->
                    <div class="team-member">
                        <img src="../assets/images/Barba.jpg" alt="Barba, Angela">
                        <h3>Barba, Angela</h3>
                        <p>Role: Frontend Developer</p>
                        <p>Description: Angela focuses on creating intuitive and responsive UI for the system, ensuring a seamless user experience.</p>
                    </div>

                    <!-- Admin 2 -->
                    <div class="team-member">
                        <img src="../assets/images/Carreon.jpg" alt="Carreon, Prince July">
                        <h3>Carreon, Prince July</h3>
                        <p>Role: Frontend Developer</p>
                        <p>Description: Prince July designs the visual components and interactive features, keeping the interface user-friendly and modern.</p>
                    </div>

                    <!-- Admin 3 -->
                    <div class="team-member">
                        <img src="../assets/images/Lanting.jpg" alt="Lanting, Kristine">
                        <h3>Lanting, Kristine</h3>
                        <p>Role: Frontend Developer</p>
                        <p>Description: Kristine develops and maintains front-end functionalities, ensuring responsive design across devices.</p>
                    </div>

                    <!-- Admin 4 -->
                    <div class="team-member">
                        <img src="../assets/images/Maunahan.jpg" alt="Maunahan, Keith Justine">
                        <h3>Maunahan, Keith Justine</h3>
                        <p>Role: Full Stack Developer</p>
                        <p>Description: Keith handles both backend and frontend development, integrating server-side logic with smooth, interactive user interfaces.</p>
                    </div>

                </div>
            </section>
        </div>
    </div>
    
<footer>
    <p>© 2025 Simple Task Management System. All Rights Reserved.</p>
</footer>

</body>
</html>
