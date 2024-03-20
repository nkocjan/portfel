<?php
session_start();
if(!isset($_SESSION["login"])){
    $_SESSION["message"] = "Nieautoryzowany dostęp";
    header("Location: ../login.php");
}else if ($_SESSION["czy_kategorie_set"] == 0) {
    header("Location: /portfel/kategorie-wybor.php");
}
else if(!isset($_POST["kategoria"])){
    $_SESSION["message"] = "Nieautoryzowany dostęp";
    header("Location: kategorie.php");
} else {
    require_once "../private/connectDB.php";
    try {
        $conn = new PDO("mysql:host=$servernameSQL;dbname=$dbnameSQL", $usernameSQL, $passwordSQL);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $conn->beginTransaction();

        $query = "SELECT * FROM wydatki WHERE login = :_login AND kategoria = :_kategoria";
        $statement = $conn->prepare($query);
        $statement->bindParam(":_login", $_SESSION["login"]);
        $statement->bindParam(":_kategoria", $_POST["kategoria"]);
        $statement->execute();
        $dane = $statement->fetchAll(PDO::FETCH_ASSOC);

        $query1 = "SELECT * FROM kategorie WHERE login  = :_login";
        $statement1 = $conn->prepare($query1);
        $statement1->bindParam(":_login", $_SESSION["login"]);
        $statement1->execute();
        $kategorieTAB = $statement1->fetchAll(PDO::FETCH_ASSOC);

        /** @noinspection DuplicatedCode */
        $kategorie = [];
        if ($kategorieTAB[0]["jedzenie"] == 1 && $_POST["kategoria"] !=  "jedzenie") {
            $kategorie[] = "jedzenie";
        }
        if ($kategorieTAB[0]["rodzina"] == 1 && $_POST["kategoria"] !=  "rodzina") {
            $kategorie[] = "rodzina";
        }
        if ($kategorieTAB[0]["przyszlosc"] == 1 && $_POST["kategoria"] !=  "przyszlosc") {
            $kategorie[] = "przyszlosc";
        }
        if ($kategorieTAB[0]["zdrowie"] == 1 && $_POST["kategoria"] !=  "zdrowie") {
            $kategorie[] = "zdrowie";
        }
        if ($kategorieTAB[0]["hobby"] == 1 && $_POST["kategoria"] !=  "hobby") {
            $kategorie[] = "hobby";
        }
        if ($kategorieTAB[0]["dom"] == 1 && $_POST["kategoria"] !=  "dom") {
            $kategorie[] = "dom";
        }
        if ($kategorieTAB[0]["podroze"] == 1 && $_POST["kategoria"] !=  "podroze") {
            $kategorie[] = "podroze";
        }
        if ($kategorieTAB[0]["inne"] == 1 && $_POST["kategoria"] !=  "inne") {
            $kategorie[] = "inne";
        }
    }
    catch (Exception $e){
        $_SESSION["message"] = $e->getMessage();
        $conn->rollBack();
        header("Location: kategorie.php");
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Kategorie</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous" />
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    <link href="../public/styles/kategorie-show.css" rel="stylesheet"/>
</head>
<body>
<div class="container-fluid">
    <div class="row flex-nowrap">
        <div class="bg-dark col-lg-2 col-auto min-vh-100 style-sidebar">
            <div class="bg-dark">
                <ul class="nav nav-pills flex-column mt-4 align-items-center">
                    <li class="nav-item">
                        <a href="/portfel" class="d-flex nav-link text-white text-decoration-none align-items-center">
                            <span class="material-symbols-outlined">Menu</span>
                            <div style="width: 10px"></div>
                            <span class="fs-4 d-none d-lg-inline">Portfel</span>
                        </a>
                    </li>
                    <li class="nav-item mt-3">
                        <a href="przychody.php" class="d-flex nav-link text-white text-decoration-none align-items-center">
                            <span class="material-symbols-outlined">trending_up</span>
                            <div style="width: 10px"></div>
                            <span class="d-none d-lg-inline">Przychody</span>
                        </a>
                    </li>
                    <li class="nav-item mt-3">
                        <a href="wydatki.php" class="d-flex nav-link text-white text-decoration-none align-items-center">
                            <span class="material-symbols-outlined">trending_down</span>
                            <div style="width: 10px"></div>
                            <span class="d-none d-lg-inline ml-2">Wydatki</span>
                        </a>
                    </li>
                    <li class="nav-item mt-3">
                        <a href="kategorie.php" class="d-flex nav-link text-white text-decoration-none align-items-center">
                            <span class="material-symbols-outlined text-active">category</span>
                            <div style="width: 10px"></div>
                            <span class="d-none d-lg-inline ml-2 text-active">Kategorie</span>
                        </a>
                    </li>
                    <li class="nav-item mt-3">
                        <a href="konto.php" class="d-flex nav-link text-white text-decoration-none align-items-center">
                            <span class="material-symbols-outlined">person</span>
                            <div style="width: 10px"></div>
                            <span class="d-none d-lg-inline ml-2">Konto</span>
                        </a>
                    </li>
                    <li class="nav-item mt-3">
                        <a href="../wyloguj.php" class="d-flex nav-link text-white text-decoration-none align-items-center">
                            <span class="material-symbols-outlined">logout</span>
                            <div style="width: 10px"></div>
                            <span class="d-none d-lg-inline ml-2">Wyloguj</span>
                        </a>
                    </li>
                </ul>
            </div>
        </div>


        <div class="col-lg-10 ">
            <div class="row"><div class="col-12 text-white"><h1>Wydatki na <?php echo $_POST["kategoria"]?></h1></div></div>
            <div class="row">
                <table class="table text-white style-table">
                    <thead>
                    <tr>
                        <th>Data</th>
                        <th>Wydatek</th>
                        <th>Kwota</th>
                        <th>Usuń</th>
                        <th>Kateogoria</th>
                        <th>Zmień kategorie</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    for ($i = 0; $i < count($dane); $i++) {
                        echo "<tr>";
                        echo '<form method="POST" action="../prv/prv_usun_przychod_wydatek.php">';
                        echo "<td>";
                        echo $dane[$i]["data"];
                        echo "</td>";
                        echo "<td>";
                        echo $dane[$i]["nazwa"];
                        echo "</td>";
                        echo "<td>";
                        echo $dane[$i]["kwota"] . " zł";
                        echo "</td>";
                        echo "<td>";
                        echo '<input type="hidden" name="nazwa" value="' . $dane[$i]["nazwa"] . '" />';
                        echo '<input type="hidden" name="kwota" value="' . $dane[$i]["kwota"] . '" />';
                        echo '<input type="hidden" name="data" value="' . $dane[$i]["data"] . '" />';
                        echo '<input type="hidden" name="type" value="wydatek" />';
                        echo '<button type="submit" name="przycisk_usun" value="przycisk' . $i . '">Usuń</button></td>';
                        echo "</form>";
                        echo '<form method="POST" action="../prv/prv_zmien_kategorie.php">';
                        echo "<td>";
                        echo "<select name='kategoria'>";
                        echo "<option>".$kategorie[0]."</option>";
                        echo "<option>".$kategorie[1]."</option>";
                        echo "<option>".$kategorie[2]."</option>";
                        echo "<option>".$kategorie[3]."</option>";
                        echo "</td>";
                        echo "<td>";
                        echo '<input type="hidden" name="nazwa" value="' . $dane[$i]["nazwa"] . '" />';
                        echo '<input type="hidden" name="kwota" value="' . $dane[$i]["kwota"] . '" />';
                        echo '<input type="hidden" name="data" value="' . $dane[$i]["data"] . '" />';
                        echo '<button type="submit" name="przycisk_zmien" value="przycisk' . $i . '">Zmień kategorie</button></td>';
                        echo "</td>";
                        echo "</form>";
                        echo "</tr>";
                    }
                    ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>


</body>
</html>
