<?php

$conn = new mysqli(
    "localhost",
    "root",
    "",
    "messagerie"
);

if($conn->connect_error){
    die("Erreur connexion");
}

?>