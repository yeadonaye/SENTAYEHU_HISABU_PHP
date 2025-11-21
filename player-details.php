<?php
session_start();
require_once 'config.php';

// Check if user is logged in
if (!isset($_SESSION['user'])) {
    header('Location: login.php');
    exit();
}

// Get player ID from URL
$playerId = $_GET['id'] ?? 0;

// Sample player data - in a real app, this would come from a database
$players = [
    1 => [
        'id' => 1, 
        'name' => 'Jean Dupont', 
        'position' => 'Attaquant', 
        'number' => 10,
        'age' => 25,
        'height' => '180 cm',
        'weight' => '75 kg',
        'matches_played' => 24,
        'goals' => 15,
        'assists' => 8,
        'yellow_cards' => 2,
        'red_cards' => 0,
        'bio' => 'Jean Dupont est un attaquant talentueux qui a rejoint l\'équipe en 2020. Il est connu pour sa vitesse et sa finition précise.'
    ],
    // Other players would be here in a real app
];

// Get the requested player or redirect if not found
$player = $players[$playerId] ?? null;
if (!$player) {
    header('Location: players.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($player['name']); ?> - Détails du Joueur</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <header>
        <h1>Gestion d'équipe</h1>
        <nav>
            <a href="players.php">Retour à la liste</a>
            <a href="logout.php" class="logout-btn">Se déconnecter</a>
        </nav>
    </header>

    <div class="container">
        <h2>Détails du Joueur</h2>
        
        <div class="player-details">
            <div class="player-details-avatar">
                <div class="player-avatar" style="width: 200px; height: 200px; margin: 0 auto;">
                    <?php echo substr($player['name'], 0, 1); ?>
                </div>
                <h3><?php echo htmlspecialchars($player['name']); ?></h3>
                <p>Poste: <?php echo htmlspecialchars($player['position']); ?></p>
                <p>Numéro: <?php echo htmlspecialchars($player['number']); ?></p>
            </div>
            
            <div class="player-details-info">
                <h3>Informations personnelles</h3>
                <p>Âge: <?php echo htmlspecialchars($player['age']); ?> ans</p>
                <p>Taille: <?php echo htmlspecialchars($player['height']); ?></p>
                <p>Poids: <?php echo htmlspecialchars($player['weight']); ?></p>
                
                <h3 style="margin-top: 2rem;">Statistiques de la saison</h3>
                <div class="player-stats">
                    <div class="stat-card">
                        <h4>Matchs joués</h4>
                        <p><?php echo $player['matches_played']; ?></p>
                    </div>
                    <div class="stat-card">
                        <h4>Buts</h4>
                        <p><?php echo $player['goals']; ?></p>
                    </div>
                    <div class="stat-card">
                        <h4>Passes décisives</h4>
                        <p><?php echo $player['assists']; ?></p>
                    </div>
                    <div class="stat-card">
                        <h4>Cartons jaunes/rouges</h4>
                        <p><?php echo $player['yellow_cards']; ?>/<?php echo $player['red_cards']; ?></p>
                    </div>
                </div>
                
                <h3 style="margin-top: 2rem;">Biographie</h3>
                <p><?php echo nl2br(htmlspecialchars($player['bio'])); ?></p>
            </div>
        </div>
    </div>
</body>
</html>
