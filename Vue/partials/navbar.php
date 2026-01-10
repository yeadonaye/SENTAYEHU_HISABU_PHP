<?php
// Shared navbar partial — build a base path from the current script to avoid hard-coded app folder
$script = str_replace('\\','/', $_SERVER['SCRIPT_NAME'] ?? '');
$parts = explode('/', trim($script, '/'));
$base = '/' . ($parts[0] ?? '');
?>
<nav class="navbar navbar-expand-lg navbar-dark sticky-top navbar-custom">
    <div class="container-fluid">
        <a class="navbar-brand fw-bold" href="<?php echo $base; ?>/index.php"><i class="bi bi-shield-check"></i> Gestion des Joueurs</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item">
                    <a class="nav-link" href="<?php echo $base; ?>/index.php"><i class="bi bi-house-door"></i> Accueil</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="<?php echo $base; ?>/Vue/Afficher/liste_joueurs.php"><i class="bi bi-people"></i> Joueurs</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="<?php echo $base; ?>/Vue/Afficher/afficher_match.php"><i class="bi bi-calendar3"></i> Matchs</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="<?php echo $base; ?>/Vue/Afficher/statistiques.php"><i class="bi bi-graph-up"></i> Statistiques</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="<?php echo $base; ?>/logout.php"><i class="bi bi-box-arrow-right"></i> Déconnexion</a>
                </li>
            </ul>
        </div>
    </div>
</nav>
