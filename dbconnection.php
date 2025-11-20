<?php
$servername = "localhost";  
$username   = "root";       
$password   = "";           
$dbname     = "school_db";  

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    // Stop script if may error
    die("Connection failed: " . $conn->connect_error);
}

?>

