<?php

// Récupération de l'ID du joueur depuis l'URL, si présent
$id = $_GET['id'] ?? null;

// Redirection vers la page d'ajout/modification du joueur
// Si un ID est présent, on considère que c'est une modification
if ($id) {
    header('Location: /Vue/Ajouter/ajouter_joueur.php?id=' . $id);
} else {
    // Sinon, c'est un ajout de nouveau joueur
    header('Location: /Vue/Ajouter/ajouter_joueur.php');
}

// Terminer le script après redirection
exit;
?>