<?php
/*
if (isset($_POST['btn_search'])) {
    print_r($_POST);
    exit();
}*/
session_start();
$api_key = getenv('API_KEY');

$username = $_SESSION['username'];
$id = $_SESSION['id_user'];

// chiamata api per i json dei cocktail 
$curl = curl_init();

curl_setopt_array($curl, [
    CURLOPT_URL => "https://the-cocktail-db.p.rapidapi.com/popular.php",
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

// isset che si attiva quando la pagina viene ricaricata
if (isset($_POST['btn_search'])) {
    $search = $_POST['search'];

    if (empty($search)) {
        header('Location: dashboard.php');
    }

    $curl = curl_init();

    curl_setopt_array($curl, [
        CURLOPT_URL => "https://the-cocktail-db.p.rapidapi.com/search.php?s=$search",
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

    //echo $response;
}


?>

<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Dashboard</title>
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
    <nav class="navbar sticky-top navbar-expand-lg navbar-light bg-light">
        <div class="container-fluid">
            <a class="navbar-brand mt-2 mt-lg-0" href="#">
                <img src="../img/app-logo.png" height="50" alt="app Logo"
                    loading="lazy" />
            </a>
            <button class="navbar-toggler" type="button" data-mdb-toggle="collapse"
                data-mdb-target="#navbarTogglerDemo01" aria-controls="navbarTogglerDemo01" aria-expanded="false"
                aria-label="Toggle navigation">
                <i class="fas fa-bars"></i>
            </button>
            <div class="collapse navbar-collapse" id="navbarTogglerDemo01">
                <a class="navbar-brand" href="dashboard.php">Cocktail database</a>
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="btn btn-outline-secondary" data-mdb-ripple-color="dark" href="casuale.php"
                            role="button" style="margin-right: 10px;">cocktail casuale
                            <i class="fas fa-dice"></i>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="btn btn-outline-secondary" data-mdb-ripple-color="dark" href="preferiti.php"
                            role="button" style="margin-right: 10px;">Salvati

                        </a>
                    </li>
                </ul>
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <form class="d-flex input-group w-auto" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>"
                        role="search">
                        <input class="form-control" name=" search" type="search" placeholder="Search"
                            aria-label="Search">
                        <button class="btn btn-outline-primary" data-mdb-ripple-color="dark" name="btn_search"
                            type="submit">Search</button>
                    </form>
                </ul>

                <a class="btn text-white" style="background-color: #333333; margin-right: 10px;" href="https://github.com/fasolinidavide/cocktail-recipe"
                    role="button"> <i class="fab fa-github"></i>
                </a>

                <a href="../index.html"><button style="margin-right: 10px;" type="button"
                        class="btn btn-primary">Login</button>
                </a>

                <a href="../register.html"><button style="margin-right: 10px;" type="button"
                        class="btn btn-info">Registrati</button>
                </a>

                <!-- Avatar -->
                <div class="dropdown">
                    <a class="dropdown-toggle d-flex align-items-center hidden-arrow" href="#"
                        id="navbarDropdownMenuAvatar" role="button" data-mdb-toggle="dropdown" aria-expanded="false">
                        <img src="../img/profile-img.png" class="rounded-circle" height="25" loading="lazy" />
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdownMenuAvatar">
                        <li>
                            <p class="dropdown-item"><b>
                                    <?php echo $username; ?>
                                </b></p>
                        </li>

                        <li>
                            <a class="dropdown-item" href="logout.php">Logout</a>
                        </li>
                    </ul>
                </div>
            </div>

        </div>
    </nav>

    <div class="container text-center">
        <?php
        $result = json_decode($response);

        if ($result->drinks == null) {
            echo "<div class='row'>";
            echo "<div class='col-sm'>";
            echo "<h1>Non ci sono risultati</h1>";
            echo "</div>";
            echo "</div>";
        } else {
            $numero_drink = count($result->drinks);
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
                    echo "<img src='" . $result->drinks[$j]->strDrinkThumb . "' class='card-img-to   alt='...'>";
                    echo "<div class='card-body'>";
                    echo "<h5 class='card-title'>" . $result->drinks[$j]->strDrink . "</h5>";
                    echo "<p class='card-text'>" . $result->drinks[$j]->strCategory . "</p>";
                    echo "<a href='./cocktail-information.php?idDrink=" . $result->drinks[$j]->idDrink . "' class='btn btn-primary'>Dettagli</a>";
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
    <!-- MDB -->
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/6.3.0/mdb.min.js"></script>
</body>

</html>
