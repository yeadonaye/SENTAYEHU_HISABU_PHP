<?php
// Système d'authentification simple
session_start();

// Mot de passe en dur
const ADMIN_PASSWORD = 'liverpoolPhp';

/**
 * Vérifier si l'utilisateur est authentifié
 */
function isAuthenticated() {
    return isset($_SESSION['authenticated']) && $_SESSION['authenticated'] === true;
}

/**
 * Authentifier l'utilisateur
 */
function authenticate($password) {
    if ($password === ADMIN_PASSWORD) {
        $_SESSION['authenticated'] = true;
        $_SESSION['login_time'] = time();
        return true;
    }
    return false;
}

/**
 * Rediriger vers la page de login si non authentifié
 */
function requireAuth() {
    if (!isAuthenticated()) {
        header('Location: ' . dirname($_SERVER['PHP_SELF']) . '/login.php?redirect=' . urlencode($_SERVER['REQUEST_URI']));
        exit;
    }
}

/**
 * Déconnecter l'utilisateur
 */
function logout() {
    $_SESSION['authenticated'] = false;
    session_destroy();
    header('Location: login.php');
    exit;
}

/**
 * Connexion à la base de données SQLite
 */
function getDBConnection() {
    // Chemin du fichier de base de données SQLite
    $dbPath = __DIR__ . '/data/gestion_joueurs.db';
    $dbDir = dirname($dbPath);
    
    // Créer le répertoire data s'il n'existe pas
    if (!is_dir($dbDir)) {
        mkdir($dbDir, 0755, true);
    }
    
    try {
        $pdo = new PDO('sqlite:' . $dbPath);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
        // Initialiser la base de données si elle n'existe pas
        initializeDatabase($pdo);
        
        return $pdo;
    } catch (PDOException $e) {
        die("Erreur de connexion à la base de données: " . $e->getMessage());
    }
}

/**
 * Initialiser la base de données avec les tables nécessaires
 */
function initializeDatabase($pdo) {
    // Vérifier si la table Joueur existe
    $stmt = $pdo->query("SELECT name FROM sqlite_master WHERE type='table' AND name='Joueur'");
    if ($stmt->fetch()) {
        return; // La base de données est déjà initialisée
    }
    
    // Créer les tables
    $sql = file_get_contents(__DIR__ . '/database.sqlite.sql');
    
    // Exécuter les commandes SQL
    $pdo->exec($sql);
}
?>
