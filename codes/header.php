<?php
// Vérifier que l'utilisateur est connecté
if (!isset($_SESSION["username"])) {
    header("Location: login.php");
    exit();
}
?>

<header style="position: fixed; top: 0; left: 0; right: 0; background: linear-gradient(135deg, #31a24c 0%, #4db366 100%); color: white; padding: 15px 20px; box-shadow: 0 2px 8px rgba(0, 0, 0, 0.15); z-index: 1000;">
    <div style="max-width: 1200px; margin: 0 auto; display: flex; justify-content: space-between; align-items: center;">
        <div>
            <h2 style="margin: 0; font-size: 1.5rem; font-weight: 600; color: red;">Messagerie</h2>
        </div>
        <div style="display: flex; align-items: center; gap: 15px;">
            <span style="font-size: 0.95rem;">Connecté en tant que :</span>
            <strong style="background-color: rgba(255, 255, 255, 0.2); padding: 8px 15px; border-radius: 20px; font-size: 0.95rem;">
                <?= htmlspecialchars($_SESSION["username"]); ?>
            </strong>
            <a href="traitements/logout.php" style="padding: 8px 15px; background-color: rgba(255, 255, 255, 0.2); color: white; text-decoration: none; border-radius: 5px; font-size: 0.9rem; transition: background-color 0.2s ease;" onmouseover="this.style.backgroundColor='rgba(255, 255, 255, 0.3)'" onmouseout="this.style.backgroundColor='rgba(255, 255, 255, 0.2)'">Se déconnecter</a>
        </div>
    </div>
</header>

<div style="margin-top: 70px;"></div>
