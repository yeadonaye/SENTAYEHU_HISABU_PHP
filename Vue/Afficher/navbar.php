<?php
// Compute a relative prefix based on current script depth (keeps links relative, no leading slash)
$script = str_replace('\\', '/', $_SERVER['SCRIPT_NAME'] ?? '');
$segments = explode('/', trim($script, '/'));
// drop app folder (first) and filename (last) to get depth inside app
$depth = max(count($segments) - 2, 0);
$prefix = str_repeat('../', $depth);
?>
<nav class="navbar navbar-expand-lg navbar-dark sticky-top navbar-custom">
    <div class="container-fluid">
        <a class="navbar-brand fw-bold" href="<?php echo $prefix; ?>index.php"><i class="bi bi-shield-check"></i> Gestion des Joueurs</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item">
                    <a class="nav-link" href="<?php echo $prefix; ?>index.php"><i class="bi bi-house-door"></i> Accueil</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="<?php echo $prefix; ?>Vue/Afficher/liste_joueurs.php"><i class="bi bi-people"></i> Joueurs</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="<?php echo $prefix; ?>Vue/Afficher/afficher_match.php"><i class="bi bi-calendar3"></i> Matchs</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="<?php echo $prefix; ?>Vue/Afficher/statistiques.php"><i class="bi bi-graph-up"></i> Statistiques</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="<?php echo $prefix; ?>logout.php"><i class="bi bi-box-arrow-right"></i> Déconnexion</a>
                </li>
            </ul>
        </div>
    </div>
</nav>
