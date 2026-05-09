<?php
session_start();

// Vérifier que l'utilisateur est connecté
if (!isset($_SESSION["user_id"])) {
    header("Location: ../login.php");
    exit();
}

include 'db.php';

$user_id = $_SESSION["user_id"];
$remote_user_id = $_GET['id'] ?? null;

// Vérifier que l'ID de l'utilisateur distant est fourni
if (!$remote_user_id || !is_numeric($remote_user_id)) {
    header("Location: ../inbox.php");
    exit();
}

// Vérifier que l'utilisateur distant existe
$check_sql = "SELECT id, username FROM users WHERE id = ?";
$check_stmt = $conn->prepare($check_sql);
$check_stmt->bind_param("i", $remote_user_id);
$check_stmt->execute();
$check_result = $check_stmt->get_result();

if ($check_result->num_rows === 0) {
    header("Location: ../inbox.php");
    exit();
}

$remote_user = $check_result->fetch_assoc();
$remote_username = $remote_user['username'];
$check_stmt->close();

// Vérifier si une conversation existe entre ces deux utilisateurs
$conv_sql = "SELECT id FROM conversations WHERE (user1_id = ? AND user2_id = ?) OR (user1_id = ? AND user2_id = ?)";
$conv_stmt = $conn->prepare($conv_sql);
$conv_stmt->bind_param("iiii", $user_id, $remote_user_id, $remote_user_id, $user_id);
$conv_stmt->execute();
$conv_result = $conv_stmt->get_result();

if ($conv_result->num_rows > 0) {
    $conversation = $conv_result->fetch_assoc();
    $conversation_id = $conversation['id'];
} else {
    // Créer une nouvelle conversation
    $insert_sql = "INSERT INTO conversations (user1_id, user2_id, last_message_time) VALUES (?, ?, NOW())";
    $insert_stmt = $conn->prepare($insert_sql);
    $insert_stmt->bind_param("ii", $user_id, $remote_user_id);
    $insert_stmt->execute();
    $conversation_id = $conn->insert_id;
    $insert_stmt->close();
}

$conv_stmt->close();

// Récupérer tous les messages de la conversation
$msg_sql = "SELECT m.id, m.sender_id, m.contenu, m.created_at, u.username 
           FROM messages m 
           JOIN users u ON m.sender_id = u.id 
           WHERE m.conversation_id = ? 
           ORDER BY m.created_at ASC";
$msg_stmt = $conn->prepare($msg_sql);
$msg_stmt->bind_param("i", $conversation_id);
$msg_stmt->execute();
$messages_result = $msg_stmt->get_result();
$messages = $messages_result->fetch_all(MYSQLI_ASSOC);
$msg_stmt->close();
$conn->close();
?>
