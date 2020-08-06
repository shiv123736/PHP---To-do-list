<?php
$servername="localhost:3306";
$username="delhi36_shivi";
$password="8285171814@";
$database="delhi36_notes";

$conn = new mysqli($servername, $username, $password, $database);
if($conn->connect_error)
    die("error". $conn->error);




?>