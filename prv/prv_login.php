<?php
session_start();
if ($_POST["password"] == "") {
  $_SESSION["error"] = "Puste pole hasło";
  header("Location: /portfolio/portfel/login.php");
} else if ($_POST["login"] == "") {
  $_SESSION["error"] = "Puste pole login";
  header("Location: /portfolio/portfel/login.php");
} else {

  require_once "../private/connectDB.php";
  $login = $_POST["login"];
  $password = $_POST["password"];
  try {
    $conn = new PDO("mysql:host=$servernameSQL;dbname=$dbnameSQL", $usernameSQL, $passwordSQL);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo ("Connected successfully !!!");
    $query = "SELECT * FROM users WHERE login = :login";
    $statement = $conn->prepare($query);
    $statement->bindParam(":login", $login );
    $statement->execute();
    $results = $statement->fetchAll(PDO::FETCH_ASSOC);
    if (count($results) == 0) {
      $_SESSION["error"] = "Brak użytkownika o takich danych 1";
      $conn = null;
      header("Location: /portfolio/portfel/login.php");
    } else if (password_verify($password, $results[0]['haslo'])) {
      $_SESSION["login"] = $results[0]["login"];
      $_SESSION["haslo"] = $results[0]["haslo"];
      $_SESSION["czy_kategorie_set"] = $results[0]["kategorie"];
      $conn = null;
      unset($_SESSION["error"]);
      header("Location: /portfolio/portfel");
    } else {
      $_SESSION["error"] = "Brak użytkownika o takich danych";
      $conn = null;
      header("Location: /portfolio/portfel/login.php");
    }
  } catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
  }
}
