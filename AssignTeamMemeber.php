<?php
require_once("dbconfig.php");
if (!isset($_SESSION['role']) || !isset($_SESSION['logged']) || !isset($_SESSION['userid'])) {
  header("Location: login.php");
  exit();
}
if ($_SESSION['role'] !== "Project Leader") {
  header("Location: dashboard.php");
  exit();
}
?>
<h1 id='titleofselectproj'>Please Select The project from the list : </h1>
  <form method="post" action="dashboard.php?page=assignTeamMember" id='selctprojforms'>
  <label for="project selection">Project Selection:</label>
  <select name="projectselection" required>
    <?php
    $query = "SELECT * FROM projects WHERE team_leader_id = :userid;";
    $stmt = $pdo->prepare($query);
    $stmt->bindValue(":userid", $_SESSION['userid']);
    $stmt->execute();
    $res = $stmt->fetchAll(PDO::FETCH_ASSOC);
    if (!empty($res)) {
      $selectedProjectId = isset($_POST['projectselection']) ? $_POST['projectselection'] : ''; 
      foreach ($res as $row) {
          echo "<option value='" . $row['project_id'] . "'";
          if ($row['project_id'] == $selectedProjectId) {
              echo " selected";
          }
          echo ">" .$row['project_title']. "</option>";
      }  
    } else {
        echo "<option value=''>No projects assigned</option>";
    }
    ?>
  </select>
  <input type="submit" name="searchtask" value="View Tasks">
</form>
<?php
if(isset($_SESSION['tasklistfinish'])){
  echo "<h3 id='note'> Here is the List Of Tasks for this Project ID ".$_SESSION['projsel']."</h3>";
  $query = "SELECT t.task_id, t.task_name, t.start_date, t.status, t.priority, 
            (SELECT COUNT(*) FROM task_allocate WHERE task_id = t.task_id) AS allocation_count
            FROM tasks t
            WHERE t.project_id = :project_id
            ORDER BY allocation_count ASC, t.task_id ASC";

  $stmt = $pdo->prepare($query);
  $stmt->bindValue(':project_id', $_SESSION['projsel']);
  $stmt->execute();
  $tasks = $stmt->fetchAll(PDO::FETCH_ASSOC);

  if (!empty($tasks)) {
      echo "<table id='tasksafterselectproj'>";
      echo "<tr>
              <th>Task ID</th>
              <th>Task Name</th>
              <th>Start Date</th>
              <th>Status</th>
              <th>Priority</th>
              <th>Team Allocation</th>
            </tr>";
      foreach ($tasks as $task) {
          echo "<tr>";
          echo "<td>" .$task['task_id'] . "</td>";
          echo "<td>" .$task['task_name'] . "</td>";
          echo "<td>" . $task['start_date'] . "</td>";
          echo "<td>" . $task['status'] . "</td>";
          echo "<td>" . $task['priority'] . "</td>";
          echo "<td><a href='dashboard.php?page=assignmemeberform&task_id=" . $task['task_id'] . "'>Assign Team Members</a></td>";
          echo "</tr>";
      }
      echo "</table>";
  } else {
      echo "<p id='err'>No tasks found for this project.</p>";
  }
  unset($_SESSION['tasklistfinish']);
}
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  if(isset($_POST['searchtask'])){
  $project_id = $_POST['projectselection'];
  $_SESSION['projsel']=$project_id;
 echo "<h3 id='notess'> Here is the List Of Tasks for this Project ID ".$project_id."</h3>";
  $query = "SELECT t.task_id, t.task_name, t.start_date, t.status, t.priority, 
            (SELECT COUNT(*) FROM task_allocate WHERE task_id = t.task_id) AS allocation_count
            FROM tasks t
            WHERE t.project_id = :project_id
            ORDER BY allocation_count ASC, t.task_id ASC";

  $stmt = $pdo->prepare($query);
  $stmt->bindValue(':project_id', $project_id);
  $stmt->execute();
  $tasks = $stmt->fetchAll(PDO::FETCH_ASSOC);
  if (!empty($tasks)) {
      echo "<table class='tasksafterselectproj1'>";
      echo "<tr>
              <th>Task ID</th>
              <th>Task Name</th>
              <th>Start Date</th>
              <th>Status</th>
              <th>Priority</th>
              <th>Team Allocation</th>
            </tr>";
      foreach ($tasks as $task) {
          echo "<tr>";
          echo "<td>" .$task['task_id'] . "</td>";
          echo "<td>" .$task['task_name'] . "</td>";
          echo "<td>" . $task['start_date'] . "</td>";
          echo "<td>" . $task['status'] . "</td>";
          echo "<td>" . $task['priority'] . "</td>";
          echo "<td><a href='dashboard.php?page=assignmemeberform&task_id=" . $task['task_id'] . "'>Assign Team Members</a></td>";
          echo "</tr>";
      }
      echo "</table>";
  } else {
      echo "<p id='err'>No tasks found for this project.</p>";
  }
}
}
?>