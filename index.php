<?php
require_once dirname(__FILE__) . '/auth.php';
requireAuth();

// Database connection
$playerCount = 0;
$upcomingMatch = null;
$recentComments = [];

try {
    $pdo = getDBConnection();
    
    // Get total number of players
    $stmt = $pdo->query('SELECT COUNT(*) FROM Joueur');
    $playerCount = (int)$stmt->fetchColumn();

    // Get number of injured players (statut contenant 'bles' case-insensitive)
    try {
        $stmt = $pdo->query("SELECT COUNT(*) FROM Joueur WHERE LOWER(Statut) LIKE '%bles%'");
        $injuredCount = (int)$stmt->fetchColumn();
    } catch (Exception $e) {
        $injuredCount = 0;
    }

    // Get matches stats: total and wins
    try {
        $stmt = $pdo->query("SELECT COUNT(*) as total, SUM(CASE WHEN Resultat = 'Victoire' OR (Score_Nous IS NOT NULL AND Score_Adverse IS NOT NULL AND Score_Nous > Score_Adverse) THEN 1 ELSE 0 END) as wins FROM `Match_`");
        $m = $stmt->fetch(PDO::FETCH_ASSOC);
        $totalMatches = (int)($m['total'] ?? 0);
        $wins = (int)($m['wins'] ?? 0);
    } catch (Exception $e) {
        $totalMatches = 0;
        $wins = 0;
    }

    // Get next match (SQLite compatible)
    try {
        $stmt = $pdo->prepare("SELECT Date_Rencontre, Heure FROM `Match_` WHERE Date_Rencontre >= date('now') ORDER BY Date_Rencontre, Heure LIMIT 1");
        $stmt->execute();
        $next = $stmt->fetch(PDO::FETCH_ASSOC);
        $nextMatch = $next ?: null;
    } catch (Exception $e) {
        $nextMatch = null;
    }

    // Get recent comments
    $stmt = $pdo->query('SELECT c.Description, c.Date_, j.Nom, j.Prenom 
                         FROM Commentaire c 
                         JOIN Joueur j ON c.Id_Joueur = j.Id_Joueur 
                         ORDER BY c.Date_ DESC LIMIT 3');
    $recentComments = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
} catch(PDOException $e) {
    $error = "Erreur de connexion à la base de données: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Joueurs - Accueil</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css">
    <link rel="stylesheet" href="Vue/CSS/common.css">
    <link rel="stylesheet" href="Vue/CSS/index.css">
</head>
<body>
    <!-- Barre de navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark sticky-top navbar-custom">
        <div class="container-fluid">
            <a class="navbar-brand fw-bold" href="/SENTAYEHU_HISABU_PHP/index.php"><img src="Vue/img/logo.png" alt="Logo Liverpool FC"> Liverpool FC</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link active" href="/SENTAYEHU_HISABU_PHP/index.php"><i class="bi bi-house-door"></i> Accueil</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/SENTAYEHU_HISABU_PHP/Vue/joueurs/liste_joueurs.php"><i class="bi bi-people"></i> Joueurs</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/SENTAYEHU_HISABU_PHP/Vue/matchs/calendrier.php"><i class="bi bi-calendar3"></i> Matchs</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/SENTAYEHU_HISABU_PHP/statistiques.php"><i class="bi bi-graph-up"></i> Statistiques</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/SENTAYEHU_HISABU_PHP/logout.php"><i class="bi bi-box-arrow-right"></i> Déconnexion</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Première partie de la page d'acceuil -->
    <section class="hero-section">
        <div class="container-fluid">
            <!-- Carousel as background using background-image on items -->
            <div id="heroCarousel" class="carousel slide carousel-fade hero-carousel" data-bs-ride="carousel" data-bs-interval="4000">
                <div class="carousel-inner">
                    <div class="carousel-item active" style="background-image: url('Vue/img/acceuil1.jpg');"></div>
                    <div class="carousel-item" style="background-image: url('Vue/img/acceuil2.jpg');"></div>
                    <div class="carousel-item" style="background-image: url('Vue/img/acceuil3.jpg');"></div>
                    <div class="carousel-item" style="background-image: url('Vue/img/acceuil4.jpg');"></div>
                </div>
            </div>

            <div class="hero-content">
                <h1 class="hero-title">BIENVENUE À LIVERPOOL FC</h1>
                <h2 class="hero-subtitle">Système de Gestion des Joueurs</h2>
                <p class="hero-text">Gérez votre équipe de football avec efficacité et professionnalisme</p>
            </div>
        </div>
    </section>

    <!-- Section des statistiques -->
    <div class="container my-5">
        <div class="row g-4 mb-5">
            <!-- Stats Cards -->
            <div class="col-md-3 col-sm-6">
                <div class="stat-card text-center">
                    <div class="stat-icon">
                        <i class="bi bi-people-fill"></i>
                    </div>
                    <h3 class="stat-number"><?php echo $playerCount; ?></h3>
                    <p class="stat-label">Joueurs Enregistrés</p>
                </div>
            </div>

            <div class="col-md-3 col-sm-6">
                <div class="stat-card text-center">
                                        <div class="stat-icon">
                                                <!-- Use repository SVG file for infirmary cross -->
                                                <img src="Vue/img/infirmary.svg" alt="Infirmerie" style="width:48px;height:48px;" />
                                        </div>
                    <h3 class="stat-number"><?php echo isset($injuredCount) ? $injuredCount : 0; ?></h3>
                    <p class="stat-label">Joueurs Blessés</p>
                </div>
            </div>

            <div class="col-md-3 col-sm-6">
                <div class="stat-card text-center">
                    <div class="stat-icon">
                        <i class="bi bi-trophy-fill"></i>
                    </div>
                    <h3 class="stat-number"><?php echo (isset($wins) ? $wins : 0) . '/' . (isset($totalMatches) ? $totalMatches : 0); ?></h3>
                    <p class="stat-label">Matchs gagnés / Total</p>
                </div>
            </div>

            <div class="col-md-3 col-sm-6">
                <div class="stat-card text-center">
                    <div class="stat-icon">
                        <i class="bi bi-calendar3"></i>
                    </div>
                    <h3 class="stat-number">
                        <?php
                        if (!empty($nextMatch)) {
                            echo date('d/m/Y H:i', strtotime($nextMatch['Date_Rencontre'] . ' ' . ($nextMatch['Heure'] ?? '00:00:00')));
                        } else {
                            echo 'Aucun';
                        }
                        ?>
                    </h3>
                    <p class="stat-label">Prochain Match</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="footer mt-5">
        <div class="container-fluid">
            <div class="footer-content">
                <div class="text-center">
                    <h5 class="footer-title">Gestion des Joueurs</h5>
                    <p class="footer-text">Système de gestion professionnel pour votre équipe de football</p>
                </div>
            </div>
            <div class="footer-bottom">
                <div class="container text-center">
                    <p class="mb-0">&copy; <?php echo date('Y'); ?> Liverpool FC</p>
                </div>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Précharger les images du hero en arrière-plan (non bloquant)
        (function() {
            const imgs = [
                'Vue/img/acceuil1.jpg',
                'Vue/img/acceuil2.jpg',
                'Vue/img/acceuil3.jpg',
                'Vue/img/acceuil4.jpg'
            ];
            imgs.forEach(src => { const i = new Image(); i.src = src; });

            // Initialiser le carousel avec un interval plus court et démarrer immédiatement
            document.addEventListener('DOMContentLoaded', function () {
                const el = document.getElementById('heroCarousel');
                if (el) {
                    const carousel = new bootstrap.Carousel(el, {
                        interval: 2500, // vitesse réduite: 2.5s
                        ride: 'carousel'
                    });

                    // Forcer le cycle pour être sûr que ça démarre
                    setTimeout(() => {
                        try { carousel.cycle(); } catch (e) { /* ignore */ }
                    }, 100);
                }
            });
        })();
    </script>
</body>
</html>
