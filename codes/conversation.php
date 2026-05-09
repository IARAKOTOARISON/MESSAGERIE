<?php
include 'traitements/traitementConversation.php';
$username = $_SESSION["username"];
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Conversation avec <?= htmlspecialchars($remote_username); ?></title>
    <link rel="stylesheet" href="assets/style.css">
</head>
<body style="padding: 0;">
    <div class="conversation-container" style="display: flex; flex-direction: column; height: 100vh; width: 100%; max-width: 100%; margin: 0; border-radius: 0;">
        <div class="conversation-header" style="flex-shrink: 0;">
            <h1 style="color: white; margin-bottom: var(--spacing-sm);">Conversation avec <?= htmlspecialchars($remote_username); ?></h1>
            <p>Vous êtes connecté en tant que : <strong><?= htmlspecialchars($username); ?></strong></p>
        </div>
        
        <div style="flex: 1; overflow-y: auto; padding: var(--spacing-lg); background-color: var(--light-gray);">
            <div class="messages-container" style="height: auto; min-height: 300px;">
                <?php if (count($messages) > 0) { ?>
                    <?php foreach ($messages as $msg) { ?>
                        <div class="message <?= ($msg['sender_id'] == $user_id) ? 'sent' : 'received'; ?>">
                            <div class="message-meta">
                                <strong><?= htmlspecialchars($msg['username']); ?></strong>
                                <span><?= date('H:i', strtotime($msg['created_at'])); ?></span>
                            </div>
                            <div class="message-content">
                                <?= htmlspecialchars($msg['contenu']); ?>
                            </div>
                        </div>
                    <?php } ?>
                <?php } else { ?>
                    <p style="text-align: center; color: var(--dark-gray);">Aucun message pour le moment. Commencez la conversation !</p>
                <?php } ?>
            </div>
        </div>
        
        <div style="flex-shrink: 0; padding: var(--spacing-lg); background-color: var(--white); border-top: 1px solid var(--medium-gray);">
            <form action="traitements/send_message.php" method="POST" style="display: flex; gap: 10px; align-items: center; width: 100%;">
                <input type="hidden" name="conversation_id" value="<?= $conversation_id; ?>">
                <input type="text" name="message" placeholder="Écrivez votre message..." required style="flex: 1; padding: 12px 15px; border: 2px solid #d0d0d0; border-radius: 8px; font-size: 1rem; font-family: inherit;">
                <button type="submit" style="width: 200px; padding: 12px 20px; background-color: #31a24c; color: white; border: none; border-radius: 8px; font-weight: 600; font-size: 1rem; cursor: pointer;">Envoyer</button>
            </form>
            
            <a href="inbox.php" class="back-link" style="margin-top: var(--spacing-md); display: block; text-align: center;">← Retour à la boîte de réception</a>
        </div>
    </div>
</body>
</html>
