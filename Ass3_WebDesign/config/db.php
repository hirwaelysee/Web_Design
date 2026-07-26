<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "user_profile_system";

// Connection with database created
$conn = mysqli_connect($servername,$username,$password,$dbname);

//Check Connection if it is valid
if($conn->connect_error){
    die("connection failed:" . $conn->connect_error);
}

//this tells mysql to use the UTF-8 character encoding for the connection.
mysqli_set_charset($conn,"utf8");

?>