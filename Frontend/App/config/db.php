<?php 
$host = 'localhost'; // Database host 
$username = 'handyhelper'; // Database username
$password = 'admin123'; // Database password
$database = 'handyhelper'; // Database name
$conn = new mysqli ($host, $username, $password, $database); // Create connection

    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    } else {
        // echo "Connected successfully";   
    }
// Check connection