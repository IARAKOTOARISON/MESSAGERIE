<?php
$erreur = isset($_GET['erreur']) ? $_GET['erreur'] : '';
$succes = isset($_GET['succes']) ? "Inscription réussie ! Vous pouvez maintenant vous connecter." : '';
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription</title>
    <link rel="stylesheet" href="assets/style.css">
</head>
<body>
    <div class="auth-container">
        <h1>Créer un compte</h1>
        
        <?php
        if ($erreur) { ?>
            <div class="alert alert-danger"><?= htmlspecialchars($erreur); ?></div>
        <?php }
        if ($succes) { ?>
            <div class="alert alert-success"><?= htmlspecialchars($succes); ?></div>
        <?php }
        ?>
        
        <form method="POST" action="traitements/traitementRegister.php">
            <div class="form-group">
                <label for="username">Nom d'utilisateur</label>
                <input type="text" id="username" name="username" required>
            </div>
            
            <div class="form-group">
                <label for="password">Mot de passe</label>
                <input type="password" id="password" name="password" required>
            </div>
            
            <input type="submit" value="S'inscrire">
        </form>
        
        <div class="auth-link">
            <p>Déjà inscrit ? <a href="login.php">Se connecter</a></p>
        </div>
    </div>
</body>
</html>
