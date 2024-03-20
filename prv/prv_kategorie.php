<?php
session_start();
if (!isset($_SESSION["login"])) {
  sleep(7);
  header("Location: /portfel/login.php");
} else {
  require_once "../private/connectDB.php";
  try {
    $jeden = 1;
    $zero = 0;
    $conn = new PDO("mysql:host=$servernameSQL;dbname=$dbnameSQL", $usernameSQL, $passwordSQL);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $conn->beginTransaction();

    $query = "INSERT INTO kategorie (login, jedzenie, rodzina, przyszlosc, zdrowie, hobby, dom, podroze, inne) VALUES (:login, :jedzenie, :rodzina, :przyszlosc, :zdrowie, :hobby, :dom, :podroze, :inne)";
    $statement = $conn->prepare($query);

    $statement->bindParam(":login", $_SESSION["login"], PDO::PARAM_STR);
    if ($_POST["jedzenie"]) {
      $statement->bindParam(":jedzenie", $jeden, PDO::PARAM_INT);
    } else {
      $statement->bindParam(":jedzenie", $zero, PDO::PARAM_INT);
    }
    if ($_POST["rodzina"]) {
      $statement->bindParam(":rodzina", $jeden, PDO::PARAM_INT);
    } else {
      $statement->bindParam(":rodzina", $zero, PDO::PARAM_INT);
    }
    if ($_POST["przyszlosc"]) {
      $statement->bindParam(":przyszlosc", $jeden, PDO::PARAM_INT);
    } else {
      $statement->bindParam(":przyszlosc", $zero, PDO::PARAM_INT);
    }
    if ($_POST["zdrowie"]) {
      $statement->bindParam(":zdrowie", $jeden, PDO::PARAM_INT);
    } else {
      $statement->bindParam(":zdrowie", $zero, PDO::PARAM_INT);
    }
    if ($_POST["hobby"]) {
      $statement->bindParam(":hobby", $jeden, PDO::PARAM_INT);
    } else {
      $statement->bindParam(":hobby", $zero, PDO::PARAM_INT);
    }
    if ($_POST["dom"]) {
      $statement->bindParam(":dom", $jeden, PDO::PARAM_INT);
    } else {
      $statement->bindParam(":dom", $zero, PDO::PARAM_INT);
    }
    if ($_POST["podroze"]) {
      $statement->bindParam(":podroze", $jeden, PDO::PARAM_INT);
    } else {
      $statement->bindParam(":podroze", $zero, PDO::PARAM_INT);
    }
    if ($_POST["inne"]) {
      $statement->bindParam(":inne", $jeden, PDO::PARAM_INT);
    } else {
      $statement->bindParam(":inne", $zero, PDO::PARAM_INT);
    }

    $statement->execute();

    $query2 = "UPDATE users SET kategorie = :newVal WHERE login = :newWarunek";
    $statement2 = $conn->prepare($query2);

    $newVal = 1;
    $newWarunek = $_SESSION["login"];
    $statement2->bindParam(":newVal", $newVal, PDO::PARAM_INT);
    $statement2->bindParam(":newWarunek", $newWarunek, PDO::PARAM_STR);
    $statement2->execute();

    $conn->commit();

    echo "Dane zapisane pomyślnie";
    $_SESSION["czy_kategorie_set"] = 1;
    $conn = null;
    sleep(5);
    header("Location: /portfel");
  } catch (PDOException $e) {
    echo "Connected failed" . $e->getMessage();
  }
}
