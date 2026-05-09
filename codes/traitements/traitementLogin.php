<?php
session_start();

include 'db.php';

$erreur = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';
    
    // Validation
    if (empty($username) || empty($password)) {
        $erreur = "Le nom d'utilisateur et le mot de passe sont requis.";
    } else {
        // Vérifier les credentials
        $sql = "SELECT id, username FROM users WHERE username = ? AND password = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ss", $username, $password);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows === 1) {
            $user = $result->fetch_assoc();
            
            // Démarrer la session
            $_SESSION["user_id"] = $user['id'];
            $_SESSION["username"] = $user['username'];
            
            $stmt->close();
            $conn->close();
            
            // Redirection vers inbox
            header("Location: ../inbox.php");
            exit();
        } else {
            $erreur = "Nom d'utilisateur ou mot de passe incorrect.";
        }
        $stmt->close();
    }
    
    $conn->close();
    
    // Redirection vers la page de connexion avec erreur
    header("Location: ../login.php?erreur=" . urlencode($erreur));
    exit();
}
?>
