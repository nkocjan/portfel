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
  header("Location: /portfolio/portfel/login.php");
} else if ($_SESSION["czy_kategorie_set"] == 0) {
  header("Location: /portfolio/portfel/kategorie-wybor.php");
} else {
  try {
    require_once "../private/connectDB.php";
    $conn = new PDO("mysql:host=$servernameSQL;dbname=$dbnameSQL", $usernameSQL, $passwordSQL);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $query = "SELECT * FROM cele WHERE login = :login";
    $statement = $conn->prepare($query);
    $statement->bindParam(":login", $_SESSION["login"], PDO::PARAM_STR);
    $statement->execute();
    $celeUzytkownika = $statement->fetchAll(PDO::FETCH_ASSOC);

    if (count($celeUzytkownika) == 0) {
      $cel1 = "";
      $cel2 = "";
      $cel3 = "";
      $cel4 = "";
      $buttonDelete1 = '';
      $buttonDelete2 = '';
      $buttonDelete3 = '';
      $buttonDelete4 = '';
    } else {
      if ($celeUzytkownika[0]["cel1"] == "empty") {
        $buttonDelete1 = '';
        $cel1 = "";
      } else {
        $buttonDelete1 = '<form method="POST" action = "/portfel/prv/prv_usunCel.php"><button type="submit" name="cel1">Usuń</button></form>';
        $cel1 = $celeUzytkownika[0]["cel1"] . " ...";
      }
      if ($celeUzytkownika[0]["cel2"] == "empty") {
        $buttonDelete2 = '';
        $cel2 = "";
      } else {
        $buttonDelete2 = '<form method="POST" action = "/portfel/prv/prv_usunCel.php"><button type="submit" name="cel2">Usuń</button></form>';
        $cel2 = $celeUzytkownika[0]["cel2"] . " ...";
      }
      if ($celeUzytkownika[0]["cel3"] == "empty") {
        $buttonDelete3 = '';
        $cel3 = "";
      } else {
        $buttonDelete3 = '<form method="POST" action = "/portfel/prv/prv_usunCel.php"><button type="submit" name="cel3">Usuń</button></form>';
        $cel3 = $celeUzytkownika[0]["cel3"] . " ...";
      }
      if ($celeUzytkownika[0]["cel4"] == "empty") {
        $buttonDelete4 = '';
        $cel4 = "";
      } else {
        $buttonDelete4 = '<form method="POST" action = "/portfel/prv/prv_usunCel.php"><button type="submit" name="cel4">Usuń</button></form>';
        $cel4 = $celeUzytkownika[0]["cel4"] . " ...";
      }
    }
    $buttonDelete = '<form><button type="submit">Usuń</button></form>';
    $conn = null;
  } catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
  }

  /// ### POBIERANIE HASŁA Z BAZY DANYCH DO PORÓWNYWANIA ### //

  try {
    require_once "../private/connectDB.php";
    $conn = new PDO("mysql:host=$servernameSQL;dbname=$dbnameSQL", $usernameSQL, $passwordSQL);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $query = "SELECT haslo FROM users WHERE login = :login";
    $statement = $conn->prepare($query);
    $statement->bindParam(":login", $_SESSION["login"], PDO::PARAM_STR);
    $statement->execute();
    $result = $statement->fetchAll(PDO::FETCH_ASSOC);
    $haslo = $result[0]["haslo"];
    $conn = null;
  } catch (PDOException $e) {
    echo "error z bazą dancyh";
    header("Location: /portfolio/portfel/wyloguj.php");
  }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Konto</title>
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous" />
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
  <link href="../public/styles/konto.css" rel="stylesheet" />
</head>

