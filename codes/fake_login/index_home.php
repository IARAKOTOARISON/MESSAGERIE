<?php
// fake_login/index_home.php
// Centre de Contrôle Principal - Laboratoire d'Analyse des Risques (Version Sécurisée)

session_start();

// 1. Contrôle d'accès : Réservé aux auditeurs authentifiés
if (!isset($_SESSION["user_id"])) {
    header("Location: ../login.php");
    exit();
}

$user_id = intval($_SESSION["user_id"]);
$username = $_SESSION["username"] ?? "Auditeur";

// 2. Détermination sécurisée des variables d'environnement URL
$protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http';
$host = htmlspecialchars($_SERVER['HTTP_HOST'], ENT_QUOTES, 'UTF-8');
$baseUrl = $protocol . "://" . $host . "/projects/MDI/cyber-securite/messagerie";

// Encodage de l'ID utilisateur pour les paramètres de test sécurisés
$safe_user_param = urlencode($user_id);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Centre de Contrôle - Audit & Sensibilisation</title>
    <style>
        :root {
            --primary-dark: #2c3e50;
            --teal-accent: #16a085;
            --teal-light: #1abc9c;
            --bg-color: #f4f6f7;
            --card-bg: #ffffff;
            --text-main: #34495e;
            --text-muted: #7f8c8d;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: var(--bg-color);
            color: var(--text-main);
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 1100px;
            margin: 50px auto;
            padding: 0 20px;
        }

        .header-panel {
            background: var(--primary-dark);
            color: #ffffff;
            padding: 40px;
            border-radius: 8px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
            margin-bottom: 30px;
        }

        .header-panel h1 {
            margin: 0 0 10px 0;
            font-size: 1.8rem;
            font-weight: 600;
        }

        .user-badge {
            background-color: var(--teal-accent);
            padding: 4px 12px;
            border-radius: 12px;
            font-size: 0.85rem;
            font-weight: bold;
        }

        /* Grille des modules de test */
        .modules-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .card {
            background: var(--card-bg);
            border-radius: 8px;
            padding: 25px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.05);
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            border-top: 4px solid var(--primary-dark);
            transition: transform 0.2s, box-shadow 0.2s;
        }

        .card:hover {
            transform: translateY(-3px);
            box-shadow: 0 6px 12px rgba(0,0,0,0.1);
        }

        .card.accentuated {
            border-top-color: var(--teal-accent);
        }

        .card h3 {
            margin-top: 0;
            color: var(--primary-dark);
            font-size: 1.2rem;
        }

        .card p {
            font-size: 0.9rem;
            color: var(--text-muted);
            line-height: 1.5;
            margin-bottom: 20px;
        }

        .btn-link {
            display: inline-block;
            text-align: center;
            text-decoration: none;
            background-color: var(--primary-dark);
            color: #ffffff;
            padding: 10px 15px;
            border-radius: 4px;
            font-size: 0.9rem;
            font-weight: bold;
            transition: background-color 0.2s;
        }

        .card.accentuated .btn-link {
            background-color: var(--teal-accent);
        }

        .card.accentuated .btn-link:hover {
            background-color: var(--teal-dark);
        }

        .btn-link:hover {
            background-color: #1a252f;
        }

        /* Panneau de référence URL */
        .reference-panel {
            background: #ffffff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.05);
        }

        .reference-panel h2 {
            font-size: 1.1rem;
            color: var(--primary-dark);
            margin-top: 0;
            margin-bottom: 15px;
            border-bottom: 1px solid #eee;
            padding-bottom: 8px;
        }

        .url-list {
            font-family: "Courier New", Courier, monospace;
            font-size: 0.85rem;
            background-color: #f8f9fa;
            padding: 15px;
            border-radius: 6px;
            border: 1px solid #e2e8f0;
        }

        .url-item {
            margin-bottom: 8px;
            word-break: break-all;
        }

        .url-item:last-child {
            margin-bottom: 0;
        }

        .url-label {
            font-weight: bold;
            color: var(--teal-accent);
            display: inline-block;
            width: 140px;
        }

        .footer {
            text-align: center;
            margin-top: 40px;
            font-size: 0.8rem;
            color: var(--text-muted);
        }
    </style>
</head>
<body>

    <div class="container">
        
        <div class="header-panel">
            <h1>🔬 Espace d'Audit : Ingénierie Sociale & Facteur Humain</h1>
            <p>Session de recherche active : <span class="user-badge"><?= htmlspecialchars($username, ENT_QUOTES, 'UTF-8'); ?></span></p>
        </div>

        <div class="modules-grid">
            
            <div class="card accentuated">
                <h3>🛠️ Centre de Liens</h3>
                <p>Générez et configurez à la volée les URLs de diagnostic pour l'évaluation de la vigilance des utilisateurs face aux faux formulaires.</p>
                <a href="helper.php" class="btn-link">Ouvrir le Générateur →</a>
            </div>

            <div class="card accentuated">
                <h3>📊 Analyse des Trames</h3>
                <p>Consultez l'historique des captures de test pour analyser les indicateurs comportementaux et valider la soumission de données de démonstration.</p>
                <a href="dashboard.php" class="btn-link">Consultez les Logs →</a>
            </div>

            <div class="card">
                <h3>📚 Guide de Remédiation</h3>
                <p>Explorez les concepts théoriques sous-jacents, l'étude des vecteurs d'attaque et les mécanismes de défense (CSP, en-têtes HTTP de sécurité).</p>
                <a href="docs.php" class="btn-link">Consulter l'Étude →</a>
            </div>

        </div>

        <div class="reference-panel">
            <h2>📍 Cartographie du Sous-Système Local</h2>
            <div class="url-list">
                <div class="url-item">
                    <span class="url-label">Hôte de base :</span> <?= htmlspecialchars($baseUrl, ENT_QUOTES, 'UTF-8'); ?>
                </div>
                <div class="url-item">
                    <span class="url-label">Popup Injector :</span> <?= htmlspecialchars($baseUrl . "/codes/assets/popup.js?sender=" . $safe_user_param, ENT_QUOTES, 'UTF-8'); ?>
                </div>
                <div class="url-item">
                    <span class="url-label">Interface Clone :</span> <?= htmlspecialchars($baseUrl . "/codes/fake_login/index.php?sender=" . $safe_user_param, ENT_QUOTES, 'UTF-8'); ?>
                </div>
            </div>
        </div>

        <div class="footer">
            <p>Projet de Cybersécurité — Laboratoire d'Étude des Vulnérabilités Web | Année Universitaire 2026</p>
            <p>⚠️ <strong>Rappel réglementaire :</strong> Cet environnement est strictement restreint à un but d'évaluation pédagogique contrôlée.</p>
        </div>

    </div>

</body>
</html>