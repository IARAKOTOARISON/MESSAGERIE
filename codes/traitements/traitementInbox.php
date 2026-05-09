<?php
session_start();

// Vérifier que l'utilisateur est connecté
if (!isset($_SESSION["user_id"])) {
    header("Location: ../login.php");
    exit();
}

include 'db.php';

$user_id = $_SESSION["user_id"];

// Récupérer tous les utilisateurs sauf l'utilisateur courant
$sql = "SELECT id, username FROM users WHERE id != ? ORDER BY username";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$users = $result->fetch_all(MYSQLI_ASSOC);
$stmt->close();
$conn->close();
?>
