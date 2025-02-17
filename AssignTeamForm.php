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

if ($_SERVER['REQUEST_METHOD'] == "POST") {
  if (isset($_POST['clears'])) {
    unset($_SESSION['valid']);
    unset($_SESSION['errors']);
    header("Location: dashboard.php?page=assignmemeberform&task_id=" . $_SESSION['taskid']);
    exit();
}
    if (isset($_POST['assign'])) {
        $errs = [];

        if (!isset($_POST['allocation_start_date']) || empty($_POST['allocation_start_date'])) {
          $errs['allocation_start_date'] = "Please Enter the Allocation Start Date.";
      } else {
          $taskid = $_POST['task_id'];
          $query = "SELECT start_date, end_date FROM tasks WHERE task_id = :taskid;";
          $stmt = $pdo->prepare($query);
          $stmt->bindValue(":taskid", $taskid);
          $stmt->execute();
          $taskData = $stmt->fetch(PDO::FETCH_ASSOC);
      
          if ($taskData) {
              $taskStartDate = $taskData['start_date'];
              $taskEndDate = $taskData['end_date'];
              $allocationStartDate = $_POST['allocation_start_date'];
      
              if ($allocationStartDate < $taskStartDate) {
                  $errs['allocation_start_date'] = "Allocation start date cannot precede the task's start date ($taskStartDate).";
              } elseif ($allocationStartDate > $taskEndDate) {
                  $errs['allocation_start_date'] = "Allocation start date cannot exceed the task's end date ($taskEndDate).";
              } else {
                  $_SESSION['valid']['allocation_start_date'] = $allocationStartDate;
              }
          } else {
              $errs['task_id'] = "Invalid Task ID.";
          }
      }

        if (!isset($_POST['team_member']) || empty($_POST['team_member'])) {
            $errs['team_member'] = "Please Select a Team Member!";
        } else {
            $_SESSION['valid']['team_member'] = $_POST['team_member'];
        }

        // Validate Role
        if (!isset($_POST['role']) || empty($_POST['role'])) {
            $errs['role'] = "Please Select the Role of the Team Member!";
        } else {
            $_SESSION['valid']['role'] = $_POST['role'];
        }

        // Validate Contribution
        if (!isset($_POST['contribution']) || empty($_POST['contribution'])) {
            $errs['contribution'] = "Please Enter the Contribution Percentage!";
        } else {
            $contribution = (int)$_POST['contribution'];
            $queryss = "SELECT SUM(contribution_percentage) AS total_contribution FROM task_allocate WHERE task_id = :taskid;";
            $stmtss = $pdo->prepare($queryss);
            $stmtss ->bindValue(":taskid", $taskid);
            $stmtss ->execute();
            $res=   $stmtss ->fetch(PDO::FETCH_ASSOC);

            $totalContributions =   $res['total_contribution'] ?? 0;
            $newTotalContributions = $totalContributions + $contribution;

            if ($newTotalContributions > 100) {
                $errs['contribution'] = "The total contribution for this task exceeds 100%. Current total: $totalContributions%.";
            } else {
                $_SESSION['valid']['contribution'] = $contribution;
            }
          }
        if (!empty($errs)) {
          $_SESSION['errors'] = $errs;
          header("Location: dashboard.php?page=assignmemeberform&task_id=" . $_SESSION['taskid']);
          exit();
      } else {
        $queryq = "INSERT INTO task_allocate (task_id, user_id, role, contribution_percentage, start_date) 
        VALUES (:task_id, :user_id, :role, :contribution, :allocation_date)";
$stmt = $pdo->prepare($queryq);
$stmt->bindValue(":task_id",$_SESSION['taskid']);
$stmt->bindValue(":user_id",  $_SESSION['valid']['team_member']);
$stmt->bindValue(":role",$_SESSION['valid']['role']);
$stmt->bindValue(":contribution", $_SESSION['valid']['contribution']);
$stmt->bindValue(":allocation_date",   $_SESSION['valid']['allocation_start_date']);
$stmt->execute();
          $_SESSION['another'] =true;
          $_SESSION['success'] = "Team member successfully assigned to Task ". $_SESSION['taskid']."as".$_SESSION['valid']['role'];
          unset($_SESSION['errors']); 
          unset($_SESSION['valid']);
          header("Location: success.php");
          exit();
      }
    }
}

