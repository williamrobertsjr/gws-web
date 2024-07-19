<?php  

$servername = "localhost";
$username = "nzgyhzgduk"; 
$password = "95sW9Nn9st"; 
$dbname = "nzgyhzgduk"; 

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
$conn->set_charset("utf8mb4");  // Ensure the connection charset is set to utf8mb4

// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}
// echo "Connected successfully";



?>