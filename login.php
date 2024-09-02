<?php





$host = "localhost";
$username = "root";
$password = "";
$db = "fyp";


session_start();

// $con is the variable name that holds database value in a query
$con = mysqli_connect($host, $username, $password, $db);





//Get the form values that were entered into the form
if(isset($_POST["username"])){
    $username =$_POST["username"];
}

if(isset($_POST["password"])){
    $password =$_POST["password"];
}

if(isset($_POST["usertype"])){
    $usertype =$_POST["usertype"];
}


$sql = "SELECT * FROM login WHERE username = '".$username."' AND password ='".$password."' ";

$result=mysqli_query($con, $sql);

//fetches the result row

$row = mysqli_fetch_array($result);

if ($row !== null) {
    if($row["usertype"] == "user") {   
        $_SESSION["username"] = $username;
        header("location:userhome.php");
    } elseif($row["usertype"] == "admin") {
        $_SESSION["username"] = $username;
        header("location:adminhome.php");
    } else {
        echo "username or password incorrect";
    }

}

?>




<!DOCTYPE html>
<html>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
<title>FYP Home  </title>
<link rel="stylesheet" type="text/css" href="css/style.css">

</head>

<body style="background-color:#c8d5dd;">

<div class="header">
   

        <div class="navcontainer">
        <div class="header-right">
            
            <a href="register.php">Register</a>
            <!--<a href="Report.php">Report</a> -->
            <a href="logout.php">Logout</a>
        </div>
        <div class= "header-bottom">
        <h2>Database Login FYP</h2>
        </div>
    </div>
    
</div>



     <!--<h2>Welcome to FYP</h2> -->

<center>
    <h1>Login </h1>
    <br><br><br>
    <!-- form action is the page name in which you want the form to take user to if sucessful-->
    <form action="login.php" method="POST">
    <div>
        <label>Username<label>
        <!--  REQUIRED means this section needs to be filled out-->
        <input type="text" placeholder= "Enter username" name="username" required>
    </div>
    <br><br>
    <div>
        <label>Password<label>
        
        <input type="password" placeholder= "Enter password"  name="password" required>
    </div>
    <br><br>
    <div>
        <input type="submit" name="login_btn" value= "Login">
    </div>
    </form>

</center>
<br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>

</html>

<?php
include('footer.php');

?>