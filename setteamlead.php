<?php
require_once("dbconfig.php");
session_start();

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    if (isset($_POST['confirms']) && isset($_POST['selectedlead']) && isset($_POST['projectId'])) {
        $projectId = $_POST['projectId']; 
        $teamLeaderId =$_POST['selectedlead']; 

        $qu1 = "UPDATE projects SET team_leader_id = :team_leader_id WHERE project_id = :projid";
        $stmtss = $pdo->prepare($qu1);
        $stmtss->bindValue(":team_leader_id", $teamLeaderId);
        $stmtss->bindValue(":projid", $projectId);

        if ($stmtss->execute()) {
            $_SESSION['success'] = "The Team Leader with ID: " . htmlspecialchars($teamLeaderId) . 
                                   " has been successfully assigned to Project ID: " . htmlspecialchars($projectId) . ".";
        } else {
            $_SESSION['fail'] = "Failed to assign the team leader! Please try again.";
        }
        header("Location: success.php");
        exit();
    }
}
?>
