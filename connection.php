<?php


$host = "localhost";
$username = "root";
$password = "";
$db = "fyp";


session_start();

 $con = mysqli_connect($host, $username, $password, $db);


$sql = "SELECT * FROM login";

$result = mysqli_query($con, $sql);

?>




