  <?php
  require_once("dbconfig.php");
  if (session_status() === PHP_SESSION_NONE) {
    session_start();
  }
  if (!isset($_SESSION['role']) || !isset($_SESSION['logged']) || !isset($_SESSION['userid'])) {
    header("Location: login.php");
    exit();
  }
  if ($_SESSION['role'] !== "Project Leader") {
    header("Location: dashboard.php");
    exit();
  }

  ?>
      <?php
      $qu="SELECT * FROM users WHERE UserId=:userid;";
      $stm=$pdo->prepare($qu);
      $userid=$_SESSION['userid'];
      $stm->bindValue(":userid",$userid);
      $stm->execute();
      $res=$stm->fetch(PDO::FETCH_ASSOC);
  if($res){
    $username=$res['Name'];
  }

  if ($_SERVER['REQUEST_METHOD'] == "POST") {
    unset($_SESSION['errors']);
    unset($_SESSION['valid']);
    $errs = [];

    if (isset($_POST['create_task'])) {
        if (empty($_POST['taskName'])) {
            $errs['taskName'] = "Please Enter The Title Of the Task!";
        } else {
            $_SESSION['valid']['taskName'] = $_POST['taskName'];
        }

        if (empty($_POST['description'])) {
            $errs['description'] = "Please Enter The Description Of the Task!";
        } else {
            $_SESSION['valid']['description'] = $_POST['description'];
        }

        if (empty($_POST['project'])) {
            $errs['project'] = "Please Select The Project To Create The Task!";
        } else {
            $_SESSION['valid']['project'] = $_POST['project'];
        }

        if (empty($_POST['startdate'])) {
          $errs['startdate'] = "Please Select the Start Date of the Task!";
      } else {
          $queryss = "SELECT start_date FROM projects WHERE project_id = :projid;";
          $stmt = $pdo->prepare($queryss);
          $stmt->bindValue(":projid", $_POST['project']);
          $stmt->execute();
          $project = $stmt->fetch(PDO::FETCH_ASSOC);
      
          if ($_POST['startdate'] < $project['start_date']) {
              $errs['startdate'] = "Start Date must be equal to or later than the project's start date: " . $project['start_date'];
          } else {
              $_SESSION['valid']['startdate'] = $_POST['startdate'];
          }
      }
      
      if (empty($_POST['enddate'])) {
          $errs['enddate'] = "Please Select the End Date of the Task!";
      } else {
          $queryss = "SELECT end_date FROM projects WHERE project_id = :projid;";
          $stmt = $pdo->prepare($queryss);
          $stmt->bindValue(":projid", $_POST['project']);
          $stmt->execute();
          $project = $stmt->fetch(PDO::FETCH_ASSOC);
      

          if ($_POST['enddate'] > $project['end_date']) {
              $errs['enddate'] = "End Date must be equal to or earlier than the project's end date: " . $project['end_date'];
          } 
          elseif($_POST['enddate'] < $_POST['startdate']){
            $errs['enddate'] = "End Date must be Greater Than The Stsrt Date" . $_POST['startdate'];
          }
          else {
              $_SESSION['valid']['enddate'] = $_POST['enddate'];
          }


      }
        if (empty($_POST['effort']) || $_POST['effort'] < 0 ) {
            $errs['effort'] = "Effort must be positive number!";
        } else {
            $_SESSION['valid']['effort'] = $_POST['effort'];
        }

        if (empty($_POST['priority'])) {
            $errs['priority'] = "Please Select The Priority Of The Task!";
        } else {
            $_SESSION['valid']['priority'] = $_POST['priority'];
        }

        if (!empty($errs)) {
          $_SESSION['errors'] = $errs;
          header("Location: dashboard.php?page=taskcreate");
          exit();

      } else {

          unset($_SESSION['errors']);
          $insertQuery = "INSERT INTO tasks (task_name, description, project_id, start_date, end_date, effort, status, priority) 
                          VALUES (:taskName, :description, :project_id, :start_date, :end_date, :effort, :status, :priority)";

          $stmt = $pdo->prepare($insertQuery);
          $stmt->bindValue(":taskName", $_SESSION['valid']['taskName']);
          $stmt->bindValue(":description", $_SESSION['valid']['description']);
          $stmt->bindValue(":project_id", $_SESSION['valid']['project']);
          $stmt->bindValue(":start_date", $_SESSION['valid']['startdate']);
          $stmt->bindValue(":end_date", $_SESSION['valid']['enddate']);
          $stmt->bindValue(":effort", $_SESSION['valid']['effort']);
          $stmt->bindValue(":status", "Pending");
          $stmt->bindValue(":priority", $_SESSION['valid']['priority']);
      
          if ($stmt->execute()) {
              unset($_SESSION['valid']);
              $_SESSION['success'] = "The Task Was Added Successfully!";
          } else {
              $_SESSION['fail'] = "Failed to insert the task into the database.";
          }
          header("Location: success.php");
          exit();
      }  
    }
  }
      ?>
  <h1 id='taskcreatehead'>Please Project Leader [<?php echo $username; ?>] Fill The Form If You Want To Add Task: </h1>
  <form method="post" action="TaskCreation.php" id='taskcreateform'>
    <div class='taskcreateid'>
  <label for='taskid'>Task ID: </label>
  <?php
    $query = "SELECT MAX(task_id) AS last_id FROM tasks";
    $stmt = $pdo->query($query);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    $nextTaskId = isset($result['last_id']) ? $result['last_id'] + 1 : 1;
  ?>
  <label id='taskidvalue'><?php echo $nextTaskId; ?></label>
  </div>
  <label for='taskname'>Task Title :</label>
  <input type="text" id="taskname" name="taskName" placeholder="Please Enter Task Title" maxlength="50" required value="<?php if(isset($_SESSION['valid']['taskName'])){ echo $_SESSION['valid']['taskName'];} ?>"/>
    <?php
  if(isset($_SESSION['errors']['taskname'])){
  echo "<span class='error'>".$_SESSION['errors']['taskname']."</span>";
  }
  ?>
  <label for='description'>Task Description :</label>
  <textarea required id="description" name="description" placeholder="Please Enter Description" rows='5' column='5'><?php if(isset($_SESSION['valid']['description'])){ echo $_SESSION['valid']['description'];} ?></textarea>
  <?php
  if(isset($_SESSION['errors']['taskname'])){
  echo "<span class='error'>".$_SESSION['errors']['taskname']."</span>";
  }
    ?>
  <label for="projectselect">Project Name:</label>
  <select id="projectselect" name="project" required>
  <?php
  $query = "SELECT * FROM projects WHERE team_leader_id = :teamleadid;";
  $stmt = $pdo->prepare($query);
  $stmt->bindValue(":teamleadid", $_SESSION['userid']);
  $stmt->execute();
  $projects = $stmt->fetchAll(PDO::FETCH_ASSOC);
  if (!empty($projects)) {
      foreach ($projects as $project) {
          echo "<option value='" . $project['project_id']. "'";
          if (isset($_SESSION['valid']['project']) && $_SESSION['valid']['project'] == $project['project_id']) {
              echo " selected";
          }
          echo ">" .$project['project_title'] . "</option>";
      }
  } else {
      echo "<option value=''>No projects assigned</option>";
  }
  ?>
  </select>
  <?php
  if(isset($_SESSION['errors']['project'])){
  echo "<span class='error'>".$_SESSION['errors']['project']."</span>";
  }
    ?>
  <label for="startDate">Start Date:</label>
  <input type="date" id="startDate" name="startdate" required value="<?php if(isset($_SESSION['valid']['startdate'])){ echo $_SESSION['valid']['startdate'];} ?>">
  <?php
  if (isset($_SESSION['errors']['startdate'])) {
      echo "<span class='error'>" . $_SESSION['errors']['startdate'] . "</span>";
  }
  ?>
  <label for="endDate">End Date:</label>
  <input type="date" id="endDate" name="enddate" required value="<?php if(isset($_SESSION['valid']['enddate'])){ echo $_SESSION['valid']['enddate'];} ?>">
  <?php
  if (isset($_SESSION['errors']['enddate'])) {
      echo "<span class='error'>" . $_SESSION['errors']['enddate'] . "</span>";
  }
  ?>
          <label for="effort">Effort (man-months):</label>
  <input type="number" id="effort" name="effort" step="0.1"  required value="<?php if(isset($_SESSION['valid']['effort'])){ echo $_SESSION['valid']['effort'];} ?>">
  <?php
  if(isset($_SESSION['errors']['effort'])){
  echo "<span class='error'>".$_SESSION['errors']['effort']."</span>";
  }
  ?>
  <div class='taskstatusdiv'>
  <label for="status">Task Status:</label>
  <label id ="statusvalue">Pending</label>
  </div>
  <label for="priority">Task Priority:</label>
  <select id="priority" name="priority" required>
      <option value="" disabled <?php if (!isset($_SESSION['valid']['priority'])) { echo 'selected'; } ?>>Select Priority</option>
      <option value="Low" <?php if (isset($_SESSION['valid']['priority']) && $_SESSION['valid']['priority'] == 'Low') { echo 'selected'; } ?>>Low</option>
      <option value="Medium" <?php if (isset($_SESSION['valid']['priority']) && $_SESSION['valid']['priority'] == 'Medium') { echo 'selected'; } ?>>Medium</option>
      <option value="High" <?php if (isset($_SESSION['valid']['priority']) && $_SESSION['valid']['priority'] == 'High') { echo 'selected'; } ?>>High</option>
  </select>
  <?php
  if (isset($_SESSION['errors']['priority'])) {
      echo "<span class='error'>" . $_SESSION['errors']['priority'] . "</span>";
  }
  ?>
  <input type='submit'  value='Create Task' name='create_task'/>
  </form>