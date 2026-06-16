<?php
/**
 * fake_login/debug_steghide.php
 * Page de diagnostic et d'analyse d'environnement pour Steghide (Version Sécurisée)
 */

session_start();

// 1. Contrôle d'accès rigoureux
if (!isset($_SESSION["user_id"])) {
    header("Location: ../login.php");
    exit();
}

$user_id = $_SESSION["user_id"];
$upload_dir = __DIR__ . '/../uploads/images/';

// Initialisation des variables de diagnostic
$system_errors = [];
$steghide_version = "Non détecté";
$is_writable = is_writable($upload_dir);

// Vérification de la présence de steghide sur le système de manière sécurisée
$check_bin = shell_exec('which steghide 2>&1');
if (!empty($check_bin)) {
    // Récupération de la version si disponible
    $version_output = shell_exec('steghide --version 2>&1');
    if ($version_output) {
        $steghide_version = trim(explode("\n", $version_output)[0]);
    }
} else {
    $system_errors[] = "Le binaire 'steghide' n'est pas installé ou n'est pas accessible dans le PATH du serveur.";
}

?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Debug Steghide - Analyse de l'Environnement</title>
    <style>
        :root {
            --bg-gradient: linear-gradient(135deg, #2c3e50 0%, #3498db 100%);
            --card-bg: #ffffff;
            --text-main: #333333;
            --success-color: #27ae60;
            --error-color: #c0392b;
            --warning-color: #f39c12;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: var(--bg-gradient);
            min-height: 100vh;
            margin: 0;
            padding: 30px 20px;
            color: var(--text-main);
        }

        .container {
            max-width: 900px;
            margin: 0 auto;
            background: var(--card-bg);
            border-radius: 8px;
            padding: 30px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.2);
        }

        h1, h2 {
            color: #2c3e50;
            margin-top: 0;
        }

        h1 {
            border-bottom: 2px solid #ecf0f1;
            padding-bottom: 15px;
            font-size: 1.8rem;
        }

        h2 {
            font-size: 1.3rem;
            margin-top: 25px;
            border-bottom: 1px solid #ecf0f1;
            padding-bottom: 8px;
        }

        .grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-top: 20px;
        }

        .card {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 6px;
            border-left: 4px solid #bdc3c7;
        }

        .card.success { border-left-color: var(--success-color); }
        .card.error { border-left-color: var(--error-color); }
        .card.warning { border-left-color: var(--warning-color); }

        .status-badge {
            display: inline-block;
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 0.85rem;
            font-weight: bold;
            color: white;
            margin-top: 5px;
        }

        .bg-success { background-color: var(--success-color); }
        .bg-error { background-color: var(--error-color); }

        code {
            background: #f1f3f5;
            padding: 3px 6px;
            border-radius: 4px;
            font-family: "Courier New", Courier, monospace;
            color: #e74c3c;
            font-size: 0.9rem;
        }

        .alert {
            padding: 15px;
            border-radius: 4px;
            margin-top: 15px;
            font-size: 0.95rem;
        }

        .alert-warning {
            background-color: #fef9e7;
            border: 1px solid #f9e79f;
            color: #7d6608;
        }

        ul {
            margin-left: 20px;
            padding-left: 0;
        }

        li { margin-bottom: 8px; }

        .btn-back {
            display: inline-block;
            margin-top: 30px;
            text-decoration: none;
            color: #3498db;
            font-weight: bold;
            transition: color 0.2s;
        }

        .btn-back:hover {
            color: #2980b9;
        }
    </style>
