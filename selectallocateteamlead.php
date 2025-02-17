<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once("dbconfig.php");
if (!isset($_SESSION['role']) || !isset($_SESSION['logged']) || !isset($_SESSION['userid'])) {
    header("Location: login.php");
    exit();
}
if ($_SESSION['role'] !== "Manager") {
    header("Location: dashboard.php");
    exit();
}
?>
<h1 id='allocateh1page'>Allocate Team Leader</h1>
<?php
if ($_SERVER['REQUEST_METHOD'] == "GET" && isset($_GET['projectId']) && !empty($_GET['projectId'])) {
    $projid = $_GET['projectId'];

    $info = "SELECT * FROM projects WHERE project_id = :projid";
    $st2 = $pdo->prepare($info);
    $st2->bindValue(":projid", $projid);
    $st2->execute();
    $res2 = $st2->fetch(PDO::FETCH_ASSOC);

    if ($res2) {
        echo "<div id='detailsallocateleader'>";
        echo "<fieldset id='allocateleaderfieldset'>";
        echo "<legend id='allocateleaderlegend'>Project Details</legend>";
        echo "<ul id='ulallocateleader'>";
        echo "<li><label>Project ID: </label><span>" . $res2['project_id'] . "</span></li>";
        echo "<li><label>Project Title: </label><span>" . $res2['project_title'] . "</span></li>";
        echo "<li><label>Project Description: </label><span>" .$res2['project_description'] . "</span></li>";
        echo "<li><label>Customer Name: </label><span>" . $res2['customer_name']. "</span></li>";
        echo "<li><label>Project Budget: </label><span>" .$res2['budget'] . "</span></li>";
        echo "<li><label>Start Date: </label><span>" . $res2['start_date'] . "</span></li>";
        echo "<li><label>End Date: </label><span>" .$res2['end_date'] . "</span></li>";
        echo "<li><label>Created At: </label><span>" .$res2['created_at'] . "</span></li>";
        echo "</ul>";
        echo "</fieldset>";
        echo "</div>";
        echo "<div id='leader-submit'>";
        echo "<fieldset id='slctleadfiledsset'>";
        echo "<legend id='slctlegnedteamlead'>Select Team Leader</legend>";
        $info2 = "SELECT * FROM users WHERE Role = :type";
        $type = "Project Leader";
        $st3 = $pdo->prepare($info2);
        $st3->bindValue(":type", $type);
        $st3->execute();
        $res = $st3->fetchAll(PDO::FETCH_ASSOC);

           echo "<form method='post' action='setteamlead.php' id='slctleadform'>";
           echo "<input type='hidden' name='projectId' value='" . $res2['project_id'] . "'>";
           echo "<label for='selectedlead'>Select Team Leader:</label>";
           echo "<select name='selectedlead' id='selectedlead' required>";
foreach ($res as $rows) {
    echo "<option value='" . htmlspecialchars($rows['UserID']) . "'>" . $rows['Name']. " - " .$rows['UserID'] . "</option>";
}
echo "</select><br><br>";
echo "<input type='submit' name='confirms' value='Confirm Allocation'/>";
echo "</form>";
        echo "</fieldset>";
        echo "</div>";
        echo "<div id='support-doc'>";
        echo "<fieldset>";
        echo "<legend>Supporting Documents</legend>";
        $quer2 = "SELECT * FROM project_documents WHERE project_id = :projid";
        $st3 = $pdo->prepare($quer2);
        $st3->bindValue(":projid", $projid);
        $st3->execute();
        $ress3 = $st3->fetchAll(PDO::FETCH_ASSOC);

        if (!empty($ress3)) {
            echo "<ul>";
            foreach ($ress3 as $doc) {
                $docTitle = $doc['document_title'];
                $docPath = $doc['document_path'];
                $baseUrl = 'http://localhost/webprojects/ehab1211567';
                echo "<li><a href='" . $baseUrl . $docPath . "' target='_blank'>" . $docTitle . "</a></li>";
            }
            echo "</ul>";
        } else {
            echo "<p class='error'>No supporting documents attached for this project.</p>";
        }
        echo "</fieldset>";
        echo "</div>";
    } else {
        echo "<p class='error'>Invalid Project ID!</p>";
    }
} else {
    echo "<p class='error'>No Project ID provided!</p>";
}
?>

