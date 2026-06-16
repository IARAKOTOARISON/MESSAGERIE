<?php
// traitements/db.php

$host = "localhost";
$user = "root";
$password = "";
$dbname = "messagerie";

// Connexion à la base de données avec gestion des erreurs
$conn = new mysqli($host, $user, $password, $dbname);

if ($conn->connect_error) {
    die("Erreur critique de connexion à la base de données : " . $conn->connect_error);
}

// Configuration du jeu de caractères en utf8mb4 pour supporter tous les caractères/emojis
$conn->set_charset("utf8mb4");
?>