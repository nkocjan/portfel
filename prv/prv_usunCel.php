<?php
session_start();
if (!isset($_SESSION["login"])) {
  $_SESSION["message"] = "Musisz najpierw się zalogować";
  header("Location: /portfel/login.php");
} else {
  try {
    $pusto = "empty";
    require_once "../private/connectDB.php";
    $conn = new PDO("mysql:host=$servernameSQL;dbname=$dbnameSQL", $usernameSQL, $passwordSQL);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $conn->beginTransaction();

    $query = "SELECT * FROM cele WHERE login = :login";
    $statement = $conn->prepare($query);
    $statement->bindParam(":login", $_SESSION["login"], PDO::PARAM_STR);
    $statement->execute();
    $celeUzytkownika = $statement->fetchAll(PDO::FETCH_ASSOC);

    if (count($celeUzytkownika) == 0) {
      header("Location: /portfel/wyloguj.php");
    } else {

      if (isset($_POST["cel1"])) {
        $query1 = "UPDATE cele SET cel1 = :poprzednik1, cel2 = :poprzednik2, cel3 = :poprzednik3, cel4 = :string WHERE login = :login";
        $statement1 = $conn->prepare($query1);

        $statement1->bindParam(":login", $_SESSION["login"], PDO::PARAM_STR);
        $statement1->bindParam(":poprzednik1", $celeUzytkownika[0]["cel2"], PDO::PARAM_STR);
        $statement1->bindParam(":poprzednik2", $celeUzytkownika[0]["cel3"], PDO::PARAM_STR);
        $statement1->bindParam(":poprzednik3", $celeUzytkownika[0]["cel4"], PDO::PARAM_STR);
        $statement1->bindParam(":string", $pusto, PDO::PARAM_STR);
      } else if (isset($_POST["cel2"])) {
        $query1 = "UPDATE cele SET cel2 = :poprzednik2, cel3 = :poprzednik3, cel4 = :string WHERE login = :login";
        $statement1 = $conn->prepare($query1);

        $statement1->bindParam(":login", $_SESSION["login"], PDO::PARAM_STR);
        $statement1->bindParam(":poprzednik2", $celeUzytkownika[0]["cel3"], PDO::PARAM_STR);
        $statement1->bindParam(":poprzednik3", $celeUzytkownika[0]["cel4"], PDO::PARAM_STR);
        $statement1->bindParam(":string", $pusto, PDO::PARAM_STR);
      } else if (isset($_POST["cel3"])) {
        $query1 = "UPDATE cele SET cel3 = :poprzednik, cel4 = :string WHERE login = :login";
        $statement1 = $conn->prepare($query1);

        $statement1->bindParam(":login", $_SESSION["login"], PDO::PARAM_STR);
        $statement1->bindParam(":poprzednik", $celeUzytkownika[0]["cel4"], PDO::PARAM_STR);
        $statement1->bindParam(":string", $pusto, PDO::PARAM_STR);
      } else if (isset($_POST["cel4"])) {
        $query1 = "UPDATE cele SET cel4 = :string WHERE login = :login";
        $statement1 = $conn->prepare($query1);
        $statement1->bindParam(":login", $_SESSION["login"], PDO::PARAM_STR);

        $statement1->bindParam(":string", $pusto, PDO::PARAM_STR);
      } else {
        echo "Coś jest bardzo nie tak";
      }
      $statement1->execute();
      $conn->commit();
      $conn = null;
      $_SESSION["message"] = "Poprawnie usunięto cel";
      header("Location: /portfel/views/konto.php");
    }
  } catch (PDOException $e) {
    $_SESSION["message"] = "Błąd podczas usuwania celu" . $e->getMessage();
  }
}
