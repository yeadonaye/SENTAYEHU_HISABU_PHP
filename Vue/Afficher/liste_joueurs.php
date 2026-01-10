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
    <!-- Navbar -->
    <?php include '../partials/navbar.php'; ?>

    <!-- Page Header -->
    <div style="background: linear-gradient(135deg, #C8102E 0%, #E8283C 100%); color: white; padding: 2rem 0; margin-bottom: 2rem;">
        <div class="container-fluid">
            <div style="display: flex; justify-content: space-between; align-items: center;">
                <div>
                    <h1 style="font-size: 2rem; font-weight: 700; margin: 0;"><i class="bi bi-people"></i> Liste des Joueurs</h1>
                    <p style="margin: 0.5rem 0 0; opacity: 0.9;">Gérez tous vos joueurs</p>
                </div>
                <a href="../Ajouter/ajouter_joueur.php" class="btn btn-light" style="font-weight: 600;">
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

        <div style="background: white; border-radius: 12px; box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08); overflow: hidden;">
            <?php if (!empty($joueurs)): ?>
                <div style="overflow-x: auto;">
                    <table style="width: 100%; border-collapse: collapse;">
                        <thead>
                            <tr style="background-color: #f8f9fa; border-bottom: 2px solid #ecf0f1;">
                                <th style="padding: 1rem; text-align: left; font-weight: 600; color: #2c3e50;">Id_Joueur</th>
                                <th style="padding: 1rem; text-align: left; font-weight: 600; color: #2c3e50;">Numéro de Licence</th>
                                <th style="padding: 1rem; text-align: left; font-weight: 600; color: #2c3e50;">Nom</th>
                                <th style="padding: 1rem; text-align: left; font-weight: 600; color: #2c3e50;">Prénom</th>
                                <th style="padding: 1rem; text-align: left; font-weight: 600; color: #2c3e50;">Date de Naissance</th>
                                <th style="padding: 1rem; text-align: left; font-weight: 600; color: #2c3e50;">Taille</th>
                                <th style="padding: 1rem; text-align: left; font-weight: 600; color: #2c3e50;">Poids</th>
                                <th style="padding: 1rem; text-align: left; font-weight: 600; color: #2c3e50;">Statut</th>
                                <th style="padding: 1rem; text-align: center; font-weight: 600; color: #2c3e50;">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($joueurs as $joueur): ?>
                                <tr style="border-bottom: 1px solid #ecf0f1; transition: background-color 0.3s ease;" onmouseover="this.style.backgroundColor='#f8f9fa'" onmouseout="this.style.backgroundColor='white'">
                                    <td style="padding: 1rem; color: #7f8c8d;"><?php echo htmlspecialchars($joueur['Id_Joueur']); ?></td>
                                    <td style="padding: 1rem; color: #2c3e50;"><?php echo htmlspecialchars($joueur['Num_Licence'] ?? '-'); ?></td>
                                    <td style="padding: 1rem; font-weight: 600; color: #2c3e50;"><?php echo htmlspecialchars($joueur['Nom']); ?></td>
                                    <td style="padding: 1rem; color: #2c3e50;"><?php echo htmlspecialchars($joueur['Prenom']); ?></td>
                                    <td style="padding: 1rem; color: #2c3e50;"><?php echo htmlspecialchars($joueur['Date_Naissance'] ?? '-'); ?></td>
                                    <td style="padding: 1rem; color: #2c3e50;"><?php echo htmlspecialchars($joueur['Taille'] ?? '-'); ?> cm</td>
                                    <td style="padding: 1rem; color: #2c3e50;"><?php echo htmlspecialchars($joueur['Poids'] ?? '-'); ?> kg</td>
                                    <td style="padding: 1rem; color: #2c3e50;">
                                        <span style="background-color: <?php echo (stripos($joueur['Statut'], 'bles') !== false) ? '#ffebee' : '#e8f5e9'; ?>; color: <?php echo (stripos($joueur['Statut'], 'bles') !== false) ? '#c62828' : '#2e7d32'; ?>; padding: 0.35rem 0.75rem; border-radius: 20px; font-size: 0.85rem; font-weight: 500;">
                                            <?php echo (stripos($joueur['Statut'], 'bles') !== false) ? 'Blessé' : 'Actif'; ?>
                                        </span>
                                    </td>
                                    <td style="padding: 1rem; text-align: center;">
                                        <a href="../Ajouter/ajouter_joueur.php?id=<?php echo $joueur['Id_Joueur']; ?>" class="btn btn-sm btn-outline-primary" style="margin-right: 0.5rem;">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <a href="../../Controleur/suppirmer/supprimer_joueur.php?id=<?php echo $joueur['Id_Joueur']; ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Êtes-vous sûr?')">
                                            <i class="bi bi-trash"></i>
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <div style="padding: 3rem; text-align: center;">
                    <i class="bi bi-inbox" style="font-size: 3rem; color: #bdc3c7; margin-bottom: 1rem; display: block;"></i>
                    <h3 style="color: #7f8c8d; margin-bottom: 1rem;">Aucun joueur trouvé</h3>
                    <p style="color: #95a5a6; margin-bottom: 1.5rem;">Commencez par ajouter votre premier joueur</p>
                    <a href="../Ajouter/ajouter_joueur.php" class="btn btn-primary">
                        <i class="bi bi-plus-circle me-2"></i>Ajouter un Joueur
                    </a>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <?php include '../partials/footer.php'; ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
