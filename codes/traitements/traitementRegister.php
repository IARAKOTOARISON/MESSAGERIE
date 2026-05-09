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
        // Vérifier si l'utilisateur existe déjà
        $check_sql = "SELECT id FROM users WHERE username = ?";
        $check_stmt = $conn->prepare($check_sql);
        $check_stmt->bind_param("s", $username);
        $check_stmt->execute();
        $result = $check_stmt->get_result();
        
        if ($result->num_rows > 0) {
            $erreur = "Ce nom d'utilisateur est déjà utilisé.";
        } else {
            // Insérer le nouvel utilisateur
            $insert_sql = "INSERT INTO users (username, password) VALUES (?, ?)";
            $insert_stmt = $conn->prepare($insert_sql);
            $insert_stmt->bind_param("ss", $username, $password);
            
            if ($insert_stmt->execute()) {
                // Redirection vers la page de connexion
                header("Location: ../login.php?succes=1");
                exit();
            } else {
                $erreur = "Erreur lors de l'inscription : " . $conn->error;
            }
            $insert_stmt->close();
        }
        $check_stmt->close();
    }
    
    $conn->close();
    
    // Retour au formulaire avec erreur
    header("Location: ../register.php?erreur=" . urlencode($erreur));
    exit();
}
?>
