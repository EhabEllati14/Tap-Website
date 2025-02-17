<?php
require_once("dbconfig.php");
session_start();
if (!isset($_SESSION['role']) || !isset($_SESSION['userid']) || !isset($_SESSION['usernamelog']) || !isset($_SESSION['logged'])) {
    header("Location: login.php");
    exit();
}

$role=$_SESSION['role'];
$userid=$_SESSION['userid'];
$username=$_SESSION['usernamelog'];
$query="SELECT * FROM users WHERE UserID=:userid;";
$stmt=$pdo->prepare($query);
$stmt->bindValue(":userid",$userid);
$stmt->execute();
$res=$stmt->fetch(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html>
  <head>
<meta charset="UTF-8">
<title>TAP TASK MANAGEMENT</title>
<link rel="stylesheet" href="CSS/dashboard.css?v=<?php echo time(); ?>">
  </head>
  <body>
    <header>
    <div id='left_header'>
        <a href='dashboard.php'><img src='TAP_LOGO.png' alt='logo_image'/></a><h1> TAP FOR TASK MANAGEMENT</h1>
        </div>
      <div id="right_header_information">
        <div id="userProfile">
      <a href="dashboard.php"><img src="<?php echo $res['ProfilePicture'];?>" alt=" user Profile Picture"></a>
      <label id="userprofile_username"><?php echo $res['Username'];?></label>
      </div>
      <a href="login.php">Login</a><span class="betweens">|</span>
      <a href="logout.php">Logout</a><span class="betweens">|</span>
      <a href="signUp.php">SignUp</a>
      </div>
                                                                                                                </header>
  <nav>
        <?php   
        if ($role == "Manager") {
          echo "<ul>";
          echo "<li><a href='dashboard.php'>Dashboard</a></li>";
          echo "<li><a href='dashboard.php?page=searchtask'>Search Tasks</a></li>";
          echo "<li><a href='dashboard.php?page=addproject'>Add Project</a></li>";
          echo "<li><a href='dashboard.php?page=allocateleader'>Allocate Team Leader To Project</a></li>";
          echo "</ul>";
      }
        elseif($role=="Project Leader"){
          echo "<ul>";
          echo "<li><a href='dashboard.php'>Dashboard</a></li>";
          echo "<li><a href='dashboard.php?page=searchtask'>Search Tasks</a></li>";
          echo "<li><a href='dashboard.php?page=taskcreate'>Task Creation</a></li>";
          echo "<li><a href='dashboard.php?page=assignTeamMember'>Assign Team Members to Tasks</a></li>";
          echo "</ul>";
        }
        elseif($role == "Team Member") {
          echo "<ul>";
          echo "<li><a href='dashboard.php'>Dashboard</a></li>";
          echo "<li><a href='dashboard.php?page=searchtask'>Search Tasks</a></li>"; 
          $query = "SELECT COUNT(*) AS pending_tasks 
                    FROM tasks t
                    JOIN task_allocate ta ON t.task_id = ta.task_id
                    WHERE ta.user_id = :user_id AND t.status = 'Pending'";
          $stmt = $pdo->prepare($query);
          $stmt->execute([':user_id' => $_SESSION['userid']]);
          $results = $stmt->fetch(PDO::FETCH_ASSOC);
          if (!empty($results['pending_tasks']) && $results['pending_tasks'] > 0) {
            echo "<li><a href='dashboard.php?page=acceptTaskAssignments' id='youhavetask'>Accept Task Assignments</a></li>";
        } else {
            echo "<li><a href='dashboard.php?page=acceptTaskAssignments'>Accept Task Assignments</a></li>";
        }
          echo "<li><a href='dashboard.php?page=searchAndUpdateTask'>Search and Update Task Progress </a></li>";
          echo "</ul>";
      }
         ?>
    </nav>
    <main>
    <?php
if ($_SERVER['REQUEST_METHOD'] == "GET" || $_SERVER['REQUEST_METHOD'] == "POST") {
    if ($role == "Manager") {
        if (isset($_GET['page']) && $_GET['page'] === 'addproject') {
            include 'AddProject.php';
        } elseif (isset($_GET['page']) && $_GET['page'] === 'allocateleader') {
            include 'allocateLeader.php';
        } elseif (isset($_GET['page']) && $_GET['page'] === 'selectallocateteamlead' && isset($_GET['projectId'])) {
            include 'selectallocateteamlead.php';
        } elseif (empty($_GET['page'])) {
          include 'managermain.php';
        }
        elseif(isset($_GET['page']) && $_GET['page'] === 'searchtask'){
            include 'searchtasks.php';
        }
        elseif(isset($_GET['page']) && $_GET['page'] === 'taskdetails'){
          include 'TaskDetailsPage.php';
        
        }
    } elseif ($role == "Project Leader") {
        if (isset($_GET['page']) && $_GET['page'] === 'taskcreate') {
            include 'TaskCreation.php';
        } elseif (isset($_GET['page']) && $_GET['page'] === 'assignTeamMember') {
            include 'AssignTeamMemeber.php';
      } elseif(isset($_GET['page']) && $_GET['page']=== 'assignmemeberform' && isset($_GET['task_id'])){

            include 'AssignTeamForm.php';
      }
      elseif (empty($_GET['page'])) {
        echo "<div class='headsmang'><img id='handsmanager' src='waveHand.png' alt='wavehand_welcoming'><h2 id='welcomesmang'>Welcome Project Leader ".$_SESSION['usernamelog']."</h2></div>";
        }
        elseif(isset($_GET['page']) && $_GET['page'] === 'searchtask'){
          include 'searchtasks.php';
      }
      elseif(isset($_GET['page']) && $_GET['page'] === 'taskdetails'){
        include 'TaskDetailsPage.php';
      
      }
    }

    elseif ($role == "Team Member") {

      
      if (isset($_GET['page']) && $_GET['page'] === 'acceptTaskAssignments') {
          if (isset($_GET['taskid'])) {
              include 'confirmAssign.php';
          } else{
              include 'AcceptTaskAssignmnets.php';
          }
      }
      elseif(isset($_GET['page']) && $_GET['page'] === 'searchAndUpdateTask'){
        if(isset($_GET['task_id'])){
            include 'teamUpdateTask.php';
        }
        else{
          include 'searchAndUpdateTask.php';
        }
      }
      elseif(isset($_GET['page']) && $_GET['page'] === 'searchtask'){
        if(isset($_GET['orderBy']) && isset($_GET['order'])){
          include 'searchtasks.php';
        }
        else{
          include 'searchtasks.php';
        }
      
    }
    elseif(isset($_GET['page']) && $_GET['page'] === 'taskdetails'){
      include 'TaskDetailsPage.php';
    
    }  
    elseif (empty($_GET['page'])) {
      echo "<div class='headsmang'><img id='handsmanager' src='waveHand.png' alt='wavehand_welcoming'><h2 id='welcomesmang'>Welcome Team Member ".$_SESSION['usernamelog']."</h2></div>";
      }
  }
}
?>
    </main>
  
    <footer>
        <p>
            <strong>Contact Us:</strong> 
            <a href="mailto:taptaskmangement@gmail.com">tap_support@gmail.com</a> | 
            <a href="tel:+0569744223">+0569744223</a>
        </p>
        <div>
            <a href="contactus.php" target="_blank">About Us</a>
            <a href="https://policies.google.com/privacy?hl=en-US" target="_blank">Privacy Policy</a>
        </div>
        <p>&copy; 2025 TAP Task Management. All rights reserved.</p>
    </footer>

  </body>
</html>

