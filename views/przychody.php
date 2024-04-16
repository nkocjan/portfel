<?php
session_start();
if (!isset($_SESSION["login"])) {
  header("Location: /portfolio/portfel/login.php");
}else if ($_SESSION["czy_kategorie_set"] == 0) {
    header("Location: /portfolio/portfel/kategorie-wybor.php");
}else {

    require_once "../private/connectDB.php";
    try {
        $conn = new PDO("mysql:host=$servernameSQL;dbname=$dbnameSQL", $usernameSQL, $passwordSQL);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $conn->beginTransaction();

        $query = "SELECT * FROM przychody WHERE login = :_login";
        $statement1 = $conn->prepare($query);
        $statement1->bindParam(":_login", $_SESSION["login"]);
        $statement1->execute();
        $dane = $statement1->fetchAll(PDO::FETCH_ASSOC);
        if (!count($dane)) {
            $_SESSION["message"] = "Nie wczytano żadnych przychodów";
        }
    } catch (Exception $e) {
        $conn->rollBack();
        $_SESSION["message"] = $e->getMessage();
    }
    $conn = null;

// ### COMPARE 2 DATAS ### //
    function compare_data($string1, $string2)
    {
        $data1 = new DateTime($string1);
        $data2 = new DateTime($string2);

        if ($data1 < $data2) {
            return -1;              // DATA 1 jest wcześniejsza
        } else if ($data1 > $data2) {
            return 1;
        } else {
            return 1;
        }
    }

// ### POSEGREGOWANIE DATAMI OD NAJNOWSZEJ DO NAJSTARSZEJ ### //

    for ($i = 0; $i < count($dane); $i++) {
        for ($j = 1; $j < count($dane) - $i; $j++) {
            if (compare_data($dane[$j - 1]["data"], $dane[$j]["data"]) == -1) {
                $tmp = $dane[$j - 1];
                $dane[$j - 1] = $dane[$j];
                $dane[$j] = $tmp;
            }
        }
    }

}

//echo $dane[0]["kwota"];
//echo $dane[1]["kwota"];
//echo $dane[2]["kwota"]; 
//echo count($dane);    ->  3
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Przychody</title>
  <link href="../public/styles/przychody.css" rel="stylesheet" />
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous" />
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous">
  </script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</head>

