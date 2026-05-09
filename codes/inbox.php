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
</head>
<body>
    <h1>Boîte de réception</h1>
    <p>Connecté en tant que : <strong><?= htmlspecialchars($username); ?></strong></p>
    
    <h2>Utilisateurs disponibles</h2>
    
    <?php if (count($users) > 0) { ?>
        <ul class="user-list">
            <?php foreach ($users as $user) { ?>
                <li class="user-item">
                    <a href="conversation.php?id=<?= $user['id']; ?>" class="user-link">
                        [<?= htmlspecialchars($user['username']); ?>]
                    </a>
                </li>
            <?php } ?>
        </ul>
    <?php } else { ?>
        <p>Aucun autre utilisateur disponible.</p>
    <?php } ?>
    
    <br>
    <a href="traitements/logout.php" class="logout">Se déconnecter</a>
</body>
</html>
