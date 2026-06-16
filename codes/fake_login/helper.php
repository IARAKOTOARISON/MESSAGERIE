<?php
// fake_login/helper.php
// Générateur de Liens de Démonstration Pédagogique (Version Sécurisée)

session_start();
require_once '../traitements/db.php';

// 1. Contrôle d'accès : Réservé aux membres authentifiés du projet
if (!isset($_SESSION["user_id"])) {
    header("Location: ../login.php");
    exit();
}

$user_id = intval($_SESSION["user_id"]);
$username = $_SESSION["username"] ?? "Utilisateur";

// 2. Détermination sécurisée des variables d'environnement URL
$protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http';
$host = htmlspecialchars($_SERVER['HTTP_HOST'], ENT_QUOTES, 'UTF-8');

// URL de base nettoyée pour correspondre à l'arborescence locale du projet MDI
$baseUrl = $protocol . "://" . $host . "/projects/MDI/cyber-securite/messagerie";

// Encodage sécurisé de l'identifiant pour les paramètres GET
$safe_user_param = urlencode($user_id);

// Génération des différents vecteurs de test documentés
$popupUrl    = $baseUrl . "/codes/assets/popup.js?sender=" . $safe_user_param;
$fakeLoginUrl = $baseUrl . "/codes/fake_login/index.php?sender=" . $safe_user_param;
$dashboardUrl = $baseUrl . "/codes/fake_login/dashboard.php";
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Générateur de Diagnostics - Sensibilisation</title>
    <style>
        :root {
            --primary-color: #2c3e50;
            --teal-accent: #16a085;
            --bg-color: #f4f6f7;
            --card-bg: #ffffff;
            --text-color: #34495e;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: var(--bg-color);
            color: var(--text-color);
            margin: 0;
            padding: 20px;
        }

        .container {
            max-width: 900px;
            margin: 40px auto;
            background: var(--card-bg);
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.05);
        }

        h1 {
            color: var(--primary-color);
            border-bottom: 2px solid #eee;
            padding-bottom: 10px;
            font-size: 1.6rem;
            margin-top: 0;
        }

        h2 {
            font-size: 1.2rem;
            color: var(--primary-color);
            margin-top: 25px;
        }

        .user-badge {
            background-color: #e8f8f5;
            color: var(--teal-accent);
            padding: 5px 12px;
            border-radius: 15px;
            font-weight: bold;
            font-size: 0.9rem;
        }

        .url-box {
            background: #f8f9fa;
            border: 1px solid #e2e8f0;
            padding: 15px;
            border-radius: 6px;
            margin-top: 10px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 15px;
        }

        .url-text {
            font-family: "Courier New", Courier, monospace;
            font-size: 0.85rem;
            color: #c0392b;
            word-break: break-all;
            user-select: all;
        }

        .btn-copy {
            background-color: var(--primary-color);
            color: white;
            border: none;
            padding: 8px 14px;
            border-radius: 4px;
            cursor: pointer;
            font-size: 0.8rem;
            white-space: nowrap;
            transition: background 0.2s;
        }

        .btn-copy:hover {
            background-color: #1a252f;
        }

        .btn-copy.copied {
            background-color: #27ae60;
        }

        .alert-warning {
            background-color: #fef9e7;
            border-left: 4px solid #f39c12;
            color: #7d6608;
            padding: 15px;
            margin-top: 25px;
            border-radius: 4px;
            font-size: 0.9rem;
        }

        .nav-links {
            margin-top: 35px;
            padding-top: 15px;
            border-top: 1px solid #eee;
            display: flex;
            gap: 15px;
        }

        .btn-nav {
            text-decoration: none;
            color: var(--teal-accent);
            font-weight: bold;
            font-size: 0.9rem;
        }

        .btn-nav:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

    <div class="container">
        <h1>🛠️ Centre de Configuration : Liens de Simulation</h1>
        <p>Session active pour l'auditeur : <span class="user-badge"><?= htmlspecialchars($username, ENT_QUOTES, 'UTF-8'); ?> (ID: <?= $user_id; ?>)</span></p>

        <p style="font-size: 0.95rem; color: #7f8c8d;">
            Ces URLs permettent de simuler et d'analyser le comportement des utilisateurs face à des interfaces d'authentification tierces ou à des scripts injectés dans le cadre du laboratoire d'ingénierie sociale.
        </p>

        <h2>Vecteur A : Script d'injection asynchrone (Pop-up)</h2>
        <p style="font-size: 0.85rem; margin: 0;">Simule une inclusion de script JavaScript externe (XSS / Stéganographie) :</p>
        <div class="url-box">
            <span class="url-text" id="link-popup"><?= htmlspecialchars($popupUrl, ENT_QUOTES, 'UTF-8'); ?></span>
            <button class="btn-copy" onclick="copyToClipboard('link-popup', this)">📋 Copier l'URL</button>
        </div>

        <h2>Vecteur B : Copie d'interface autonome (Fake Login Link)</h2>
        <p style="font-size: 0.85rem; margin: 0;">Lien hypertexte direct imitant le portail d'authentification centralisé :</p>
        <div class="url-box">
            <span class="url-text" id="link-fake"><?= htmlspecialchars($fakeLoginUrl, ENT_QUOTES, 'UTF-8'); ?></span>
            <button class="btn-copy" onclick="copyToClipboard('link-fake', this)">📋 Copier l'URL</button>
        </div>

        <div class="alert-warning">
            <strong>📋 Protocole de validation du laboratoire :</strong>
            <ol style="margin-top: 8px; padding-left: 20px;">
                <li>Copiez l'un des vecteurs ci-dessus à l'aide du bouton.</li>
                <li>Utilisez un navigateur secondaire ou une session de navigation privée pour visiter le lien généré.</li>
                <li>Saisissez des identifiants factices de test (ex: <code>demo_user / password123</code>).</li>
                <li>Consultez le tableau de bord pour analyser la structure de la trame interceptée.</li>
            </ol>
        </div>

        <div class="nav-links">
            <a href="dashboard.php" class="btn-nav">📊 Consulter le Dashboard des Logs</a>
            <a href="docs.php" class="btn-nav">📚 Voir la Documentation Théorique</a>
            <a href="../inbox.php" class="btn-nav" style="color: #7f8c8d;">← Retourner à la Boîte Principale</a>
        </div>
    </div>

    <script>
        function copyToClipboard(elementId, button) {
            const textToCopy = document.getElementById(elementId).innerText;
            navigator.clipboard.writeText(textToCopy).then(() => {
                const originalText = button.innerHTML;
                button.textContent = '✓ Copié !';
                button.classList.add('copied');
                setTimeout(() => {
                    button.innerHTML = originalText;
                    button.classList.remove('copied');
                }, 2000);
            }).catch(err => {
                console.error('Erreur lors de la copie : ', err);
            });
        }
    </script>
</body>
</html>