<?php
require_once __DIR__ . '/../Modele/DAO/authapi.php';
require_once __DIR__ . '/../Modele/DAO/jwt_utils.php';

$secret = "secret_key";
$error = null;

// Handle POST login
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $jwt = seConnecter(); // return JWT if login succeeded

    if ($jwt) {
        // set cookie
        setcookie('jwt', $jwt, time() + 3600, "/", "", false, true);

        // Redirect to index.php immediately
        header('Location: /index.php');
        exit;
    } else {
        $error = 'Login ou mot de passe incorrect';
    }
}

// Show login form here if $error is set
?>