<?php include '../../Controleur/ajouter/ajouter_commentaire.php'; ?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajouter un commentaire</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css">
    <link rel="stylesheet" href="../CSS/common.css">
    <link rel="stylesheet" href="../CSS/joueurs.css">
</head>
<body>
    <?php include '../Afficher/navbar.php'; ?>
    <div class="container my-4">
        <h1 class="mb-3"><i class="bi bi-chat-left-text"></i> Ajouter un commentaire</h1>
        <p class="text-muted">Joueur : <strong><?php echo htmlspecialchars($joueur?->getNom() . ' ' . $joueur?->getPrenom()); ?></strong></p>

        <?php if ($error): ?>
            <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>

        <form method="POST" action="">
            <div class="mb-3">
                <label class="form-label fw-bold" for="description">Commentaire *</label>
                <textarea class="form-control" id="description" name="description" rows="4" required><?php echo htmlspecialchars($_POST['description'] ?? ''); ?></textarea>
            </div>
            <div class="mb-3">
                <label class="form-label fw-bold" for="date_commentaire">Date du commentaire</label>
                <input type="text" class="form-control" id="date_commentaire" name="date_commentaire" placeholder="jj/mm/aaaa" value="<?php echo htmlspecialchars($_POST['date_commentaire'] ?? ''); ?>">
                <small class="text-muted">Format attendu : jj/mm/aaaa. Laisser vide pour utiliser la date du jour.</small>
            </div>
            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-success"><i class="bi bi-save me-2"></i>Enregistrer</button>
                <a href="../Afficher/afficher_commentaires.php?id=<?php echo $joueurId; ?>" class="btn btn-secondary">Annuler</a>
            </div>
        </form>
    </div>
    <?php include '../Afficher/footer.php'; ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
