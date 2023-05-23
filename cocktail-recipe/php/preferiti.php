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

$username = $_SESSION['username'];
$id = $_SESSION['id_user'];

// visualizzare i coktail preferiti dell'utente
$preferiti = "SELECT * FROM preferiti WHERE utente_id = '$id'";
$res = mysqli_query($conn, $preferiti);

$a = 0;
$JsonArray = [];

for ($j = 0; $j < mysqli_num_rows($res); $j++) {
    while ($row = mysqli_fetch_assoc($res)) {

        $idCocktail = $row['cocktail_id'];
        $curl = curl_init();

        curl_setopt_array($curl, [
            CURLOPT_URL => "https://the-cocktail-db.p.rapidapi.com/lookup.php?i=$idCocktail",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_HTTPHEADER => [
                "X-RapidAPI-Host: the-cocktail-db.p.rapidapi.com",
                "X-RapidAPI-Key:YOUR_API_KEY"
            ],
        ]);

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            echo "cURL Error #:" . $err;
        } else {
            $result = json_decode($response);
            array_push($JsonArray, $result);

            $response = null;
        }
        $a++;
    }
}

?>

<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>preferiti</title>
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet" />
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700&display=swap" rel="stylesheet" />
    <!-- MDB -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/6.3.0/mdb.min.css" rel="stylesheet" />
    <style>
        .card {
            margin-bottom: 20px;
        }

        .navbar {
            margin-bottom: 20px;
        }
    </style>
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container-fluid">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <a class="btn text-white" style="background-color: #333333; margin-right: 10px;"
                        href="dashboard.php" role="button">
                        <i class="fas fa-arrow-left"></i>
                    </a>
                </li>
            </ul>
        </div>
    </nav>

    <div class="container text-center">
        <?php

        // array = null
        if ($JsonArray == null) {
            echo "<div class='row'>";
            echo "<div class='col-sm'>";
            echo "<h1>Non ci sono risultati</h1>";
            echo "</div>";
            echo "</div>";
        } else {
            // numero di elementi all'interno dell'array
            $numero_drink = count($JsonArray);

            // per adattare le righe in base al numero di drink
            $num_rows = ceil($numero_drink / 3);
            $j = 0;

            while (0 < $num_rows) {
                echo "<div class='row'>";
                for ($i = 0; $i < 3; $i++) {
                    if ($j == $numero_drink) {
                        // se non ci sono più drink da visualizzare
                        echo "<div class='col-sm'>";
                        echo "<div class='card' style='width: 20rem; border-color: white;'>";
                        echo "</div>";
                        echo "</div>";
                        break;
                    }
                    echo "<div class='col-sm'>";
                    echo "<div class='card' style='width: 20rem;'>";
                    echo "<img src='" . $JsonArray[$j]->drinks[0]->strDrinkThumb . "' class='card-img-to   alt='...'>";
                    echo "<div class='card-body'>";
                    echo "<h5 class='card-title'>" . $JsonArray[$j]->drinks[0]->strDrink . "</h5>";
                    echo "<p class='card-text'>" . $JsonArray[$j]->drinks[0]->strAlcoholic . "</p>";
                    echo "<a href='./cocktail-information.php?idDrink=" . $JsonArray[$j]->drinks[0]->idDrink . "' class='btn btn-primary'>Dettagli</a>";
                    echo "</div>";
                    echo "</div>";
                    echo "</div>";
                    $j++;
                }
                echo "</div>";
                $num_rows--;
            }

        }

        ?>
    </div>

    </div>
        <!-- MDB -->
        <script type="text/javascript"
            src="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/6.3.0/mdb.min.js"></script>
</body>

</html>
