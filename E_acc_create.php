<?php
require_once("dbconfig.php");
session_start();

if($_SERVER['REQUEST_METHOD']=="POST"){
  $errs=[];

  if (!isset($_POST['username']) || empty($_POST['username'])) {
    $errs['username'] = "Please Enter Your Username";
} else {
    $usern = trim($_POST['username']);
    $query = "SELECT * FROM users WHERE username = :username;";
    $stmt = $pdo->prepare($query);
    $stmt->bindValue(":username", $usern);
    $stmt->execute();
    if ($stmt->rowCount() > 0) {
        $errs['username'] = "This Username Already Exists!";
    }
}


if (!isset($errs['username'])) { 
    if (strlen($usern) < 6 || strlen($usern) > 13) {
        $errs['username'] = "Your Username Must Be Between 6 and 13 Characters";
    } elseif (!preg_match('/^[a-zA-Z0-9]{6,13}$/', $usern)) {
        $errs['username'] = "Username Must Be Alphanumeric Only!";
    } else {
        $_SESSION['username'] = $usern;
    }
}

  if(!isset($_POST['password']) || empty($_POST['password'])){
    $errs['password']="Please Enter Your Password";
  }elseif(strlen($_POST['password']) <8 || strlen($_POST['password']) > 12){
    $errs['password']="Please Your Password Must Be Between  8 and 12 character";
    }
elseif(!preg_match('/^(?=.*[a-zA-Z])(?=.*[0-9])[a-zA-Z0-9]{8,12}$/', $_POST['password'])){
  $errs['password']="Please Your Password Must include letter and number ate least ONE!";
  }
  else{
    $password=$_POST['password'];
     $_SESSION['password']=$password;
  }

  if(!isset($_POST['confirmpassword']) || empty($_POST['confirmpassword'])){
    $errs['confirmpassword']="Please Enter Your Confirm Password";
  }
  elseif ($_POST['password'] !== $_POST['confirmpassword']){
    $errs['confirmpassword']="Please Confirm Password must match the Password !";
  }
  else{
    $confirmpass=$_POST['confirmpassword'];
    $_SESSION['confirmpassword']=$confirmpass;
  }

  if(!empty($errs)){
    $_SESSION['error']=$errs;
    header("Location: E_acc_create.php");
    exit();
  }
  else{
    unset($_SESSION['error']);
    header("Location: Confirmation.php");
    exit();
  }
}
?>
<!DOCTYPE html>
<html>
  <head>
    <meta charset="UTF-8">
    <title>E-Account Creation</title>
    <link rel="stylesheet" href="CSS/e_com.css">
  </head>
  <body>
  <header id="task_header">
      <div id='left_header'>
      <a href='dashboard.php'><img src='TAP_LOGO.png' alt='logo_image'/></a><h1> TAP FOR TASK MANAGEMENT</h1>
        </div>
        <h2>E-Account Creation</h2>
        <div id="right_header_information">
      <a href="login.php">Login</a><span class="betweens">|</span>
      <a href="logout.php">Logout</a><span class="betweens">|</span>
      <a href="signUp.php">SignUp</a>
      </div>
    </header>
    <main>
      <fieldset>
        <legend>E-Account Information</legend>
        <form action="E_acc_create.php" method="post">
          <div id="usernameField">
            <label for="username">Username: </label>
            <input minlength="6" maxlength="13"
              type="text" id="username"
              name="username" required pattern="^[a-zA-Z0-9]{6,13}$" 
              title="Please username must be between 6 - 13 characters (alphanumeric)"
              value="<?php if(isset($_SESSION['username'])){echo $_SESSION['username'];}?>" />
              <span class="error"><?php
                    if(isset($_SESSION['error']['username'])){
                    echo $_SESSION['error']['username'];
                    unset($_SESSION['error']['username']);}?></span><br><br>
          </div>
          <div id="passwordField">
            <label for="password">Password: </label>
            <input type="password" id="password"name="password"
            minlength="8" maxlength="12"
              required  pattern="^(?=.*[a-zA-Z])(?=.*[0-9])[a-zA-Z0-9]{8,12}$"
              title="Please The Password must be between 8- 12 character and at least one letter/numbner !"
              />
              <span class="error"><?php
                    if(isset($_SESSION['error']['password'])){
                    echo $_SESSION['error']['password'];
                    unset($_SESSION['error']['password']);
                    }
                    ?></span><br><br>
              
          </div>
          <div id="confirmpasswordField">
            <label for="confpassword">Confirm Password: </label>
            <input
              type="password"
              id="confpassword"
              name="confirmpassword"
              required 
            />
            <span class="error"><?php
                    if(isset($_SESSION['error']['confirmpassword'])){
                    echo $_SESSION['error']['confirmpassword'];
                    unset($_SESSION['error']['confirmpassword']);
                    }
                    ?></span><br><br>
          </div>
          <div id="submitField">
            <button type="submit">Proceed to Confirmation</button>
          </div>
        </form>
      </fieldset>
    </main>
  </body>
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
</html>
