<?php
require_once("dbconfig.php");
session_start();
?>
<?php
if($_SERVER['REQUEST_METHOD']=="POST"){
  if(isset($_POST['confirm'])){
    try{
      $que="SELECT * FROM users WHERE IdNumber=:idnum;";
      $stms=$pdo->prepare($que);
      $stms->bindValue(":idnum",$_SESSION['IdNumber']);
      $stms->execute();
      $res=$stms->fetch(PDO::FETCH_ASSOC);
      if($res){
        $_SESSION['errorhere']="Please This User Is Already Register !";
        header("Location: Confirmation.php");
        exit();
      }
      else{
    $query = "INSERT INTO Address (FlatHouseNo, Street, City, Country) VALUES (:flatHouseNo, :street, :city, :country)";
    $stmt = $pdo->prepare($query);
    $stmt->bindValue(':flatHouseNo', $_SESSION['flatno']);
    $stmt->bindValue(':street',$_SESSION['street']);
    $stmt->bindValue(':city', $_SESSION['city']);
    $stmt->bindValue(':country',$_SESSION['country']);
    $stmt->execute();

    $addressID = $pdo->lastInsertId();
    $userID = generateUniqe10digUserId($pdo);
    $query2 = "INSERT INTO users (UserID, Name, AddressID, DateOfBirth, IDNumber, EmailAddress, Telephone, Role, Qualification, Skills, Username, Password) 
    VALUES (:userID, :name, :addressID, :dateOfBirth, :idNumber, :emailAddress, :telephone, :role, :qualification, :skills, :username, :password)";
$stmt = $pdo->prepare($query2);
$stmt->bindValue(':userID', $userID);
$stmt->bindValue(':name', $_SESSION['name']);
$stmt->bindValue(':addressID', $addressID);
$stmt->bindValue(':dateOfBirth', $_SESSION['birthdate']);
$stmt->bindValue(':idNumber', $_SESSION['IdNumber']);
$stmt->bindValue(':emailAddress', $_SESSION['email']);
$stmt->bindValue(':telephone', $_SESSION['telephone']);
$stmt->bindValue(':role', $_SESSION['role']);
$stmt->bindValue(':qualification', $_SESSION['qualification']);
$stmt->bindValue(':skills', $_SESSION['skills']);
$stmt->bindValue(':username', $_SESSION['username']);
$stmt->bindValue(':password', $_SESSION['password']);
$stmt->execute();
$_SESSION['Unique_UserID']=$userID;
header("Location: confirmsuccess.php");
exit();
    }
  }
    catch(Exception $e){
      die("Error: " . $e->getMessage());
    }
  }
}

function generateUniqe10digUserId($pdo) {
  do {
      $userID = random_int(1000000000, 9999999999);
      $query3 = "SELECT COUNT(*) FROM Users WHERE UserID = :userID";
      $stmt3 = $pdo->prepare($query3);
      $stmt3->bindValue(':userID', $userID);
      $stmt3->execute();
      $res3 = $stmt3->fetchColumn();
      // if exists we take another one !!!
      // so easy 
  } while ($res3 > 0);

  return $userID;
}
?>
<!DOCTYPE html>
<html>
  <head>
   <meta charset="UTF-8">
   <title>Confirmation Page: </title>
   <link rel='stylesheet' href='CSS/confirmationafterE.css'>
  </head>
  <body>
  <header id="task_header">
      <div id='left_header'>
      <a href='dashboard.php'><img src='TAP_LOGO.png' alt='logo_image'/></a><h1> TAP FOR TASK MANAGEMENT</h1>
        </div>
        <h2>Confirmation</h2>
        <div id="right_header_information">
      <a href="login.php">Login</a><span class="betweens">|</span>
      <a href="logout.php">Logout</a><span class="betweens">|</span>
      <a href="signUp.php">SignUp</a>
      </div>
    </header>
    <main>
      <fieldset>
        <legend>Confirmation Page</legend>
        <?php
                    echo "<span class='error'>";
                    if(isset($_SESSION['errorhere'])){
                      echo $_SESSION['errorhere']; 
                    }
                    echo "</span>";
                    ?>
      <div class=confirm_data>
                    
                    <label for="name">Name: </label>
                    <span id="namevalue"><?php echo $_SESSION['name']; ?></span>
            
                    <label for="flatno">Flat No: </label>
                    <span id="flatnoValue"><?php echo $_SESSION['flatno']; ?></span>

                    <label for="street">Street: </label>
                    <span id="atreetValue"><?php echo $_SESSION['street']; ?></span>

                    <label for="city">City: </label>
                    <span id="cityValue"><?php echo $_SESSION['city']; ?></span>


                    <label for="country">Country: </label>
                    <span id="countryValue"><?php echo $_SESSION['country']; ?></span>


                    <label for="birthdate">Date of Birth: </label>
                    <span id="birthdateValue"><?php echo $_SESSION['birthdate']; ?></span>


                    <label for="idnum">ID Number: </label>
                    <span id="idnumValue"><?php echo $_SESSION['IdNumber']; ?></span>

                    <label for="email">Email: </label>
                    <span id="emailValue"><?php echo $_SESSION['email']; ?></span>

                    <label for="role">Role: </label>
                    <span id="roleValue"><?php echo $_SESSION['role']; ?></span>

                    <label for="telephone">Telephone: </label>
                    <span id="telephoneValue"><?php echo $_SESSION['telephone']; ?></span>

                    <label for="qualification">Qualification: </label>
                    <span id="qualificationValue"><?php echo $_SESSION['qualification']; ?></span>

                    <label for="skills">Skills: </label>
                    <span id="skillsValue"><?php echo $_SESSION['skills']; ?></span>

                    <label for="username">Username: </label>
                    <span id="usernameValue"><?php echo $_SESSION['username']; ?></span>
</div>
      <form action="Confirmation.php" method="post">
        <input type="submit" name="confirm" value="Confirm" />
</form>
</fieldset>
</main>
  </body>
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
</html>
