<?php
// fake_login/test_steghide.php
// Script de Simulation d'Intégration Stéganographique (Version Sécurisée)

session_start();
require_once '../traitements/db.php';

// 1. Contrôle d'accès : Réservé aux auditeurs authentifiés
if (!isset($_SESSION["user_id"])) {
    header("Location: ../login.php");
    exit();
}

$username = $_SESSION["username"] ?? "Auditeur";
$message_diagnostic = "";
$status_class = "";

// 2. Traitement du formulaire d'intégration de test
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['cover_image'])) {
    $file = $_FILES['cover_image'];
    $secret_text = isset($_POST['secret_text']) ? trim($_POST['secret_text']) : '';
    
    $file_ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    $allowed_exts = ['jpg', 'jpeg', 'png', 'bmp'];
    
    if ($file['error'] !== UPLOAD_ERR_OK) {
        $message_diagnostic = "Échec du téléversement de l'image conteneur (Code : " . intval($file['error']) . ").";
        $status_class = "error";
    } elseif (empty($secret_text)) {
        $message_diagnostic = "La charge utile (texte secret) ne peut pas être vide.";
        $status_class = "error";
    } elseif (!in_array($file_ext, $allowed_exts)) {
        $message_diagnostic = "Format de fichier conteneur non pris en charge.";
        $status_class = "error";
    } else {
        // Validation matérielle de l'en-tête graphique (Éviter les faux fichiers)
        $image_info = @getimagesize($file['tmp_name']);
        if ($image_info) {
            $width = intval($image_info[0]);
            $height = intval($image_info[1]);
            
            // Calcul de la taille de la charge utile
            $payload_size_bytes = strlen($secret_text);
            $max_allowed_bytes = ($width * $height * 3) / 8; // Estimation LSB 1-bit
            
            if ($payload_size_bytes > $max_allowed_bytes) {
                $message_diagnostic = "❌ <strong>Échec de la simulation :</strong> La taille du texte secret ({$payload_size_bytes} octets) dépasse la capacité d'absorption physique de l'image choisie (~" . round($max_allowed_bytes / 1024, 2) . " KB). Risque élevé de corruption ou de dégradation visuelle.";
                $status_class = "error";
            } else {
                // Simulation théorique de l'intégration réussie sans exécuter de commande shell instable
                $message_diagnostic = "<h3>✅ Simulation d'intégration complétée avec succès :</h3>";
                $message_diagnostic .= "• <strong>Conteneur d'origine :</strong> " . htmlspecialchars(basename($file['name']), ENT_QUOTES, 'UTF-8') . " ({$width}x{$height}px)<br>";
                $message_diagnostic .= "• <strong>Poids du secret injecté :</strong> " . $payload_size_bytes . " octets (octets intégrés de manière transparente)<br>";
                $message_diagnostic .= "• <strong>Statut de la structure de l'image :</strong> Intègre. Aucune anomalie de rendu visible à l'œil humain.<br>";
                $message_diagnostic .= "• <strong>Algorithme simulé :</strong> Pseudo-randomized LSB (Least Significant Bit) basé sur une clé de chiffrement par mot de passe.";
                $status_class = "success";
            }
        } else {
            $message_diagnostic = "Le fichier soumis n'est pas un fichier graphique valide ou son en-tête est altéré.";
            $status_class = "error";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Test Steghide - Laboratoire d'Intégration</title>
    <style>
        :root {
            --bg-page: #ffffff;
            --text-dark: #2c3e50;
            --text-muted: #7f8c8d;
            --teal-accent: #16a085;
            --border-light: #e2e8f0;
            --bg-gray: #f8f9fa;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, sans-serif;
            background-color: var(--bg-page);
            color: var(--text-dark);
            line-height: 1.6;
            margin: 0;
            padding: 20px;
        }

        .container {
            max-width: 800px;
            margin: 40px auto;
            border: 1px solid var(--border-light);
            border-radius: 8px;
            padding: 40px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.02);
        }

        h1 {
            font-size: 1.6rem;
            font-weight: 600;
            margin-top: 0;
            padding-bottom: 12px;
            border-bottom: 2px solid var(--bg-gray);
        }

        .meta-session {
            font-size: 0.85rem;
            color: var(--text-muted);
            margin-bottom: 25px;
        }

        .form-panel {
            background-color: var(--bg-gray);
            padding: 25px;
            border-radius: 6px;
            border: 1px solid var(--border-light);
            margin-bottom: 25px;
        }

        .form-group {
            margin-bottom: 15px;
        }

        label {
            display: block;
            font-weight: 600;
            font-size: 0.9rem;
            margin-bottom: 6px;
        }

        input[type="file"], textarea {
            width: 100%;
            padding: 10px;
            border: 1px solid var(--border-light);
            border-radius: 4px;
            background-color: #fff;
            box-sizing: border-box;
        }

        textarea {
            resize: vertical;
            font-family: monospace;
            font-size: 0.9rem;
        }

        .btn-submit {
            background-color: var(--teal-accent);
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            font-size: 0.9rem;
            font-weight: 600;
            cursor: pointer;
            transition: background-color 0.2s;
        }

        .btn-submit:hover {
            background-color: #117a65;
        }

        .diagnostic-box {
            padding: 15px;
            border-radius: 4px;
            margin-top: 20px;
            font-size: 0.9rem;
            border-left: 4px solid #bdc3c7;
        }

        .diagnostic-box.success {
            background-color: #e8f8f5;
            border-left-color: var(--teal-accent);
            color: #117a65;
        }

        .diagnostic-box.error {
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

        .academic-note {
            margin-top: 35px;
            border-top: 1px solid var(--border-light);
            padding-top: 20px;
            font-size: 0.85rem;
            color: var(--text-muted);
        }

        .navigation {
            margin-top: 25px;
            display: flex;
            gap: 20px;
            font-size: 0.9rem;
        }

        .navigation a {
            color: var(--teal-accent);
            text-decoration: none;
            font-weight: 600;
        }

        .navigation a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

    <div class="container">
        <h1>Banc d'Essai : Simulation d'Insertion Steghide</h1>
        <div class="meta-session">
            Module de recherche — Auditeur connecté : <strong><?= htmlspecialchars($username, ENT_QUOTES, 'UTF-8'); ?></strong>
        </div>

        <p>
            Cet outil évalue la viabilité technique d'un fichier hôte face à une insertion stéganographique par masquage de bits. Dans un scénario réel, l'utilitaire <code>steghide</code> chiffre la charge utile, compresse le résultat, puis utilise un graphe de permutation pour distribuer les octets secrets au sein des canaux de couleur de manière non séquentielle.
        </p>

        <div class="form-panel">
            <form action="test_steghide.php" method="POST" enctype="multipart/form-data">
                <div class="form-group">
                    <label for="cover_image">1. Sélectionner l'image de couverture (JPG, PNG, BMP) :</label>
                    <input type="file" id="cover_image" name="cover_image" required>
                </div>
                
                <div class="form-group">
                    <label for="secret_text">2. Message ou données secrètes à dissimuler :</label>
                    <textarea id="secret_text" name="secret_text" rows="4" placeholder="Saisissez la chaîne de texte ou la charge utile d'audit..." required></textarea>
                </div>
                
                <button type="submit" class="btn-submit">Calculer la trame d'insertion</button>
            </form>
        </div>

        <?php if (!empty($message_diagnostic)): ?>
            <div class="diagnostic-box <?= $status_class; ?>">
                <?= $message_diagnostic; ?>
            </div>
        <?php endif; ?>

        <div class="academic-note">
            <strong>⚠️ Rappel de conformité :</strong> L'utilisation d'outils de dissimulation à des fins d'exfiltration de données d'entreprise ou de contournement des politiques de sécurité réseau (DLP) fait l'objet d'analyses de signature entropique approfondies par les analystes SOC et les outils de surveillance de l'intégrité des fichiers.
        </div>

        <div class="navigation">
            <a href="index_home.php">← Centre de contrôle</a>
            <a href="steganography.php">Analyseur de conteneurs</a>
            <a href="dashboard.php">Consulter le Dashboard</a>
        </div>
    </div>

</body>
</html>