<?php
session_start();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Success</title>
    <link rel='stylesheet' href='CSS/success.css'>
</head>
<body>
  <main>
    <?php
    if (true) {
        echo "<h1 id='successmsg'> Task added successfully !</h1>";
        unset($_SESSION['success']);
    } elseif (isset($_SESSION['fail'])) {
        echo "<h1 id='failerror'>" .$_SESSION['fail']. "</h1>";
        unset($_SESSION['fail']);
    }
    ?>
    <?php
  if(isset($_SESSION['another'])){
    if($_SESSION['another']===true){
      echo "<form method='post' action='success.php' id='successfailform'>";
      echo "<input type='submit' value='Add Another Team Member' name='anotherone' id='addanotherbutton'>";
      echo "<input type='submit' value='Finish Allocation' name='finish' id='finishbtn'>";
      echo "</form>";
      unset($_SESSION['another']);
    }
  }
  else{
    echo "<form method='post' action='success.php' id='successforms'>";
    echo "<input type='submit' value='Back' name='back' id='backsuccessbtn'>";
    echo "</form>";
  }
    ?>  
</main>
</body>
</html>
<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  if(isset($_POST['back'])){
    header('Location: dashboard.php');
    exit();
  }
  elseif(isset($_POST['finish'])){
    $_SESSION['tasklistfinish']=true;
    header('Location: dashboard.php?page=assignTeamMember');
    exit();
  }
  elseif(isset($_POST['anotherone'])){
    header('Location: dashboard.php?page=assignmemeberform&task_id='.$_SESSION['taskid']);
    exit();
  }
}
?>
