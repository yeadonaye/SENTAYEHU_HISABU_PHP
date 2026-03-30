<?php
// On démarre la session pour pouvoir y accéder
session_start();

// On vide toutes les variables de session (dont le token)
$_SESSION = array();

// On détruit le cookie de session sur le navigateur du client si il existe
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

// On détruit la session sur le serveur frontend
session_destroy();

// On redirige vers la page de login
header("Location: login.php"); 
exit();
?>