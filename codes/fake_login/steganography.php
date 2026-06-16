<?php
// fake_login/steganography.php
// Module d'Analyse Structurelle et Stéganographie (Version Sécurisée)

session_start();
require_once '../traitements/db.php';

// 1. Contrôle d'accès : Réservé aux auditeurs authentifiés du laboratoire
if (!isset($_SESSION["user_id"])) {
    header("Location: ../login.php");
    exit();
}

$user_id = intval($_SESSION["user_id"]);
$username = $_SESSION["username"] ?? "Auditeur";
$analyse_result = "";
$status_class = "";

// 2. Traitement sécurisé de l'analyse d'image en POST
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['stego_image'])) {
    $file = $_FILES['stego_image'];
    $file_ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    $allowed_extensions = ['jpg', 'jpeg', 'png', 'bmp'];
    
    if ($file['error'] !== UPLOAD_ERR_OK) {
        $analyse_result = "Erreur lors du téléversement du fichier (Code réseau : " . intval($file['error']) . ").";
        $status_class = "error";
    } elseif (!in_array($file_ext, $allowed_extensions)) {
        $analyse_result = "Format d'image non supporté pour l'analyse structurelle standard (.jpg, .png, .bmp uniquement).";
        $status_class = "error";
    } else {
        // Analyse des en-têtes réels du conteneur graphique via la bibliothèque GD
        $image_info = @getimagesize($file['tmp_name']);
        if ($image_info) {
            $width = intval($image_info[0]);
            $height = intval($image_info[1]);
            $mime = htmlspecialchars($image_info['mime'], ENT_QUOTES, 'UTF-8');
            $size_kb = round($file['size'] / 1024, 2);
            
            // Calcul théorique de la capacité d'accueil maximale (méthode LSB 1-bit par canal RVB)
            // Formule : (Largeur * Hauteur * 3 canaux) / 8 bits par octet
            $max_capacity_bytes = ($width * $height * 3) / 8;
            $max_capacity_kb = round($max_capacity_bytes / 1024, 2);
            
            $analyse_result = "<strong>Résultat de l'analyse structurelle :</strong><br>";
            $analyse_result .= "• Type MIME détecté : <code>{$mime}</code><br>";
            $analyse_result .= "• Dimensions physiques : {$width} x {$height} pixels<br>";
            $analyse_result .= "• Poids du fichier d'origine : {$size_kb} KB<br>";
            $analyse_result .= "• Capacité théorique d'insertion LSB : ~{$max_capacity_kb} KB de données textuelles dissimulables.";
            $status_class = "success";
        } else {
            $analyse_result = "Le fichier soumis est altéré ou ne possède pas une structure d'en-tête graphique valide.";
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
    <title>Laboratoire Stéganographie - Analyse de Conteneurs</title>
    <style>
        :root {
            --bg-color: #ffffff;
            --text-main: #2c3e50;
            --text-muted: #7f8c8d;
            --teal-accent: #16a085;
            --border-color: #e2e8f0;
            --light-gray: #f8f9fa;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif;
            background-color: var(--bg-color);
            color: var(--text-main);
            line-height: 1.6;
            margin: 0;
            padding: 20px;
        }

        .container {
            max-width: 850px;
            margin: 40px auto;
            border: 1px solid var(--border-color);
            border-radius: 8px;
            padding: 40px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.02);
        }

        h1 {
            font-size: 1.8rem;
            font-weight: 600;
            margin-top: 0;
            padding-bottom: 15px;
            border-bottom: 2px solid var(--light-gray);
        }

        h2 {
            font-size: 1.2rem;
            margin-top: 30px;
            color: var(--teal-accent);
        }

        p {
            font-size: 0.95rem;
            color: var(--text-main);
        }

        .meta-description {
            color: var(--text-muted);
            font-size: 0.9rem;
            margin-bottom: 25px;
        }

        .form-section {
            background-color: var(--light-gray);
            padding: 25px;
            border-radius: 6px;
            border: 1px solid var(--border-color);
            margin-bottom: 25px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        label {
            display: block;
            font-weight: 600;
            font-size: 0.9rem;
            margin-bottom: 8px;
        }

        input[type="file"] {
            display: block;
            font-size: 0.9rem;
        }

        .btn {
            background-color: var(--text-main);
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 0.9rem;
            font-weight: 600;
            transition: background-color 0.2s;
        }

        .btn:hover {
            background-color: #1a252f;
        }

        .btn-teal {
            background-color: var(--teal-accent);
        }

        .btn-teal:hover {
            background-color: #117a65;
        }

        /* Panneaux d'affichage de statuts */
        .result-box {
            padding: 15px;
            border-radius: 4px;
            margin-top: 20px;
            font-size: 0.9rem;
            border-left: 4px solid #bdc3c7;
        }

        .result-box.success {
            background-color: #e8f8f5;
            border-left-color: var(--teal-accent);
            color: #117a65;
        }

        .result-box.error {
            background-color: #fce4d6;
            border-left-color: #e67e22;
            color: #b95e0c;
        }

        code {
            background: rgba(0, 0, 0, 0.05);
            padding: 2px 5px;
            border-radius: 3px;
            font-family: monospace;
            font-size: 0.9rem;
        }

        .academic-info {
            border-top: 1px solid var(--border-color);
            margin-top: 40px;
            padding-top: 20px;
            font-size: 0.85rem;
            color: var(--text-muted);
        }

        .nav-links {
            margin-top: 25px;
            display: flex;
            gap: 20px;
        }

        .nav-links a {
            color: var(--teal-accent);
            text-decoration: none;
            font-size: 0.9rem;
            font-weight: 600;
        }

        .nav-links a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

    <div class="container">
        <h1>Analyseur Théorique de Conteneurs Graphiques</h1>
        <p class="meta-description">
            Espace d'étude académique — Session de recherche de l'auditeur : <strong><?= htmlspecialchars($username, ENT_QUOTES, 'UTF-8'); ?></strong>
        </p>

        <p>
            La stéganographie moderne s'appuie sur la redondance des données binaires d'une image pour y dissimuler des chaînes de caractères. Ce module permet d'évaluer la structure physique d'une image afin de valider son intégrité et de mesurer sa capacité de stockage avant l'application d'un masque de chiffrement ou d'insertion LSB.
        </p>

        <div class="form-section">
            <form action="steganography.php" method="POST" enctype="multipart/form-data">
                <div class="form-group">
                    <label for="stego_image">Sélectionner l'image conteneur à auditer :</label>
                    <input type="file" id="stego_image" name="stego_image" accept="image/*" required>
                </div>
                <button type="submit" class="btn btn-teal">Lancer le diagnostic de structure</button>
            </form>
        </div>

        <?php if (!empty($analyse_result)): ?>
            <div class="result-box <?= $status_class; ?>">
                <?= $analyse_result; ?>
            </div>
        <?php endif; ?>

        <h2>Principe scientifique : La substitution des bits de poids faible (LSB)</h2>
        <p>
            Chaque pixel d'une image couleur non compressée est encodé sur 24 bits répartis en 3 canaux : Rouge (8 bits), Vert (8 bits) et Bleu (8 bits). En modifiant uniquement le tout dernier bit (le bit de poids faible) de chaque octet, la variation de teinte est d'environ $1/255$, ce qui la rend parfaitement invisible à l'œil humain. Les analyses statistiques (stégananalyse) permettent toutefois de détecter ces modifications en observant la distribution entropique des nuances du fichier.
        </p>

        <div class="academic-info">
            <strong>Rappel de sécurité :</strong> L'inclusion de scripts opérationnels ou l'exfiltration automatisée de données par stéganographie au sein de réseaux d'entreprise représente un canal d'attaque discret majeur surveillé par les systèmes d'analyse comportementale de type EDR/SIEM.
        </div>

        <div class="nav-links">
            <a href="index_home.php">← Centre de contrôle</a>
            <a href="debug_steghide.php">Analyse d'environnement système (Debug)</a>
            <a href="dashboard.php">Consulter les logs d'interception</a>
        </div>
    </div>

</body>
</html>