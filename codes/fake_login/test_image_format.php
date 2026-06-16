<?php
// fake_login/test_image_format.php
// Script de Diagnostic Technique des Formats de Fichiers (Version Sécurisée)

session_start();

// 1. Contrôle d'accès : Réservé aux membres authentifiés du laboratoire
if (!isset($_SESSION["user_id"])) {
    header("Location: ../login.php");
    exit();
}

$username = $_SESSION["username"] ?? "Auditeur";
$analyse_output = "";
$status_type = "";

// 2. Traitement sécurisé de la soumission du fichier en POST
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['file_to_test'])) {
    $file = $_FILES['file_to_test'];
    
    if ($file['error'] !== UPLOAD_ERR_OK) {
        $analyse_output = "Erreur de transmission réseau (Code : " . intval($file['error']) . ").";
        $status_type = "error";
    } else {
        $file_path = $file['tmp_name'];
        $file_name = basename($file['name']);
        $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
        
        // Lecture sécurisée des premiers octets (Signatures/Octets magiques ou Magic Numbers)
        $handle = @fopen($file_path, 'rb');
        if ($handle) {
            $bytes = bin2hex(fread($handle, 4));
            fclose($handle);
            
            // Analyse de l'en-tête réel (Magic Bytes) indépendamment de l'extension
            $real_format = "Inconnu ou Corrompu";
            if (strpos($bytes, 'ffd8ffe0') === 0 || strpos($bytes, 'ffd8ffe1') === 0 || strpos($bytes, 'ffd8ffed') === 0) {
                $real_format = "JPEG / JPG";
            } elseif (strpos($bytes, '89504e47') === 0) {
                $real_format = "PNG";
            } elseif (strpos($bytes, '47494638') === 0) {
                $real_format = "GIF";
            } elseif (strpos($bytes, '424d') === 0) {
                $real_format = "BMP (Bitmap Windows)";
            }

            // Utilisation de la bibliothèque d'images GD pour extraire les métadonnées de structure
            $image_info = @getimagesize($file_path);
            
            if ($image_info) {
                $width = intval($image_info[0]);
                $height = intval($image_info[1]);
                $mime = htmlspecialchars($image_info['mime'], ENT_QUOTES, 'UTF-8');
                $size_mb = round($file['size'] / (1024 * 1024), 2);

                $analyse_output = "<h3>📊 Rapport d'Analyse Structurelle :</h3>";
                $analyse_output .= "• <strong>Nom d'origine :</strong> " . htmlspecialchars($file_name, ENT_QUOTES, 'UTF-8') . "<br>";
                $analyse_output .= "• <strong>Extension déclarée :</strong> <code>." . htmlspecialchars($file_ext, ENT_QUOTES, 'UTF-8') . "</code><br>";
                $analyse_output .= "• <strong>Signature binaire (4 premiers octets) :</strong> <code>0x" . strtoupper($bytes) . "</code><br>";
                $analyse_output .= "• <strong>Format identifié par en-tête :</strong> " . $real_format . "<br>";
                $analyse_output .= "• <strong>Type MIME matériel :</strong> <code>" . $mime . "</code><br>";
                $analyse_output .= "• <strong>Résolution géométrique :</strong> " . $width . " x " . $height . " pixels<br>";
                $analyse_output .= "• <strong>Espace mémoire disque :</strong> " . $size_mb . " Mo (" . number_format($file['size']) . " octets)";
                $status_type = "success";
            } else {
                $analyse_output = "❌ <strong>Anomalie détectée :</strong> Le fichier possède la signature binaire <code>0x" . strtoupper($bytes) . "</code> mais sa structure interne ou ses tables de pixels sont corrompues. Il est inutilisable pour des tests stéganographiques.";
                $status_type = "error";
            }
        } else {
            $analyse_output = "Impossible d'accéder au flux binaire temporaire.";
            $status_type = "error";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Outil d'Audit : Vérification de Formats d'Images</title>
    <style>
        :root {
            --bg-page: #ffffff;
            --text-dark: #2c3e50;
            --text-secondary: #7f8c8d;
            --teal-accent: #16a085;
            --border-light: #e2e8f0;
            --bg-box: #f8f9fa;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
            background-color: var(--bg-page);
            color: var(--text-dark);
            line-height: 1.5;
            margin: 0;
            padding: 20px;
        }

        .wrapper {
            max-width: 800px;
            margin: 40px auto;
            border: 1px solid var(--border-light);
            border-radius: 8px;
            padding: 35px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.01);
        }

        h1 {
            font-size: 1.6rem;
            font-weight: 600;
            margin-top: 0;
            padding-bottom: 12px;
            border-bottom: 2px solid var(--bg-box);
        }

        .session-info {
            font-size: 0.85rem;
            color: var(--text-secondary);
            margin-bottom: 20px;
        }

        .upload-zone {
            background-color: var(--bg-box);
            border: 1px dashed var(--border-light);
            padding: 25px;
            border-radius: 6px;
            margin-bottom: 25px;
        }

        .form-group {
            margin-bottom: 15px;
        }

        .btn-submit {
            background-color: var(--text-dark);
            color: #ffffff;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            font-size: 0.9rem;
            font-weight: 600;
            cursor: pointer;
            transition: background-color 0.2s;
        }

        .btn-submit:hover {
            background-color: #1a252f;
        }

        .report-panel {
            padding: 20px;
            border-radius: 4px;
            font-size: 0.9rem;
            margin-top: 20px;
            border-left: 4px solid #bdc3c7;
        }

        .report-panel.success {
            background-color: #e8f8f5;
            border-left-color: var(--teal-accent);
            color: #117a65;
        }

        .report-panel.error {
            background-color: #fcedec;
            border-left-color: #e74c3c;
            color: #c0392b;
        }

        code {
            background: rgba(0, 0, 0, 0.05);
            padding: 2px 5px;
            border-radius: 3px;
            font-family: monospace;
        }

        .theory-section {
            margin-top: 35px;
            border-top: 1px solid var(--border-light);
            padding-top: 20px;
        }

        .theory-section h2 {
            font-size: 1.1rem;
            color: var(--teal-accent);
        }

        .theory-section p {
            font-size: 0.9rem;
            color: var(--text-dark);
        }

        .navigation-footer {
            margin-top: 30px;
            display: flex;
            gap: 20px;
            font-size: 0.9rem;
        }

        .navigation-footer a {
            color: var(--teal-accent);
            text-decoration: none;
            font-weight: 600;
        }

        .navigation-footer a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

    <div class="wrapper">
        <h1>Analyseur de Structure et Validation d'En-têtes Binaires</h1>
        <div class="session-info">
            Espace d'expérimentation — Auditeur actif : <strong><?= htmlspecialchars($username, ENT_QUOTES, 'UTF-8'); ?></strong>
        </div>

        <p>
            Ce module permet de décortiquer les métadonnées et signatures de bas niveau des conteneurs de fichiers. Il sert à valider qu'une image n'a pas subi de modification malveillante de son en-tête ou de masquage de type de contenu (MIME bypass) avant d'être traitée par l'application.
        </p>

        <div class="upload-zone">
            <form action="test_image_format.php" method="POST" enctype="multipart/form-data">
                <div class="form-group">
                    <label for="file_to_test" style="display:block; font-weight:600; margin-bottom:8px; font-size:0.9rem;">Sélectionner le fichier à analyser :</label>
                    <input type="file" id="file_to_test" name="file_to_test" required>
                </div>
                <button type="submit" class="btn-submit">Extraire la signature binaire</button>
            </form>
        </div>

        <?php if (!empty($analyse_output)): ?>
            <div class="report-panel <?= $status_type; ?>">
                <?= $analyse_output; ?>
            </div>
        <?php endif; ?>

        <div class="theory-section">
            <h2>💡 Importance pédagogique des "Magic Numbers"</h2>
            <p>
                L'extension d'un fichier (ex: <code>.png</code>) n'est qu'une indication superficielle pour le système d'exploitation. Les applications sécurisées vérifient systématiquement les premiers octets du fichier en mémoire (les <i>Magic Numbers</i>) pour confirmer la nature exacte du flux de données. Cette analyse est la première ligne de défense contre le téléversement de scripts malveillants renommés abusivement en images.
            </p>
        </div>

        <div class="navigation-footer">
            <a href="index_home.php">← Centre de contrôle</a>
            <a href="steganography.php">Module Stéganographie</a>
            <a href="../inbox.php" style="color: #7f8c8d;">Retour à la messagerie</a>
        </div>
    </div>

</body>
</html>