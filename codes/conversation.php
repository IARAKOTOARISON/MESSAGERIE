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
                    <?php foreach ($messages as $msg) { 
                        // Séparer le texte et l'image
                        $content = $msg['contenu'];
                        $image_path = '';
                        
                        // Extraire le chemin de l'image s'il existe
                        if (preg_match('/\[IMG:(.+?)\]/', $content, $matches)) {
                            $image_path = $matches[1];
                            $content = trim(preg_replace('/\[IMG:.+?\]/', '', $content));
                        }
                    ?>
                        <div class="message <?= ($msg['sender_id'] == $user_id) ? 'sent' : 'received'; ?>">
                            <div class="message-meta">
                                <strong><?= htmlspecialchars($msg['username']); ?></strong>
                                <span><?= date('H:i', strtotime($msg['created_at'])); ?></span>
                            </div>
                            <div class="message-content">
                                <?php if ($content && $content !== '[Image partagée]'): ?>
                                    <div><?= htmlspecialchars($content); ?></div>
                                <?php endif; ?>
                                
                                <?php if ($image_path && file_exists(__DIR__ . '/' . $image_path)): ?>
                                    <div style="margin-top: 10px;">
                                        <img src="<?= htmlspecialchars($image_path); ?>" style="max-width: 300px; max-height: 300px; border-radius: 5px; cursor: pointer;" 
                                             onclick="window.open('<?= htmlspecialchars($image_path); ?>', '_blank')" 
                                             alt="Image partagée" title="Cliquez pour agrandir">
                                        <br>
                                        <small style="color: #999;">
                                            <a href="<?= htmlspecialchars($image_path); ?>" download style="color: #31a24c; text-decoration: none;">📥 Télécharger</a>
                                        </small>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php } ?>
                <?php } else { ?>
                    <p style="text-align: center; color: var(--dark-gray);">Aucun message pour le moment. Commencez la conversation !</p>
                <?php } ?>
            </div>
        </div>
        
        <div style="padding: 15px 20px; background-color: var(--white); border-top: 1px solid var(--medium-gray);">
            <form action="traitements/send_message.php" method="POST" enctype="multipart/form-data" style="width: 100%;">
                <input type="hidden" name="conversation_id" value="<?= $conversation_id; ?>">
                
                <div style="display: flex; gap: 10px; align-items: center; margin-bottom: 10px;">
                    <input type="text" name="message" placeholder="Écrivez votre message..." style="flex: 1; padding: 12px 15px; border: 2px solid #d0d0d0; border-radius: 8px; font-size: 1rem; font-family: inherit;">
                    
                    <label for="image-input" style="cursor: pointer; padding: 12px 15px; background-color: #f0f0f0; border-radius: 8px; border: 2px solid #d0d0d0; font-size: 1.2rem;" title="Joindre une image">
                        🖼️
                    </label>
                    <input type="file" id="image-input" name="image" accept="image/*" style="display: none;">
                    
                    <button type="submit" style="width: 200px; padding: 12px 20px; background-color: #31a24c; color: white; border: none; border-radius: 8px; font-weight: 600; font-size: 1rem; cursor: pointer;">Envoyer</button>
                </div>
                
                <div id="image-preview" style="display: none; margin-bottom: 10px;">
                    <img id="preview-img" src="" style="max-width: 150px; max-height: 150px; border-radius: 5px; margin-right: 10px;">
                    <span id="preview-name" style="color: #666; font-size: 0.9rem;"></span>
                    <button type="button" onclick="document.getElementById('image-input').value=''; document.getElementById('image-preview').style.display='none';" style="margin-left: 10px; color: #e74c3c; background: none; border: none; cursor: pointer; font-size: 0.9rem;">✕ Supprimer</button>
                </div>
            </form>
            
            <a href="inbox.php" style="text-align: center; display: block; color: #31a24c; text-decoration: none; font-size: 0.9rem;">← Retour à la boîte de réception</a>
        </div>
        
        <script>
            document.getElementById('image-input').addEventListener('change', function(e) {
                if (this.files.length > 0) {
                    const file = this.files[0];
                    const reader = new FileReader();
                    
                    reader.onload = function(event) {
                        document.getElementById('preview-img').src = event.target.result;
                        document.getElementById('preview-name').textContent = file.name;
                        document.getElementById('image-preview').style.display = 'block';
                    };
                    
                    reader.readAsDataURL(file);
                }
            });
        </script>
    </div>
</body>
</html>
