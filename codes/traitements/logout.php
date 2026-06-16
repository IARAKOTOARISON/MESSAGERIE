<?php
// traitements/logout.php
session_start();

// Vider toutes les variables de session
$_SESSION = array();

// Détruire le cookie de session associé au navigateur si présent
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

// Destruction de la session sur le serveur
session_destroy();

// Redirection vers l'interface de connexion
header("Location: ../login.php");
exit();
?>