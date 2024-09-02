<?php
include('connection.php');
session_start();

//if register button is clicked on
if(isset($_POST['registerBtn']))
{
     $firstname =  ( $_POST['firstname']);
     $lastname =  ( $_POST['lastname']);
     $username =  ( $_POST['username']);
     $password =  ( $_POST['password']);
     $usertype =  ( $_POST['usertype']);


     //add user type today

         $sql_query = "INSERT INTO login (firstname,lastname,username,password,usertype) VALUES ('$firstname', '$lastname', '$username', '$password', '$usertype' )" ;

         $sql_query_run = mysqli_query($con, $sql_query);

         if($sql_query_run){
             $_SESSION['message'] = "Registered Sucessfully";
             header("Location: login.php");
         }

         else{
            $_SESSION['message'] = "Error";
            header("Location: register.php");

         }
     


}
//if anyone else tries to enter page through link sends them to regiser page
else{
    header("Location: register.php");
}
?>