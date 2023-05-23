<?php
session_start();

$servername = "localhost";
$database = "my_fasolinidavideh";
$username = "fasolinidavideh";
$password = "";

// Create connection2
$conn = new mysqli($servername, $username, $password, $database);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
$id = $_SESSION['id_user'];
$_SESSION['id_user'];

$curl = curl_init();

curl_setopt_array($curl, [
    CURLOPT_URL => "https://the-cocktail-db.p.rapidapi.com/randomselection.php",
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => "",
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 30,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => "GET",
    CURLOPT_HTTPHEADER => [
        "X-RapidAPI-Host: the-cocktail-db.p.rapidapi.com",
        "X-RapidAPI-Key: a1899d0f26msh5e3acbc429b2c71p1fe414jsne0ea092fbbb1"
    ],
]);

$response = curl_exec($curl);
$err = curl_error($curl);


$randomNumber = rand(0, 9);

$result = json_decode($response);

$idDrink = $result->drinks[$randomNumber]->idDrink;

curl_close($curl);
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
        .image-info {
            display: flex;
            justify-content: center;
            align-items: center;
            margin-top: 70px;
        }

        .description-info {
            display: flex;
            justify-content: left;
            align-items: left;
            margin-top: 40px;
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

    <?php
            

            $nome_video = "how+to+make" . $result->drinks[0]->strDrink . "+drink+tutorial";

            curl_setopt_array($curl, [
                CURLOPT_URL => "https://youtube-search-results.p.rapidapi.com/youtube-search/?q=$nome_video",
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => "",
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 30,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => "GET",
                CURLOPT_HTTPHEADER => [
                    "X-RapidAPI-Host: youtube-search-results.p.rapidapi.com",
                    "X-RapidAPI-Key: a1899d0f26msh5e3acbc429b2c71p1fe414jsne0ea092fbbb1"
                ],
            ]);

            $response_video = curl_exec($curl);
            $err = curl_error($curl);

            curl_close($curl);

            $result_video = json_decode($response_video);

            ?>
    
   	<div class="container">
   		<div class='image-info'>
        	<?php echo "<img src='" . $result->drinks[0]->strDrinkThumb . "' class='img-fluid' alt='img'>" ?>
        </div>
        
        <div>
        	 <div class='description-info'>
                <div class="card  w-100" style="width: 18rem;">
                    <ul class="list-group list-group-light">
                        <li class="list-group-item px-3">
                            <?php echo "<h2>Nome:  " . $result->drinks[0]->strDrink . "</h2>"; ?>

                            <form method="post" action="add_preferiti.php?id_cocktail=<?php echo $idDrink; ?>">
                            <?php
                                $check = "SELECT * FROM preferiti WHERE utente_id ='$id' AND cocktail_id = '$idDrink'";
                                $res = $conn->query($check);

                                if ($res->num_rows > 0) {
                                    echo "<button class='btn btn-outline-secondary' data-mdb-ripple-color='dark'
                                    role='button' type='submit'><i class='fas fa-star'></i></button>";
                                } else {
                                    echo "<button class='btn btn-outline-secondary' data-mdb-ripple-color='dark'
                                    role='button' type='submit'><i class='far fa-star'></i></button>";
                                }
                                ?>
                            </form>
                        </li>
                        <li class="list-group-item px-3">
                            <?php echo "<h5>descrizione:</h5> <p>" . $result->drinks[0]->strInstructionsIT . "</p>"; ?>
                        </li>
                        <li class="list-group-item px-3">
                            <h5>ingredienti:</h5>
                            <ul>
                                <?php
                                for ($i = 1; $i < 15; $i++) {
                                    if ($result->drinks[0]->{"strIngredient" . $i} == null)
                                        break;

                                    echo "<li>";
                                    echo $result->drinks[0]->{"strIngredient" . $i} . " - " . $result->drinks[0]->{"strMeasure" . $i};
                                    echo "</li>";
                                }
                                ?>
                            </ul>
                        </li>
                        <li class="list-group-item px-3">
                            <?php echo "<h5>bicchiere utilizzato:</h5> <p>" . $result->drinks[0]->strGlass . "</p>"; ?>
                        </li>
                        <li class="list-group-item px-3">
                            <iframe src="<?php echo "https://www.youtube.com/embed/" . $result_video->items[0]->id; ?>"
                                width="100%" height="280" style="border:1px solid black;" allowfullscreen scrolling="no"
                                allow="accelerometer; clipboard-write; encrypted-media; gyroscope; picture-in-picture;">
                            </iframe>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

        <script type="text/javascript"
            src="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/6.3.0/mdb.min.js"></script>
</body>

</html>
