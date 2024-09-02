<?php
session_start();

?>
<!DOCTYPE html>
<html>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" type="text/css" href="css/style.css">
<title>Register  </title>

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
<h2>Register</h2>
        </div>
    </div>
    
</div>

    <title>Register</title>
</head>
<body>
    <h1> Register page</h1>

    <form action= "registercode.php" method="POST">
        <div class= "form-group1">
            <label>First Name</label>
            <input type="text" name="firstname" placeholder= "Enter First Name" class="form-control">
        </div>
        <br>
        <div class= "form-group1">
            <label>Last Name</label>
            <input type="text" name="lastname" placeholder= "Enter Last Name" class="form-control">
        </div> 
        <br>
        <div class= "form-group1">
            <label>Username</label>
            <input type="text" name="username" placeholder= "Enter Username" class="form-control">
        </div>
        <br>
         <div class= "form-group1">
            <label>Password</label>
            <input type="text" name="password"  placeholder= "Enter First Password" class="form-control">
        </div>
        <br>
        <div class= "form-group1">
            <label>Usertype</label>
            <select name="usertype" id="usertype"  class="form-control">Admin
            <option value="admin">Admin</option>
            <option value="user">User</option>
            </select>

        </div>
        <br>
        <div class="form-group1">
            <button type="submit" name="registerBtn">Register Now</button>
        </div>
    <form>
        

    <br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>

    <?php
include('footer.php');

?>