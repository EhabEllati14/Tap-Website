<?php
require_once("dbconfig.php");
session_start();
if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $errs = [];
    if (!isset($_POST['usernameLogin']) || empty($_POST['usernameLogin'])) {
        $errs['usernameerr'] = "Please Enter Your Username !";
    } 
    elseif(strlen($_POST['usernameLogin']) <6 || strlen($_POST['usernameLogin']) > 13){
      $errs['usernameerr']="Please Your Username Must Be Between 6 and 13 characters.";
  }
    else {
        $usernamelogin = $_POST['usernameLogin'];
        $query = "SELECT * FROM users WHERE Username = :username";
        $stmt = $pdo->prepare($query);
        $stmt->bindValue(":username", $usernamelogin);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($user) {
            if (!isset($_POST['passwordLogin']) || empty($_POST['passwordLogin'])) {
                $errs['passworderr'] = "Please Enter Your Password";
            } 
            elseif(strlen($_POST['passwordLogin']) <8 || strlen($_POST['passwordLogin']) > 12){
              $errs['passworderr']="Please Your Password Must Be Between  8 and 12 character";
              }
            else {
                $passwordlogin = $_POST['passwordLogin'];
                if ($passwordlogin === $user['Password']) {
                    $_SESSION['usernamelog'] = $user['Username'];
                    $_SESSION['userid'] = $user['UserID'];
                    $_SESSION['role'] = $user['Role']; 
                    $_SESSION['logged']=true;
                } else {
                    $errs['passworderr'] = "Incorrect Password";
                }
            }
        } else {
            $errs['usernameerr'] = "This Username does not exist!";
        }
    }
    if (!empty($errs)) {
        $_SESSION['error'] = $errs;
        header("Location: login.php");
        exit();
    } else {
        unset($_SESSION['error']);
        header("Location: dashboard.php");
        exit();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="CSS/logins.css">
    <title>Login</title>
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
    <section id="login_form">
        <h1>LOGIN</h1>
        <form method="post" action="login.php">
            <label id="usernamelab" for="username">Username:</label>
            <input type="text" id="username" name="usernameLogin" required 
                   value="<?php if(isset($_SESSION['usernamelog'])) { echo $_SESSION['usernamelog']; } ?>" /> 
            <span><?php if(isset($_SESSION['error']['usernameerr'])) { echo $_SESSION['error']['usernameerr']; } ?></span>

            <label id="passwordlab" for="password">Password:</label>
            <input type="password" id="password" name="passwordLogin" required />
            <span><?php if(isset($_SESSION['error']['passworderr'])) { echo $_SESSION['error']['passworderr']; } ?></span>

            <input type="submit" name="logins" value="LogIn" />
        </form>
    </section>
    <footer>
        <p>
            <strong>Contact Us:</strong> 
            <a href="mailto:taptaskmangement@gmail.com">tap_support@gmail.com</a> | 
            <a href="tel:+0569744223">+0569744223</a>
        </p>
        <div>
            <a href="contactus.php" >About Us</a>
            <a href="https://policies.google.com/privacy?hl=en-US" target="_blank">Privacy Policy</a>
        </div>
        <p>&copy; 2025 TAP Task Management. All rights reserved.</p>
    </footer>
</body>
</html>