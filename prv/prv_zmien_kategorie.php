<?php
session_start();
if (!isset($_SESSION["login"])) {
    session_destroy();
    header("Location: /portfolio/portfel/login.php");
} else {
    require_once "../private/connectDB.php";
    try {
        $conn = new PDO("mysql:host=$servernameSQL;dbname=$dbnameSQL", $usernameSQL, $passwordSQL);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $conn->beginTransaction();

        $query = "UPDATE wydatki SET kategoria = :_kategoria WHERE login = :_login AND kwota=:_kwota AND data=:_data AND nazwa=:_nazwa ";
        $statement = $conn->prepare($query);
        $statement->bindParam(":_kategoria", $_POST["kategoria"]);
        $statement->bindParam(":_login", $_SESSION["login"]);
        $statement->bindParam(":_kwota", $_POST["kwota"]);
        $statement->bindParam(":_data", $_POST["data"]);
        $statement->bindParam(":_nazwa", $_POST["nazwa"]);
        $statement->execute();

        $conn->commit();
        $_SESSION["message"] = "poprawnie edytowano kategorie wydatku ".$_POST["nazwa"];
        header("Location: ../views/kategorie.php");
    }
    catch (Exception $e){
        $conn->rollBack();
        $_SESSION["message"] = $e->getMessage();
        header("Location: ../views/kategorie.php");
    }
}