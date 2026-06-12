<?php
session_start();

// Vérifier que l'utilisateur est connecté
if (!isset($_SESSION["user_id"])) {
    header("Location: ../login.php");
    exit();
}

include 'db.php';

$user_id = $_SESSION["user_id"];
$conversation_id = $_POST['conversation_id'] ?? null;
$message = $_POST['message'] ?? '';
$image_path = '';

// Validation
if (!$conversation_id || !is_numeric($conversation_id)) {
    header("Location: ../inbox.php");
    exit();
}

// Vérifier que la conversation appartient à l'utilisateur
$check_sql = "SELECT id FROM conversations WHERE id = ? AND (user1_id = ? OR user2_id = ?)";
$check_stmt = $conn->prepare($check_sql);
$check_stmt->bind_param("iii", $conversation_id, $user_id, $user_id);
$check_stmt->execute();
$check_result = $check_stmt->get_result();

if ($check_result->num_rows === 0) {
    header("Location: ../inbox.php");
    exit();
}

$check_stmt->close();

// Traiter l'upload d'image
if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
    $file = $_FILES['image'];
    
    // Validation du fichier
    $allowed_types = ['image/jpeg', 'image/png', 'image/bmp', 'image/gif'];
    if (!in_array($file['type'], $allowed_types)) {
        header("Location: ../conversation.php?id=" . $conversation_id . "&error=format");
        exit();
    }
    
    if ($file['size'] > 5 * 1024 * 1024) { // 5MB
        header("Location: ../conversation.php?id=" . $conversation_id . "&error=size");
        exit();
    }
    
    // Créer le dossier s'il n'existe pas
    $upload_dir = __DIR__ . '/../uploads/images/';
    if (!is_dir($upload_dir)) {
        mkdir($upload_dir, 0755, true);
    }
    
    // Générer un nom de fichier unique
    $filename = time() . '_' . preg_replace('/[^a-zA-Z0-9._-]/', '', basename($file['name']));
    $filepath = $upload_dir . $filename;
    
    if (move_uploaded_file($file['tmp_name'], $filepath)) {
        $image_path = 'uploads/images/' . $filename;
        // Si le message est vide, créer un message par défaut
        if (empty(trim($message))) {
            $message = '[Image partagée]';
        }
    } else {
        header("Location: ../conversation.php?id=" . $conversation_id . "&error=upload");
        exit();
    }
} elseif (empty(trim($message))) {
    // Pas de message ni d'image
    header("Location: ../conversation.php?id=" . $conversation_id);
    exit();
}

// Insérer le message (avec le chemin de l'image si présent)
$full_message = $message;
if ($image_path) {
    $full_message .= "\n[IMG:" . $image_path . "]";
}

$insert_sql = "INSERT INTO messages (conversation_id, sender_id, contenu, created_at) VALUES (?, ?, ?, NOW())";
$insert_stmt = $conn->prepare($insert_sql);
$insert_stmt->bind_param("iis", $conversation_id, $user_id, $full_message);

if ($insert_stmt->execute()) {
    // Mettre à jour l'heure du dernier message dans la conversation
    $update_sql = "UPDATE conversations SET last_message_time = NOW() WHERE id = ?";
    $update_stmt = $conn->prepare($update_sql);
    $update_stmt->bind_param("i", $conversation_id);
    $update_stmt->execute();
    $update_stmt->close();
}

$insert_stmt->close();

// Récupérer l'ID de l'utilisateur distant pour la redirection
$conv_sql = "SELECT user1_id, user2_id FROM conversations WHERE id = ?";
$conv_stmt = $conn->prepare($conv_sql);
$conv_stmt->bind_param("i", $conversation_id);
$conv_stmt->execute();
$conv_result = $conv_stmt->get_result();
$conv = $conv_result->fetch_assoc();
$conv_stmt->close();

$remote_user_id = ($conv['user1_id'] == $user_id) ? $conv['user2_id'] : $conv['user1_id'];

$conn->close();

// Redirection vers la conversation
header("Location: ../conversation.php?id=" . $remote_user_id);
exit();
?>
