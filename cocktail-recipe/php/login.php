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

if (empty($username) || empty($password)) {
    $msg = 'Inserisci username e password %s';
} else {
    $query = "SELECT id,username, password FROM users WHERE username = '$username' AND password = '$hashedPassword'";

    $result = mysqli_query($conn, $query);

    if (!$result) {
        die('Error:' . mysqli_error($conn));
    }
    $record = mysqli_fetch_array($result);

    if ($record != null) {
        session_regenerate_id();
        $_SESSION['session_id'] = session_id();
        $_SESSION['username'] = $record['username'];
        $_SESSION['id_user'] = $record['id'];


        header('Location: dashboard.php');
    } else {
        echo "Credenziali errate";
        header('Location: ../index.html');
    }
}

mysqli_close($conn);