<?php
/*
// Configuration pour XAMPP local
define('DB_TYPE', 'mysql');
define('DB_HOST', '127.0.0.1');  // localhost pour XAMPP
define('DB_PORT', 3306);         // port MySQL standard
define('DB_NAME', 'yeadonaye_bd_gestion_equipe'); // nom de la BD
define('DB_USER', 'root');       // utilisateur XAMPP par défaut
define('DB_PASS', '');           // mot de passe par défaut vide pour XAMPP
define('DB_CHARSET', 'utf8mb4');*/


// Configuration pour alwaysdata
define('DB_TYPE', 'mysql');     // type de la BD
define('DB_HOST', 'mysql-yeadonaye.alwaysdata.net');  // nom du serveur alwaysdata
define('DB_PORT', 3306);         // port MySQL standard
define('DB_NAME', 'yeadonaye_bd_gestion_equipe'); // nom de la BD
define('DB_USER', 'yeadonaye');       // utilisateur sur alwaysdata, mais je pense créer un nouvel utilisateur pour l'appli
define('DB_PASS', 'admin@gestionFoot');           // mdp pour alwaysdata
define('DB_CHARSET', 'utf8mb4');

?>

