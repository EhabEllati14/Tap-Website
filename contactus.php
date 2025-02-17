<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="CSS/contactus.css">
    <title>Contact Us - TAP Task Management</title>
</head>
<body>
<header id="task_header">
    <div id="left_header">
        <a href='dashboard.php'><img src='TAP_LOGO.png' alt='logo_image'/></a>
        <h1>TAP FOR TASK MANAGEMENT</h1>
    </div>
    <div id="right_header_information">
        <a href="login.php">Login</a><span class="betweens">|</span>
        <a href="logout.php">Logout</a><span class="betweens">|</span>
        <a href="signUp.php">SignUp</a>
    </div>
</header>
<main id="contact_main">
    <section id="about_tap">
        <h1>About TAP Task Management</h1>
        <p>
            Welcome to TAP Task Management, your reliable solution for streamlining task allocation and project 
            management processes. Our system is designed to enhance productivity and ensure seamless collaboration 
            within your team.
        </p>
        <p>
            TAP empowers managers to efficiently assign tasks, track progress, and manage deadlines while enabling 
            team members to stay organized and focused on their responsibilities. From small teams to large-scale 
            projects, TAP adapts to your needs.
        </p>
        <p>
            Join us in building a culture of efficiency, accountability, and success through smart task management.
        </p>
    </section>
    <section id="contact_details">
        <h1>Contact Information</h1>
        <div class="contact-item">
            <strong>Location:</strong> Altireh, Nelson Mandela Building, 8th Floor
        </div>
        <div class="contact-item">
            <strong>Telephone:</strong> <a href="tel:+0569744223">+0569744223</a>
        </div>
        <div class="contact-item">
            <strong>Email:</strong> <a href="mailto:taptaskmanagement@gmail.com">taptaskmanagement@gmail.com</a>
        </div>
    </section>
</main>
<footer>
    <p>
        <strong>Contact Us:</strong> 
        <a href="mailto:taptaskmanagement@gmail.com">taptaskmanagement@gmail.com</a> | 
        <a href="tel:+0569744223">+0569744223</a>
    </p>
    <div>
        <a href="contactus.php">About Us</a>
        <a href="https://policies.google.com/privacy?hl=en-US" target="_blank">Privacy Policy</a>
    </div>
    <p>&copy; 2025 TAP Task Management. All rights reserved.</p>
</footer>
</body>
</html>
