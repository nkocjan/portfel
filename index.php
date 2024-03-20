<?php /** @noinspection DuplicatedCode */
session_start();
if (!isset($_SESSION["login"])) {
  header("Location: /portfel/login.php");
} else if ($_SESSION["czy_kategorie_set"] == 0) {
  header("Location: /portfel/kategorie-wybor.php");
} else {
  try {
    require_once "./private/connectDB.php";
    $conn = new PDO("mysql:host=$servernameSQL;dbname=$dbnameSQL", $usernameSQL, $passwordSQL);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $query = "SELECT * FROM cele WHERE login = :login";
    $statement = $conn->prepare($query);
    $statement->bindParam(":login", $_SESSION["login"]);
    $statement->execute();
    $celeUzytkownika = $statement->fetchAll(PDO::FETCH_ASSOC);      //Cele do pokazania

    $query2 = "SELECT * FROM wydatki WHERE login = :_login";
      $statement2 = $conn->prepare($query2);
      $statement2->bindParam(":_login", $_SESSION["login"]);
      $statement2->execute();
      $dane = $statement2->fetchAll(PDO::FETCH_ASSOC);                  //wydatki

      $query3 = "SELECT * FROM przychody WHERE login = :_login";
      $statement3 = $conn->prepare($query3);
      $statement3->bindParam(":_login", $_SESSION["login"]);
      $statement3->execute();
      $dane2 = $statement3->fetchAll(PDO::FETCH_ASSOC);                 //przychody

      $query4 = "SELECT * FROM kategorie WHERE login  = :_login";
      $statement4 = $conn->prepare($query4);
      $statement4->bindParam(":_login", $_SESSION["login"]);
      $statement4->execute();
      $kategorieTAB = $statement4->fetchAll(PDO::FETCH_ASSOC);          //wszystkie kategorie


      // ### Tworzy tebele $kategorie zawierającą kategorie usera
      $kategorie = [];
      if($kategorieTAB[0]["jedzenie"] == 1){
          $kategorie[] = "jedzenie";
      } if($kategorieTAB[0]["rodzina"] == 1) {
          $kategorie[] = "rodzina";
      } if($kategorieTAB[0]["przyszlosc"] == 1) {
          $kategorie[] = "przyszlosc";
      } if($kategorieTAB[0]["zdrowie"] == 1) {
          $kategorie[] = "zdrowie";
      } if($kategorieTAB[0]["hobby"] == 1) {
          $kategorie[] = "hobby";
      } if($kategorieTAB[0]["dom"] == 1) {
          $kategorie[] = "dom";
      } if($kategorieTAB[0]["podroze"] == 1) {
          $kategorie[] = "podroze";
      } if($kategorieTAB[0]["inne"] == 1) {
          $kategorie[] = "inne";
      }

      // ### Tworzy tebele $kat_suma podającą łącznie ile wydano na daną kategorię
        $kat_suma = [0,0,0,0,0];
      for($i = 0; $i < count($dane); $i++){
          if($dane[$i]["kategoria"] == $kategorie[0]){
              $kat_suma[0] = $kat_suma[0] + $dane[$i]["kwota"];
          } else if($dane[$i]["kategoria"] == $kategorie[1]){
              $kat_suma[1] = $kat_suma[1] + $dane[$i]["kwota"];
          }else if($dane[$i]["kategoria"] == $kategorie[2]){
              $kat_suma[2] = $kat_suma[2] + $dane[$i]["kwota"];
          }else if($dane[$i]["kategoria"] == $kategorie[3]){
              $kat_suma[3] = $kat_suma[3] + $dane[$i]["kwota"];
          }else if($dane[$i]["kategoria"] == $kategorie[4]){
              $kat_suma[4] = $kat_suma[4] + $dane[$i]["kwota"];
          }
      }

      // ### Kod dotyczący celów
    if (count($celeUzytkownika) == 0) {
      $buttonToAdd = '<a href="views/konto.php"><button type="submit">Dodaj cele</button></a>';
      $cel1 = "";
      $cel2 = "";
      $cel3 = "";
      $cel4 = "";
    } else {
      $buttonToAdd = "";
      if ($celeUzytkownika[0]["cel1"] == "empty") {
        $cel1 = "";
      } else {
        $cel1 = $celeUzytkownika[0]["cel1"] . " ...";
      }
      if ($celeUzytkownika[0]["cel2"] == "empty") {
        $cel2 = "";
      } else {
        $cel2 = $celeUzytkownika[0]["cel2"] . " ...";
      }
      if ($celeUzytkownika[0]["cel3"] == "empty") {
        $cel3 = "";
      } else {
        $cel3 = $celeUzytkownika[0]["cel3"] . " ...";
      }
      if ($celeUzytkownika[0]["cel4"] == "empty") {
        $cel4 = "";
      } else {
        $cel4 = $celeUzytkownika[0]["cel4"] . " ...";
      }
    }

    // ### Funkcja porównująca daty podane przez stringa
      function compare_data($string1, $string2)
      {
          $dat1 = new DateTime($string1);
          $dat2 = new DateTime($string2);

          if ($dat1 < $dat2) {
              return -1;              // DATA 1 jest wcześniejsza
          } else if ($dat1 > $dat2) {
              return 1;
          } else {
              return 1;
          }
      }

    // Uporządkowanie kolejności wydatków
      for ($i = 0; $i < count($dane); $i++) {
          for ($j = 1; $j < count($dane) - $i; $j++) {
              if (compare_data($dane[$j - 1]["data"], $dane[$j]["data"]) == -1) {
                  $tmp = $dane[$j - 1];
                  $dane[$j - 1] = $dane[$j];
                  $dane[$j] = $tmp;
              }
          }
      }

      // Uporządkowanie kolejności przychodów
      for ($i = 0; $i < count($dane2); $i++) {
          for ($j = 1; $j < count($dane2) - $i; $j++) {
              if (compare_data($dane2[$j - 1]["data"], $dane2[$j]["data"]) == -1) {
                  $tmp = $dane2[$j - 1];
                  $dane2[$j - 1] = $dane2[$j];
                  $dane2[$j] = $tmp;
              }
          }
      }

      // Nowa tablica zawierająca wydatki od najwiekszego
    $dane_cpy = $dane;
      for ($i = 0; $i < count($dane_cpy); $i++) {
          for ($j = 1; $j < count($dane_cpy) - $i; $j++) {
              if ($dane_cpy[$j-1]["kwota"] >= $dane_cpy[$j]["kwota"]) {
                  $tmp = $dane_cpy[$j - 1];
                  $dane_cpy[$j - 1] = $dane_cpy[$j];
                  $dane_cpy[$j] = $tmp;
              }
          }
      }
    // Nowa tablica zawierająca przychody od największego
      $dane_cpy2 = $dane2;
      for ($i = 0; $i < count($dane_cpy2); $i++) {
          for ($j = 1; $j < count($dane_cpy2) - $i; $j++) {
              if ($dane_cpy2[$j-1]["kwota"] >= $dane_cpy2[$j]["kwota"]) {
                  $tmp = $dane_cpy2[$j - 1];
                  $dane_cpy2[$j - 1] = $dane_cpy2[$j];
                  $dane_cpy2[$j] = $tmp;
              }
          }
      }

      //3 ostatnie wydatki zalezne od kategorii
      $wyd_kategoria = [];
      for($i = 0; $i < count($dane); $i++) {
          for ($j = 0; $j < 5; $j++) {
              if ($dane[$i]["kategoria"] == $kategorie[$j]) {
                  $wyd_kategoria[$j][] = $dane[$i];
              }
        }
      }
      $conn = null;
  } catch (PDOException $e) {
    echo "Connected failed" . $e->getMessage();
  }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Strona główna</title>
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous" />
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous">
  </script>
  <link href="./public/styles/index.css" rel="stylesheet" />
</head>

<body style="background-color: #343a40">
  <div class="container-fluid">
    <div class="row flex-nowrap">
      <div class="bg-dark col-lg-2 col-auto min-vh-100">
        <div class="bg-dark">
          <ul class="nav nav-pills flex-column mt-4 align-items-center">
            <li class="nav-item active">
              <a href="/portfel" class="d-flex nav-link text-white text-decoration-none align-items-center">
                <span class="material-symbols-outlined text-active"> Menu </span>
                <div style="width: 10px"></div>
                <span class="fs-4 d-none d-lg-inline ml-2 text-active"> Portfel </span>
              </a>
            </li>
            <li class="nav-item mt-3">
              <a href="views/przychody.php" class="d-flex nav-link text-white text-decoration-none align-items-center">
                <span class="material-symbols-outlined">trending_up</span>
                <div style="width: 10px"></div>
                <span class="d-none d-lg-inline ml-2">Przychody</span>
              </a>
            </li>
            <li class="nav-item mt-3">
              <a href="views/wydatki.php" class="d-flex nav-link text-white text-decoration-none align-items-center">
                <span class="material-symbols-outlined">trending_down</span>
                <div style="width: 10px"></div>
                <span class="d-none d-lg-inline ml-2">Wydatki</span>
              </a>
            </li>
            <li class="nav-item mt-3">
              <a href="views/kategorie.php" class="d-flex nav-link text-white text-decoration-none align-items-center">
                <span class="material-symbols-outlined">category</span>
                <div style="width: 10px"></div>
                <span class="d-none d-lg-inline ml-2">Kategorie</span>
              </a>
            </li>
            <li class="nav-item mt-3">
              <a href="views/konto.php" class="d-flex nav-link text-white text-decoration-none align-items-center">
                <span class="material-symbols-outlined">person</span>
                <div style="width: 10px"></div>
                <span class="d-none d-lg-inline ml-2">Konto</span>
              </a>
            </li>
            <li class="nav-item mt-3">
              <a href="wyloguj.php" class="d-flex nav-link text-white text-decoration-none align-items-center">
                <span class="material-symbols-outlined">logout</span>
                <div style="width: 10px"></div>
                <span class="d-none d-lg-inline ml-2">Wyloguj</span>
              </a>
            </li>
          </ul>
        </div>
      </div>
      <div class="col-lg-10 col-9">
        <div class="row">
          <div class="col-lg-4 col-12 mt-5">
            <div class="carousel slide" id="demo1" data-bs-ride="carousel">
              <div class="carousel-indicators">
                <button type="button" data-bs-target="#demo1" data-bs-slide-to="0" class="active"></button>
                <button type="button" data-bs-target="#demo1" data-bs-slide-to="1"></button>
                <button type="button" data-bs-target="#demo1" data-bs-slide-to="2"></button>
              </div>
              <div class="carousel-inner">
                <div class="carousel-item active">
                  <div class="style-carousel-box">
                    <h3>Wydatki</h3>
                    <p>Ostatni rok:
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
                    </p>
                    <p>Ostatni miesiąc:
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
                    </p>
                      <p>Ostatni tydzień:
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
                      </p>
                  </div>
                </div>
                <div class="carousel-item">
                  <div class="style-carousel-box">
                    <h3>Ostatnie wydatki</h3>
                      <p><?php if(isset($dane[0])){ echo $dane[0]["nazwa"]. ": ".$dane[0]["kwota"]; } else echo "Brak, dodaj wydatki"?></p>
                      <p><?php if(isset($dane[1])) echo $dane[1]["nazwa"]. ": ".$dane[1]["kwota"]; ?></p>
                      <p><?php if(isset($dane[2])) echo $dane[2]["nazwa"]. ": ".$dane[2]["kwota"]; ?></p>
                  </div>
                </div>
                <div class="carousel-item">
                  <div class="style-carousel-box">
                    <h3>Największe wydatki</h3>
                      <p><?php if(isset($dane_cpy[0])){echo $dane_cpy[0]["nazwa"]. ": ". $dane_cpy[0]["kwota"];} else echo "Brak, dodaj wydatki"?></p>
                      <p><?php if(isset($dane_cpy[1])) echo $dane_cpy[1]["nazwa"]. ": ". $dane_cpy[1]["kwota"]?></p>
                      <p><?php if(isset($dane_cpy[2])) echo $dane_cpy[2]["nazwa"]. ": ". $dane_cpy[2]["kwota"]?></p>
                  </div>
                </div>
              </div>
              <button class="carousel-control-prev" type="button" data-bs-target="#demo1" data-bs-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
              </button>
              <button class="carousel-control-next" type="button" data-bs-target="#demo1" data-bs-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
              </button>
            </div>
          </div>
          <div class="col-lg-4 col-12 mt-5">
            <div class="carousel slide" id="demo2" data-bs-ride="carousel">
              <div class="carousel-indicators">
                <button type="button" data-bs-target="#demo2" data-bs-slide-to="0" class="active"></button>
                <button type="button" data-bs-target="#demo2" data-bs-slide-to="1"></button>
                <button type="button" data-bs-target="#demo2" data-bs-slide-to="2"></button>
              </div>
              <div class="carousel-inner">
                <div class="carousel-item active">
                  <div class="style-carousel-box">
                    <h3>Przychody</h3>
                    <p>Ostatni rok:
                        <?php
                        $ostatni_rok = 0;
                        $start_date = (new DateTime())->modify("-1 year");

                        for ($i = 0; $i < count($dane2); $i++) {
                            $date_to_check = new DateTime($dane2[$i]["data"]);
                            if ($date_to_check >= $start_date) {
                                $ostatni_rok += (int) $dane2[$i]["kwota"];
                            }
                        }
                        echo $ostatni_rok . "zł";
                        ?>
                    </p>
                    <p>Ostatni miesiąc:
                        <?php
                        $ostatni_miesiac = 0;
                        $start_date = (new DateTime())->modify("-1 month");

                        for ($i = 0; $i < count($dane2); $i++) {
                            $date_to_check = new DateTime($dane2[$i]["data"]);
                            if ($date_to_check >= $start_date) {
                                $ostatni_miesiac += (int) $dane2[$i]["kwota"];
                            }
                        }
                        echo $ostatni_miesiac . "zł";
                        ?>
                    </p>
                    <p>Ostatni tydzień:
                        <?php
                        $ostatni_tydzien = 0;
                        $start_date = (new DateTime())->modify("-1 week");

                        for ($i = 0; $i < count($dane2); $i++) {
                            $date_to_check = new DateTime($dane2[$i]["data"]);
                            if ($date_to_check >= $start_date) {
                                $ostatni_tydzien += (int) $dane2[$i]["kwota"];
                            }
                        }
                        echo $ostatni_tydzien. "zł";
                        ?>
                    </p>
                  </div>
                </div>
                <div class="carousel-item">
                  <div class="style-carousel-box">
                    <h3>Ostatnie przychody</h3>
                      <p><?php if(isset($dane2[0])) {echo $dane2[0]["nazwa"]. ": ".$dane2[0]["kwota"];} else echo "Brak, dodaj przychody" ?></p>
                      <p><?php if(isset($dane2[1])) echo $dane2[1]["nazwa"]. ": ".$dane2[1]["kwota"]; ?></p>
                      <p><?php if(isset($dane2[2])) echo $dane2[2]["nazwa"]. ": ".$dane2[2]["kwota"]; ?></p>
                  </div>
                </div>
                <div class="carousel-item">
                  <div class="style-carousel-box">
                    <h3>Największe przychody</h3>
                      <p><?php if(isset($dane_cpy2[0])) {echo $dane_cpy2[0]["nazwa"]. ": ". $dane_cpy2[0]["kwota"];} else echo "Brak, dodaj przychody"?></p>
                      <p><?php if(isset($dane_cpy2[1])) echo $dane_cpy2[1]["nazwa"]. ": ". $dane_cpy2[1]["kwota"]?></p>
                      <p><?php if(isset($dane_cpy2[2])) echo $dane_cpy2[2]["nazwa"]. ": ". $dane_cpy2[2]["kwota"]?></p>
                  </div>
                </div>
              </div>
              <button class="carousel-control-prev" type="button" data-bs-target="#demo2" data-bs-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
              </button>
              <button class="carousel-control-next" type="button" data-bs-target="#demo2" data-bs-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
              </button>
            </div>
          </div>
          <div class="col-lg-4 col-12 mt-5">
            <div class="carousel slide" id="demo3" data-bs-ride="carousel">
              <div class="carousel-indicators">
                <button type="button" data-bs-target="#demo3" data-bs-slide-to="0" class="active"></button>
                <button type="button" data-bs-target="#demo3" data-bs-slide-to="1"></button>
                <button type="button" data-bs-target="#demo3" data-bs-slide-to="2"></button>
                <button type="button" data-bs-target="#demo3" data-bs-slide-to="3"></button>
                <button type="button" data-bs-target="#demo3" data-bs-slide-to="4"></button>
              </div>
              <div class="carousel-inner">
                <div class="carousel-item active">
                  <div class="style-carousel-box">
                    <h3><?php  echo $kategorie[0];?></h3>
                    <p>
                        <?php
                            if(isset($wyd_kategoria[0][0])) {echo $wyd_kategoria[0][0]["nazwa"]. " - ". $wyd_kategoria[0][0]["kwota"] . "zł";} else { echo "Dodaj nowy wydatek kategorii ".$kategorie[0];}
                        ?>
                    </p>
                    <p>
                        <?php
                        if(isset($wyd_kategoria[0][1])) echo $wyd_kategoria[0][1]["nazwa"]. " - ". $wyd_kategoria[0][1]["kwota"] . "zł";
                        ?>
                    </p>
                    <p>
                        <?php
                        if(isset($wyd_kategoria[0][2])) echo $wyd_kategoria[0][2]["nazwa"]. " - ". $wyd_kategoria[0][2]["kwota"] . "zł";
                        ?>
                    </p>
                  </div>
                </div>
                <div class="carousel-item">
                  <div class="style-carousel-box">
                    <h3><?php  echo $kategorie[1];?></h3>
                      <p>
                          <?php
                          if(isset($wyd_kategoria[1][0])) {echo $wyd_kategoria[1][0]["nazwa"]. " - ". $wyd_kategoria[1][0]["kwota"] . "zł";} else { echo "Dodaj nowy wydatek kategorii ".$kategorie[0];}
                          ?>
                      </p>
                      <p>
                          <?php
                          if(isset($wyd_kategoria[1][1])) echo $wyd_kategoria[1][1]["nazwa"]. " - ". $wyd_kategoria[1][1]["kwota"] . "zł";
                          ?>
                      </p>
                      <p>
                          <?php
                          if(isset($wyd_kategoria[1][2])) echo $wyd_kategoria[1][2]["nazwa"]. " - ". $wyd_kategoria[1][2]["kwota"] . "zł";
                          ?>
                      </p>
                  </div>
                </div>
                <div class="carousel-item">
                  <div class="style-carousel-box">
                    <h3><?php  echo $kategorie[2];?></h3>
                      <?php
                      if(isset($wyd_kategoria[2][0])) {echo $wyd_kategoria[2][0]["nazwa"]. " - ". $wyd_kategoria[2][0]["kwota"] . "zł";} else { echo "Dodaj nowy wydatek kategorii ".$kategorie[0];}
                      ?>
                      </p>
                      <p>
                          <?php
                          if(isset($wyd_kategoria[2][1])) echo $wyd_kategoria[2][1]["nazwa"]. " - ". $wyd_kategoria[2][1]["kwota"] . "zł";
                          ?>
                      </p>
                      <p>
                          <?php
                          if(isset($wyd_kategoria[2][2])) echo $wyd_kategoria[2][2]["nazwa"]. " - ". $wyd_kategoria[2][2]["kwota"] . "zł";
                          ?>
                  </div>
                </div>
                  <div class="carousel-item">
                      <div class="style-carousel-box">
                          <h3><?php  echo $kategorie[3];?></h3>
                          <?php
                          if(isset($wyd_kategoria[3][0])) {echo $wyd_kategoria[3][0]["nazwa"]. " - ". $wyd_kategoria[3][0]["kwota"] . "zł";} else { echo "Dodaj nowy wydatek kategorii ".$kategorie[0];}
                          ?>
                          </p>
                          <p>
                              <?php
                              if(isset($wyd_kategoria[3][1])) echo $wyd_kategoria[3][1]["nazwa"]. " - ". $wyd_kategoria[3][1]["kwota"] . "zł";
                              ?>
                          </p>
                          <p>
                              <?php
                              if(isset($wyd_kategoria[3][2])) echo $wyd_kategoria[3][2]["nazwa"]. " - ". $wyd_kategoria[3][2]["kwota"] . "zł";
                              ?>
                      </div>
                  </div>
                  <div class="carousel-item">
                      <div class="style-carousel-box">
                          <h3><?php  echo $kategorie[4];?></h3>
                          <p>
                          <?php
                          if(isset($wyd_kategoria[4][0])) {echo $wyd_kategoria[4][0]["nazwa"]. " - ". $wyd_kategoria[4][0]["kwota"] . "zł";} else { echo "Dodaj nowy wydatek kategorii ".$kategorie[0];}
                          ?>
                          </p>
                          <p>
                              <?php
                              if(isset($wyd_kategoria[4][1])) echo $wyd_kategoria[4][1]["nazwa"]. " - ". $wyd_kategoria[4][1]["kwota"] . "zł";
                              ?>
                          </p>
                          <p>
                              <?php
                              if(isset($wyd_kategoria[4][2])) echo $wyd_kategoria[4][2]["nazwa"]. " - ". $wyd_kategoria[4][2]["kwota"] . "zł";
                              ?>
                      </div>
                  </div>
              </div>
              <button class="carousel-control-prev" type="button" data-bs-target="#demo3" data-bs-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
              </button>
              <button class="carousel-control-next" type="button" data-bs-target="#demo3" data-bs-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
              </button>
            </div>
          </div>
        </div>
        <div class="row mt-4">
          <div class="col-12 text-align-center">
            <h2>Dlaczego to robię?</h2>
            <br />
            <p class="style-teksty">
              <?php
              echo $buttonToAdd;
              echo $cel1;
              ?>
            </p>
            <br />
            <p>
              <?php
              echo $cel2;
              ?>
            </p>
            <br />
            <p>
              <?php
              echo $cel3;
              ?>
            </p>
            <br />
            <p>
              <?php
              echo $cel4;
              ?>
            </p>
          </div>
        </div>
      </div>
    </div>
  </div>
</body>

</html>