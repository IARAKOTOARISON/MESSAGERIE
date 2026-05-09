<?php
include 'traitements/traitementInbox.php';
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Boîte de réception</title>
    <link rel="stylesheet" href="assets/style.css">
</head>
<body style="padding: 0; margin: 0;">
    <?php include 'header.php'; ?>
    
    <div style="max-width: 600px; margin: 20px auto; padding: 20px;">
        <h2>Utilisateurs disponibles</h2>

        <?php if (count($users) > 0) { ?>
            <ul class="user-list">
                <?php foreach ($users as $user) { ?>
                    <li class="user-item">
                        <a href="conversation.php?id=<?= $user['id']; ?>" class="user-link">
                            <?= htmlspecialchars($user['username']); ?>
                        </a>
                    </li>
                <?php } ?>
            </ul>
        <?php } else { ?>
            <p class="text-center text-muted">Aucun autre utilisateur disponible.</p>
        <?php } ?>
    </div>
</body>
</html>
