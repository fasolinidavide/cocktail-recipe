<?php

session_start();

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

$id_cocktail = $_GET['id_cocktail'];
$id = $_SESSION['id_user'];

$check = "SELECT * FROM preferiti WHERE utente_id ='$id' AND cocktail_id = '$id_cocktail'";
$res = $conn->query($check);

if ($res->num_rows > 0 ) {
    // eliminare il cocktail dai preferiti nel db
    $delete = "DELETE FROM preferiti WHERE utente_id = '$id' AND cocktail_id = '$id_cocktail'";
    echo $delete;
    $result = $conn->query($delete);
    header('Location: cocktail-information.php?idDrink=' . $id_cocktail . '');
} else {
    // aggiungi il cocktail ai preferiti nel db
    $add = "INSERT INTO preferiti (utente_id, cocktail_id) VALUES ('$id', '$id_cocktail')";
    echo $add;
    $result = $conn->query($add);
    header('Location: cocktail-information.php?idDrink=' . $id_cocktail . '');
}

?>