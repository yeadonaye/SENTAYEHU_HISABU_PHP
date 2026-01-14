<?php include '../../Controleur/afficher/afficher_match.php'; ?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Calendrier des Matchs</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css">
    <link rel="stylesheet" href="../CSS/common.css">
    <link rel="stylesheet" href="../CSS/matchs.css">
</head>
<body>
    <!-- Navbar -->
    <?php include '../partials/navbar.php'; ?>
    
    <div class="container-fluid">
        <?php if (!empty($matchs)): ?>
            <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(350px, 1fr)); gap: 1.5rem; margin-bottom: 2rem;">
                <?php foreach ($matchs as $match): ?>
                    <div style="background: white; border-radius: 12px; box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08); overflow: hidden; transition: all 0.3s ease;" class="match-card">
                        <div style="background: linear-gradient(135deg, #C8102E 0%, #E8283C 100%); color: white; padding: 1.25rem;">
                            <div style="display: flex; justify-content: space-between; align-items: start;">
                                <div>
                                    <p style="margin: 0; font-size: 0.85rem; opacity: 0.9;">
                                        <i class="bi bi-calendar"></i> 
                                        <?php echo (new DateTime($match['Date_Rencontre']))->format('d/m/Y'); ?>
                                    </p>
                                    <h3 style="margin: 0.5rem 0 0; font-size: 1.5rem; font-weight: 700;">
                                        <?php echo htmlspecialchars($match['Nom_Equipe_Adverse']); ?>
                                    </h3>
                                </div>
                                <div style="text-align: right;">
                                    <p style="margin: 0; font-size: 0.85rem; opacity: 0.9;">
                                        <i class="bi bi-clock"></i> 
                                        <?php echo substr($match['Heure'], 0, 5); ?>
                                    </p>
                                </div>
                            </div>
                        </div>

                        <div style="padding: 1.25rem;">
                            <div style="display: grid; grid-template-columns: 1fr auto 1fr; gap: 1rem; align-items: center; margin-bottom: 1rem;">
                                <div style="text-align: center;">
                                    <p style="margin: 0; font-size: 0.9rem; color: #7f8c8d;">Notre équipe</p>
                                    <p style="margin: 0.5rem 0 0; font-size: 1.5rem; font-weight: 700; color: #2c3e50;">
                                        <?php echo htmlspecialchars($match['Score_Nous'] ?? '-'); ?>
                                    </p>
                                </div>
                                <div style="text-align: center;">
                                    <p style="margin: 0; color: #7f8c8d; font-weight: 600;">vs</p>
                                </div>
                                <div style="text-align: center;">
                                    <p style="margin: 0; font-size: 0.9rem; color: #7f8c8d;">Adversaires</p>
                                    <p style="margin: 0.5rem 0 0; font-size: 1.5rem; font-weight: 700; color: #2c3e50;">
                                        <?php echo htmlspecialchars($match['Score_Adverse'] ?? '-'); ?>
                                    </p>
                                </div>
                            </div>

                            <div style="background-color: #f8f9fa; padding: 0.75rem; border-radius: 6px; margin-bottom: 1rem; font-size: 0.9rem; color: #7f8c8d;">
                                <i class="bi bi-geo-alt me-2"></i><?php echo htmlspecialchars($match['Lieu'] ?? 'Lieu non spécifié'); ?>
                            </div>

                            <?php 
                                $comp = $compositions[$match['Id_Match']] ?? ['titulaires' => 0, 'remplacants' => 0];
                            ?>
                            <div style="background-color: #fff3cd; padding: 0.75rem; border-radius: 6px; margin-bottom: 1rem; font-size: 0.85rem;">
                                <i class="bi bi-people-fill me-2" style="color: #C8102E;"></i>
                                Titulaires: <strong><?php echo $comp['titulaires']; ?>/11</strong> | 
                                Remplaçants: <strong><?php echo $comp['remplacants']; ?></strong>
                            </div>

                            <div style="display: flex; gap: 0.5rem;">
                                <a href="saisie_feuille_match.php?id=<?php echo $match['Id_Match']; ?>" class="btn btn-sm btn-success" style="flex: 1;">
                                    <i class="bi bi-clipboard2-data me-1"></i>Composer l'équipe
                                </a>
                                <a href="../Modifier/modifier_match.php?id=<?php echo $match['Id_Match']; ?>" class="btn btn-sm btn-outline-primary" style="flex: 1;">
                                    <i class="bi bi-pencil me-1"></i>Modifier
                                </a>
                                <a href="../../Controleur/suppirmer/supprimer_match.php?id=<?php echo $match['Id_Match']; ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Êtes-vous sûr?')" style="flex: 1;">
                                    <i class="bi bi-trash me-1"></i>Supprimer
                                </a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div style="background: white; border-radius: 12px; box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08); padding: 3rem; text-align: center;">
                <i class="bi bi-calendar-x" style="font-size: 3rem; color: #bdc3c7; margin-bottom: 1rem; display: block;"></i>
                <h3 style="color: #7f8c8d; margin-bottom: 1rem;">Aucun match trouvé</h3>
                <p style="color: #95a5a6; margin-bottom: 1.5rem;">Planifiez votre premier match</p>
                <a href="ajouter_match.php" class="btn btn-primary">
                    <i class="bi bi-plus-circle me-2"></i>Planifier un Match
                </a>
            </div>
        <?php endif; ?>
    </div>

    <?php include '../partials/footer.php'; ?>

    <style>
        .match-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.15);
        }
    </style>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
