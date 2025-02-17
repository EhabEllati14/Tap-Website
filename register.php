<?php
require_once("dbconfig.php");
session_start();
if($_SERVER['REQUEST_METHOD']=="POST"){
  $err=[];
  if(!isset($_POST['name'])|| empty($_POST['name'])){
    $err['name']="Please Enter Your Name";
  }
  else{
    $_SESSION['name']=$_POST['name'];
  }
  
  if(!isset($_POST['flatno']) && empty($_POST['flatno'])){
    $err['flatno']="Please Enter Your House Number";
  }
  elseif((int)$_POST['flatno'] < 0){
    $err['flatno']="Please House Number Must Be Positive Number !";
  }
  else{
  $_SESSION['flatno']=$_POST['flatno'];
  }

  if(!isset($_POST['street']) && empty($_POST['street'])){
    $err['street']="Please Enter Your Street";
  }
  else{
  $_SESSION['street']=$_POST['street'];
  }

  if(!isset($_POST['city']) && empty($_POST['city'])){
    $err['city']="Please Enter Your City";
  }
  else{
  $_SESSION['city']=$_POST['city'];
  }

  if(!isset($_POST['country']) && empty($_POST['country'])){
    $err['country']="Please Enter Your Country";
  }
  else{
  $_SESSION['country']=$_POST['country'];
  }

  if(!isset($_POST['birthdate']) && empty($_POST['birthdate'])){
    $err['birthdate']="Please Enter Your Date Of Birth";
  }
  else{
  $_SESSION['birthdate']=$_POST['birthdate'];
  }

  if (!isset($_POST['IdNumber']) || empty(trim($_POST['IdNumber']))) {
    $err['IdNumber'] = "Please Enter Your ID Number";
} elseif (!preg_match('/^\d{9}$/', trim($_POST['IdNumber']))) {
    $err['IdNumber'] = "ID Number must be exactly 9 digits.";
} else {
  $query="SELECT * FROM users WHERE IDNumber=:idnum;";
  $sts=$pdo->prepare($query);
  $sts->bindValue(":idnum",$_POST['IdNumber']);
  $sts->execute();
  $res=$sts->fetch(PDO::FETCH_ASSOC);
  if($res){
    $err['IdNumber'] = "This ID Number Is Already Exists !.";
  }
  else{
    $_SESSION['IdNumber'] = trim($_POST['IdNumber']);
  }
}


  if(!isset($_POST['email']) && empty($_POST['email'])){
    $err['email']="Please Enter Your Email";
  }
  else{
  $_SESSION['email']=$_POST['email'];
  }


  if(!isset($_POST['telephone']) || empty($_POST['telephone'])){
    $err['telephone'] = "Please Enter Your Telephone Number";
} elseif (!preg_match('/^\d{10}$/', $_POST['telephone'])) {
    $err['telephone'] = "Telephone Number Must Be Exactly 10 Digits";
} else {
    $_SESSION['telephone'] = $_POST['telephone'];
}

  if(!isset($_POST['role']) && empty($_POST['role'])){
    $err['role']="Please Enter Your Email";
  }
  else{
  $_SESSION['role']=$_POST['role'];
  }

  if(!isset($_POST['qualification']) && empty($_POST['qualification'])){
    $err['qualification']="Please Enter Your Qualifications";
  }
  else{
  $_SESSION['qualification']=$_POST['qualification'];
  }

  if(!isset($_POST['skills']) && empty($_POST['skills'])){
    $err['skills']="Please Enter Your Skills";
  }
  else{
  $_SESSION['skills']=$_POST['skills'];
  }

  if(!empty($err)){
    $_SESSION['errors'] = $err;
    header("Location: register.php"); 
    exit();
  }
  else{
    unset($_SESSION['errors']);
    header("Location: E_acc_create.php");
    exit();
  }

}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="CSS/register.css">
    <title>TAP Register Form</title>
