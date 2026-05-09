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

// Validation
if (!$conversation_id || !is_numeric($conversation_id) || empty(trim($message))) {
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

// Insérer le message
$insert_sql = "INSERT INTO messages (conversation_id, sender_id, contenu, created_at) VALUES (?, ?, ?, NOW())";
$insert_stmt = $conn->prepare($insert_sql);
$insert_stmt->bind_param("iis", $conversation_id, $user_id, $message);

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
