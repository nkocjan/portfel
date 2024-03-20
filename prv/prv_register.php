<?php
session_start();
if ($_POST["login"] == "") {
  $_SESSION["error"] = "Puste pole login";
  header("Location: /portfel/register.php");
} else if ($_POST["password"] == "") {
  $_SESSION["error"] = "Puste pole haslo";
  header("Location: /portfel/register.php");
} else if (strlen($_POST["password"]) <= 3 || strlen($_POST["password"]) >= 24) {
  $_SESSION["error"] = "Hasło musi zawierać od 3 do 24 znaków";
  header("Location: /portfel/register.php");
} else if ($_POST["passwordRepeat"] == "") {
  $_SESSION["error"] = "Puste pole powtorz haslo";
  header("Location: /portfel/register.php");
} else if ($_POST["passwordRepeat"] != $_POST["password"]) {
  $_SESSION["error"] = "Hasła różnią się od siebie";
  header("Location: /portfel/register.php");
} else if ($_POST["email"] == "") {
  $_SESSION["error"] = "Puste pole email";
  header("Location: /portfel/register.php");
} else {
  $zero = 0;
  require_once "../private/connectDB.php";
  $conn = new PDO("mysql:host=$servernameSQL;dbname=$dbnameSQL", $usernameSQL, $passwordSQL);
  $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  $query = "INSERT INTO users (login, haslo, email, kategorie) VALUES (:login, :haslo, :email, :kategorie)";
  $statement = $conn->prepare($query);

  $statement->bindParam(":login", $_POST["login"], PDO::PARAM_STR);
  $statement->bindParam(":email", $_POST["email"], PDO::PARAM_STR);
  $statement->bindParam(":kategorie", $zero, PDO::PARAM_INT);

  $hashedPassword = password_hash($_POST["password"], PASSWORD_DEFAULT);
  $statement->bindParam(":haslo", $hashedPassword, PDO::PARAM_STR);

  $statement->execute();
  echo "Konto utworzeone pomyślnie";
  $conn = null;
  sleep(7);
  $_SESSION["login"] = $_POST["login"];
  header("Location: ../kategorie-wybor.php");
}
