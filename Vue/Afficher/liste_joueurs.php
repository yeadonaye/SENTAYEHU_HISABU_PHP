<?php include '../../Controleur/afficher/afficher_joueur.php'; ?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liste des Joueurs</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css">
    <link rel="stylesheet" href="../CSS/common.css">
    <link rel="stylesheet" href="../CSS/joueurs.css">
</head>
<body>
    <?php include 'navbar.php'; ?>
    <!-- Page Header -->
    <div class="page-header-gradient">
        <div class="container-fluid">
            <div class="page-header-content">
                <div>
                    <h1 class="page-header-title"><i class="bi bi-people"></i> Liste des Joueurs</h1>
                    <p class="page-header-subtitle">Gérez tous vos joueurs</p>
                </div>
                <a href="../Ajouter/ajouter_joueur.php" class="btn btn-light btn-add-player">
                    <i class="bi bi-plus-circle me-2"></i>Ajouter un Joueur
                </a>
            </div>
        </div>
    </div>

    <div class="container-fluid">
        <?php if ($error): ?>
            <div class="alert alert-danger" role="alert">
                <i class="bi bi-exclamation-circle me-2"></i>
                <?php echo htmlspecialchars($error); ?>
            </div>
        <?php endif; ?>

        <div class="players-card">
            <?php if (!empty($joueurs)): ?>
                <div class="table-responsive">
                    <table class="players-table">
                        <thead>
                            <tr class="players-table-header">
                                <th>Id_Joueur</th>
                                <th>Numéro de Licence</th>
                                <th>Nom</th>
                                <th>Prénom</th>
                                <th>Date de Naissance</th>
                                <th>Taille</th>
                                <th>Poids</th>
                                <th>Statut</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($joueurs as $joueur): ?>
                                <tr class="players-table-row">
                                    <td class="players-table-id"><?php echo htmlspecialchars($joueur['Id_Joueur']); ?></td>
                                    <td><?php echo htmlspecialchars($joueur['Num_Licence'] ?? '-'); ?></td>
                                    <td class="players-table-name"><?php echo htmlspecialchars($joueur['Nom']); ?></td>
                                    <td><?php echo htmlspecialchars($joueur['Prenom']); ?></td>
                                    <td><?php echo $joueur['Date_Naissance'] ? (new DateTime($joueur['Date_Naissance']))->format('d/m/Y') : '-'; ?></td>
                                    <td><?php echo htmlspecialchars($joueur['Taille'] ?? '-'); ?> m</td>
                                    <td><?php echo htmlspecialchars($joueur['Poids'] ?? '-'); ?> kg</td>
                                    <td>
                                        <span class="status-badge <?php echo (stripos($joueur['Statut'], 'bles') !== false) ? 'status-injured' : 'status-active'; ?>">
                                            <?php echo (stripos($joueur['Statut'], 'bles') !== false) ? 'Blessé' : 'Actif'; ?>
                                        </span>
                                    </td>
                                    <td class="players-table-actions">
                                        <a href="../Modifier/modifier_joueur.php?id=<?php echo $joueur['Id_Joueur']; ?>" class="btn btn-sm btn-outline-primary" title="Modifier">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <a href="../Ajouter/ajouter_commentaire.php?id=<?php echo $joueur['Id_Joueur']; ?>" class="btn btn-sm btn-outline-success" title="Ajouter un commentaire">
                                            <i class="bi bi-chat-left-text"></i>
                                        </a>
                                        <a href="afficher_commentaires.php?id=<?php echo $joueur['Id_Joueur']; ?>" class="btn btn-sm btn-outline-info" title="Voir les commentaires">
                                            <i class="bi bi-chat-dots"></i>
                                        </a>
                                        <a href="../../Controleur/suppirmer/supprimer_joueur.php?id=<?php echo $joueur['Id_Joueur']; ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce joueur ?')" title="Supprimer">
                                            <i class="bi bi-trash"></i>
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <div class="empty-state">
                    <i class="bi bi-inbox empty-state-icon"></i>
                    <h3 class="empty-state-title">Aucun joueur trouvé</h3>
                    <p class="empty-state-text">Commencez par ajouter votre premier joueur</p>
                    <a href="../Ajouter/ajouter_joueur.php" class="btn btn-primary">
                        <i class="bi bi-plus-circle me-2"></i>Ajouter un Joueur
                    </a>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <?php include 'footer.php'; ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
