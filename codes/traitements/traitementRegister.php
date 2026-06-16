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
        // Vérifier de manière sécurisée si le nom d'utilisateur existe déjà
        $check_sql = "SELECT id FROM users WHERE username = ?";
        $check_stmt = $conn->prepare($check_sql);
        $check_stmt->bind_param("s", $username);
        $check_stmt->execute();
        $result = $check_stmt->get_result();
        
        if ($result->num_rows > 0) {
            $erreur = "Ce nom d'utilisateur est déjà utilisé.";
        } else {
            // Hachage sécurisé du mot de passe
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            
            // Insertion du nouvel utilisateur
            $insert_sql = "INSERT INTO users (username, password) VALUES (?, ?)";
            $insert_stmt = $conn->prepare($insert_sql);
            $insert_stmt->bind_param("ss", $username, $hashed_password);
            
            if ($insert_stmt->execute()) {
                $insert_stmt->close();
                $check_stmt->close();
                $conn->close();
                header("Location: ../login.php?succes=1");
                exit();
            } else {
                $erreur = "Une erreur interne est survenue lors de l'inscription.";
            }
            $insert_stmt->close();
        }
        $check_stmt->close();
    }
    
    $conn->close();
    header("Location: ../register.php?erreur=" . urlencode($erreur));
    exit();
}
?>