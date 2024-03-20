<?php
session_start();
if (!isset($_SESSION["error"])) {
  $_SESSION["error"] = "";
}
if (!isset($_SESSION["message"])) {
  $_SESSION["message"] = false;
}
if (!isset($_SESSION["login"])) {
  header("Location: /portfel/login.php");
} else {
  try {
    require_once "../private/connectDB.php";
    $conn = new PDO("mysql:host=$servernameSQL;dbname=$dbnameSQL", $usernameSQL, $passwordSQL);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $conn->beginTransaction();
    $query = "SELECT * FROM users WHERE login = :login";
    $statement = $conn->prepare($query);
    $statement->bindParam(":login", $_SESSION["login"], PDO::PARAM_STR);
    $statement->execute();
    $dane = $statement->fetchAll(PDO::FETCH_ASSOC);
    if (count($dane) == 0) {
      $conn = null;
      $_SESSION["message"] = "BŁĄD BRAK USERA < SPRAWDZ KOD >";
      header("Location: /portfel/wyloguj.php");
    } else {
      if (isset($_POST["newLogin1"]) && isset($_POST["haslo1"])) {
        if (strlen($_POST["newLogin1"]) <= 3 || strlen($_POST["newLogin1"]) > 20) {
          $_SESSION["message"] = "Nowy login musi zawierać od 4 do 19 zanków";
          header("Location: /portfel/views/konto.php");
        } else if (!password_verify($_POST["haslo1"], $dane[0]["haslo"])) {
          $_SESSION["message"] = "Podane hasło nie jest prawidłowe. Spróbuj ponownie xddd";
          header("Location: /portfel/views/konto.php");
        } else {
          $query1a = "UPDATE users SET login = :newlogin WHERE login = :login";
          $statement1a = $conn->prepare($query1a);
          $statement1a->bindParam(":newlogin", $_POST["newLogin1"], PDO::PARAM_STR);
          $statement1a->bindParam(":login", $_SESSION["login"], PDO::PARAM_STR);
          $statement1a->execute();

          $query1b = "UPDATE cele SET login = :newlogin WHERE login = :login";
          $statement1b = $conn->prepare($query1b);
          $statement1b->bindParam(":newlogin", $_POST["newLogin1"], PDO::PARAM_STR);
          $statement1b->bindParam(":login", $_SESSION["login"], PDO::PARAM_STR);
          $statement1b->execute();

          $query1c = "UPDATE kategorie SET login = :newlogin WHERE login = :login";
          $statement1c = $conn->prepare($query1c);
          $statement1c->bindParam(":newlogin", $_POST["newLogin1"], PDO::PARAM_STR);
          $statement1c->bindParam(":login", $_SESSION["login"], PDO::PARAM_STR);
          $statement1c->execute();

          $query1d = "UPDATE przychody SET login = :newlogin WHERE login = :login";
          $statement1d = $conn->prepare($query1d);
          $statement1d->bindParam(":newlogin", $_POST["newLogin1"], PDO::PARAM_STR);
          $statement1d->bindParam(":login", $_SESSION["login"], PDO::PARAM_STR);
          $statement1d->execute();

          $query1e = "UPDATE wydatki SET login = :newlogin WHERE login = :login";
          $statement1e = $conn->prepare($query1e);
          $statement1e->bindParam(":newlogin", $_POST["newLogin1"], PDO::PARAM_STR);
          $statement1e->bindParam(":login", $_SESSION["login"], PDO::PARAM_STR);
          $statement1e->execute();
          $conn->commit();
          $conn = null;
          $_SESSION["login"] = $_POST["newLogin1"];
          $_SESSION["message"] = "Poprawnie zmieniono dane";
          header("Location: /portfel/views/konto.php");
        }
      } else if (isset($_POST["newPassword2"]) && isset($_POST["haslo2"])) {
        if (strlen($_POST["newPassword2"]) <= 3) {
          $_SESSION["message"] = "Nowe hasło musi mieć minimum 3 znaki";
          header("Location: /portfel/views/konto.php");
        } else if (!password_verify($_POST["haslo2"], $dane[0]["haslo"])) {
          $_SESSION["message"] = "Podane hasło nie jest prawidłowe. Spróbuj ponownie";
          header("Location: /portfel/views/konto.php");
        } else {
          $query2 = "UPDATE users SET haslo = :haslo WHERE login = :login";
          $statement2 = $conn->prepare($query2);
          $statement2->bindParam(":login", $_SESSION["login"], PDO::PARAM_STR);
          $newPassword = password_hash($_POST["newPassword2"], PASSWORD_DEFAULT);
          $statement2->bindParam(":haslo", $newPassword, PDO::PARAM_STR);
          $statement2->execute();
          $conn->commit();
          $conn = null;
          $_SESSION["message"] = "Poprawnie zmieniono dane";
          header("Location: /portfel/views/konto.php");
        }
      } else if (isset($_POST["newEmail3"]) && isset($_POST["haslo3"])) {
        if (!password_verify($_POST["haslo3"], $dane[0]["haslo"])) {
          $_SESSION["message"] = "Podane hasło nie jest prawidłowe. Spróbuj ponownie";
          header("Location: /portfel/views/konto.php");
        } else {
          $query3 = "UPDATE users SET email = :email WHERE login = :login";
          $statement3 = $conn->prepare($query3);
          $statement3->bindParam(":login", $_SESSION["login"], PDO::PARAM_STR);
          $statement3->bindParam(":email", $_POST["newEmail3"], PDO::PARAM_STR);
          $statement3->execute();
          $conn->commit();
          $conn = null;
          $_SESSION["message"] = "Poprawnie zmieniono dane";
          header("Location: /portfel/views/konto.php");
        }
      } else {
        $_SESSION["message"] = "Mega błąd";
        header("Location: /portfel/views/konto.php");
      }
    }
  } catch (PDOException $e) {
    $_SESSION["message"] = "Błąd w try" . $e->getMessage();
  }
}
