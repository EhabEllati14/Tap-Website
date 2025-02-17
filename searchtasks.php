<?php
require_once("dbconfig.php");

// Redirect if session variables are missing
if (!isset($_SESSION['role']) || !isset($_SESSION['logged']) || !isset($_SESSION['userid'])) {
    header("Location: login.php");
    exit();
}

$orderBy = $_GET['orderBy'] ?? 'task_id'; 
$order = $_GET['order'] ?? 'ASC'; 
$order = strtoupper($order) === 'ASC' ? 'ASC' : 'DESC'; 
$newOrder = $order === 'ASC' ? 'DESC' : 'ASC'; 

$filters = [
    'priority' => $_POST['priority'] ?? $_GET['priority'] ?? '',
    'status' => $_POST['status'] ?? $_GET['status'] ?? '',
    'start_date' => $_POST['start_date'] ?? $_GET['start_date'] ?? '',
    'end_date' => $_POST['end_date'] ?? $_GET['end_date'] ?? '',
    'project' => $_POST['project'] ?? $_GET['project'] ?? ''
];


$queryString = http_build_query(array_filter([
    'priority' => $filters['priority'],
    'status' => $filters['status'],
    'start_date' => $filters['start_date'],
    'end_date' => $filters['end_date'],
    'project' => $filters['project']
]));
?>

<h1>Search On Task:</h1>
<form method="post" action="dashboard.php?page=searchtask" id='searchtaskform'>
    <label for="taskproiority">Task Priority: </label>
    <select id="taskproiority" name="priority">
        <option disabled <?= empty($filters['priority']) ? 'selected' : '' ?> value="">Select Priority</option>
        <option <?= $filters['priority'] === 'Low' ? 'selected' : '' ?>>Low</option>
        <option <?= $filters['priority'] === 'Medium' ? 'selected' : '' ?>>Medium</option>
        <option <?= $filters['priority'] === 'High' ? 'selected' : '' ?>>High</option>
    </select>
    <br>
    <label for="taskstatus">Task Status: </label>
    <select id="taskstatus" name="status">
        <option disabled <?= empty($filters['status']) ? 'selected' : '' ?> value="">Select Status</option>
        <option <?= $filters['status'] === 'Pending' ? 'selected' : '' ?>>Pending</option>
        <option <?= $filters['status'] === 'In Progress' ? 'selected' : '' ?>>In Progress</option>
        <option <?= $filters['status'] === 'Completed' ? 'selected' : '' ?>>Completed</option>
    </select><br>
    <label for="start_date">Start Date: </label>
    <input type="date" name="start_date" id="start_date" value="<?= htmlspecialchars($filters['start_date']) ?>">
    <label for="end_date">  End Date:</label>
    <input type="date" name="end_date" id="end_date" value="<?= htmlspecialchars($filters['end_date']) ?>"><br>
    <label for="project">Project:</label>
    <select name="project" id="project">
        <option disabled <?= empty($filters['project']) ? 'selected' : '' ?> value="">Select Project</option>
        <?php
        $params = [];
        if ($_SESSION['role'] == "Manager") {
            $query = "SELECT * FROM projects;";
        } elseif ($_SESSION['role'] == "Project Leader") {
            $query = "SELECT * FROM projects WHERE team_leader_id = :team_lead_id;";
            $params[':team_lead_id'] = $_SESSION['userid'];
        } elseif ($_SESSION['role'] == "Team Member") {
            $query = "
                SELECT DISTINCT p.project_id, p.project_title 
                FROM projects p 
                JOIN tasks t ON p.project_id = t.project_id 
                JOIN task_allocate ta ON t.task_id = ta.task_id 
                WHERE ta.user_id = :user_id;";
            $params[':user_id'] = $_SESSION['userid'];
        }
        $stmt = $pdo->prepare($query);
        foreach ($params as $key => $value) {
            $stmt->bindValue($key, $value);
        }
        $stmt->execute();
        $projects = $stmt->fetchAll(PDO::FETCH_ASSOC);
        foreach ($projects as $project) {
            $selected = $filters['project'] === $project['project_id'] ? 'selected' : '';
            echo "<option value='{$project['project_id']}' $selected>{$project['project_title']}</option>";
        }
        ?>
    </select><br>
    <input type="submit" name="search" value="Search">
    <input type='submit' name='reset' value='Reset'>
</form>

