<?php include '../../Controleur/afficher/statistiques.php'; ?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Statistiques</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css">
    <link rel="stylesheet" href="../CSS/common.css">
    <link rel="stylesheet" href="../CSS/index.css">
</head>
<body>
    <?php include 'navbar.php'; ?>
    <!-- Page Header -->
    <div class="page-header-gradient">
        <div class="container-fluid">
            <h1 class="page-header-title"><i class="bi bi-graph-up"></i> Statistiques</h1>
            <p class="page-header-subtitle">Vue d'ensemble des performances</p>
        </div>
    </div>

    <div class="container-fluid">
        <?php if ($error): ?>
            <div class="alert alert-danger" role="alert">
                <i class="bi bi-exclamation-circle me-2"></i>
                <?php echo htmlspecialchars($error); ?>
            </div>
        <?php endif; ?>
        <!-- Main Stats Row -->
        <div class="row g-4 mb-4">
            <!-- Total Joueurs -->
            <div class="col-md-3 col-sm-6">
                <div class="stat-card">
                    <div class="stat-icon">
                        <i class="bi bi-people-fill"></i>
                    </div>
                    <h3 class="stat-number"><?php echo $stats['totalJoueurs']; ?></h3>
                    <p class="stat-label">Joueurs Totaux</p>
                </div>
            </div>

            <!-- Total Matchs -->
            <div class="col-md-3 col-sm-6">
                <div class="stat-card">
                    <div class="stat-icon">
                        <i class="bi bi-calendar-event"></i>
                    </div>
                    <h3 class="stat-number"><?php echo $stats['totalMatchs']; ?></h3>
                    <p class="stat-label">Matchs Joués</p>
                </div>
            </div>

            <!-- Taux Victoire -->
            <div class="col-md-3 col-sm-6">
                <div class="stat-card">
                    <div class="stat-icon">
                        <i class="bi bi-trophy-fill"></i>
                    </div>
                    <h3 class="stat-number"><?php echo $tauxVictoire; ?>%</h3>
                    <p class="stat-label">Taux de Victoire</p>
                </div>
            </div>

            <!-- Différence Buts -->
            <div class="col-md-3 col-sm-6">
                <div class="stat-card">
                    <div class="stat-icon">
                        <i class="bi bi-target"></i>
                    </div>
                    <h3 class="stat-number" data-value="<?php echo $differenceButts; ?>">
                        <?php echo $differenceButtsDisplay; ?>
                    </h3>
                    <p class="stat-label">Différence Buts</p>
                </div>
            </div>
        </div>

        <!-- Performance Details -->
        <div class="row g-4 mb-4">
            <div class="col-12">
                <div class="section-card">
                    <div class="section-header">
                        <h2 class="section-title"><i class="bi bi-bar-chart"></i> Performance de l'Équipe</h2>
                    </div>
                    <div class="section-body">
                        <div class="performance-grid">
                            <div class="performance-card wins">
                                <p class="performance-label">Victoires</p>
                                <h3 class="performance-number"><?php echo $stats['victoires']; ?></h3>
                            </div>

                            <div class="performance-card draws">
                                <p class="performance-label">Nuls</p>
                                <h3 class="performance-number"><?php echo $stats['nuls']; ?></h3>
                            </div>

                            <div class="performance-card losses">
                                <p class="performance-label">Défaites</p>
                                <h3 class="performance-number"><?php echo $stats['defaites']; ?></h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    <!-- Per-player table -->
    <div class="container my-5">
        <div class="section-card">
            <div class="section-header">
                <h2 class="section-title"><i class="bi bi-people"></i> Statistiques par Joueur</h2>
            </div>
            <div class="section-body">
                <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Nom</th>
                            <th>Statut</th>
                            <th>Titulaires</th>
                            <th>Remplaçants</th>
                            <th>Moyenne notes</th>
                            <th>% Victoires (lorsqu'il a joué)</th>
                            <th>Sélections consécutives</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($players as $pl): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($pl['Nom'] . ' ' . $pl['Prenom']); ?></td>
                                <td><?php echo htmlspecialchars($pl['Statut'] ?? ''); ?></td>
                                <td><?php echo (int)($pl['starts'] ?? 0); ?></td>
                                <td><?php echo (int)($pl['subs'] ?? 0); ?></td>
                                <td><?php echo $pl['avgNote'] !== null ? $pl['avgNote'] . '/5' : '-'; ?></td>
                                <td><?php echo $pl['winPercentWhenParticipated'] !== null ? $pl['winPercentWhenParticipated'] . '%' : '-'; ?></td>
                                <td><?php echo (int)($pl['consecutiveSelections'] ?? 0); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
                </div>
            </div>
        </div>
    </div>

        <!-- Buts Stats -->
        <div class="row g-4 mb-4">
            <div class="col-lg-12">
                <div class="section-card">
                    <div class="section-header">
                        <h2 class="section-title"><i class="bi bi-target"></i> Buts</h2>
                    </div>
                    <div class="section-body">
                        <div class="goals-stat">
                            <div class="goals-stat-header">
                                <span class="goals-label">Buts Marqués</span>
                                <span class="goals-number scored"><?php echo $stats['totalButs']; ?></span>
                            </div>
                            <div class="progress-bar-wrapper">
                                <div class="progress-bar-filled"></div>
                            </div>
                        </div>

                        <div class="goals-stat">
                            <div class="goals-stat-header">
                                <span class="goals-label">Buts Encaissés</span>
                                <span class="goals-number conceded"><?php echo $stats['butsEncaisses']; ?></span>
                            </div>
                            <div class="progress-bar-wrapper">
                                <div class="progress-bar-filled conceded" style="width: <?php echo $progressEncaissesPct; ?>%;"></div>
                            </div>
                        </div>

                        <div class="goals-average">
                            <p class="goals-average-label">Moyenne par Match</p>
                            <p class="goals-average-value">
                                <?php echo $butsMoyenneParMatch; ?> buts/match
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="row g-4">
            <div class="col-12">
                <div class="section-card">
                    <div class="section-header">
                        <h2 class="section-title"><i class="bi bi-lightning"></i> Actions Rapides</h2>
                    </div>
                    <div class="section-body">
                        <div class="quick-actions-grid">
                            <a href="liste_joueurs.php" class="action-card action-card-blue">
                                <i class="bi bi-person-plus action-card-icon"></i>
                                <strong>Ajouter un Joueur</strong>
                            </a>

                            <a href="../matchs/calendrier.php" class="action-card action-card-green">
                                <i class="bi bi-calendar-plus action-card-icon"></i>
                                <strong>Planifier un Match</strong>
                            </a>

                            <a href="liste_joueurs.php" class="action-card action-card-purple">
                                <i class="bi bi-people action-card-icon"></i>
                                <strong>Voir les Joueurs</strong>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php include 'footer.php'; ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