</head>
<body>

    <div class="container">
        <h1>🔍 Diagnostic de l'environnement Stéganographie</h1>
        
        <div class="grid">
            <div class="card <?= empty($system_errors) ? 'success' : 'error'; ?>">
                <strong>Détection du binaire :</strong>
                <div><code>/usr/bin/steghide</code></div>
                <span class="status-badge <?= empty($system_errors) ? 'bg-success' : 'bg-error'; ?>">
                    <?= empty($system_errors) ? 'Disponible' : 'Introuvable'; ?>
                </span>
                <p style="font-size: 0.85rem; margin: 8px 0 0 0; color: #7f8c8d;">
                    Version : <?= htmlspecialchars($steghide_version, ENT_QUOTES, 'UTF-8'); ?>
                </p>
            </div>

            <div class="card <?= $is_writable ? 'success' : 'error'; ?>">
                <strong>Permissions du dossier cible :</strong>
                <div style="font-size: 0.85rem; word-break: break-all;"><code><?= htmlspecialchars($upload_dir, ENT_QUOTES, 'UTF-8'); ?></code></div>
                <span class="status-badge <?= $is_writable ? 'bg-success' : 'bg-error'; ?>">
                    <?= $is_writable ? 'Écritures autorisées (0755/0777)' : 'Écritures interdites'; ?>
                </span>
            </div>
        </div>

        <?php
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['test_image'])) {
            echo "<h2>Analyse du fichier soumis</h2>";
            echo "<div class='card'>";
            
            $file = $_FILES['test_image'];
            $file_ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
            $allowed_exts = ['jpg', 'jpeg', 'png', 'bmp'];
            
            if ($file['error'] !== UPLOAD_ERR_OK) {
                echo "<p style='color:var(--error-color);'>❌ Erreur de téléversement réseau (Code " . intval($file['error']) . ").</p>";
            } elseif (!in_array($file_ext, $allowed_exts)) {
                echo "<p style='color:var(--error-color);'>❌ Extension <code>." . htmlspecialchars($file_ext, ENT_QUOTES, 'UTF-8') . "</code> non supportée par défaut.</p>";
            } else {
                // Analyse des propriétés physiques de l'image pour estimer sa résistance au buffer overflow
                $image_info = @getimagesize($file['tmp_name']);
                if ($image_info) {
                    $width = $image_info[0];
                    $height = $image_info[1];
                    $size_kb = round($file['size'] / 1024, 2);
                    
                    echo "<p><strong>Dimensions de l'image :</strong> " . intval($width) . " x " . intval($height) . " pixels</p>";
                    echo "<p><strong>Taille du fichier :</strong> " . $size_kb . " KB</p>";
                    
                    // Analyse prédictive des anomalies de mémoire (Steghide requiert une capacité d'accueil suffisante)
                    if ($width < 300 || $height < 300 || $file['size'] < 50 * 1024) {
                        echo "<div class='alert alert-warning'><strong>⚠️ Risque d'erreur (Capacité insuffisante) :</strong> L'image sélectionnée est trop petite. L'intégration de charges utiles (payloads) dans de petits conteneurs compressés peut provoquer des anomalies de structure ou des comportements imprévus lors de l'exécution de la commande système.</div>";
                    } else {
                        echo "<p style='color:var(--success-color);'>✅ Les caractéristiques physiques de l'image (dimensions et poids) conviennent pour des tests d'intégration stéganographique.</p>";
                    }
                } else {
                    echo "<p style='color:var(--error-color);'>❌ Le fichier n'est pas reconnu comme une image valide par la bibliothèque GD (En-tête corrompue).</p>";
                }
            }
            echo "</div>";
        }
        ?>

        <h2>🧪 Tester la compatibilité d'un conteneur</h2>
        <form action="debug_steghide.php" method="POST" enctype="multipart/form-data" style="margin-top: 15px;">
            <p style="font-size: 0.9rem; color: #555;">Sélectionnez une image locale pour analyser sa structure avant d'appliquer un algorithme d'insertion :</p>
            <input type="file" name="test_image" accept="image/*" required style="margin-bottom: 15px;"><br>
            <input type="submit" value="Lancer l'analyse structurelle" style="padding: 10px 20px; background-color: #2c3e50; color: white; border: none; border-radius: 4px; cursor: pointer;">
        </form>

        <h2>💡 Rappels techniques & Prévention des plantages</h2>
        <div class="alert alert-warning">
            <strong>Optimisation des conteneurs d'intégration :</strong>
            <ul style="margin-top: 10px;">
                <li>Utilisez des fichiers sources de haute résolution (ex: 1920x1080 pixels ou plus) pour maximiser le nombre de bits de poids faible (LSB) modifiables.</li>
                <li>Privilégiez les formats d'images non compressés ou à compression sans perte (comme le BMP ou le PNG natif) si les algorithmes de stéganographie subissent des altérations lors de la re-compression JPEG.</li>
                <li>Assurez-vous que les variables d'environnement système du serveur PHP disposent des allocations de mémoire suffisantes (<code>memory_limit</code>) lors de la manipulation de fichiers graphiques volumineux.</li>
            </ul>
        </div>

        <div style="text-align: center;">
            <a href="steganography.php" class="btn-back">← Retour à l'interface de Stéganographie</a>
        </div>
    </div>

</body>
</html>