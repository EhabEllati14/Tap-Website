<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once("dbconfig.php");

if (!isset($_SESSION['role'])||!isset($_SESSION['logged'])||!isset($_SESSION['userid'])) {
  //we check if the user is already logged to the system and ust be manager
header("Location: login.php");
exit();
}

if ($_SESSION['role'] !== "Manager") {
  //if the rol is manager
  header("Location: dashboard.php");
    exit();
}
?>
<h1 id='allocateleaderh1'>List of Projects Without Assigned Team Leaders:</h1>
  <table  id='allocateleadertable'>
    <tr>
      <th>Project Id</th>
      <th>Project Title</th>
      <th>Start Date</th>
      <th>End Date</th>
      <th>Action</th>
    </tr>
    <?php
    $querys="SELECT * FROM projects WHERE team_leader_id IS NULL ORDER BY start_date ASC;";
    $st1=$pdo->prepare($querys);
    $st1->execute();
    $ress=$st1->fetchAll(PDO::FETCH_ASSOC);
    foreach($ress as $proj_without_teamLead){
    echo "<tr>";
    echo "<td>".$proj_without_teamLead['project_id']. "</td>";
    echo "<td>".$proj_without_teamLead['project_title']. "</td>";
    echo "<td>".$proj_without_teamLead['start_date']. "</td>";
    echo "<td>".$proj_without_teamLead['end_date']. "</td>";
    echo "<td><a href='dashboard.php?page=selectallocateteamlead&projectId=".$proj_without_teamLead['project_id']."'>[Allocate Team Leader]</a></td>";
    echo "</tr>";
    }
    ?>
  </table>
