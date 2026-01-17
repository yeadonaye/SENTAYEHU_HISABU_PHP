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
    <?php include 'navbar.php'; ?>
    <div class="container-fluid">
        <?php if (!empty($matchs)): ?>
            <div class="match-cards-grid">
                <?php foreach ($matchs as $match): ?>
                    <div class="match-card">
                        <div class="match-card-header">
                            <div>
                                <p class="match-card-date">
                                    <i class="bi bi-calendar"></i> 
                                    <?php echo (new DateTime($match['Date_Rencontre']))->format('d/m/Y'); ?>
                                </p>
                                <h3 class="match-card-opponent h3">
                                    <?php echo htmlspecialchars($match['Nom_Equipe_Adverse']); ?>
                                </h3>
                            </div>
                            <div class="match-card-time">
                                <p>
                                    <i class="bi bi-clock"></i> 
                                    <?php echo substr($match['Heure'], 0, 5); ?>
                                </p>
                            </div>
                        </div>

                        <div class="match-card-body">
                            <div class="match-score">
                                <div class="match-score-team">
                                    <p>Notre équipe</p>
                                    <p><?php echo htmlspecialchars($match['Score_Nous'] ?? '-'); ?></p>
                                </div>
                                <div class="match-score-vs">vs</div>
                                <div class="match-score-team">
                                    <p>Adversaires</p>
                                    <p><?php echo htmlspecialchars($match['Score_Adverse'] ?? '-'); ?></p>
                                </div>
                            </div>

                            <div class="match-location">
                                <i class="bi bi-geo-alt"></i><?php echo htmlspecialchars($match['Lieu'] ?? 'Lieu non spécifié'); ?>
                            </div>

                            <?php 
                                $comp = $compositions[$match['Id_Match']] ?? ['titulaires' => 0, 'remplacants' => 0];
                            ?>
                            <div class="match-composition">
                                <i class="bi bi-people-fill" style="color: #C8102E;"></i>
                                Titulaires: <strong><?php echo $comp['titulaires']; ?>/11</strong> | 
                                Remplaçants: <strong><?php echo $comp['remplacants']; ?></strong>
                            </div>

                            <div class="match-actions">
                                <a href="../Ajouter/saisie_feuille_match.php?id=<?php echo $match['Id_Match']; ?>" class="btn btn-sm btn-success">
                                    <i class="bi bi-clipboard2-data me-1"></i>Composer l'équipe
                                </a>
                                <a href="../Modifier/modifier_match.php?id=<?php echo $match['Id_Match']; ?>" class="btn btn-sm btn-outline-primary">
                                    <i class="bi bi-pencil me-1"></i>Modifier
                                </a>
                                <a href="../../Controleur/suppirmer/supprimer_match.php?id=<?php echo $match['Id_Match']; ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Êtes-vous sûr?')">
                                    <i class="bi bi-trash me-1"></i>Supprimer
                                </a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div class="empty-state">
                <i class="bi bi-calendar-x empty-state-icon"></i>
                <h3 class="empty-state-title">Aucun match trouvé</h3>
                <p class="empty-state-text">Planifiez votre premier match</p>
                <a href="../Ajouter/ajouter_match.php" class="btn btn-primary">
                    <i class="bi bi-plus-circle me-2"></i>Planifier un Match
                </a>
            </div>
        <?php endif; ?>
    </div>

    <?php include 'footer.php'; ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
