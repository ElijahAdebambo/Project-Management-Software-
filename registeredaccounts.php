<?php
//include('includes/header.php');
include('connection.php');



?>




<!DOCTYPE html>
<html>
<style>
table {
  font-family: arial, sans-serif;
  border-collapse: collapse;
  width: 70%;
  text-align: center;
  margin-left: 10%;

}

td, th {
  border: 1px solid ;
  text-align: center;
  padding: 8px;
}


</style>
<head>

    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" type="text/css" href="css/style.css">


<title>Registered Accounts  </title>

</head>

<body style = "background-color:#c8d5dd;">

<div class="header" style= "background-color: #c8d5dd;">
    <!--<a href="userhome.php" ><img src="images/cypershop.png" class="logo"/></a> -->

        <div class="navcontainer">
        <div class="header-right">
        <a href="adminhome.php">Admin Dashboard</a>  
           <a href="userhome.php">User Dashboard</a>    
            <a href="workitemanalytics.php">Analytics</a>
		<a href="developerpipeline.php">Pipeline</a>
		
            <a href="calender.php"> Calender</a>
            <a href="logout.php">Logout</a>
        </div>
        <div class= "header-bottom">
<h2>Registered Accounts</h2>
        </div>
    </div>
    
</div>


<h2>Accounts</h2>

    <title>Registered Accounts</title>
</head>
<body>

     <hr>

     <center> <h3> <b><?php  echo   $_SESSION["username"]
 ?> </b>is logged in</h3> </center> 



<table class="table table-bordered">
            <thead>
        <tr>
            <th>Username</th>
            <th>Password</th>
            <th>Usertype</th>

            <th>FirstName</th>
            <th>LastName</th>
            <th>Edit</th>
            <th>Delete</th>

    </tr>
        </thead>
        <tbody>
            <?php
            $query = "SELECT * FROM login";
            $query_run = mysqli_query($con, $query);

                if(mysqli_num_rows($query_run) > 0)
                {
                    foreach($query_run as $row)
                    {
                        ?>      
                            <tr>
                                <td><?= $row['username'] ?></td>
                                <td><?= $row['password'] ?></td>
                                <td><?= $row['usertype'] ?></td>
                                <td><?= $row['firstname'] ?></td>
                                <td><?= $row['lastname'] ?></td>
                                <!-- ?name fetcheds users first name-->
                                <td><button onclick="document.location='edit.php?id<?= $row['id']?>'">Edit</button></td>
                                <td><button onclick="document.location='delete.php?id<?= $row['id']?>'">Delete</button></td>
                                </tr>
                        <?php
                    }

             }
                else{
                    ?>
                    <tr>
                        <td colspan = "7"> error</td>
                    </tr>    
                    <?php
                }
            ?>

       
           

              

           

        </tbody>

        </table>
        <br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>

</body>
</html>

<?php

include('footer.php');
?>