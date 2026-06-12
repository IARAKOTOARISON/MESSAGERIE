<?php
/**
 * Test des formats d'image pour la stéganographie
 */

session_start();

if (!isset($_SESSION["user_id"])) {
    header("Location: ../login.php");
    exit();
}

?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tester les Formats d'Image</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 1000px;
            margin: 50px auto;
            padding: 20px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
        }
        
        .container {
            background: white;
            border-radius: 10px;
            padding: 30px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.2);
        }
        
        h1 {
            color: #333;
            text-align: center;
            margin-bottom: 30px;
        }
        
        .test-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 20px;
            margin: 30px 0;
        }
        
        .test-card {
            border: 2px solid #ddd;
            border-radius: 8px;
            padding: 15px;
            text-align: center;
        }
        
        .test-card h3 {
            color: #667eea;
            margin-bottom: 10px;
        }
        
        .test-card p {
            color: #666;
            font-size: 0.9em;
            margin: 5px 0;
        }
        
        .test-card img {
            max-width: 100%;
            height: 150px;
            object-fit: contain;
            margin: 10px 0;
        }
        
        button {
            background: #667eea;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            width: 100%;
            margin-top: 10px;
        }
        
        button:hover {
            background: #764ba2;
        }
        
        .status {
            text-align: center;
            padding: 15px;
            margin: 20px 0;
            border-radius: 5px;
            font-weight: bold;
        }
        
        .success {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        
        .error {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
        
        .warning {
            background: #fff3cd;
            color: #856404;
            border: 1px solid #ffeeba;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>🖼️ Testeur de Formats d'Image</h1>
        
        <p style="text-align: center; color: #666;">
            Ce test vérifie que votre image est compatible avec la stéganographie.
        </p>
        
        <h2>Importer une Image</h2>
        
        <form id="uploadForm" enctype="multipart/form-data">
            <input type="file" id="imageInput" name="image" accept="image/*" style="padding: 10px; width: 100%; margin: 10px 0;">
            <button type="button" onclick="testImage()">🔍 Analyser l'Image</button>
        </form>
        
        <div id="result"></div>
        
        <hr style="margin: 40px 0;">
        
        <h2>Images Recommandées pour Tester</h2>
        
        <p style="color: #666;">Téléchargez une de ces images et testez-la:</p>
        
        <div class="test-grid">
            <div class="test-card">
                <h3>📸 Photo Aléatoire</h3>
                <p>Image haute résolution</p>
                <p><strong>1920x1280</strong></p>
                <p>Format: JPG</p>
                <a href="https://picsum.photos/1920/1280" download="test_photo.jpg">
                    <button>⬇️ Télécharger</button>
                </a>
            </div>
            
            <div class="test-card">
                <h3>🎨 Image Colorée</h3>
                <p>Bonne résolution</p>
                <p><strong>800x600</strong></p>
                <p>Format: JPG</p>
                <a href="https://picsum.photos/800/600" download="test_color.jpg">
                    <button>⬇️ Télécharger</button>
                </a>
            </div>
            
            <div class="test-card">
                <h3>🖼️ Image PNG</h3>
                <p>Format PNG</p>
                <p><strong>800x600</strong></p>
                <p>Format: PNG</p>
                <a href="https://via.placeholder.com/800x600/667eea/ffffff?text=Test+PNG" download="test_image.png">
                    <button>⬇️ Télécharger</button>
                </a>
            </div>
        </div>
        
        <hr style="margin: 40px 0;">
        
        <h2>💡 Conseils pour les Images</h2>
        
        <div style="background: #f8f9fa; padding: 20px; border-radius: 8px; color: #666; line-height: 1.8;">
            <p>✅ <strong>Format recommandé:</strong> JPG ou PNG</p>
            <p>✅ <strong>Taille minimale:</strong> 100x100 pixels</p>
            <p>✅ <strong>Taille recommandée:</strong> 800x600 ou plus</p>
            <p>✅ <strong>Taille fichier:</strong> jusqu'à 5 MB</p>
            <p>❌ <strong>À éviter:</strong> Très petites images (< 100x100)</p>
            <p>❌ <strong>À éviter:</strong> Fichiers corrompus ou renommés</p>
        </div>
        
        <hr style="margin: 40px 0;">
        
        <p style="text-align: center;">
            <a href="steganography.php" style="color: #667eea; text-decoration: none;">
                ← Retour à la stéganographie
            </a>
        </p>
    </div>
    
    <script>
        function testImage() {
            const fileInput = document.getElementById('imageInput');
            const file = fileInput.files[0];
            const resultDiv = document.getElementById('result');
            
            if (!file) {
                resultDiv.innerHTML = '<div class="status error">❌ Veuillez sélectionner une image</div>';
                return;
            }
            
            const reader = new FileReader();
            
            reader.onload = function(e) {
                const img = new Image();
                
                img.onload = function() {
                    const width = img.width;
                    const height = img.height;
                    const type = file.type;
                    const size = (file.size / 1024).toFixed(2);
                    
                    let html = '<div style="margin: 20px 0;">';
                    
                    // Preview
                    html += '<h3>Aperçu de l\'image:</h3>';
                    html += '<img src="' + e.target.result + '" style="max-width: 100%; max-height: 300px; border-radius: 8px; margin: 10px 0;">';
                    
                    // Détails
                    html += '<h3 style="margin-top: 20px;">📊 Détails de l\'image:</h3>';
                    html += '<table style="width: 100%; border-collapse: collapse; margin: 10px 0;">';
                    html += '<tr style="border-bottom: 1px solid #ddd;">';
                    html += '<td style="padding: 10px; font-weight: bold;">Dimensions:</td>';
                    html += '<td style="padding: 10px;">' + width + 'x' + height + ' pixels</td>';
                    html += '</tr>';
                    
                    html += '<tr style="border-bottom: 1px solid #ddd;">';
                    html += '<td style="padding: 10px; font-weight: bold;">Type MIME:</td>';
                    html += '<td style="padding: 10px;">' + type + '</td>';
                    html += '</tr>';
                    
                    html += '<tr style="border-bottom: 1px solid #ddd;">';
                    html += '<td style="padding: 10px; font-weight: bold;">Taille:</td>';
                    html += '<td style="padding: 10px;">' + size + ' KB</td>';
                    html += '</tr>';
                    
                    html += '</table>';
                    
                    // Validations
                    html += '<h3 style="margin-top: 20px;">✅ Vérifications:</h3>';
                    
                    const checks = [];
                    let isValid = true;
                    
                    // Taille minimale
                    if (width >= 100 && height >= 100) {
                        checks.push('<div class="status success">✅ Dimensions OK (' + width + 'x' + height + ')</div>');
                    } else {
                        checks.push('<div class="status error">❌ Image trop petite! Minimum 100x100 (actuellement ' + width + 'x' + height + ')</div>');
                        isValid = false;
                    }
                    
                    // Type MIME
                    const validTypes = ['image/jpeg', 'image/png', 'image/bmp'];
                    if (validTypes.includes(type)) {
                        checks.push('<div class="status success">✅ Format OK (' + type + ')</div>');
                    } else {
                        checks.push('<div class="status error">❌ Format non supporté (' + type + ')</div>');
                        isValid = false;
                    }
                    
                    // Taille fichier
                    if (file.size <= 5 * 1024 * 1024) {
                        checks.push('<div class="status success">✅ Taille OK (' + size + ' KB)</div>');
                    } else {
                        checks.push('<div class="status error">❌ Fichier trop volumineux (max 5 MB)</div>');
                        isValid = false;
                    }
                    
                    html += checks.join('');
                    
                    // Résultat final
                    if (isValid) {
                        html += '<div class="status success" style="margin-top: 20px; font-size: 1.2em;">✅ Cette image est compatible!</div>';
                        html += '<p style="text-align: center; color: #666; margin-top: 15px;">Vous pouvez utiliser cette image pour la stéganographie.</p>';
                    } else {
                        html += '<div class="status error" style="margin-top: 20px; font-size: 1.2em;">❌ Cette image n\'est pas compatible</div>';
                        html += '<p style="text-align: center; color: #666; margin-top: 15px;">Téléchargez une image plus grande ou dans un format supporté.</p>';
                    }
                    
                    html += '</div>';
                    
                    resultDiv.innerHTML = html;
                };
                
                img.onerror = function() {
                    resultDiv.innerHTML = '<div class="status error">❌ Impossible de lire l\'image. Le fichier peut être corrompu.</div>';
                };
                
                img.src = e.target.result;
            };
            
            reader.readAsDataURL(file);
        }
    </script>
</body>
</html>
