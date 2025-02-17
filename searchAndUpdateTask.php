<?php
require_once("dbconfig.php");
if (!isset($_SESSION['role']) || !isset($_SESSION['logged']) || !isset($_SESSION['userid'])) {
  header("Location: login.php");
  exit();
}
if ($_SESSION['role'] !== "Team Member") {
  header("Location: dashboard.php");
  exit();
}
?>
<form method='post' action='dashboard.php?page=searchAndUpdateTask' id='form_search_update_form_teamtask'>
<label for='taskid'>Task ID: </label>
<input type='number' name='taskidsearch'  id='taskid' placeholder='Enter Task ID' maxlength="5"/><br><br>
<label for='taskname'>Task Name: </label>
<input type='text' name='tasknamesearch'  id='taskname' placeholder='Enter Task Name'/><br><br>
<label for='projectname'>Project Name :</label>
<input type='text' name='projectnamesearch'  id='projectname' placeholder='Enter Project Name'/><br><br>
<input type='submit' name='search' value='Search'/>
</form>

<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['search'])) {
        $user_id = $_SESSION['userid'];
        $query = "
        SELECT 
            t.task_id, 
            t.task_name, 
            p.project_title AS project_name, 
            t.progress, 
            t.status 
        FROM 
            tasks t
        LEFT JOIN 
            task_allocate ta ON t.task_id = ta.task_id
        LEFT JOIN 
            projects p ON t.project_id = p.project_id
        WHERE 
            ta.user_id = :user_id";

        $filters = [];
        if (!empty($_POST['taskidsearch'])) {
            $query .= " AND t.task_id = :task_id";
            $filters[':task_id'] = $_POST['taskidsearch'];
        }
        if (!empty($_POST['tasknamesearch'])) {
            $query .= " AND t.task_name LIKE :task_name";
            $filters[':task_name'] = "%" . $_POST['tasknamesearch'] . "%";
        }
        if (!empty($_POST['projectnamesearch'])) {
            $query .= " AND p.project_title LIKE :project_name";
            $filters[':project_name'] = "%" . $_POST['projectnamesearch'] . "%";
        }

        $stmt = $pdo->prepare($query);
        $stmt->bindValue(':user_id', $user_id);

        foreach ($filters as $key => $value) {
            $stmt->bindValue($key, $value);
        }

        $stmt->execute();
        $tasks = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if (!empty($tasks)) {
            echo "<table id='searchupdatetaskteam'>";
            echo "<tr>
                    <th>Task ID</th>
                    <th>Task Name</th>
                    <th>Project Name</th>
                    <th>Progress</th>
                    <th>Status</th>
                    <th>Action</th>
                  </tr>";
            foreach ($tasks as $task) {
                echo "<tr>";
                echo "<td>" . $task['task_id'] . "</td>";
                echo "<td>" . $task['task_name'] . "</td>";
                echo "<td>" . $task['project_name'] . "</td>";
                echo "<td>" . $task['progress'] . "%</td>";
                echo "<td>" . $task['status'] . "</td>";
                echo "<td><a href='dashboard.php?page=searchAndUpdateTask&task_id=" . $task['task_id'] . "'>Update</a></td>";
                echo "</tr>";
            }
            echo "</table>";
        } else {
            echo "<p class='error'>No tasks found matching the search criteria.</p>";
        }
    }
}
?>
