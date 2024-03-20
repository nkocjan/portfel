<?php
session_start();
if(!isset($_SESSION["login"])){
    $_SESSION["message"] = "Musisz się zalogowac";
    header("Location: ../login.php");
}else if(strlen($_POST["nazwa"]) <= 3 || strlen($_POST["nazwa"]) > 30){
    $_SESSION["message"] = "Nazwa musi zawierac od 3 do 30 znakow";
    header("Location: ../views/wydatki.php");
}else if($_POST["nazwa"] == "" || $_POST["kwota"] == "" || $_POST["data"] == "") {
    $_SESSION["message"] = "Wypełnij wszystkie pola";
    header("Location: ../views/wydatki.php");
} else {
    require_once "../private/connectDB.php";
    try{
        $conn = new PDO("mysql:host=$servernameSQL;dbname=$dbnameSQL", $usernameSQL, $passwordSQL);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $conn->beginTransaction();

        $query = "INSERT INTO wydatki (login, nazwa, kategoria, kwota, data) VALUES (:_login, :_nazwa,:_kategoria, :_kwota, :_data)";
        $statement = $conn->prepare($query);
        $statement->bindParam(":_login", $_SESSION["login"]);
        $statement->bindParam(":_nazwa", $_POST["nazwa"]);
        $statement->bindParam(":_data", $_POST["data"]);
        $statement->bindParam(":_kwota", $_POST["kwota"]);


        $kat = strtolower($_POST["kategoria"]);
        if($kat == "kategoria"){
            $kat = "inne";
        }
        $statement->bindParam(":_kategoria", $kat);
        $statement->execute();
        $conn->commit();
        $conn = null;
        $_SESSION["message"] = "Poprawnie dodano nowy wydatek";
        header("Location: ../views/wydatki.php");
    }catch(Exception $e){
        $conn->rollBack();
        $conn = null;
        $_SESSION["message"] = $e->getMessage();
        header("Location: ../views/wydatki.php");
    }
}