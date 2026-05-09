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
</head>
<body>
    <h1>Créer un compte</h1>
    
    <?php
    if ($erreur) {
        echo '<p style="color: red;">' . htmlspecialchars($erreur) . '</p>';
    }
    if ($succes) {
        echo '<p style="color: green;">' . htmlspecialchars($succes) . '</p>';
    }
    ?>
    
    <form method="POST" action="traitements/traitementRegister.php">
        <label for="username">Nom d'utilisateur:</label>
        <input type="text" id="username" name="username" required>
        <br>
        
        <label for="password">Mot de passe:</label>
        <input type="password" id="password" name="password" required>
        <br>
        
        <input type="submit" value="S'inscrire">
    </form>
    
    <p><a href="login.php">Déjà inscrit ? Connectez-vous</a></p>
</body>
</html>
