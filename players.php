<?php
session_start();
require_once 'config.php';

// Check if user is logged in
if (!isset($_SESSION['user'])) {
    header('Location: login.php');
    exit();
}

// Sample player data - in a real app, this would come from a database
$players = [
    ['id' => 1, 'name' => 'Jean Dupont', 'position' => 'Attaquant', 'number' => 10],
    ['id' => 2, 'name' => 'Pierre Martin', 'position' => 'Milieu', 'number' => 8],
    ['id' => 3, 'name' => 'Thomas Bernard', 'position' => 'Défenseur', 'number' => 4],
    ['id' => 4, 'name' => 'Lucas Petit', 'position' => 'Gardien', 'number' => 1],
    ['id' => 5, 'name' => 'Hugo Moreau', 'position' => 'Milieu', 'number' => 6],
    ['id' => 6, 'name' => 'Alexandre Laurent', 'position' => 'Attaquant', 'number' => 9],
];
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liste des Joueurs - Gestion d'équipe</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <header>
        <h1>Gestion d'équipe</h1>
        <nav>
            <a href="logout.php" class="logout-btn">Se déconnecter</a>
        </nav>
    </header>

    <div class="container">
        <h2>Liste des Joueurs</h2>
        
        <div class="players-grid">
            <?php foreach ($players as $player): ?>
                <a href="player-details.php?id=<?php echo $player['id']; ?>" class="player-card">
                    <div class="player-avatar">
                        <?php echo substr($player['name'], 0, 1); ?>
                    </div>
                    <h3><?php echo htmlspecialchars($player['name']); ?></h3>
                    <p>Poste: <?php echo htmlspecialchars($player['position']); ?></p>
                    <p>Numéro: <?php echo htmlspecialchars($player['number']); ?></p>
                </a>
            <?php endforeach; ?>
        </div>
    </div>
</body>
</html>
