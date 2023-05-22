<?php
session_start();

ini_set('display_errors', 1);
ini_set('display_sturtup_errors', 1);
error_reporting(E_ALL);


$servername = "localhost";
$database = "cocktail_db";
$username = "root";
$password = "";

// Create connection2
$conn = new mysqli($servername, $username, $password, $database);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$username = $_POST['username'] ?? '';
$password = $_POST['password'] ?? '';
$hashedPassword = md5($password);

// controllare se l'utente esiste già
$query = "SELECT id,username, password FROM users WHERE username = '$username'";
$result = mysqli_query($conn, $query);
$record = mysqli_fetch_array($result);

if ($record != null) {
    echo "Utente già registrato";
    header('Location: ../register.html');
}else if (empty($username) || empty($password)) {
    $msg = 'Inserisci username e password %s';
} else{
    $query = "INSERT INTO users(username, password) VALUES ('$username', '$hashedPassword')";

    $result = mysqli_query($conn, $query);

    header('Location: ../index.html');
}
