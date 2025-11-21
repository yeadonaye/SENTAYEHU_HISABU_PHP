<?php
session_start();
require_once 'config.php';
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion d'équipe - Accueil</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="hero">
        <header>
            <div class="logo">
                <i class="fas fa-futbol"></i>
                <h1>Gestion d'équipe</h1>
            </div>
            <nav>
                <a href="login.php" class="btn btn-login"><i class="fas fa-sign-in-alt"></i> Connexion</a>
            </nav>
        </header>
        <div class="hero-content">
            <h2>Bienvenue sur notre plateforme de gestion d'équipe</h2>
            <p>Suivez les performances de votre équipe en temps réel</p>
        </div>
    </div>

    <div class="container">
        <div class="grid">
            <div class="card match-card">
                <div class="card-icon">
                    <i class="fas fa-trophy"></i>
                </div>
                <h2>Dernier match</h2>
                <div class="match-score">
                    <div class="team">
                        <span class="team-name">Équipe A</span>
                        <span class="team-score winner">2</span>
                    </div>
                    <div class="vs">-</div>
                    <div class="team">
                        <span class="team-name">Équipe B</span>
                        <span class="team-score">1</span>
                    </div>
                </div>
                <div class="match-date">
                    <i class="far fa-calendar-alt"></i> 20/11/2023
                </div>
            </div>

            <div class="card next-match-card">
                <div class="card-icon">
                    <i class="fas fa-calendar-alt"></i>
                </div>
                <h2>Prochain match</h2>
                <div class="match-teams">
                    <div class="team">
                        <span class="team-name">Équipe A</span>
                        <span class="vs-text">VS</span>
                        <span class="team-name">Équipe C</span>
                    </div>
                </div>
                <div class="match-info">
                    <p><i class="far fa-clock"></i> 27/11/2023 - 20:00</p>
                    <p><i class="fas fa-map-marker-alt"></i> Stade Municipal</p>
                </div>
            </div>

            <div class="card highlight-card">
                <div class="card-icon">
                    <i class="fas fa-video"></i>
                </div>
                <h2>Meilleurs moments</h2>
                <div class="video-container">
                    <div class="video-wrapper">
                        <video controls poster="stadium.jpg">
                            <source src="team-highlights.mp4" type="video/mp4">
                            Votre navigateur ne supporte pas la lecture de vidéos.
                        </video>
                        <div class="play-button"><i class="fas fa-play"></i></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
