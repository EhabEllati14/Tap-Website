<?php
require_once("dbconfig.php");
session_start();
if(!isset($_SESSION['Unique_UserID'])){
header("Location: register.php");
exit();
}
?>
<!DOCTYPE html>
<html>
  <head>
<meta charset="UTF-8">
<link rel='stylesheet' href='CSS/confirmsucess.css'>
  </head>
  <body>
<header id="task_header">
      <div id='left_header'>
        <img src='TAP_LOGO.png' alt='logo_image'/><h1> TAP FOR TASK MANAGEMENT</h1>
        </div>
        <h2>Confirmation Message</h2>
        <div id="right_header_information">
      <a href="login.php">Login</a><span class="betweens">|</span>
      <a href="logout.php">Logout</a><span class="betweens">|</span>
      <a href="signUp.php">SignUp</a>
      </div>
</header>
<section>
        <h2>Thank You for Your Registration!</h2>
        <span>You Have Been Successfully Registered!</span> <img src='verify.png'alt='register icon'/>
        <p><strong>Your UserID:</strong> <?php $_SESSION['Unique_UserID']; ?></p>
        <span id="note">Please do not share it with anyone!</span><br>
        <a href="login.php" class="button-link">Login</a>
    </section>
    <footer>
        <p>
            <strong>Contact Us:</strong> 
            <a href="mailto:taptaskmangement@gmail.com">tap_support@gmail.com</a> | 
            <a href="tel:+0569744223">+0569744223</a>
        </p>
    </div>
    <div>
        <a href="about.html" >About Us</a>
        <a href="privacy-policy.html">Privacy Policy</a>
        <a href="terms-of-service.html">Terms of Service</a>
    </div>
    <div>
        <p>&copy; 2025 TAP Task Management. All rights reserved.</p>
    </div>
</footer>
  </body>
</html>