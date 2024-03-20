<?php
session_start();
if (!isset($_SESSION["login"])) {
  header("Location: /portfel/login.php");
} else if (!isset($_POST["nazwa"])) {
  header("Location: /portfel/login.php");
} else if ($_POST["nazwa"] == "" || $_POST["kwota"] == "" || $_POST["data"] == "") {
  $_SESSION["message"] = "Musisz wypełnić nazwe, kwotę, datę";
  header("Location: /portfel/views/przychody.php");
} else {

  if (strlen($_POST["nazwa"]) < 4 || strlen($_POST["nazwa"]) > 30) {
    $_SESSION["message"] = "Nazwa musi zawierać od 4 do 30 znaków";
    header("Location: /portfel/views/przychody.php");
  } else {
    require_once("../private/connectDB.php");
    $conn = new PDO("mysql:host=$servernameSQL;dbname=$dbnameSQL", $usernameSQL, $passwordSQL);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    try {
      $conn->beginTransaction();

      $query = "INSERT INTO przychody (login, kwota, nazwa, data) VALUES ( :_login, :_kwota, :_nazwa, :_data)";
      $statement = $conn->prepare($query);
      $statement->bindParam(":_login", $_SESSION["login"], PDO::PARAM_STR);
      $statement->bindParam(":_kwota", $_POST["kwota"], PDO::PARAM_STR);
      $statement->bindParam(":_nazwa", $_POST["nazwa"], PDO::PARAM_STR);
      $statement->bindParam(":_data", $_POST["data"], PDO::PARAM_STR);

      $statement->execute();
      $conn->commit();

      $_SESSION["message"] = "Poprawnie dodano nowy przychod";
      header("Location: /portfel/views/przychody.php");
    } catch (Exception $e) {
      $conn->rollBack();
      $conn = null;
      $_SESSION["message"] = "Niepoprawnie dodano nowy przychod";
      header("Location: /portfel/views/przychody.php");
    }
  }
}