<body style="background-color: #343a40">
  <div class="container-fluid">
    <div class="row flex-nowrap">
      <div class="bg-dark col-lg-2 col-auto min-vh-100 style-sidebar">
        <div class="bg-dark">
          <ul class="nav nav-pills flex-column mt-4 align-items-center">
            <li class="nav-item">
              <a href="../index.php" class="d-flex nav-link text-white text-decoration-none align-items-center">
                <span class="material-symbols-outlined">Menu</span>
                <div style="width: 10px"></div>
                <span class="fs-4 d-none d-lg-inline">Portfel</span>
              </a>
            </li>
            <li class="nav-item mt-3">
              <a href="przychody.php" class="d-flex nav-link text-white text-decoration-none align-items-center">
                <span class="material-symbols-outlined text-active">trending_up</span>
                <div style="width: 10px"></div>
                <span class="d-none d-lg-inline text-active">Przychody</span>
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
                <span class="material-symbols-outlined">category</span>
                <div style="width: 10px"></div>
                <span class="d-none d-lg-inline ml-2">Kategorie</span>
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
      <div class="col-lg-10 col mt-4 text-align-center">
        <form method="post" action="../prv/prv_dodaj_przychod.php">
          <div class="row">
            <div class="col-lg-2 col-md-4 col-sm-6 text-white mt-md-3 mt-sm-3 mt-2 text-align-center fw-bolder">
              <h6>Nowy wpływ</h6>
            </div>
            <div class="col-lg-2 col-md-4 col-sm-6 text-align-center mt-md-3 mt-sm-3 mt-2">
                <label>
                    <input type="text" placeholder="nazwa" class="style-width text-align-center" name="nazwa" />
                </label>
            </div>
            <div class="col-lg-2 col-md-4 col-sm-6 text-align-center mt-md-3 mt-sm-3 mt-2">
                <label>
                    <input class="style-width text-align-center" type="number" placeholder="kwota" name="kwota" />
                </label>
            </div>
            <div class="col-lg-2 col-md-4 col-sm-6 text-align-center mt-md-3 mt-sm-3 mt-2">
                <label>
                    <input type="date" class="style-width text-align-center" name="data" />
                </label>
            </div>
            <div class="col-lg-2 col-md-4 col-sm-6 text-align-center mt-md-3 mt-sm-3 mb-3 mt-2">
              <button type="submit" class="style-width">Dodaj</button>
            </div>
          </div>
        </form>
        <div class="row mb-5"></div>
        <div class="row mb-4">
          <div class="col-12 mt-5" style="overflow-x: auto">
            <div class="table-responsive" style="max-height: 200px; overflow-y: auto">
              <table class="table text-white style-table">
                <thead>
                  <tr>
                    <th>Data</th>
                    <th>Rodzaj</th>
                    <th>Wpływ</th>
                    <th>Usuń</th>
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
                    echo '<input type="hidden" name="type" value="przychod" />';
                    echo '<button type="submit" name="przycisk" value="przycisk' . $i . '">Usuń</button></td>';
                    echo "</form>";
                    echo "</tr>";
                  }
                  ?>
                </tbody>
              </table>
            </div>
          </div>
        </div>
        <div class="row mb-5"></div>
        <div class="row mb-5"></div>
        <div class="row text-white style-border" style="margin-left: 6px; margin-right: 6px">
          <div class="col-lg-4 col-12 mt-lg-4 mt-3"> Wpływy w ciągu ostatniego roku: <br />

            <?php
            $ostatni_rok = 0;
            $start_date = (new DateTime())->modify("-1 year");

            for ($i = 0; $i < count($dane); $i++) {
              $date_to_check = new DateTime($dane[$i]["data"]);
              if ($date_to_check >= $start_date) {
                $ostatni_rok += (int) $dane[$i]["kwota"];
              }
            }
            echo $ostatni_rok . "zł";
            ?>

          </div>
          <div class="col-lg-4 col-12 mt-lg-4 mt-3 mb-3"> Wpływy w ciągu ostatniego miesiąca: <br />

            <?php
            $ostatni_miesiac = 0;
            $start_date = (new DateTime())->modify("-1 month");

            for ($i = 0; $i < count($dane); $i++) {
              $date_to_check = new DateTime($dane[$i]["data"]);
              if ($date_to_check >= $start_date) {
                $ostatni_miesiac += (int) $dane[$i]["kwota"];
              }
            }
            echo $ostatni_miesiac . "zł";
            ?>

          </div>
          <div class="col-lg-4 col-12 mt-lg-4 mb-4"> Wpływy w ciągu ostatniego tygodnia: <br />

            <?php
            $ostatni_tydzien = 0;
            $start_date = (new DateTime())->modify("-1 week");

            for ($i = 0; $i < count($dane); $i++) {
              $date_to_check = new DateTime($dane[$i]["data"]);
              if ($date_to_check >= $start_date) {
                $ostatni_tydzien += (int) $dane[$i]["kwota"];
              }
            }
            echo $ostatni_tydzien. "zł";
            ?>

          </div>
        </div>
        <form method="POST" action="../prv/prv_pokaz_kwoty_data.php" style="border: none;">
          <div class="row text-white mb-5">
            <div class="col-lg-2 col-md-4 col-12 mt-4">Wpływy od</div>
            <div class="col-lg-2 col-md-4 col-12 mt-4">
                <label>
                    <input type="date" class="text-align-center" name="data_od" />
                </label>
            </div>
            <div class="col-lg-1 col-md-4 col-12 mt-4">do</div>
            <div class="col-lg-2 col-md-4 col-12 mt-4">
                <label>
                    <input type="hidden" class="text-align-center" name="type" value="przychod" />
                </label>
                <label>
                    <input type="date" class="text-align-center" name="data_do" />
                </label>
            </div>
            <div class="col-lg-2 col-md-4 col-12 mt-4">
              <button type="submit">Wyświetl</button>
            </div>
            <div class="col-lg-2 col-md-4 col-12 mt-4">
              <?php
              if (isset($_SESSION["kwota_do_wyswietlenia_przychody"])) {
                echo $_SESSION["kwota_do_wyswietlenia_przychody"];
                unset($_SESSION["kwota_do_wyswietlenia_przychody"]);
              } else {
                echo "0 ";
              }
              ?>
              zł</div>

          </div>
        </form>
        <div class="row" style="height: 20px"></div>
      </div>
    </div>
  </div>



  <!-- ### okienko powiadomienia error -->
  <div class="toast fade 
  <?php
  if ($_SESSION["message"]) {
    echo "show";
  }
  ?>
  " style="position: fixed;top: 5px;right: 5px;
    <?php
    if (!$_SESSION["message"]) {
        echo "width: 0px;";
    }
    ?>
">
    <div class="toast-header">
      <strong class="me-auto">Wiadomość</strong>
      <button type="button" class="btn-close" data-bs-dismiss="toast"></button>
    </div>
    <div class="toast-body">
      <p>
        <?php
        echo $_SESSION["message"];
        $_SESSION["message"] = false;
        ?>
      </p>
    </div>
  </div>
</body>

</html>