<?php
// Display the table only if the search button is clicked or sorting is used
if ($_SERVER['REQUEST_METHOD'] === 'POST' || !empty($_GET['orderBy'])) {
    if (isset($_POST['reset'])) {
        header("Location: dashboard.php?page=searchtask");
        exit();
    }

    $query = "
        SELECT 
            t.task_id, 
            t.task_name, 
            p.project_title AS project_name, 
            t.priority, 
            t.status, 
            t.start_date, 
            t.end_date, 
            t.progress
        FROM 
            tasks t
        JOIN 
            projects p ON t.project_id = p.project_id";

    $whereClauses = [];
    $params = [];

    if ($_SESSION['role'] == "Project Leader") {
        $whereClauses[] = "p.team_leader_id = :team_leader_id";
        $params[":team_leader_id"] = $_SESSION['userid'];
    } elseif ($_SESSION['role'] == "Team Member") {
        $query .= " JOIN task_allocate ta ON t.task_id = ta.task_id";
        $whereClauses[] = "ta.user_id = :user_id";
        $params[":user_id"] = $_SESSION['userid'];
    }

    if (!empty($filters['priority'])) {
        $whereClauses[] = "t.priority = :priority";
        $params[":priority"] = $filters['priority'];
    }
    if (!empty($filters['status'])) {
        $whereClauses[] = "t.status = :status";
        $params[":status"] = $filters['status'];
    }
    if (!empty($filters['start_date'])) {
        $whereClauses[] = "t.start_date >= :start_date";
        $params[":start_date"] = $filters['start_date'];
    }
    if (!empty($filters['end_date'])) {
        $whereClauses[] = "t.end_date <= :end_date";
        $params[":end_date"] = $filters['end_date'];
    }
    if (!empty($filters['project'])) {
        $whereClauses[] = "t.project_id = :project_id";
        $params[":project_id"] = $filters['project'];
    }

    if (!empty($whereClauses)) {
        $query .= " WHERE " . implode(" AND ", $whereClauses);
    }

    $query .= " ORDER BY $orderBy $order";

    $stmt = $pdo->prepare($query);
    foreach ($params as $key => $value) {
        $stmt->bindValue($key, $value);
    }
    $stmt->execute();
    $tasks = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Display tasks
    if (!empty($tasks)) {
        echo "<table id='searchtable_result'>";
        echo "<tr>
                <th><a href='dashboard.php?page=searchtask&orderBy=task_id&order=$newOrder&$queryString#searchtable_result'>Task ID</a></th>
                <th><a href='dashboard.php?page=searchtask&orderBy=task_name&order=$newOrder&$queryString#searchtable_result'>Task Name</a></th>
                <th><a href='dashboard.php?page=searchtask&orderBy=project_name&order=$newOrder&$queryString#searchtable_result'>Project Name</a></th>
                <th><a href='dashboard.php?page=searchtask&orderBy=priority&order=$newOrder&$queryString#searchtable_result'>Priority</a></th>
                <th><a href='dashboard.php?page=searchtask&orderBy=status&order=$newOrder&$queryString#searchtable_result'>Status</a></th>
                <th><a href='dashboard.php?page=searchtask&orderBy=start_date&order=$newOrder&$queryString#searchtable_result'>Start Date</a></th>
                <th><a href='dashboard.php?page=searchtask&orderBy=end_date&order=$newOrder&$queryString#searchtable_result'>End Date</a></th>
                <th><a href='dashboard.php?page=searchtask&orderBy=progress&order=$newOrder&$queryString#searchtable_result'>Progress</a></th>
              </tr>";
        foreach ($tasks as $task) {
            $priorityClass = '';
            if ($task['priority'] === 'Low') {
                $priorityClass = 'priority-low';
            } elseif ($task['priority'] === 'Medium') {
                $priorityClass = 'priority-medium';
            } elseif ($task['priority'] === 'High') {
                $priorityClass = 'priority-high';
            }

            $statusClass = '';
            if ($task['status'] === 'Pending') {
                $statusClass = 'status-pending';
            } elseif ($task['status'] === 'In Progress') {
                $statusClass = 'status-inprogress';
            } elseif ($task['status'] === 'Completed') {
                $statusClass = 'status-completed';
            }
            echo "<tr>";
            echo "<td><a href='dashboard.php?page=taskdetails&taskid=" . $task['task_id'] . "'>" . $task['task_id'] . "</a></td>";
            echo "<td>" . $task['task_name'] . "</td>";
            echo "<td>" . $task['project_name']. "</td>";
            echo "<td class='$priorityClass'>" . $task['priority'] . "</td>";
            echo "<td class='$statusClass'>" . $task['status']. "</td>";
            echo "<td>" .$task['start_date']. "</td>";
            echo "<td>" .$task['end_date']. "</td>";
            echo "<td>" . $task['progress'] . "%</td>";
            echo "</tr>";
        }
        echo "</table>";
    } else {
        echo "<p>No tasks found matching the search criteria.</p>";
    }
}
?>
