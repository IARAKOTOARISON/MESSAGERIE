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
   </head>
<body>
    <div class="conversation-header">
        <h1>Conversation avec <?= htmlspecialchars($remote_username); ?></h1>
        <p>Vous êtes connecté en tant que : <strong><?= htmlspecialchars($username); ?></strong></p>
    </div>
    
    <div class="messages-container">
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
            <p style="text-align: center; color: #999;">Aucun message pour le moment. Commencez la conversation !</p>
        <?php } ?>
    </div>
    
    <form action="traitements/send_message.php" method="POST" class="message-form">
        <input type="hidden" name="conversation_id" value="<?= $conversation_id; ?>">
        <textarea name="message" placeholder="Écrivez votre message..." required></textarea>
        <button type="submit">Envoyer</button>
    </form>
    
    <a href="inbox.php" class="back-link">← Retour à la boîte de réception</a>
</body>
</html>
