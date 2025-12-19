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
    $playerCount = $stmt->fetchColumn();
    
    // Get next match
    $stmt = $pdo->query('SELECT * FROM `Match_` WHERE Date_Rencontre >= CURDATE() ORDER BY Date_Rencontre, Heure LIMIT 1');
    $upcomingMatch = $stmt->fetch(PDO::FETCH_ASSOC);
    
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
            <a class="navbar-brand fw-bold" href="index.php"><img src="Vue/img/logo.png" alt="Logo Liverpool FC"> Liverpool FC</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link active" href="index.php"><i class="bi bi-house-door"></i> Accueil</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="Vue/joueurs/liste_joueurs.php"><i class="bi bi-people"></i> Joueurs</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="Vue/matchs/calendrier.php"><i class="bi bi-calendar3"></i> Matchs</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="statistiques.php"><i class="bi bi-graph-up"></i> Statistiques</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="logout.php"><i class="bi bi-box-arrow-right"></i> Déconnexion</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Première partie de la page d'acceuil -->
    <section class="hero-section">
        <div class="container-fluid">
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
                        <i class="bi bi-calendar-event"></i>
                    </div>
                    <h3 class="stat-number"><?php echo isset($upcomingMatch) ? '1' : '0'; ?></h3>
                    <p class="stat-label">Prochain Match</p>
                </div>
            </div>
            <div class="col-md-3 col-sm-6">
                <div class="stat-card text-center">
                    <div class="stat-icon">
                        <i class="bi bi-chat-dots-fill"></i>
                    </div>
                    <h3 class="stat-number"><?php echo count($recentComments); ?></h3>
                    <p class="stat-label">Commentaires</p>
                </div>
            </div>
            <div class="col-md-3 col-sm-6">
                <div class="stat-card text-center">
                    <div class="stat-icon">
                        <i class="bi bi-check-circle-fill"></i>
                    </div>
                    <h3 class="stat-number">OK</h3>
                    <p class="stat-label">Système Actif</p>
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
</body>
</html>
