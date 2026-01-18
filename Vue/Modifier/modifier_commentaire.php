<?php include '../../Controleur/modifier/modifier_commentaire.php'; ?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier un commentaire</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css">
    <link rel="stylesheet" href="../CSS/common.css">
    <link rel="stylesheet" href="../CSS/joueurs.css">
</head>
<body>
    <?php include '../Afficher/navbar.php'; ?>
    <div class="container my-4">
        <h1 class="mb-3"><i class="bi bi-pencil-square"></i> Modifier le commentaire</h1>

        <?php if ($error): ?>
            <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>

        <?php if ($comment): ?>
            <form method="POST" action="">
                <div class="mb-3">
                    <label class="form-label fw-bold" for="description">Commentaire *</label>
                    <textarea class="form-control" id="description" name="description" rows="4" required><?php echo htmlspecialchars($comment->getDescription()); ?></textarea>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-bold" for="date_commentaire">Date du commentaire</label>
                    <?php
                        $dtVal = '';
                        $dt = DateTime::createFromFormat('Y-m-d H:i:s', $comment->getDate()) ?: DateTime::createFromFormat('Y-m-d', $comment->getDate());
                        if ($dt) {
                            $dtVal = $dt->format('Y-m-d\TH:i');
                        }
                    ?>
                    <input type="datetime-local" class="form-control" id="date_commentaire" name="date_commentaire" value="<?php echo htmlspecialchars($_POST['date_commentaire'] ?? $dtVal); ?>">
                    <small class="text-muted">Laisser vide pour conserver la date actuelle du commentaire.</small>
                </div>
                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary"><i class="bi bi-save me-2"></i>Enregistrer</button>
                    <a href="../Afficher/afficher_commentaires.php?id=<?php echo $comment->getIdJoueur(); ?>" class="btn btn-secondary">Annuler</a>
                </div>
            </form>
        <?php endif; ?>
    </div>
    <?php include '../Afficher/footer.php'; ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
