<?php
require_once("dbconfig.php");

if (!isset($_GET['taskid'])) {
    echo "Task ID not provided.";
    exit();
}

$taskId = $_GET['taskid'];

$query = "
    SELECT 
        t.task_id, 
        t.task_name, 
        t.description, 
        p.project_title AS project_name, 
        t.start_date, 
        t.end_date, 
        t.progress, 
        t.status, 
        t.priority 
    FROM 
        tasks t
    JOIN 
        projects p ON t.project_id = p.project_id
    WHERE 
        t.task_id = :task_id";
$stmt = $pdo->prepare($query);
$stmt->bindValue(':task_id', $taskId);
$stmt->execute();
$task = $stmt->fetch(PDO::FETCH_ASSOC);

$query = "
    SELECT 
        u.ProfilePicture AS photo,  
        u.UserID AS member_id,
        u.Name AS name,
        ta.start_date,
        ta.end_date,  -- Add end_date field
        ta.contribution_percentage
    FROM 
        task_allocate ta
    JOIN 
        users u ON ta.user_id = u.UserID
    WHERE 
        ta.task_id = :task_id";

$stmt = $pdo->prepare($query);
$stmt->bindValue(':task_id', $taskId);
$stmt->execute();
$teamMembers = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
  <div class="container">
        <div class="task-detailspartA">
            <h2>Task Details</h2>
            <?php if ($task){ ?>
                <p><strong>Task ID:</strong> <?php  echo $task['task_id']; ?></p>
                <p><strong>Task Name:</strong> <?php echo $task['task_name']; ?></p>
                <p><strong>Description:</strong> <?php echo $task['description']; ?></p>
                <p><strong>Project:</strong> <?php echo  $task['project_name']; ?></p>
                <p><strong>Start Date:</strong> <?php echo $task['start_date']; ?></p>
                <p><strong>End Date:</strong> <?php echo  $task['end_date']; ?></p>
                <p><strong>Completion Percentage:</strong> <?php  echo $task['progress']; ?>%</p>
                <p><strong>Status:</strong> 
                    <span class="<?php echo $task['status'] === 'In Progress' ? 'bold-in-progress' : '' ;?>">
                        <?php echo $task['status']; ?>
                    </span>
                </p>
                <p><strong>Priority:</strong> <?php echo  $task['priority']; ?></p>
            <?php }else{?>
                <p>Task details not found.</p>
            <?php } ?>
        </div>

        <div class="team-memberspartb">
            <h2>Team Members</h2>
            <?php if (!empty($teamMembers)){ ?>
                <table id='taskdetailstable'>
                    <thead>
                        <tr>
                            <th>Photo</th>
                            <th>Member ID</th>
                            <th>Name</th>
                            <th>Start Date</th>
                            <th>End Date</th>
                            <th>Effort Allocated (%)</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($teamMembers as $member){ ?>
                            <tr>
                                <td><img src="<?php echo $member['photo']; ?>" alt="Profile_Picture"></td>
                                <td><?php echo $member['member_id']; ?></td>
                                <td><?php echo $member['name']; ?></td>
                                <td><?php echo $member['start_date']; ?></td>
                                <td>
                                    <?php echo $member['end_date'] ? $member['end_date'] : "<span class='bold-in-progress'>In Progress</span>" ;?>
                                </td>
                                <td><?php  echo $member['contribution_percentage'] ;?>%</td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            <?php }else{ ?>
                <p>No team members assigned to this task.</p>
            <?php } ?>
        </div>
    </div>
