<?php
require_once __DIR__ . '/Modele/DAO/auth.php';
requireAuth();

// Page d'accueil sans accès DAO/BD
$playerCount = 0;
$injuredCount = 0;
$wins = 0;
$totalMatches = 0;
$nextMatch = null;
$recentComments = [];
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
    <?php include 'Vue/Afficher/navbar.php'; ?>

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

    <?php include 'Vue/Afficher/footer.php'; ?>

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
