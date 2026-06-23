<?php
$servername = "localhost";
$username = "root";
$password = "4312";
$database = "digital_library";

$conn = mysqli_connect($servername, $username, $password, $database);
if (!$conn){
    die("Connection Faild: ".mysqli_connect_error());
}
?>  