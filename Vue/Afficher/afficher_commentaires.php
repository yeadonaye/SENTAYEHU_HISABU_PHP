<?php include '../../Controleur/afficher/afficher_commentaires.php'; ?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Commentaires du joueur</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css">
    <link rel="stylesheet" href="../CSS/common.css">
    <link rel="stylesheet" href="../CSS/joueurs.css">
</head>
<body>
    <?php include 'navbar.php'; ?>

    <div class="container my-4">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <div>
                <h1 class="mb-1"><i class="bi bi-chat-dots"></i> Commentaires</h1>
                <p class="text-muted mb-0">Joueur : <strong><?php echo htmlspecialchars($joueur?->getNom() . ' ' . $joueur?->getPrenom()); ?></strong></p>
            </div>
            <div class="d-flex gap-2">
                <a class="btn btn-success" href="../Ajouter/ajouter_commentaire.php?id=<?php echo $joueurId; ?>">
                    <i class="bi bi-plus-circle me-2"></i>Ajouter un commentaire
                </a>
                <a class="btn btn-secondary" href="liste_joueurs.php">
                    <i class="bi bi-arrow-left me-2"></i>Retour
                </a>
            </div>
        </div>

        <?php if ($error): ?>
            <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>
        <?php if ($success && !$error): ?>
            <div class="alert alert-success"><?php echo htmlspecialchars($success); ?></div>
        <?php endif; ?>

        <?php if (!empty($commentaires)): ?>
            <div class="list-group">
                <?php foreach ($commentaires as $com): ?>
                    <div class="list-group-item">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <p class="mb-1"><?php echo nl2br(htmlspecialchars($com->getDescription())); ?></p>
                            </div>
                            <small class="text-muted ms-3">
                                <?php 
                                    $dt = DateTime::createFromFormat('Y-m-d H:i:s', $com->getDate()) ?: DateTime::createFromFormat('Y-m-d', $com->getDate());
                                    echo $dt ? $dt->format('d/m/Y H:i') : htmlspecialchars($com->getDate());
                                ?>
                            </small>
                        </div>
                        <div class="mt-2 d-flex gap-2">
                            <a class="btn btn-sm btn-outline-primary" href="../Modifier/modifier_commentaire.php?id=<?php echo $com->getIdCommentaire(); ?>">
                                <i class="bi bi-pencil"></i> Modifier
                            </a>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div class="alert alert-info">Aucun commentaire pour ce joueur.</div>
        <?php endif; ?>
    </div>

    <?php include 'footer.php'; ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
