<?php
session_start();
if (!isset($_SESSION["login"])) {
  session_destroy();
  header("Location: /portfel/login.php");
} else {

  require_once "../private/connectDB.php";
  try {
    $conn = new PDO("mysql:host=$servernameSQL;dbname=$dbnameSQL", $usernameSQL, $passwordSQL);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $conn->beginTransaction();

    if($_POST["type"] == "wydatek"){
        $query = "DELETE FROM wydatki WHERE login = :_login AND kwota = :_kwota AND data = :_data AND nazwa = :_nazwa;";
    }else {
        $query = "DELETE FROM przychody WHERE login = :_login AND kwota = :_kwota AND data = :_data AND nazwa = :_nazwa;";
    }
    $statement1 = $conn->prepare($query);
    $statement1->bindParam(":_login", $_SESSION["login"], PDO::PARAM_STR);
    $statement1->bindParam(":_kwota", $_POST["kwota"], PDO::PARAM_STR);
    $statement1->bindParam(":_nazwa", $_POST["nazwa"], PDO::PARAM_STR);
    $statement1->bindParam(":_data", $_POST["data"], PDO::PARAM_STR);
    $statement1->execute();
    $conn->commit();


      if($_POST["type"] == "wydatek"){
          $_SESSION["message"] = "Poprawnie usunięto wydatek";
      }else {
          $_SESSION["message"] = "Poprawnie usunięto przychód";
      }

      if($_POST["type"] == "wydatek"){
          header("Location: /portfel/views/wydatki.php");
      }else {
          header("Location: /portfel/views/przychody.php");
      }

  } catch (Exception $e) {
    $conn->rollBack();
    $_SESSION["message"] = $e->getMessage();
      if($_POST["type"] == "wydatek"){
          header("Location: /portfel/views/wydatki.php");
      }else {
          header("Location: /portfel/views/przychody.php");
      }
  }
}
