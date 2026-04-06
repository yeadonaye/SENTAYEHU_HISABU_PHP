<?php
$id = $_GET['id'] ?? null;

if ($id) {
    header('Location: /Vue/Ajouter/ajouter_commentaire.php?id=' . $id);
} else {
    header('Location: /Vue/Ajouter/ajouter_commentaire.php');
}
exit;
?>