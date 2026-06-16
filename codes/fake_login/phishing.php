<?php
// fake_login/phishing.php
// Collecteur de Trames d'Audit Comportemental (Version Sécurisée)

session_start();
header('Content-Type: application/json; charset=utf-8');

// 1. Contrôle d'accès : Seules les requêtes POST sont autorisées
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Méthode non autorisée.']);
    exit();
}

// 2. Récupération et assainissement initial des données reçues
$username = isset($_POST['username']) ? trim($_POST['username']) : '';
$password = isset($_POST['password']) ? trim($_POST['password']) : '';
$sender_id = isset($_POST['sender_id']) ? intval($_POST['sender_id']) : 0;

// 3. Validation de la présence des champs obligatoires
if (empty($username) || empty($password) || $sender_id === 0) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Données de simulation incomplètes ou invalides.']);
    exit();
}

try {
    // Inclusion sécurisée de la connexion à la base de données du laboratoire
    require_once '../traitements/db.php';
    
    // 4. Initialisation de la table de diagnostic (si non existante)
    $create_table_sql = "CREATE TABLE IF NOT EXISTS phishing_captures (
        id INT AUTO_INCREMENT PRIMARY KEY,
        sender_id INT NOT NULL,
        captured_username VARCHAR(255) NOT NULL,
        captured_password VARCHAR(255) NOT NULL,
        captured_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        ip_address VARCHAR(45),
        user_agent TEXT,
        FOREIGN KEY (sender_id) REFERENCES users(id) ON DELETE CASCADE
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";
    
    if (!$conn->query($create_table_sql)) {
        throw new Exception("Erreur lors de la vérification de l'infrastructure de table.");
    }
    
    // 5. Extraction et filtrage des métadonnées réseau
    $raw_ip = $_SERVER['REMOTE_ADDR'] ?? '127.0.0.1';
    $ip_address = filter_var($raw_ip, FILTER_VALIDATE_IP) ? $raw_ip : '0.0.0.0';
    
    $raw_ua = $_SERVER['HTTP_USER_AGENT'] ?? 'Inconnu';
    // Tronquer et assainir la chaîne du User-Agent pour éviter les dépassements ou injections de logs
    $user_agent = mb_strimwidth(strip_tags($raw_ua), 0, 500, "...");
    
    // 6. Insertion sécurisée via requête préparée
    $insert_sql = "INSERT INTO phishing_captures (sender_id, captured_username, captured_password, ip_address, user_agent) 
                   VALUES (?, ?, ?, ?, ?)";
    
    $stmt = $conn->prepare($insert_sql);
    if (!$stmt) {
        throw new Exception("Échec de la préparation de la requête d'audit.");
    }
    
    // Liaison stricte des types (i = entier, s = chaîne)
    $stmt->bind_param("issss", $sender_id, $username, $password, $ip_address, $user_agent);
    
    if ($stmt->execute()) {
        echo json_encode([
            'success' => true, 
            'message' => 'Trame enregistrée avec succès dans le tableau de bord du laboratoire.'
        ]);
    } else {
        throw new Exception("Échec de l'exécution de l'enregistrement.");
    }
    
    $stmt->close();
    $conn->close();

} catch (Exception $e) {
    // Renvoie une erreur propre sans divulguer d'informations sensibles sur l'infrastructure interne
    http_response_code(500);
    echo json_encode([
        'success' => false, 
        'message' => 'Exception système rencontrée lors de la simulation.'
    ]);
}