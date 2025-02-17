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
<table id='tasktableteam'>
    <tr>
        <th>Task ID</th>
        <th>Task Name</th>
        <th>Project Name</th>
        <th>Start Date</th>
        <th>Confirm</th>
    </tr>
    <?php
    $query = "SELECT 
        t.task_id AS task_id,
        t.task_name AS task_name,
        p.project_title AS project_title,
        t.start_date AS start_date,
        t.status AS status
    FROM 
        task_allocate ta
    JOIN 
        tasks t ON ta.task_id = t.task_id
    JOIN 
        projects p ON t.project_id = p.project_id
    WHERE 
        ta.user_id = :user_id AND t.status = 'Pending'";

    $stmt = $pdo->prepare($query);
    $stmt->bindValue(":user_id", $_SESSION['userid']);
    $stmt->execute();
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (!empty($results)) {
        foreach ($results as $row) {
            echo "<tr>";
            echo "<td>" . htmlspecialchars($row['task_id']) . "</td>";
            echo "<td>" . htmlspecialchars($row['task_name']) . "</td>";
            echo "<td>" . htmlspecialchars($row['project_title']) . "</td>";
            echo "<td>" . htmlspecialchars($row['start_date']) . "</td>";
            echo "<td><a href='dashboard.php?page=acceptTaskAssignments&taskid=" . urlencode($row['task_id']) . "'>Confirm</a></td>";
            echo "</tr>";
        }
    } else {
        echo "<tr>";
        echo "<td colspan='5' style='text-align: center;'>No tasks assigned to you yet. Have a nice day!</td>";
        echo "</tr>";
    }
    ?>
</table>
