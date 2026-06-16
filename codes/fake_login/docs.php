<?php
// fake_login/docs.php
// Page de Documentation et de Sensibilisation Pédagogique

session_start();

// 1. Contrôle d'accès : Réservé aux utilisateurs authentifiés du laboratoire
if (!isset($_SESSION["user_id"])) {
    header("Location: ../login.php");
    exit();
}

$user_id = $_SESSION["user_id"];

// Configuration sécurisée des chemins de l'environnement local
$protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http';
$host = htmlspecialchars($_SERVER['HTTP_HOST'], ENT_QUOTES, 'UTF-8');
$baseUrl = $protocol . "://" . $host . "/projects/MDI/cyber-securite/messagerie";

?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Documentation Pédagogique - Sensibilisation Sécurité</title>
    <style>
        :root {
            --primary-color: #2c3e50;
            --accent-green: #27ae60;
            --warning-orange: #d35400;
            --bg-color: #f4f6f7;
            --card-bg: #ffffff;
            --text-color: #34495e;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: var(--bg-color);
            color: var(--text-color);
            line-height: 1.6;
            margin: 0;
            padding: 0;
            display: flex;
        }

        /* Sidebar de navigation */
        .sidebar {
            width: 280px;
            background: var(--primary-color);
            color: white;
            height: 100vh;
            position: fixed;
            top: 0;
            left: 0;
            padding: 30px 20px;
            box-sizing: border-box;
        }

        .sidebar h3 {
            margin-top: 0;
            padding-bottom: 15px;
            border-bottom: 1px solid rgba(255,255,255,0.1);
            font-size: 1.2rem;
        }

        .sidebar a {
            display: block;
            color: #ecf0f1;
            text-decoration: none;
            padding: 10px 12px;
            border-radius: 4px;
            margin-bottom: 8px;
            font-size: 0.95rem;
            transition: background 0.2s;
        }

        .sidebar a:hover {
            background: rgba(255, 255, 255, 0.1);
        }

        /* Contenu principal */
        .main-content {
            margin-left: 280px;
            padding: 40px;
            max-width: 900px;
            width: calc(100% - 280px);
            box-sizing: border-box;
        }

        .section {
            background: var(--card-bg);
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.05);
            margin-bottom: 30px;
        }

        h1 {
            color: var(--primary-color);
            margin-top: 0;
        }

        h2 {
            color: var(--primary-color);
            border-bottom: 2px solid #ecf0f1;
            padding-bottom: 8px;
            margin-top: 0;
        }

        code {
            background: #f8f9fa;
            padding: 3px 6px;
            border-radius: 4px;
            font-family: "Courier New", Courier, monospace;
            color: #c0392b;
            font-size: 0.9rem;
        }

        .alert {
            padding: 15px;
            border-radius: 4px;
            margin: 15px 0;
            font-size: 0.95rem;
        }

        .alert-danger {
            background-color: #fdebd0;
            border-left: 4px solid var(--warning-orange);
            color: #7e5109;
        }

        .alert-success {
            background-color: #e8f8f5;
            border-left: 4px solid var(--accent-green);
            color: #0ebd73;
        }

        .btn-group {
            display: flex;
            gap: 15px;
            margin-top: 20px;
        }

        .btn {
            display: inline-block;
            text-decoration: none;
            padding: 10px 20px;
            background-color: var(--primary-color);
            color: white;
            border-radius: 4px;
            font-size: 0.9rem;
            font-weight: bold;
        }

        .btn-secondary {
            background-color: #7f8c8d;
        }
    </style>
</head>
<body>

    <div class="sidebar">
        <h3>📚 Guide d'Étude</h3>
        <a href="#concept">1. Concept d'Hameçonnage</a>
        <a href="#analyse">2. Analyse de la Vulnérabilité</a>
        <a href="#remediation">3. Remédiation & Défense</a>
        <a href="#outils">4. Cartographie des Modules</a>
    </div>

    <div class="main-content">
        <h1>Classeur de Laboratoire : Analyse des Vecteurs d'Attaque Formulaires</h1>
        
        <div class="alert alert-danger">
            <strong>Avertissement de conformité :</strong> Ce module est configuré exclusivement pour un usage en environnement de test isolé (sandbox). L'usage de ces techniques sur des réseaux publics ou sans consentement explicite est formellement interdit par la réglementation sur le droit de la cybersécurité.
        </div>

        <div id="concept" class="section">
            <h2>1. Mécanisme de la fausse interface (Fake Login)</h2>
            <p>
                L'ingénierie sociale par duplication d'interface consiste à reproduire l'identité visuelle (CSS, structure HTML) d'un service légitime pour tromper l'opérateur humain. L'objectif technique analysé ici est le manque de vérification de l'origine de l'URL par l'utilisateur.
            </p>
            <p>
                Lorsqu'un utilisateur soumet ses données sur une réplique non authentique, les données sont interceptées et journalisées au lieu d'initier une session réelle sur le serveur de production.
            </p>
        </div>

        <div id="analyse" class="section">
            <h2>2. Analyse des vulnérabilités sous-jacentes</h2>
            <p>Dans l'application de messagerie de base, plusieurs faiblesses structurelles facilitent ce scénario :</p>
            <ul>
                <li><strong>Absence de filtrage CSP :</strong> L'absence d'en-tête de restriction permet l'injection de scripts JavaScript tiers (comme les pop-ups asynchrones).</li>
                <li><strong>Manque d'indicateurs de confiance :</strong> Les utilisateurs n'analysent pas systématiquement le nom de domaine ou l'hôte indiqué dans la barre d'adresse avant de saisir un secret.</li>
                <li><strong>Stockage non chiffré (Ancienne version) :</strong> Les identifiants saisis par erreur étaient stockés textuellement, augmentant le niveau de risque en cas de fuite du fichier de log.</li>
            </ul>
        </div>

        <div id="remediation" class="section">
            <h2>3. Contre-mesures techniques de protection</h2>
            <p>Pour immuniser une infrastructure web contre la réplication et l'exploitation d'interfaces, appliquez les directives suivantes :</p>
            
            <div class="alert alert-success">
                <strong>1. Injection d'en-têtes HTTP de Sécurité :</strong><br>
                Configurez le fichier <code>.htaccess</code> ou l'en-tête de réponse PHP pour interdire l'encapsulation de vos pages dans des cadres malveillants :
                <br><code>Header set X-Frame-Options "DENY"</code>
            </div>

            <div class="alert alert-success">
                <strong>2. Utilisation de l'authentification Multi-Facteurs (MFA) :</strong><br>
                Même si un tiers intercepte le couple identifiant/mot de passe initial via un faux formulaire, l'accès reste bloqué sans le jeton dynamique à usage unique (TOTP) généré sur l'appareil de confiance de l'utilisateur.
            </div>
        </div>

        <div id="outils" class="section">
            <h2>4. Liens et navigation des composants d'audit</h2>
            <p>Utilisez les raccourcis ci-dessous pour naviguer entre les différents modules de diagnostic de l'environnement de test :</p>
            
            <div class="btn-group">
                <a href="helper.php" class="btn">📌 Générateur d'Analyse</a>
                <a href="dashboard.php" class="btn">📊 Tableau de Bord des Logs</a>
                <a href="../inbox.php" class="btn btn-secondary">Retour à l'Application</a>
            </div>
        </div>
    </div>

</body>
</html>