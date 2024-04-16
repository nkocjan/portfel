<?php
session_start();
if ($_POST["nowyCel"] == "") {
  $_SESSION['error'] = "Pole cel jest puste ! ! !";
  header("Location: /portfolio/portfel/views/konto.php");
} else {
  $empty = "empty";
  require_once "../private/connectDB.php";



  $conn = new PDO("mysql:host=$servernameSQL;dbname=$dbnameSQL", $usernameSQL, $passwordSQL);
  $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  $conn->beginTransaction();
  $query = "SELECT * FROM cele WHERE login = :login";
  $statement = $conn->prepare($query);
  $statement->bindParam(":login", $_SESSION["login"], PDO::PARAM_STR);
  $statement->execute();
  $celeUzytkownika = $statement->fetchAll(PDO::FETCH_ASSOC);
  echo "dziala";
  if (count($celeUzytkownika) == 0) {
    $query1 = "INSERT INTO cele (login, cel1, cel2, cel3, cel4) VALUES (:login, :cel1, :cel2, :cel3, :cel4)";
    $statement1 = $conn->prepare($query1);
    $statement1->bindParam(":login", $_SESSION["login"], PDO::PARAM_STR);
    $statement1->bindParam(":cel1", $_POST["nowyCel"], PDO::PARAM_STR);
    $statement1->bindParam(":cel2", $empty, PDO::PARAM_STR);
    $statement1->bindParam(":cel3", $empty, PDO::PARAM_STR);
    $statement1->bindParam(":cel4", $empty, PDO::PARAM_STR);
    $statement1->execute();
    $conn->commit();
    $conn = null;
    $_SESSION["message"] = "Poprawnie dodano nowy cel, oraz stworzono nowy wiersz";
    header("Location: /portfolio/portfel/views/konto.php");
  } else {
    if ($celeUzytkownika[0]["cel2"] == "empty") {
      $query2 = "UPDATE cele SET cel2 = :nowyCel WHERE login = :login";
      $statement2 = $conn->prepare($query2);
      $statement2->bindParam(":nowyCel", $_POST["nowyCel"], PDO::PARAM_STR);
      $statement2->bindParam(":login", $_SESSION["login"], PDO::PARAM_STR);
      $statement2->execute();
      $conn->commit();
      $conn = null;
      $_SESSION["message"] = "Poprawnie dodano nowy cel";
      header("Location: /portfolio/portfel/views/konto.php");
    } else if ($celeUzytkownika[0]["cel3"] == "empty") {
      $query3 = "UPDATE cele SET cel3 = :nowyCel WHERE login = :login";
      $statement3 = $conn->prepare($query3);
      $statement3->bindParam(":nowyCel", $_POST["nowyCel"], PDO::PARAM_STR);
      $statement3->bindParam(":login", $_SESSION["login"], PDO::PARAM_STR);
      $statement3->execute();
      $conn->commit();
      $conn = null;
      $_SESSION["message"] = "Poprawnie dodano nowy cel";
      header("Location: /portfolio/portfel/views/konto.php");
    } else if ($celeUzytkownika[0]["cel4"] == "empty") {
      $query4 = "UPDATE cele SET cel4 = :nowyCel WHERE login = :login";
      $statement4 = $conn->prepare($query4);
      $statement4->bindParam(":nowyCel", $_POST["nowyCel"], PDO::PARAM_STR);
      $statement4->bindParam(":login", $_SESSION["login"], PDO::PARAM_STR);
      $statement4->execute();
      $conn->commit();
      $conn = null;
      $_SESSION["message"] = "Poprawnie dodano nowy cel";
      header("Location: /portfolio/portfel/views/konto.php");
    } else {
      $conn->commit();
      $conn = null;
      $_SESSION["error"] = "Osiągnięto maksymalną ilość celów. Usuń któryś, aby dodać nowy";
      header("Location: /portfolio/portfel/views/konto.php");
    }
  }
}
