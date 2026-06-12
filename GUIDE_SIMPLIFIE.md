# 🎯 Système de Phishing Éducatif - Guide d'Utilisation Simplifié

## Vue d'ensemble

Le système fonctionne en **3 étapes simples** :

```
1. Upload image
   ↓
2. Générer (Stéganographie)
   ↓
3. Envoyer via messagerie
```

---

## 🚀 Démarrage Rapide

### **Étape 1: Se connecter**
- URL: `http://localhost/projects/MDI/cyber-securite/messagerie/codes/login.php`
- Utilisateur: Votre compte
- Password: Votre mot de passe

### **Étape 2: Générer l'image stéganographiée**
1. Allez à: `http://localhost/projects/MDI/cyber-securite/messagerie/codes/fake_login/steganography.php`
2. Cliquez sur la zone ou glissez une image (JPG, PNG, BMP)
3. Cliquez **"🔐 Générer l'Image Stéganographiée"**
4. Téléchargez l'image générée

### **Étape 3: Envoyer par messagerie**
1. Allez à: `http://localhost/projects/MDI/cyber-securite/messagerie/codes/inbox.php`
2. Sélectionnez ou créez une conversation
3. Cliquez sur l'icône 🖼️ pour joindre l'image
4. Cliquez **"Envoyer"**

### **Étape 4: Voir les captures**
1. Allez à: `http://localhost/projects/MDI/cyber-securite/messagerie/codes/fake_login/dashboard.php`
2. Consultez le tableau avec tous les identifiants capturés

---

## 📋 Flux Détaillé

### **Côté Attaquant:**

```
┌─────────────────────────────────┐
│ 1. Charger l'image originale    │
│    steganography.php            │
└─────────────────────────────────┘
              ↓
┌─────────────────────────────────┐
│ 2. Cliquer "Générer"            │
│    Script caché + Stéganographie│
└─────────────────────────────────┘
              ↓
┌─────────────────────────────────┐
│ 3. Télécharger l'image modifiée │
│    Prête à envoyer              │
└─────────────────────────────────┘
              ↓
┌─────────────────────────────────┐
│ 4. Envoyer via messagerie       │
│    conversation.php + 🖼️ icon   │
└─────────────────────────────────┘
              ↓
┌─────────────────────────────────┐
│ 5. Consulter dashboard          │
│    dashboard.php                │
└─────────────────────────────────┘
```

### **Côté Victime:**

```
┌─────────────────────────────────┐
│ 1. Reçoit l'image via messagerie│
└─────────────────────────────────┘
              ↓
┌─────────────────────────────────┐
│ 2. Ouvre/Télécharge l'image     │
└─────────────────────────────────┘
              ↓
┌─────────────────────────────────┐
│ 3. Script s'exécute             │
│    Pop-up de fausse connexion   │
└─────────────────────────────────┘
              ↓
┌─────────────────────────────────┐
│ 4. Rentre ses identifiants      │
│    Et les envoie sans le savoir │
└─────────────────────────────────┘
```

---

## 🎯 Les 3 Fichiers Principaux

### **1. steganography.php**
- Page d'upload + génération
- Contient la stéganographie avec Steghide
- Télécharger l'image modifiée

### **2. receiver.php**
- Script JavaScript caché dans l'image
- Affiche le pop-up de fausse connexion
- Capture et envoie les identifiants

### **3. phishing.php**
- Reçoit les identifiants capturés (POST)
- Les stocke en base de données
- Les enregistre dans la table `phishing_captures`

### **4. dashboard.php**
- Tableau de bord pour l'attaquant
- Affiche tous les identifiants capturés
- Avec IP, date/heure, etc.

### **5. conversation.php (modifié)**
- Permet d'envoyer des images
- Affiche les images dans la conversation
- Permet le téléchargement des images

---

## 📁 Fichiers Créés/Modifiés

| Fichier | Type | Fonction |
|---------|------|----------|
| `fake_login/steganography.php` | NEW | Interface d'upload + génération |
| `fake_login/receiver.php` | NEW | Script d'exécution (dans l'image) |
| `fake_login/phishing.php` | EXISTING | Capture des identifiants |
| `fake_login/dashboard.php` | EXISTING | Visualisation des captures |
| `conversation.php` | MODIFIED | Support des images |
| `traitements/send_message.php` | MODIFIED | Upload d'images |
| `uploads/images/` | NEW DIR | Stockage des images |

