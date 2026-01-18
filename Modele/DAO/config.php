<?php
// Configuration par défaut (surchargée via les variables d'environnement)
// Pour la production, définissez les variables d'environnement suivantes :
// DB_TYPE, DB_HOST, DB_PORT, DB_NAME, DB_USER, DB_PASS, DB_CHARSET
define('DB_TYPE', getenv('DB_TYPE') ?: 'mysql');                 // type de la bd
define('DB_HOST', getenv('DB_HOST') ?: '127.0.0.1');             // nom du serveur
define('DB_PORT', getenv('DB_PORT') ?: 3306);                    // port MySQL standard
define('DB_NAME', getenv('DB_NAME') ?: 'gestion_joueurs');       // nom de la BD par défaut
define('DB_USER', getenv('DB_USER') ?: 'root');                  // utilisateur par défaut
define('DB_PASS', getenv('DB_PASS') ?: '');                      // mot de passe par défaut vide
define('DB_CHARSET', getenv('DB_CHARSET') ?: 'utf8mb4');
?>

