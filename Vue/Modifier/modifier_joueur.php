<?php

$id = $_GET['id'] ?? null;

if ($id) {
    header('Location: /Vue/Ajouter/ajouter_joueur.php?id=' . $id);
} else {
    header('Location: /Vue/Ajouter/ajouter_joueur.php');
}
exit;
?>
