<?php
$erreur = isset($_GET['erreur']) ? $_GET['erreur'] : '';
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Page de connexion</title>
    <link rel="stylesheet" href="assets/style.css">
</head>
<body>
    <div class="auth-container">
        <h1>Bienvenue</h1>
        <p class="text-center mb-lg">Veuillez vous connecter pour accéder à votre boîte de réception.</p>
        
        <?php
        if ($erreur) { ?>
            <div class="alert alert-danger"><?= htmlspecialchars($erreur); ?></div>
        <?php }
        ?>
        
            <form action="traitements/traitementLogin.php" method="post">
            <div class="form-group">
                <label for="username">Nom d'utilisateur</label>
                <input type="text" id="username" name="username" required>
            </div>
            
            <div class="form-group">
                <label for="password">Mot de passe</label>
                <input type="password" id="password" name="password" required>
            </div>
            
            <input type="submit" value="Se connecter">
        </form>
        
        <div class="auth-link">
            <p>Pas encore inscrit ? <a href="register.php">Créer un compte</a></p>
        </div>
    </div>
</body>
</html>