if ($_SERVER['REQUEST_METHOD'] == "GET") {
    if (isset($_GET['page']) && isset($_GET['task_id'])) {
        $taskid = $_GET['task_id'];
        $_SESSION['taskid'] = $taskid;
         if (!isset($_SESSION['errors']) && !isset($_SESSION['valid'])) {
          unset($_SESSION['errors']);
          unset($_SESSION['valid']);
       }

        $query = "SELECT * FROM tasks WHERE task_id = :taskid;";
        $stmt = $pdo->prepare($query);
        $stmt->bindValue(":taskid", $taskid);
        $stmt->execute();
        $taskData = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($taskData) {
            $currentDate = date('Y-m-d');
            ?>
            <form method='post' action='dashboard.php?page=assignmemeberform&task_id='.$taskid id='assignteam'>
                <input type="hidden" name="task_id" value="<?php echo $taskid; ?>">
                <label for='taskname'>Task Name:</label>
                <input type="text" id="taskname" value="<?php echo $taskData['task_name']; ?>" readonly><br>

                <label for="allocationDate">Start Date:</label>
                <input type="date" id="allocationDate" name="allocation_start_date"
                       value="<?php echo $_SESSION['valid']['allocation_start_date'] ?? $currentDate; ?>" required>
                <?php if (isset($_SESSION['errors']['allocation_start_date'])): ?>
                    <span class="error"><?php echo $_SESSION['errors']['allocation_start_date']; ?></span>
                <?php endif; ?><br>

                <?php
                $queryUsers = "SELECT UserID, Name FROM users WHERE Role = 'Team Member'";
                $stmtUsers = $pdo->prepare($queryUsers);
                $stmtUsers->execute();
                $teamMembers = $stmtUsers->fetchAll(PDO::FETCH_ASSOC);
                ?>
                <label for="teamMember">Team Member:</label>
                <select id="teamMember" name="team_member" required>
                    <option value="" disabled <?php echo empty($_SESSION['valid']['team_member']) ? 'selected' : ''; ?>>
                        Select a Team Member
                    </option>
                    <?php foreach ($teamMembers as $member): ?>
                        <option value="<?php echo $member['UserID']; ?>" <?php echo isset($_SESSION['valid']['team_member']) && $_SESSION['valid']['team_member'] == $member['UserID'] ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($member['Name']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <?php if (isset($_SESSION['errors']['team_member'])): ?>
                    <span class="error"><?php echo $_SESSION['errors']['team_member']; ?></span>
                <?php endif; ?><br>
                <label for="role">Role:</label>
                <select id="role" name="role" required>
                    <option value="" disabled <?php echo empty($_SESSION['valid']['role']) ? 'selected' : ''; ?>>
                        Select a role
                    </option>
                    <option value="Developer" <?php echo isset($_SESSION['valid']['role']) && $_SESSION['valid']['role'] == 'Developer' ? 'selected' : ''; ?>>Developer</option>
                    <option value="Designer" <?php echo isset($_SESSION['valid']['role']) && $_SESSION['valid']['role'] == 'Designer' ? 'selected' : ''; ?>>Designer</option>
                    <option value="Tester" <?php echo isset($_SESSION['valid']['role']) && $_SESSION['valid']['role'] == 'Tester' ? 'selected' : ''; ?>>Tester</option>
                    <option value="Analyst" <?php echo isset($_SESSION['valid']['role']) && $_SESSION['valid']['role'] == 'Analyst' ? 'selected' : ''; ?>>Analyst</option>
                    <option value="Support" <?php echo isset($_SESSION['valid']['role']) && $_SESSION['valid']['role'] == 'Support' ? 'selected' : ''; ?>>Support</option>
                </select>
                <?php if (isset($_SESSION['errors']['role'])): ?>
                    <span class="error"><?php echo $_SESSION['errors']['role']; ?></span>
                <?php endif; ?><br>
                <label for="contribution">Contribution Percentage (%):</label>
                <input type="number" id="contribution" name="contribution" min="1" max="100" step="1"
                       value="<?php echo $_SESSION['valid']['contribution'] ?? ''; ?>" placeholder="Enter percentage" required>
                <?php if (isset($_SESSION['errors']['contribution'])): ?>
                    <span class="error"><?php echo $_SESSION['errors']['contribution']; ?></span>
                <?php endif; ?><br>
                <div id='butnsoptions'>
                <input type="submit" value="Assign Team Member" name="assign">
                <input type="submit" value="Clear Form" name="clears">
                </div>
            </form>
            <?php
        } else {
            echo "<p class='error'>Invalid Task ID.</p>";
        }
    }
}
?>
