<?php
// traitements/send_message.php
session_start();

if (!isset($_SESSION["user_id"])) {
    header("Location: ../login.php");
    exit();
}

include 'db.php';

$user_id = $_SESSION["user_id"];
$conversation_id = isset($_POST['conversation_id']) ? intval($_POST['conversation_id']) : null;
$message = isset($_POST['message']) ? trim($_POST['message']) : '';
$image_path = '';

if (!$conversation_id) {
    header("Location: ../inbox.php");
    exit();
}

// Validation : Vérifier si l'utilisateur fait partie de la conversation
$check_sql = "SELECT id FROM conversations WHERE id = ? AND (user1_id = ? OR user2_id = ?)";
$check_stmt = $conn->prepare($check_sql);
$check_stmt->bind_param("iii", $conversation_id, $user_id, $user_id);
$check_stmt->execute();
if ($check_stmt->get_result()->num_rows === 0) {
    $check_stmt->close();
    header("Location: ../inbox.php");
    exit();
}
$check_stmt->close();

// Traitement sécurisé du téléversement d'image
if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
    $file = $_FILES['image'];
    
    // Détection stricte du type de fichier
    $allowed_extensions = ['jpg', 'jpeg', 'png', 'gif', 'bmp'];
    $allowed_types = ['image/jpeg', 'image/png', 'image/gif', 'image/bmp', 'image/x-ms-bmp'];
    
    $file_ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    
    // Validation de l'extension et du type MIME déclaré
    if (in_array($file_ext, $allowed_extensions) && in_array($file['type'], $allowed_types)) {
        if ($file['size'] <= 5 * 1024 * 1024) { // Limite de 5 Mo
            
            // Création du dossier s'il n'existe pas
            $upload_dir = __DIR__ . '/../uploads/images/';
            if (!is_dir($upload_dir)) {
                mkdir($upload_dir, 0755, true);
            }
            
            // Génération d'un nom unique et aléatoire pour neutraliser les injections de fichiers
            $new_file_name = bin2hex(random_bytes(16)) . '.' . $file_ext;
            $dest_path = $upload_dir . $new_file_name;
            
            if (move_uploaded_file($file['tmp_name'], $dest_path)) {
                // Chemin relatif stocké pour l'application
                $image_path = 'uploads/images/' . $new_file_name;
            }
        }
    }
}

// Empêcher l'envoi d'un message vide sans image
if (empty($message) && empty($image_path)) {
    header("Location: ../conversation.php?id=" . $conversation_id);
    exit();
}

// Structuration du contenu du message
$full_message = $message;
if (!empty($image_path)) {
    $full_message .= (!empty($full_message) ? "\n" : "") . "[IMG:" . $image_path . "]";
}

// Insertion du message
$insert_sql = "INSERT INTO messages (conversation_id, sender_id, contenu, created_at) VALUES (?, ?, ?, NOW())";
$insert_stmt = $conn->prepare($insert_sql);
$insert_stmt->bind_param("iis", $conversation_id, $user_id, $full_message);

if ($insert_stmt->execute()) {
    // Mise à jour de l'indicateur temporel de la conversation
    $update_sql = "UPDATE conversations SET last_message_time = NOW() WHERE id = ?";
    $update_stmt = $conn->prepare($update_sql);
    $update_stmt->bind_param("i", $conversation_id);
    $update_stmt->execute();
    $update_stmt->close();
}
$insert_stmt->close();

// Déterminer l'ID du destinataire pour la redirection finale
$conv_sql = "SELECT user1_id, user2_id FROM conversations WHERE id = ?";
$conv_stmt = $conn->prepare($conv_sql);
$conv_stmt->bind_param("i", $conversation_id);
$conv_stmt->execute();
$conv_res = $conv_stmt->get_result()->fetch_assoc();
$conv_stmt->close();

$remote_user_id = ($conv_res['user1_id'] == $user_id) ? $conv_res['user2_id'] : $conv_res['user1_id'];

$conn->close();
header("Location: ../conversation.php?id=" . $remote_user_id);
exit();
?>