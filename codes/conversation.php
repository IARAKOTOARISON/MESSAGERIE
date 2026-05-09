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
        
        <div style="flex: 1; overflow-y: auto; padding: 20px; background-color: var(--light-gray);">
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
        
        <div style="padding: 15px 20px; background-color: var(--white); border-top: 1px solid var(--medium-gray);">
            <form action="traitements/send_message.php" method="POST" style="display: flex; gap: 10px; align-items: center; width: 100%; margin-bottom: 10px;">
                <input type="hidden" name="conversation_id" value="<?= $conversation_id; ?>">
                <input type="text" name="message" placeholder="Écrivez votre message..." required style="flex: 1; padding: 12px 15px; border: 2px solid #d0d0d0; border-radius: 8px; font-size: 1rem; font-family: inherit;">
                <button type="submit" style="width: 200px; padding: 12px 20px; background-color: #31a24c; color: white; border: none; border-radius: 8px; font-weight: 600; font-size: 1rem; cursor: pointer;">Envoyer</button>
            </form>
            
            <a href="inbox.php" style="text-align: center; display: block; color: #31a24c; text-decoration: none; font-size: 0.9rem;">← Retour à la boîte de réception</a>
        </div>
    </div>
</body>
</html>
