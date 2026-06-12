<?php
/**
 * Script de test pour diagnostiquer les problèmes de stéganographie
 */

session_start();

echo "<h1>🔍 Test de Diagnostic - Stéganographie</h1>";
echo "<hr>";

// 1. Vérifier la connexion
echo "<h2>1. Vérifier la connexion utilisateur</h2>";
if (isset($_SESSION["user_id"])) {
    echo "✅ Connecté en tant que: " . htmlspecialchars($_SESSION["username"]) . " (ID: " . $_SESSION["user_id"] . ")<br>";
} else {
    echo "❌ Non connecté! Redirecting...<br>";
    header("Location: ../login.php");
    exit();
}

echo "<hr>";

// 2. Vérifier les dossiers
echo "<h2>2. Vérifier les dossiers et permissions</h2>";

$upload_dir = __DIR__ . '/../uploads/images/';
echo "Dossier cible: <code>$upload_dir</code><br>";

if (is_dir($upload_dir)) {
    echo "✅ Dossier existe<br>";
} else {
    echo "❌ Dossier n'existe pas!<br>";
}

if (is_writable($upload_dir)) {
    echo "✅ Dossier accessible en écriture<br>";
} else {
    echo "❌ Dossier NON accessible en écriture!<br>";
}

$perms = substr(sprintf('%o', fileperms($upload_dir)), -4);
echo "Permissions: <code>$perms</code><br>";

echo "<hr>";

// 3. Vérifier Steghide
echo "<h2>3. Vérifier Steghide</h2>";

$output = [];
$return_var = 0;
exec("/usr/bin/steghide --version 2>&1", $output, $return_var);

if ($return_var === 0) {
    echo "✅ Steghide trouvé:<br>";
    foreach ($output as $line) {
        echo "<code>$line</code><br>";
    }
} else {
    echo "❌ Steghide NON trouvé!<br>";
}

echo "<hr>";

// 4. Tester la commande Steghide
echo "<h2>4. Créer un fichier test</h2>";

// Créer une petite image de test (1x1 pixel PNG)
$test_image = $upload_dir . 'test_' . time() . '.png';
$png_data = base64_decode('iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAADUlEQVR42mNk+M9QDwADhgGAWjR9awAAAABJRU5ErkJggg==');

if (file_put_contents($test_image, $png_data)) {
    echo "✅ Fichier PNG de test créé: <code>$test_image</code><br>";
    echo "Taille: " . filesize($test_image) . " bytes<br>";
} else {
    echo "❌ Impossible de créer le fichier PNG de test!<br>";
}

// Créer un fichier payload
$test_payload = $upload_dir . 'test_payload_' . time() . '.txt';
if (file_put_contents($test_payload, 'Test payload')) {
    echo "✅ Fichier payload de test créé: <code>$test_payload</code><br>";
} else {
    echo "❌ Impossible de créer le fichier payload de test!<br>";
}

// Tester la commande Steghide
if (file_exists($test_image) && file_exists($test_payload)) {
    echo "<hr>";
    echo "<h2>5. Test de commande Steghide</h2>";
    
    $test_output = $upload_dir . 'test_stego_' . time() . '.png';
    // Réinitialiser LD_LIBRARY_PATH pour éviter les bibliothèques XAMPP
    $cmd = "LD_LIBRARY_PATH=/usr/lib/x86_64-linux-gnu:/usr/lib:/lib/x86_64-linux-gnu:/lib /usr/bin/steghide embed -cf " . escapeshellarg($test_image) . " -ef " . escapeshellarg($test_payload) . " -sf " . escapeshellarg($test_output) . " -p '' -f 2>&1";
    
    echo "Commande: <code>$cmd</code><br>";
    
    $output = [];
    $return_var = 0;
    exec($cmd, $output, $return_var);
    
    echo "Retour code: <code>$return_var</code><br>";
    
    if ($return_var === 0) {
        echo "✅ Commande Steghide réussie!<br>";
        if (file_exists($test_output)) {
            echo "✅ Fichier stéganographié créé: <code>$test_output</code><br>";
            echo "Taille: " . filesize($test_output) . " bytes<br>";
        } else {
            echo "❌ Fichier stéganographié NON créé!<br>";
        }
    } else {
        echo "❌ Erreur Steghide:<br>";
        foreach ($output as $line) {
            echo "<code>$line</code><br>";
        }
    }
    
    // Nettoyer
    @unlink($test_output);
}

// Nettoyer
@unlink($test_image);
@unlink($test_payload);

echo "<hr>";
echo "<h2>✨ Test terminé!</h2>";
echo "<p><a href='steganography.php'>Retour à Stéganographie</a></p>";
?>
