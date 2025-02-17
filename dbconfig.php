<?php
$dbnameandhost="mysql:host=localhost;dbname=tap";
$dbusername="root";
$dbpassword="";
try{
$pdo= new PDO($dbnameandhost,$dbusername,$dbpassword);
$pdo->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
}
catch(PDOException $ex){
  die("Connection failed :". $ex->getMessage());
}
?>