<?php
// auth.php lives in Modele/DAO
require_once __DIR__ . '/../Modele/DAO/auth.php';

if (isAuthenticated()) {
    header('Location: ' . appUrl('index.php'));
    exit;
}

$error = '';
$redirect = $_GET['redirect'] ?? appUrl('index.php');

if (!is_string($redirect) || $redirect === '' || preg_match('/^https?:\/\//i', $redirect)) {
    $redirect = appUrl('index.php');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $identifiant = $_POST['identifiant'] ?? '';
    $password = $_POST['password'] ?? '';
    
    if (authenticate($identifiant, $password)) {
        header('Location: ' . $redirect);
        exit;
    } else {
        $error = 'Identifiant ou mot de passe incorrect!';
    }
}
?>