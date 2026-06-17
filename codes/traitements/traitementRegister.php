<?php
// traitements/traitementRegister.php
session_start();
include 'db.php';

$erreur = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = isset($_POST['username']) ? trim($_POST['username']) : '';
    $password = isset($_POST['password']) ? trim($_POST['password']) : '';
    
    if (empty($username) || empty($password)) {
        $erreur = "Le nom d'utilisateur et le mot de passe sont requis.";
    } else {
        $check_sql = "SELECT id FROM users WHERE username = ?";
        $check_stmt = $conn->prepare($check_sql);
        $check_stmt->bind_param("s", $username);
        $check_stmt->execute();
        
        if ($check_stmt->get_result()->num_rows > 0) {
            $erreur = "Ce nom d'utilisateur est déjà utilisé.";
        } else {
            // Insertion du mot de passe en clair
            $insert_sql = "INSERT INTO users (username, password) VALUES (?, ?)";
            $insert_stmt = $conn->prepare($insert_sql);
            $insert_stmt->bind_param("ss", $username, $password);
            
            if ($insert_stmt->execute()) {
                header("Location: ../login.php?succes=1");
                exit();
            } else {
                $erreur = "Une erreur interne est survenue.";
            }
        }
    }
    header("Location: ../register.php?erreur=" . urlencode($erreur));
    exit();
}
?>