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
if (!isset($_GET['taskid']) || empty($_GET['taskid'])) {
    echo "Task ID is required.";
    exit();
}
$taskid = $_GET['taskid'];
if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    $query = "
    SELECT 
        t.task_id AS `Task ID`,
        t.task_name AS `Task Name`,
        t.description AS `Description`,
        t.priority AS `Priority`,
        t.status AS `Status`,
        t.effort AS `Total Effort`,
        ta.role AS `Role`,
        t.start_date AS `Start Date`,
        t.end_date AS `End Date`,
        p.project_id AS `Project ID`,
        p.project_title AS `Project Title`,
        p.project_description AS `Project Description`
    FROM 
        tasks t
    LEFT JOIN 
        task_allocate ta ON t.task_id = ta.task_id
    LEFT JOIN 
        projects p ON t.project_id = p.project_id
    WHERE 
        t.task_id = :task_id;
    ";
    $stmt = $pdo->prepare($query);
    $stmt->bindValue(":task_id", $taskid, PDO::PARAM_INT);
    $stmt->execute();
    $taskDetails = $stmt->fetch(PDO::FETCH_ASSOC);
if ($taskDetails) {
        foreach ($taskDetails as $key => $value) {
            echo "<div id='infostask'>";
            echo "<label>$key:</label>";
            echo "<label> " . $value. "</label>";
            echo "</div>";
        }
    } else {
        echo "No details found for Task ID: " . $taskid;
        exit();
    }
}
?>
<form method="post" action="" id='acceptrejecttaskfrom'>
    <input type="hidden" name="task_id" value="<?php echo $taskid; ?>">
    <input type="submit" name="Accept_The_Task" value="Accept">
    <input type="submit" name="Reject_The_Task" value="Reject">
</form>

<?php
if ($_SERVER['REQUEST_METHOD'] == "POST") {
    if (isset($_POST['task_id']) && isset($_SESSION['userid'])) {
        $userid = $_SESSION['userid'];
        $taskid = $_POST['task_id'];

        if (isset($_POST['Accept_The_Task'])) {
            $query = "UPDATE tasks SET status = 'Active' WHERE task_id = :task_id";
            $stmt = $pdo->prepare($query);
            $stmt->bindValue(':task_id', $taskid, PDO::PARAM_INT);
            if ($stmt->execute()) {
                $_SESSION['success'] = "Task successfully accepted and activated.";
            } else {
                $_SESSION['fail'] = "Failed to update the task status.";
            }
        } elseif (isset($_POST['Reject_The_Task'])) {
            $query = "DELETE FROM task_allocate WHERE task_id = :task_id AND user_id = :user_id";
            $stmt = $pdo->prepare($query);
            $stmt->bindValue(':task_id', $taskid);
            $stmt->bindValue(':user_id', $userid);
            if ($stmt->execute()) {
                $_SESSION['success'] = "Task assignment successfully rejected.";
            } else {
                $_SESSION['fail'] = "Failed to reject the task assignment.";
            }
        }
        header("Location: success.php");
        exit();
    }
}
?>