<body>
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
                <span class="material-symbols-outlined">category</span>
                <div style="width: 10px"></div>
                <span class="d-none d-lg-inline ml-2">Kategorie</span>
              </a>
            </li>
            <li class="nav-item mt-3">
              <a href="konto.php" class="d-flex nav-link text-white text-decoration-none align-items-center">
                <span class="material-symbols-outlined text-active">
                  person
                </span>
                <div style="width: 10px"></div>
                <span class="d-none d-lg-inline ml-2 text-active">Konto</span>
              </a>
            </li>
            <li class="nav-item mt-3">
              <a href="/portfel/wyloguj.php" class="d-flex nav-link text-white text-decoration-none align-items-center">
                <span class="material-symbols-outlined">logout</span>
                <div style="width: 10px"></div>
                <span class="d-none d-lg-inline ml-2">Wyloguj</span>
              </a>
            </li>
          </ul>
        </div>
      </div>
      <div class="col-lg-10 col mt-4 text-align-center">
        <div class="row text-white">
          <div class="col-md-6 col-12 mx-md-0 mx-auto">
            <h3 class="mb-5">Ustawienia konta</h3>
            <div class="row justify-content-center">
              <div class="col-xl-4 col-md-6 col-12 mt-3 mt-md-0 my-auto">
                Zmień login
              </div>
              <div class="col-xl-4 col-md-6 col-12 mt-3 mt-md-0">
                <input type="text" placeholder="nowy login" class="style-input" id="haslo_login" />
              </div>
              <div class="col-xl-4 col-md-6 col-12 mt-3 mt-md-0">
                <button type="button" id="btn_login" class="btn btn-primary mt-md-3 mt-xl-0" data-bs-toggle="modal" data-bs-target="#zmienLogin">
                  Zmień login
                </button>
              </div>
            </div>
            <div class="row mt-5 justify-content-center">
              <div class="col-xl-4 col-md-6 col-12 mt-3 mt-md-0 my-auto">
                Zmień hasło
              </div>
              <div class="col-xl-4 col-md-6 col-12 mt-3 mt-md-0">
                <input type="text" placeholder="nowe hasło" class="style-input" id="haslo_haslo" />
              </div>
              <div class="col-xl-4 col-md-6 col-12 mt-3 mt-md-0">
                <button type="button" id="btn_haslo" class="btn btn-primary mt-md-3 mt-xl-0" data-bs-toggle="modal" data-bs-target="#zmienHaslo">
                  Zmień hasło
                </button>
              </div>
            </div>
            <div class="row mt-5 justify-content-center">
              <div class="col-xl-4 col-md-6 col-12 mt-3 mt-md-0 my-auto">
                Zmień email
              </div>
              <div class="col-xl-4 col-md-6 col-12 mt-3 mt-md-0">
                <input type="email" placeholder="nowy email" class="style-input" id="haslo_email" />
              </div>
              <div class="col-xl-4 col-md-6 col-12 mt-3 mt-md-0 mb-5">
                <button type="button" id="btn_email" class="btn btn-primary mt-md-3 mt-xl-0" data-bs-toggle="modal" data-bs-target="#zmienEmail">
                  Zmień emial
                </button>
              </div>
            </div>
          </div>
          <div class="col-md-6 col-6 mx-md-0 mx-auto mt-5 mt-md-0">
            <h3 class="mb-5">Cele i kategorie</h3>
            <div class="row">
              <div class="col-9">
                <?php
                echo $cel1;
                ?>
              </div>
              <div class="col-3">
                <?php
                echo $buttonDelete1;
                ?>
              </div>
            </div>
            <div class="row mt-3">
              <div class="col-9">
                <?php
                echo $cel2;
                ?>
              </div>
              <div class="col-3">
                <?php
                echo $buttonDelete2;
                ?>
              </div>
            </div>
            <div class="row mt-3">
              <div class="col-9">
                <?php
                echo $cel3;
                ?>
              </div>
              <div class="col-3">
                <?php
                echo $buttonDelete3;
                ?>
              </div>
            </div>
            <div class="row mt-3">
              <div class="col-9">
                <?php
                echo $cel4;
                ?>
              </div>
              <div class="col-3">
                <?php
                echo $buttonDelete4;
                ?>
              </div>
            </div>
            <form method="POST" action="../prv/prv_dodaj_cel.php">
              <div class="row mt-4">
                <div class="col-md-8">
                  <input name="nowyCel" type="text" placeholder="dodaj cel" class="text-align-center" style="width: 250px; font-size: small" />
                </div>
                <div class="col-md-4"><button type="submit">Dodaj</button></div>
              </div>
            </form>
            <div class="row mt-3 text-red text-align-center">
              <div class="col-12 mx-auto text-red">
                <?php
                echo $_SESSION["error"];
                $_SESSION["error"] = "";
                ?>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="modal" id="zmienLogin">
    <div class="modal-dialog">
      <div class="modal-content">
        <form method="POST" action="/portfel/prv/prv_zmiana.php">
          <div class="modal-header">
            <h4 class="modal-title">Podaj nowy login</h4>
            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
          </div>
          <div class="modal-body">

            <input type="text" placeholder="powtórz nowy login" name="newLogin1" />
            <input type="password" placeholder="aktualne hasło" name="haslo1" />

          </div>
          <div class="modal-footer">
            <button type="submit" class="btn btn-primary">Akceptuj</button>
            <button type="button" class="btn btn-danger" data-bs-dismiss="modal">
              Zamknij
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>
  <div class="modal" id="zmienHaslo">
    <div class="modal-dialog">
      <div class="modal-content">
        <form method="POST" action="/portfel/prv/prv_zmiana.php">
          <div class="modal-header">
            <h4 class="modal-title">Podaj nowe hasło</h4>
            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
          </div>
          <div class="modal-body">

            <input type="text" placeholder="powtórz nowe hasło" name="newPassword2" />
            <input type="password" placeholder="aktualne hasło" name="haslo2" />

          </div>
          <div class="modal-footer">
            <button type="submit" class="btn btn-primary">Akceptuj</button>
            <button type="button" class="btn btn-danger" data-bs-dismiss="modal">
              Zamknij
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>
  <div class="modal" id="zmienEmail">
    <div class="modal-dialog">
      <div class="modal-content">
        <form method="POST" action="/portfel/prv/prv_zmiana.php">
          <div class="modal-header">
            <h4 class="modal-title">Podaj nowy email</h4>
            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
          </div>
          <div class="modal-body">

            <input type="email" placeholder="powtórz nowy email" name="newEmail3" />
            <input type="password" placeholder="aktualne hasło" name="haslo3" />
          </div>
          <div class="modal-footer">
            <button type="submit" class="btn btn-primary">Akceptuj</button>
            <button type="button" class="btn btn-danger" data-bs-dismiss="modal">
              Zamknij
            </button>
          </div>
        </form>
      </div>

    </div>
  </div>
  <div class="toast fade 
  <?php
  if ($_SESSION["message"]) {
    echo "show";
  }
  ?>
  " style="position: fixed;top: 5px;right: 5px;">
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