<?php
// traitements/traitementLogin.php
session_start();
include 'db.php';

$erreur = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = isset($_POST['username']) ? trim($_POST['username']) : '';
    $password = isset($_POST['password']) ? trim($_POST['password']) : '';
    
    if (empty($username) || empty($password)) {
        $erreur = "Le nom d'utilisateur et le mot de passe sont requis.";
    } else {
        // Sélectionner l'utilisateur par son nom
        $sql = "SELECT id, username, password FROM users WHERE username = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows === 1) {
            $user = $result->fetch_assoc();
            
            // Vérification de l'empreinte du mot de passe
            if (password_verify($password, $user['password'])) {
                // Initialisation des variables de session
                $_SESSION["user_id"] = $user['id'];
                $_SESSION["username"] = $user['username'];
                
                $stmt->close();
                $conn->close();
                header("Location: ../inbox.php");
                exit();
            } else {
                $erreur = "Nom d'utilisateur ou mot de passe incorrect.";
            }
        } else {
            $erreur = "Nom d'utilisateur ou mot de passe incorrect.";
        }
        $stmt->close();
    }
    
    $conn->close();
    header("Location: ../login.php?erreur=" . urlencode($erreur));
    exit();
}
?>