<?php
require_once __DIR__ . '/../Modele/DAO/authapi.php';
require_once __DIR__ . '/../Modele/DAO/jwt_utils.php';

$secret = "secret_key";
$jwt = $_COOKIE['jwt'] ?? null; // get token from cookie

// If JWT is already valid, redirect immediately
if ($jwt && is_jwt_valid($jwt, $secret)) {
    header('Location: /index.php');
    exit;
}

// Otherwise, try to log in
$error = seConnecter(); // returns null if login succeeded

// If login succeeded, $error is null, and the JWT has been generated
if ($error === null) {
    // Redirect to the main page
    header('Location: /index.php');
    exit;
}

// If $error is not null, the login form will be shown with the error
?>