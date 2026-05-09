<?php

$conn = new mysqli(
    "localhost",
    "root",
    "",
    "messagerie"
);

if($conn->connect_error){
    die("Erreur de connexion à la base de données: " . $conn->connect_error);
}

// Définir le charset UTF-8
$conn->set_charset("utf8mb4");

?>