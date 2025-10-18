<?php
$host = "142.93.26.2";
$user = "jdc19t";   // new user
$password = "Azulverde_43!"; // the password you set
$database = "ELOG_INV";

$conn = new mysqli($host, $user, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
<