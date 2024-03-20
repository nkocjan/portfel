<?php
session_start();
session_destroy();
session_start();
$_SESSION["message"] = "Poprawnie wylogowano";
header("Location: /portfel");
