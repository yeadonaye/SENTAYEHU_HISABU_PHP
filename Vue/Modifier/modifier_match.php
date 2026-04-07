<?php

// Récupération de l'ID du match depuis l'URL, si présent
$id = $_GET['id'] ?? null;

// Redirection vers la page d'ajout/modification du match
// Si un ID est présent, on considère que c'est une modification
if ($id) {
    header('Location: /Vue/Ajouter/ajouter_match.php?id=' . $id);
} else {
    // Sinon, c'est un ajout de nouveau match
    header('Location: /Vue/Ajouter/ajouter_match.php');
}

// Terminer le script après redirection
exit;
?>