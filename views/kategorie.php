<?php
session_start();
if (!isset($_SESSION["error"])) {
  $_SESSION["error"] = "";
}
if (!isset($_SESSION["message"])) {
  $_SESSION["message"] = false;
}
if (!isset($_SESSION["login"])) {
  $_SESSION["message"] = "Musisz się zalogować";
  header("Location: /portfel/login.php");
} else if ($_SESSION["czy_kategorie_set"] == 0) {
  $_SESSION["message"] = "Musisz najpierw wybrać kategorie";
  header("Location: /portfel/kategorie-wybor.php");
} else {
  require_once "../private/connectDB.php";
  try {
    $conn = new PDO("mysql:host=$servernameSQL;dbname=$dbnameSQL", $usernameSQL, $passwordSQL);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $query = "SELECT * FROM kategorie WHERE login = :login";
    $statement = $conn->prepare($query);
    $statement->bindParam(":login", $_SESSION["login"]);
    $statement->execute();
    $dane = $statement->fetchAll(PDO::FETCH_ASSOC);

    $query1 = "SELECT * FROM wydatki WHERE login = :_login";
    $statement1 = $conn->prepare($query1);
    $statement1->bindParam(":_login", $_SESSION["login"]);
    $statement1->execute();
    $dane_wydatki = $statement1->fetchAll(PDO::FETCH_ASSOC);
    $kat_wydatki = [];
    for($i=0;$i<8;$i++){
        $kat_wydatki[$i] = 0;
    }
          /** @noinspection DuplicatedCode */
      for($i = 0; $i < count($dane_wydatki); $i++){
          if($dane_wydatki[$i]["kategoria"] == "jedzenie"){
              $kat_wydatki[0] = $kat_wydatki[0] + $dane_wydatki[$i]["kwota"];
          } else if($dane_wydatki[$i]["kategoria"] == "rodzina"){
              $kat_wydatki[1] = $kat_wydatki[1] + $dane_wydatki[$i]["kwota"];
          }else if($dane_wydatki[$i]["kategoria"] == "przyszlosc"){
              $kat_wydatki[2] = $kat_wydatki[2] + $dane_wydatki[$i]["kwota"];
          }else if($dane_wydatki[$i]["kategoria"] == "hobby"){
              $kat_wydatki[3] = $kat_wydatki[3] + $dane_wydatki[$i]["kwota"];
          }else if($dane_wydatki[$i]["kategoria"] == "podroze"){
              $kat_wydatki[4] = $kat_wydatki[4] + $dane_wydatki[$i]["kwota"];
          }else if($dane_wydatki[$i]["kategoria"] == "zdrowie"){
              $kat_wydatki[5] = $kat_wydatki[5] + $dane_wydatki[$i]["kwota"];
          }else if($dane_wydatki[$i]["kategoria"] == "dom"){
              $kat_wydatki[6] = $kat_wydatki[6] + $dane_wydatki[$i]["kwota"];
          }else if($dane_wydatki[$i]["kategoria"] == "inne"){
              $kat_wydatki[7] = $kat_wydatki[7] + $dane_wydatki[$i]["kwota"];
          }
      }

    $conn = null;
  } catch (PDOException $e) {
    $_SESSION["message"] = $e->getMessage();
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
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
  <link href="../public/styles/kategorie.css" rel="stylesheet" />
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
      <div class="col-lg-10 col mt-4 text-align-center">
        <div class="row text-black style-padding justify-content-center">
          <div class="col-xxl-2 col-lg-3 col-md-4 col-sm-6 mt-4 mb-md-4"
                              <?php
                              if ($dane[0]["jedzenie"] == 0) {
                                  echo 'style="display: none;"';
                              }
                              ?>>
            <div class="card style-card mx-auto">
              <img src="../public/zdjecia/jedzenie.png" class="card-img-top" alt="..." />
              <div class="card-body">
                <h5 class="card-title">Jedzenie</h5>
                <p class="card-text">Łącznie wydano: <?php  echo $kat_wydatki[0]?> zł</p>
                    <form method="post" action="kategorie-show.php">
                  <button type="submit" class="btn btn-primary" name="kategoria" value="jedzenie">Edytuj kategorie</button>
                    </form>
              </div>
            </div>
          </div>
          <div class="col-xxl-2 col-lg-3 col-md-4 col-sm-6 mt-4 mb-md-4" <?php
                                                                          if ($dane[0]["rodzina"] == 0) {
                                                                            echo 'style="display: none;"';
                                                                          }
                                                                          ?>>
            <div class="card style-card mx-auto">
              <img src="../public/zdjecia/rodzina.png" class="card-img-top" alt="..." />
              <div class="card-body">
                <h5 class="card-title">Rodzina</h5>
                <p class="card-text">Łącznie wydano: <?php  echo $kat_wydatki[1]?> zł</p>
                  <form method="post" action="kategorie-show.php">
                      <button type="submit" class="btn btn-primary" name="kategoria" value="rodzina">Edytuj kategorie</button>
                  </form>
              </div>
            </div>
          </div>
          <div class="col-xxl-2 col-lg-3 col-md-4 col-sm-6 mt-4 mb-md-4" <?php
                                                                          if ($dane[0]["przyszlosc"] == 0) {
                                                                            echo 'style="display: none;"';
                                                                          }
                                                                          ?>>
            <div class="card style-card mx-auto">
              <img src="../public/zdjecia/przyszlosc.png" class="card-img-top" alt="..." />
              <div class="card-body">
                <h5 class="card-title">Przyszłość</h5>
                <p class="card-text">Łącznie wydano: <?php  echo $kat_wydatki[2]?> zł</p>
                  <form method="post" action="kategorie-show.php">
                      <button type="submit" class="btn btn-primary" name="kategoria" value="przyszlosc">Edytuj kategorie</button>
                  </form>
              </div>
            </div>
          </div>
          <div class="col-xxl-2 col-lg-3 col-md-4 col-sm-6 mt-4 mb-md-4" <?php
                                                                          if ($dane[0]["hobby"] == 0) {
                                                                            echo 'style="display: none;"';
                                                                          }
                                                                          ?>>
            <div class="card style-card mx-auto">
              <img src="../public/zdjecia/hobby.png" class="card-img-top" alt="..." />
              <div class="card-body">
                <h5 class="card-title">Hobyy</h5>
                <p class="card-text">Łącznie wydano: <?php  echo $kat_wydatki[3]?> zł</p>
                  <form method="post" action="kategorie-show.php">
                      <button type="submit" class="btn btn-primary" name="kategoria" value="hobby">Edytuj kategorie</button>
                  </form>
              </div>
            </div>
          </div>
          <div class="col-xxl-2 col-lg-3 col-md-4 col-sm-6 mt-4 mb-md-4" <?php
                                                                          if ($dane[0]["podroze"] == 0) {
                                                                            echo 'style="display: none;"';
                                                                          }
                                                                          ?>>
            <div class="card style-card mx-auto">
              <img src="../public/zdjecia/podroze.png" class="card-img-top" alt="..." />
              <div class="card-body">
                <h5 class="card-title">Podróże</h5>
                <p class="card-text">Łącznie wydano: <?php  echo $kat_wydatki[4]?> zł</p>
                  <form method="post" action="kategorie-show.php">
                      <button type="submit" class="btn btn-primary" name="kategoria" value="podroze">Edytuj kategorie</button>
                  </form>
              </div>
            </div>
          </div>
          <div class="col-xxl-2 col-lg-3 col-md-4 col-sm-6 mt-4 mb-md-4" <?php
                                                                          if ($dane[0]["zdrowie"] == 0) {
                                                                            echo 'style="display: none;"';
                                                                          }
                                                                          ?>>
            <div class="card style-card mx-auto">
              <img src="../public/zdjecia/zdrowie.png" class="card-img-top" alt="..." />
              <div class="card-body">
                <h5 class="card-title">Zdrowie</h5>
                <p class="card-text">Łącznie wydano: <?php  echo $kat_wydatki[5]?> zł</p>
                  <form method="post" action="kategorie-show.php">
                      <button type="submit" class="btn btn-primary" name="kategoria" value="zdrowie">Edytuj kategorie</button>
                  </form>
              </div>
            </div>
          </div>
          <div class="col-xxl-2 col-lg-3 col-md-4 col-sm-6 mt-4 mb-md-4" <?php
                                                                          if ($dane[0]["dom"] == 0) {
                                                                            echo 'style="display: none;"';
                                                                          }
                                                                          ?>>
            <div class="card style-card mx-auto">
              <img src="../public/zdjecia/dom.png" class="card-img-top" alt="..." />
              <div class="card-body">
                <h5 class="card-title">Dom</h5>
                <p class="card-text">Łącznie wydano: <?php  echo $kat_wydatki[6]?> zł</p>
                  <form method="post" action="kategorie-show.php">
                      <button type="submit" class="btn btn-primary" name="kategoria" value="dom">Edytuj kategorie</button>
                  </form>
              </div>
            </div>
          </div>
          <div class="col-xxl-2 col-lg-3 col-md-4 col-sm-6 mt-4 mb-md-4" <?php
                                                                          if ($dane[0]["inne"] == 0) {
                                                                            echo 'style="display: none;"';
                                                                          }
                                                                          ?>>
            <div class="card style-card mx-auto">
              <img src="../public/zdjecia/inne.png" class="card-img-top" alt="..." />
              <div class="card-body">
                <h5 class="card-title">Inne</h5>
                <p class="card-text">Łącznie wydano: <?php  echo $kat_wydatki[7]?> zł</p>
                  <form method="post" action="kategorie-show.php">
                      <button type="submit" class="btn btn-primary" name="kategoria" value="inne">Edytuj kategorie</button>
                  </form>
              </div>
            </div>
          </div>
        </div>
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