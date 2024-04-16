<?php
session_start();
if (!isset($_SESSION["login"])) {
  session_destroy();
  header("Location: /portfolio/portfel/login.php");
} else if ($_POST["data_od"] == "" || $_POST["data_do"] == "") {
  $_SESSION["message"] = "Musisz podać datę od i do";
    if($_POST["type"] == "wydatek"){
        header("Location: /portfolio/portfel/views/wydatki.php");
    } else if($_POST["type"] == "przychod"){
        header("Location: /portfolio/portfel/views/przychody.php");
    }
} else {
  require_once "../private/connectDB.php";
  try {
    $conn = new PDO("mysql:host=$servernameSQL;dbname=$dbnameSQL", $usernameSQL, $passwordSQL);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $conn->beginTransaction();
    $query = '';
    if($_POST["type"] == "wydatek"){
        $query = "SELECT * FROM wydatki WHERE login = :_login";
    } else if($_POST["type"] == "przychod"){
        $query = "SELECT * FROM przychody WHERE login = :_login";
    } else {
        $_SESSION["message"] = "Nieautoryzowany wstep";
        header("Location: ../login.php");
    }

    $statement1 = $conn->prepare($query);
    $statement1->bindParam(":_login", $_SESSION["login"], PDO::PARAM_STR);
    $statement1->execute();
    $conn->commit();
    $dane = $statement1->fetchAll(PDO::FETCH_ASSOC);

    $data_od = new DateTime($_POST["data_od"]);
    $data_do = new DateTime($_POST["data_do"]);

    $count = 0;
    for ($i = 0; $i < count($dane); $i++) {
      $data_check = new DateTime($dane[$i]["data"]);
      if ($data_check >= $data_od && $data_check <= $data_do) {
        $count = $count + $dane[$i]["kwota"];
      }
    }

    $_SESSION["kwota_do_wyswietlenia_daty"] = $count;
      if($_POST["type"] == "wydatek"){
          $_SESSION["message"] = "Wyswietlono wydatki od " . $_POST["data_od"] . " do " . $_POST["data_do"];
      } else if($_POST["type"] == "przychod"){
          $_SESSION["message"] = "Wyswietlono przychody od " . $_POST["data_od"] . " do " . $_POST["data_do"];
      }

      if($_POST["type"] == "wydatek"){
          $_SESSION["kwota_do_wyswietlenia_wydatki"] = $count;
          header("Location: /portfolio/portfel/views/wydatki.php");
      } else if($_POST["type"] == "przychod"){
          $_SESSION["kwota_do_wyswietlenia_przychody"] = $count;
          header("Location: /portfolio/portfel/views/przychody.php");
      }

  } catch (Exception $e) {
    $conn->rollBack();
    $_SESSION["message"] = $e->getMessage();
      if($_POST["type"] == "wydatek"){
          header("Location: /portfolio/portfel/views/wydatki.php");
      } else if($_POST["type"] == "przychod"){
          header("Location: /portfolio/portfel/views/przychody.php");
      }
  }
  $conn = null;
}
