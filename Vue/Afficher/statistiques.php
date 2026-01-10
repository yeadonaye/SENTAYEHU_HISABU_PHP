<?php
// auth.php lives in Modele/DAO; require it relative to this view
require_once __DIR__ . '/../../Modele/DAO/auth.php';
requireAuth();

$pdo = getDBConnection();
$stats = [
    'totalJoueurs' => 0,
    'totalMatchs' => 0,
    'victoires' => 0,
    'defaites' => 0,
    'nuls' => 0,
    'totalButs' => 0,
    'butsEncaisses' => 0,
];

try {
    // Total joueurs
    $stmt = $pdo->query('SELECT COUNT(*) as count FROM Joueur');
    $stats['totalJoueurs'] = $stmt->fetch(PDO::FETCH_ASSOC)['count'];

    // Matchs et scores
    $stmt = $pdo->query('
        SELECT 
            COUNT(*) as total,
            SUM(CASE WHEN Score_Nous > Score_Adverse THEN 1 ELSE 0 END) as victoires,
            SUM(CASE WHEN Score_Nous < Score_Adverse THEN 1 ELSE 0 END) as defaites,
            SUM(CASE WHEN Score_Nous = Score_Adverse THEN 1 ELSE 0 END) as nuls,
            SUM(Score_Nous) as buts,
            SUM(Score_Adverse) as butsEncaisses
        FROM `Match_`
        WHERE Score_Nous IS NOT NULL AND Score_Adverse IS NOT NULL
    ');
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    $stats['totalMatchs'] = $result['total'] ?? 0;
    $stats['victoires'] = $result['victoires'] ?? 0;
    $stats['defaites'] = $result['defaites'] ?? 0;
    $stats['nuls'] = $result['nuls'] ?? 0;
    $stats['totalButs'] = $result['buts'] ?? 0;
    $stats['butsEncaisses'] = $result['butsEncaisses'] ?? 0;
} catch (PDOException $e) {
    // Ignorer les erreurs
}

$tauxVictoire = $stats['totalMatchs'] > 0 ? round(($stats['victoires'] / $stats['totalMatchs']) * 100, 1) : 0;
$differenceButts = $stats['totalButs'] - $stats['butsEncaisses'];

// --- Calculs par joueur ---
$players = [];
try {
    $stmt = $pdo->query('SELECT Id_Joueur, Num_Licence, Nom, Prenom, Statut, Poste FROM Joueur ORDER BY Nom, Prenom');
    $players = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Préparer quelques requêtes réutilisables
    $qStarts = $pdo->prepare('SELECT COUNT(*) FROM Participer p JOIN `Match_` m ON p.Id_Match = m.Id_Match WHERE p.Id_Joueur = :id AND p.Titulaire_ou_pas = 1');
    $qSubs = $pdo->prepare('SELECT COUNT(*) FROM Participer p JOIN `Match_` m ON p.Id_Match = m.Id_Match WHERE p.Id_Joueur = :id AND p.Titulaire_ou_pas = 0');
    $qAvgNote = $pdo->prepare('SELECT AVG(Note) as avgNote FROM Participer WHERE Id_Joueur = :id AND Note IS NOT NULL');
    $qMatchesParticipated = $pdo->prepare('SELECT COUNT(DISTINCT p.Id_Match) FROM Participer p JOIN `Match_` m ON p.Id_Match = m.Id_Match WHERE p.Id_Joueur = :id');
    $qWinsWhenPart = $pdo->prepare("SELECT COUNT(DISTINCT p.Id_Match) FROM Participer p JOIN `Match_` m ON p.Id_Match = m.Id_Match WHERE p.Id_Joueur = :id AND ((m.Resultat = 'Victoire') OR (m.Score_Nous IS NOT NULL AND m.Score_Adverse IS NOT NULL AND m.Score_Nous > m.Score_Adverse))");

    // Fetch all matches ordered descending for consecutive check
    $matchIdsStmt = $pdo->query('SELECT Id_Match FROM `Match_` WHERE (Score_Nous IS NOT NULL AND Score_Adverse IS NOT NULL) OR Resultat IS NOT NULL ORDER BY Date_Rencontre DESC, Heure DESC');
    $matchesOrdered = $matchIdsStmt->fetchAll(PDO::FETCH_COLUMN);

    foreach ($players as &$pl) {
        $idp = $pl['Id_Joueur'];
        $qStarts->execute([':id' => $idp]);
        $pl['starts'] = (int)$qStarts->fetchColumn();

        $qSubs->execute([':id' => $idp]);
        $pl['subs'] = (int)$qSubs->fetchColumn();

        $qAvgNote->execute([':id' => $idp]);
        $avg = $qAvgNote->fetch(PDO::FETCH_ASSOC)['avgNote'];
        $pl['avgNote'] = $avg !== null ? round($avg, 2) : null;

        $qMatchesParticipated->execute([':id' => $idp]);
        $participations = (int)$qMatchesParticipated->fetchColumn();
        $pl['participations'] = $participations;

        $qWinsWhenPart->execute([':id' => $idp]);
        $winsPart = (int)$qWinsWhenPart->fetchColumn();
        $pl['winPercentWhenParticipated'] = $participations > 0 ? round(($winsPart / $participations) * 100, 1) : null;

        // Consecutive selections: loop matchesOrdered and count until first match where player did not participate
        $consec = 0;
        $pCheck = $pdo->prepare('SELECT COUNT(*) FROM Participer WHERE Id_Match = :mid AND Id_Joueur = :id');
        foreach ($matchesOrdered as $mid) {
            $pCheck->execute([':mid' => $mid, ':id' => $idp]);
            $c = (int)$pCheck->fetchColumn();
            if ($c > 0) $consec++; else break;
        }
        $pl['consecutiveSelections'] = $consec;
    }
    unset($pl);
} catch (PDOException $e) {
    // ignore per-player errors
}
?>

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
    <!-- Navbar -->
    <?php include '../partials/navbar.php'; ?>

    <!-- Page Header -->
    <div style="background: linear-gradient(135deg, #C8102E 0%, #E8283C 100%); color: white; padding: 2rem 0; margin-bottom: 2rem;">
        <div class="container-fluid">
            <h1 style="font-size: 2rem; font-weight: 700; margin: 0;"><i class="bi bi-graph-up"></i> Statistiques</h1>
            <p style="margin: 0.5rem 0 0; opacity: 0.9;">Vue d'ensemble des performances</p>
        </div>
    </div>

    <div class="container-fluid">
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
                    <h3 class="stat-number" style="<?php echo $differenceButts >= 0 ? 'color: #27ae60;' : 'color: #e74c3c;'; ?>">
                        <?php echo ($differenceButts >= 0 ? '+' : '') . $differenceButts; ?>
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
                        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(150px, 1fr)); gap: 1.5rem;">
                            <div style="background: linear-gradient(135deg, #27ae60 0%, #229954 100%); color: white; padding: 1.5rem; border-radius: 8px; text-align: center;">
                                <p style="margin: 0; font-size: 0.9rem; opacity: 0.9;">Victoires</p>
                                <h3 style="margin: 0.5rem 0 0; font-size: 2rem; font-weight: 700;"><?php echo $stats['victoires']; ?></h3>
                            </div>

                            <div style="background: linear-gradient(135deg, #3498db 0%, #2980b9 100%); color: white; padding: 1.5rem; border-radius: 8px; text-align: center;">
                                <p style="margin: 0; font-size: 0.9rem; opacity: 0.9;">Nuls</p>
                                <h3 style="margin: 0.5rem 0 0; font-size: 2rem; font-weight: 700;"><?php echo $stats['nuls']; ?></h3>
                            </div>

                            <div style="background: linear-gradient(135deg, #e74c3c 0%, #c0392b 100%); color: white; padding: 1.5rem; border-radius: 8px; text-align: center;">
                                <p style="margin: 0; font-size: 0.9rem; opacity: 0.9;">Défaites</p>
                                <h3 style="margin: 0.5rem 0 0; font-size: 2rem; font-weight: 700;"><?php echo $stats['defaites']; ?></h3>
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
                <div style="overflow:auto">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Nom</th>
                            <th>Statut</th>
                            <th>Poste préféré</th>
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
                                <td><?php echo htmlspecialchars($pl['Poste'] ?? ''); ?></td>
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
                        <div style="margin-bottom: 1.5rem;">
                            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.5rem;">
                                <span style="font-weight: 600; color: #2c3e50;">Buts Marqués</span>
                                <span style="font-size: 1.5rem; font-weight: 700; color: #27ae60;"><?php echo $stats['totalButs']; ?></span>
                            </div>
                            <div style="background: #ecf0f1; border-radius: 6px; height: 8px; overflow: hidden;">
                                <div style="background: linear-gradient(90deg, #27ae60 0%, #229954 100%); height: 100%; width: 100%;"></div>
                            </div>
                        </div>

                        <div>
                            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.5rem;">
                                <span style="font-weight: 600; color: #2c3e50;">Buts Encaissés</span>
                                <span style="font-size: 1.5rem; font-weight: 700; color: #e74c3c;"><?php echo $stats['butsEncaisses']; ?></span>
                            </div>
                            <div style="background: #ecf0f1; border-radius: 6px; height: 8px; overflow: hidden;">
                                <div style="background: linear-gradient(90deg, #e74c3c 0%, #c0392b 100%); height: 100%; width: <?php echo $stats['totalButs'] > 0 ? (($stats['butsEncaisses'] / ($stats['totalButs'] + 1)) * 100) : 0; ?>%;"></div>
                            </div>
                        </div>

                        <div style="margin-top: 1.5rem; padding: 1rem; background: #f8f9fa; border-radius: 6px; text-align: center;">
                            <p style="margin: 0; color: #7f8c8d; font-size: 0.9rem;">Moyenne par Match</p>
                            <p style="margin: 0.5rem 0 0; font-size: 1.3rem; font-weight: 700; color: #2c3e50;">
                                <?php echo $stats['totalMatchs'] > 0 ? number_format($stats['totalButs'] / $stats['totalMatchs'], 1, ',', '') : '0'; ?> buts/match
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
                        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem;">
                            <a href="liste_joueurs.php" style="padding: 1.25rem; background: linear-gradient(135deg, #3498db 0%, #2980b9 100%); color: white; border-radius: 8px; text-decoration: none; text-align: center; transition: all 0.3s ease;" onmouseover="this.style.transform='translateY(-5px)'; this.style.boxShadow='0 8px 24px rgba(52, 152, 219, 0.4)'" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='none'">
                                <i class="bi bi-person-plus" style="display: block; font-size: 1.5rem; margin-bottom: 0.5rem;"></i>
                                <strong>Ajouter un Joueur</strong>
                            </a>

                            <a href="../matchs/calendrier.php" style="padding: 1.25rem; background: linear-gradient(135deg, #27ae60 0%, #229954 100%); color: white; border-radius: 8px; text-decoration: none; text-align: center; transition: all 0.3s ease;" onmouseover="this.style.transform='translateY(-5px)'; this.style.boxShadow='0 8px 24px rgba(39, 174, 96, 0.4)'" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='none'">
                                <i class="bi bi-calendar-plus" style="display: block; font-size: 1.5rem; margin-bottom: 0.5rem;"></i>
                                <strong>Planifier un Match</strong>
                            </a>

                            <a href="liste_joueurs.php" style="padding: 1.25rem; background: linear-gradient(135deg, #9b59b6 0%, #8e44ad 100%); color: white; border-radius: 8px; text-decoration: none; text-align: center; transition: all 0.3s ease;" onmouseover="this.style.transform='translateY(-5px)'; this.style.boxShadow='0 8px 24px rgba(155, 89, 182, 0.4)'" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='none'">
                                <i class="bi bi-people" style="display: block; font-size: 1.5rem; margin-bottom: 0.5rem;"></i>
                                <strong>Voir les Joueurs</strong>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="footer mt-5">
        <div class="footer-content">
            <div style="text-align: center;">
                <h5 class="footer-title">Gestion des Joueurs</h5>
            </div>
        </div>
        <div class="footer-bottom">
            <div class="container text-center">
                <p class="mb-0">&copy; <?php echo date('Y'); ?> Gestion des Joueurs. Tous droits réservés.</p>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
