<?php
$dsn = "mysql:host=localhost;dbname=thematic_project";
$dbusername = "root";
$dbpassword = "";
$pdo = new PDO($dsn, $dbusername, $dbpassword);
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
ini_set('display_errors', 1);
error_reporting(E_ALL);