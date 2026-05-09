<?php
include 'traitements/traitementInbox.php';
$username = $_SESSION["username"];
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Boîte de réception</title>
    <link rel="stylesheet" href="assets/style.css">
</head>
<body>
    <div class="inbox-container">
        <div class="inbox-header">
            <h1 style="margin-bottom: 0; color: white;">Messagerie</h1>
        </div>
        
        <div style="padding: var(--spacing-lg);">
            <p class="mb-lg">Connecté en tant que : <strong style="color: var(--primary-green);"><?= htmlspecialchars($username); ?></strong></p>
            
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
            
            <a href="traitements/logout.php" class="logout">Se déconnecter</a>
        </div>
    </div>
</body>
</html>
