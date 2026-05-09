<?php
$erreur = isset($_GET['erreur']) ? $_GET['erreur'] : '';
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Page de connexion</title>
</head>
<body>
    <h1>Bienvenue sur notre messagerie</h1>
    <p>Veuillez vous connecter pour accéder à votre boîte de réception.</p>
    
    <?php
    if ($erreur) { ?>
        <p style="color: red;"><?= htmlspecialchars($erreur); ?></p>
    <?php }
    ?>
    
    <form action="traitements/traitementLogin.php" method="post">
        <label for="username">Nom d'utilisateur:</label>
        <input type="text" id="username" name="username" required>
        <br>
        
        <label for="password">Mot de passe:</label>
        <input type="password" id="password" name="password" required>
        <br>
        
        <input type="submit" value="Se connecter">
    </form>
    
    <p><a href="register.php">Pas encore inscrit ? Créer un compte</a></p>
</body>
</html>