</head>
<body>
    <header id="task_header">
      <div id='left_header'>
      <a href='dashboard.php'><img src='TAP_LOGO.png' alt='logo_image'/></a><h1> TAP FOR TASK MANAGEMENT</h1>
        </div>
        <h2> Registration Form </h2>
        <div id="right_header_information">
      <a href="login.php">Login</a><span class="betweens">|</span>
      <a href="logout.php">Logout</a><span class="betweens">|</span>
      <a href="signUp.php">SignUp</a>
      </div>
    </header>
    <main>
        <h2>Please fill out the form below to register:</h2>
        <form action="register.php" method="post"  enctype="multipart/form-data">
            <div class="formFill">
                <div id="nameField">
                    <label for="name">Name: </label>
                    <input type="text" name="name" id="name" placeholder="Enter Your Name" value="<?php if(isset($_SESSION['name'])){ echo $_SESSION['name'];} ?>" required><br><br>
                    <span><?php
                    if(isset($_SESSION['errors']['name'])){
                    echo  $_SESSION['errors']['name'];
                    }
                    ?></span>
                </div>
                <div id="addressField">
                    <label for="flatid">House No: </label>
                    <input type="number" name="flatno" id="flatid" placeholder="Enter Your House Number" value="<?php if(isset($_SESSION['flatno'])){ echo $_SESSION['flatno'];} ?>" required><br><br>
                    <span><?php
                    if(isset($_SESSION['errors']['flatno'])){
                    echo  $_SESSION['errors']['flatno'];
                    }
                    ?></span>
                    <label for="street">Street: </label>
                    <input type="text" name="street" id="street" placeholder="Enter Your Street" value="<?php if(isset($_SESSION['street'])){ echo $_SESSION['street'];} ?>" required><br><br>
                    <span><?php
                    if(isset($_SESSION['errors']['street'])){
                    echo  $_SESSION['errors']['street'];
                    }
                    ?></span>

                    <label for="city">City: </label>
                    <input type="text" name="city" id="city" placeholder="Enter Your City" value="<?php if(isset($_SESSION['city'])){ echo $_SESSION['city'];} ?>" required><br><br>
                    <span><?php
                    if(isset($_SESSION['errors']['city'])){
                    echo  $_SESSION['errors']['city'];
                    }
                    ?></span>

                    <label for="country">Country :</label>
                    <input type="text" name="country" id="country"  placeholder="Enter Your Country" value="<?php if(isset($_SESSION['country'])){ echo $_SESSION['country'];} ?>" required><br><br>
                    <span><?php
                    if(isset($_SESSION['errors']['country'])){
                    echo  $_SESSION['errors']['country'];
                    }
                    ?></span>

                </div>
                <div id="birthdate">
                    <label for="birthdate">Birthdate: </label>
                    <input type="date" name="birthdate" id="birthdate" required value="<?php if(isset($_SESSION['birthdate'])){ echo $_SESSION['birthdate'];} ?>"
                    max="2004-12-31"><br><br>
                    <span><?php
                    if(isset($_SESSION['errors']['birthdate'])){
                    echo  $_SESSION['errors']['birthdate'];
                    }
                    ?></span>
                </div>
                <div id="id_num_field">
                  <label for="id_num">ID Number:</label>
                  <input type="text" name="IdNumber" id="id_number" value="<?php if(isset($_SESSION['IdNumber'])){ echo $_SESSION['IdNumber'];} ?>" required maxlength="9" pattern="\d{9}" title="ID must be exactly 9 digits !" placeholder="Enter Your ID Number"><br><br>
                  <span><?php
                    if(isset($_SESSION['errors']['IdNumber'])){
                    echo  $_SESSION['errors']['IdNumber'];
                    }
                    ?></span>
              </div>              
                <div id="emailField">
                    <label for="email">Email: </label>
                    <input type="email" name="email" id="email" required value="<?php if(isset($_SESSION['email'])){ echo $_SESSION['email'];} ?>" placeholder="Enter Your Email"><br><br>
                    <span><?php
                    if(isset($_SESSION['errors']['email'])){
                    echo  $_SESSION['errors']['email'];
                    }
                    ?></span>
                </div>
                <div id="telephoneField">
                  <label for="telephone">Telephone: </label>
                  <input type="tel" name="telephone" id="telephone" maxlength="10" pattern="\d{10}" required value="<?php if(isset($_SESSION['telephone'])){ echo $_SESSION['telephone'];} ?>" placeholder="Enter Your Telephone" title="Please Telephone number should be 10 digits ! "><br><br>
                  <span><?php
                    if(isset($_SESSION['errors']['telephone'])){
                    echo  $_SESSION['errors']['telephone'];
                    }
                    ?></span>
              </div>              
              <div id="roleField">
    <label for="role">Role:</label>
    <select id="role" name="role" required>
        <option value="Manager" <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'Manager') echo 'selected'; ?>>Manager</option>
        <option value="Project Leader" <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'Project Leader') echo 'selected'; ?>>Project Leader</option>
        <option value="Team Leader" <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'Team Leader') echo 'selected'; ?>>Team Leader</option>
    </select>
    <?php if (isset($_SESSION['errors']['role'])): ?>
        <span class="error"><?php echo $_SESSION['errors']['role']; ?></span>
    <?php endif; ?>
</div>
<br>
                <div id="qualification_Section">
                    <label for="qualification">Qualification:</label>
                    <input type="text" name="qualification" id="qualification" value="<?php if(isset($_SESSION['qualification'])){ echo $_SESSION['qualification'];} ?>" required placeholder="Please Enter Your qualifications"/><br><br>
                    <span><?php
                    if(isset($_SESSION['errors']['qualification'])){
                    echo  $_SESSION['errors']['qualification'];
                    }
                    ?></span>
                </div>
                <div id="skillsSection">
                    <label for="skills">List of Skills:</label>
                    <textarea id="skills" name="skills" rows="5" cols="40" placeholder="Enter your skills Between any skill comma please ex: PHP, JS, etc" required ><?php if(isset($_SESSION['skills'])){ echo $_SESSION['skills'];} ?></textarea>
                    <span><?php
                    if(isset($_SESSION['errors']['skills'])){
                    echo  $_SESSION['errors']['skills'];
                    }
                    ?></span>
                </div>
                <div>
                <br><br><input type="submit" value="Proceed"/> 
                </div>
            </div>
        </form>
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