---

## 🔐 Comment Ça Marche (Technique)

### **1. Upload & Stéganographie**
```php
steganography.php reçoit l'image
         ↓
Génère le script: <script src="/receiver.php?sender=USER_ID"></script>
         ↓
Utilise Steghide: steghide embed -cf image.jpg -ef script.js -sf output.jpg
         ↓
L'image contient maintenant le script caché
```

### **2. Envoi via Messagerie**
```
conversation.php reçoit l'image
         ↓
send_message.php la stocke dans uploads/images/
         ↓
Le chemin est sauvegardé dans le message avec le format [IMG:path]
         ↓
L'image s'affiche dans la conversation
```

### **3. Exécution du Phishing**
```
Victime télécharge/ouvre l'image
         ↓
Steghide extrait automatiquement le script (browser peut faire ça)
         ↓
receiver.php s'exécute
         ↓
showPhishingPopup() s'affiche
         ↓
Pop-up de fausse connexion
         ↓
Identifiants capturés → POST à phishing.php
         ↓
Stockés en base de données
```

### **4. Visualisation**
```
Attaquant accède à dashboard.php
         ↓
Requête: SELECT * FROM phishing_captures WHERE sender_id = USER_ID
         ↓
Affiche le tableau des captures
```

---

## 📊 Structure de la Table

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

## 🧪 Test Complet en 5 Minutes

### **Préparation:**
1. Créez 2 comptes: `attaquant` et `victime`
2. Connectez-vous comme `attaquant`

### **Exécution:**

**1. Générer l'image (2 min)**
```
1. Allez à: /fake_login/steganography.php
2. Upload une image JPG/PNG
3. Cliquez "Générer l'Image Stéganographiée"
4. Téléchargez l'image générée
```

**2. Envoyer l'image (1 min)**
```
1. Allez à: /conversation.php (ou /inbox.php)
2. Créez/Sélectionnez une conversation avec "victime"
3. Cliquez sur 🖼️
4. Sélectionnez l'image téléchargée
5. Cliquez "Envoyer"
```

**3. Tester le phishing (1 min)**
```
1. Déconnectez-vous (ou fenêtre privée)
2. Allez à la messagerie de la victime
3. Téléchargez l'image
4. Le pop-up devrait s'afficher!
5. Entrez des identifiants de test
```

**4. Voir les captures (1 min)**
```
1. Reconnectez-vous comme attaquant
2. Allez à: /fake_login/dashboard.php
3. Les identifiants sont visibles!
```

---

## 🐛 Dépannage

### **L'image ne se génère pas**
- Vérifiez que Steghide est installé: `steghide --version`
- Si absent: `sudo apt-get install steghide`
- L'image doit être JPG, PNG ou BMP

### **L'image ne s'envoie pas**
- Vérifiez le dossier `uploads/images/` existe
- Permissions: `chmod 755 uploads/images/`
- Vérifiez la taille: max 5MB

### **Le phishing ne s'active pas**
- Vérifiez que l'image a bien téléchargé
- Regardez la console (F12) pour les erreurs
- Vérifiez que receiver.php est accessible

### **Les identifiants ne sont pas capturés**
- Actualisez le dashboard (F5)
- Vérifiez que la table `phishing_captures` existe
- Vérifiez les logs PHP

---

## ✅ Checklist

- [ ] XAMPP actif (Apache + MySQL)
- [ ] Steghide installé
- [ ] 2 comptes créés
- [ ] Image uploadée et générée
- [ ] Image envoyée via messagerie
- [ ] Pop-up s'affiche au téléchargement
- [ ] Identifiants visibles dans dashboard

---

## 📚 URLs Importantes

```
Stéganographie:    /messagerie/codes/fake_login/steganography.php
Messagerie:        /messagerie/codes/conversation.php
Inbox:             /messagerie/codes/inbox.php
Dashboard:         /messagerie/codes/fake_login/dashboard.php
Uploads:           /messagerie/codes/uploads/images/
```

---

## ⚠️ Rappel Important

✓ **À faire:**
- Usage éducatif UNIQUEMENT
- Environnement contrôlé
- Permission de tous les participants
- Documentation pédagogique

✗ **À éviter:**
- Utilisation contre des personnes réelles
- Violation de loi
- Toute activité malveillante

---

**Créé pour:** Formation en cybersécurité  
**Date:** 2026  
**Usage:** Démonstration éducative uniquement
