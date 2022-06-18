<?php

require_once "../config.php";

//register users
function registerUser($fullnames, $email, $password, $gender, $country){
    //create a connection variable using the db function in config.php
    $conn = db();
   //check if user with this email already exist in the database
    if(!$conn){
        $conn = mysqli_connect($host, $user, $password, $db);
    }
    $sql = "SELECT email FROM Students WHERE email = '$email'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        header("Location:../forms/register.html");
         $conn->close();
        
    } else {
        $sql = "INSERT INTO Students (full_names, country, email, gender, password)
        VALUES ('$fullnames', '$country','$email','$gender', '$password')";

        if ($conn->query($sql) === TRUE) {
            $message = "User successfully Registered";
            echo $message;
             $conn->close();
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
             $conn->close();
        }
    }
}


//login users
function loginUser($email, $password){
    //create a connection variable using the db function in config.php
    $conn = db();

    echo "<h1 style='color: red'> LOG ME IN (IMPLEMENT ME) </h1>";
    //open connection to the database and check if username exist in the database
    if(!$conn){
        $conn = mysqli_connect($host, $user, $password, $db);
    }
    $userNamesql = "SELECT full_names FROM Students where email='$email'"; //check if user exists
    $queryResult = mysqli_query($conn, $userNamesql);
    if(mysqli_num_rows($queryResult)>0){
        $sql= "SELECT * FROM Students where email='$email'";
        $result=mysqli_query($conn,$sql);
        if(mysqli_num_rows($result)===1){
                $row= mysqli_fetch_assoc($result);
                //if it does, check if the password is the same with what is given
                if($row['email']==$email && $row['password']==$password){
                    //if true then set user session for the user and redirect to the dasbboard
                    session_start();
                    $_SESSION["username"] = $row["full_names"];
                    header("Location: ../dashboard.php");
                } else{
                    //redirect to login page
                    header("Location: ../forms/login.html");
                }
        $conn->close();
        }
    }else{
          //redirect to register page
          header("Location: ../forms/register.html");
    }
}


function resetPassword($email, $password){
    //create a connection variable using the db function in config.php
    $conn = db();
    echo "<h1 style='color: red'>RESET YOUR PASSWORD (IMPLEMENT ME)</h1>";
    //open connection to the database and check if username exist in the database
    if(!$conn){
        $conn = mysqli_connect($host, $user, $password, $db);
    }
    $sql = "SELECT full_names FROM Students where email='$email'";
    $queryResult = mysqli_query($conn, $sql);
    if(mysqli_num_rows($queryResult)>0){
        //if it does, replace the password with $password given
        $replaceSql = "UPDATE Students SET password = '$password' WHERE email = '$email'";
        if($conn->query($replaceSql) ===TRUE)
        {
            $message = "Record updated successfully";
            print($message);
        }else{
            $message = "Error in Updating the record";
            print($message); 
        }
    }
    else{
            $message = "User does not exist";
            print($message); 
    }
    
}

function getusers(){
    $conn = db();
    $sql = "SELECT * FROM Students";
    $result = mysqli_query($conn, $sql);
    echo"<html>
    <head></head>
    <body>
    <center><h1><u> ZURI PHP STUDENTS </u> </h1> 
    <table border='1' style='width: 700px; background-color: magenta; border-style: none'; >
    <tr style='height: 40px'><th>ID</th><th>Full Names</th> <th>Email</th> <th>Gender</th> <th>Country</th> <th>Action</th></tr>";
    if(mysqli_num_rows($result) > 0){
        while($data = mysqli_fetch_assoc($result)){
            //show data
            echo "<tr style='height: 30px'>".
                "<td style='width: 50px; background: blue'>" . $data['id'] . "</td>
                <td style='width: 150px'>" . $data['full_names'] .
                "</td> <td style='width: 150px'>" . $data['email'] .
                "</td> <td style='width: 150px'>" . $data['gender'] . 
                "</td> <td style='width: 150px'>" . $data['country'] . 
                "</td>
                <form action='action.php' method='post'>
                <input type='hidden' name='id'" .
                 "value=" . $data['id'] . ">".
                "<td style='width: 150px'> <button type='submit', name='delete'> DELETE </button>".
                "</tr>";
        }
        echo "</table></table></center></body></html>";
    }
    //return users from the database
    //loop through the users and display them on a table
}

 function deleteaccount($id){
     $conn = db();
     //delete user with the given id from the database
     $sql = "DELETE FROM Students WHERE id='$id'";

     if($conn->query($sql) === TRUE) {
        echo "Record deleted successfully";
     }else {
        echo "Error deleting record: " . $conn->error;
     }

     $conn->close();
 }
