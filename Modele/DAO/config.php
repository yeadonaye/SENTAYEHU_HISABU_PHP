<?php
// Configuration par défaut (surchargée via les variables d'environnement)
// Pour la production, définissez les variables d'environnement suivantes :
// DB_TYPE, DB_HOST, DB_PORT, DB_NAME, DB_USER, DB_PASS, DB_CHARSET

$serverName = $_SERVER['SERVER_NAME'] ?? $_SERVER['HTTP_HOST'] ?? 'localhost';
$isLocalHost = in_array($serverName, ['localhost', '127.0.0.1', '::1'], true);

$defaultHost = $isLocalHost ? '127.0.0.1' : 'mysql-yeadonaye.alwaysdata.net';
$defaultDbName = $isLocalHost ? 'gestion_joueurs' : 'yeadonaye_bd_gestion_equipe';
$defaultUser = $isLocalHost ? 'root' : 'yeadonaye';
$defaultPass = $isLocalHost ? '' : 'admin@gestionFoot';

define('DB_TYPE', getenv('DB_TYPE') ?: 'mysql');                    // type de la bd
define('DB_HOST', getenv('DB_HOST') ?: $defaultHost);               // nom du serveur
define('DB_PORT', (int)(getenv('DB_PORT') ?: 3306));                // port MySQL standard
define('DB_NAME', getenv('DB_NAME') ?: $defaultDbName);             // nom de la BD par défaut
define('DB_USER', getenv('DB_USER') ?: $defaultUser);               // utilisateur par défaut
define('DB_PASS', getenv('DB_PASS') ?: $defaultPass);               // mot de passe par défaut vide
define('DB_CHARSET', getenv('DB_CHARSET') ?: 'utf8mb4');            // charset par défaut
?>

