<?php
require_once('dbconfig.php');

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if (!isset($_SESSION['role']) || !isset($_SESSION['logged']) || !isset($_SESSION['userid'])) {
    header("Location: login.php");
    exit();
}
if ($_SESSION['role'] !== "Team Member") {
    header("Location: dashboard.php");
    exit();
}
$errs = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['cancel'])) {
        header("Location: dashboard.php");
        exit();
    }

    if (isset($_POST['save'])) {
        $taskid = $_GET['task_id'] ?? null;
        $user_id = $_SESSION['userid'];
        $new_progress = (int)$_POST['progress'];
        $new_status = $_POST['status'];
        $query = "
            SELECT t.progress, t.status
            FROM tasks t
            JOIN task_allocate ta ON t.task_id = ta.task_id
            WHERE t.task_id = :task_id AND ta.user_id = :user_id";
        $stmt = $pdo->prepare($query);
        $stmt->bindValue(':task_id', $taskid, PDO::PARAM_INT);
        $stmt->bindValue(':user_id', $user_id, PDO::PARAM_INT);
        $stmt->execute();
        $task = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($task) {
            $current_progress = (int)$task['progress'];
            $current_status = $task['status'];
            if ($new_progress === $current_progress) {
                $errs['progress'] = "Please change the progress before saving.";
            }

            if (empty($new_status)) {
                $errs['status'] = "Please select the task status.";
            }

            if ($new_progress === 100 && $new_status !== 'Complete') {
                $errs['status'] = "Progress of 100% requires status to be 'Complete'.";
            }

            if ($new_progress > 0 && $new_progress < 100 && $new_status !== 'In Progress') {
                $errs['status'] = "Progress between 1% and 99% requires status to be 'In Progress'.";
            }

            if ($new_progress === 0 && $new_status !== 'Pending') {
                $errs['status'] = "Progress of 0% requires status to be 'Pending'.";
            }
            if (empty($errs)) {
              $new_status = 'Pending'; // Default status
              if ($new_progress === 100) {
                  $new_status = 'Complete';
              } elseif ($new_progress > 0 && $new_progress < 100) {
                  $new_status = 'In Progress';
              }
          
              // Update the tasks table
              $update_task_query = "
                  UPDATE tasks
                  SET progress = :progress,
                      status = :status
                  WHERE task_id = :task_id;
              ";
              $update_task_stmt = $pdo->prepare($update_task_query);
              $update_task_stmt->bindValue(':progress', $new_progress);
              $update_task_stmt->bindValue(':status', $new_status);
              $update_task_stmt->bindValue(':task_id', $taskid);
              $task_updated = $update_task_stmt->execute();
          
              // Update the task_allocate table
              $update_allocate_query = "
                  UPDATE task_allocate
                  SET end_date = CASE
                      WHEN :progress = 100 THEN CURDATE()
                      ELSE NULL
                  END
                  WHERE task_id = :task_id AND user_id = :user_id;
              ";
              $update_allocate_stmt = $pdo->prepare($update_allocate_query);
              $update_allocate_stmt->bindValue(':progress', $new_progress);
              $update_allocate_stmt->bindValue(':task_id', $taskid);
              $update_allocate_stmt->bindValue(':user_id', $user_id);
              $allocate_updated = $update_allocate_stmt->execute();
                if ($task_updated && $allocate_updated) {
                    $_SESSION['success'] = "Task updated successfully.";
                    header("Location: success.php");
                    exit();
                } else {
                    $errs['inside'] = "Failed to update the task. Please try again.";
                }
            }
        } else {
            $errs['inside'] = "Invalid task or task does not belong to you.";
        }
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'GET' || !empty($errs)) {
    $task_id = $_GET['task_id'] ?? null;
    $user_id = $_SESSION['userid'];

    if ($task_id) {
        $query = "
            SELECT t.task_id, t.task_name, p.project_title, t.progress, t.status
            FROM tasks t
            JOIN task_allocate ta ON t.task_id = ta.task_id
            JOIN projects p ON t.project_id = p.project_id
            WHERE t.task_id = :task_id AND ta.user_id = :user_id";
        $stmt = $pdo->prepare($query);
        $stmt->bindValue(':task_id', $task_id, PDO::PARAM_INT);
        $stmt->bindValue(':user_id', $user_id, PDO::PARAM_INT);
        $stmt->execute();
        $task = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($task) {
?>
<h1>Update Task</h1>
<form method="post" action="dashboard.php?page=searchAndUpdateTask&task_id=<?php echo $task_id; ?>" id='updatetaskfromsteam'>
    <label>Task ID:</label>
    <input type="text" value="<?php echo $task['task_id']; ?>" readonly>
    <label>Task Name:</label>
    <input type="text" value="<?php echo $task['task_name']; ?>" readonly>
    <label>Project Name:</label>
    <input type="text" value="<?php echo $task['project_title']; ?>" readonly>
    <label>Progress:</label>
    <input type="range" name="progress" min="0" max="100" value="<?php echo $task['progress']; ?>" 
        onchange="this.nextElementSibling.value=this.value">
    <input type="text" value="<?php echo $task['progress']; ?>" readonly>
    <?php 
    if (isset($errs['progress'])){
     echo "<span class='errss'>" .$errs['progress']. "</span>"; 
    }
    ?>
    <label>Status:</label>
    <select name="status" required>
        <option value="Pending" <?php if ($task['status'] === 'Pending') echo 'selected'; ?>>Pending</option>
        <option value="In Progress" <?php if ($task['status'] === 'In Progress') echo 'selected'; ?>>In Progress</option>
        <option value="Complete" <?php if ($task['status'] === 'Complete') echo 'selected'; ?>>Complete</option>
    </select>
    <?php 
    if (isset($errs['status'])){
     echo "<span class='errss'>" .$errs['status']. "</span>";
    }
     ?>
    <input type="submit" name="save" value="Save">
    <input type="submit" name="cancel" value="Cancel">
</form>
<?php
        } else {
            echo "<p class='errss'>Invalid task or task does not belong to you.</p>";
        }
    } else {
        echo "<p class='errss'>Task ID is required in the URL.</p>";
    }
}
?>
