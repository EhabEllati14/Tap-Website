<?php
require_once("dbconfig.php");
?>
<?php
if (!isset($_SESSION['role']) || !isset($_SESSION['logged']) || !isset($_SESSION['userid'])) {
  header("Location: login.php");
  exit();
}
if ($_SESSION['role'] !== "Manager") {
  header("Location: dashboard.php");
  exit();
}
$query1 = "SELECT * FROM projects;";
$stmts = $pdo->prepare($query1);
$stmts->execute();
$res = $stmts->fetchAll(PDO::FETCH_ASSOC);
echo "<div class='headsmang'><img id='handsmanager' src='waveHand.png' alt='wavehand_welcoming'><h2 id='welcomesmang'>Welcome Manager ".$_SESSION['usernamelog']."</h2></div>";
echo "<table class='manager_dash'>";
echo "<caption>Project Details</caption>";
echo "<thead>";
echo "<tr>";
echo "<th>Project ID's</th>";
echo "<th>Project Title</th>";
echo "<th>Project Description</th>";
echo "<th>Customers Name</th>";
echo "<th>Budgets</th>";
echo "<th>Start Date</th>";
echo "<th>End Date</th>";
echo "</tr>";
echo "</thead>";
echo "<tbody>";
foreach ($res as $projs) {
    echo "<tr>";
    echo "<td>" .$projs['project_id']."</td>";
    echo "<td>" .$projs['project_title']."</td>";
    echo "<td>" . $projs['project_description']."</td>";
    echo "<td>" .$projs['customer_name']."</td>";
    echo "<td>" .$projs['budget']."</td>";
    echo "<td>" . $projs['start_date']."</td>";
    echo "<td>" .$projs['end_date']. "</td>";
    echo "</tr>";
}
echo "</tbody>";
echo "</table>";
?>
