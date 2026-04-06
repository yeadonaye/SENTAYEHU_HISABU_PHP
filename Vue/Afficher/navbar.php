<?php $role = $_SESSION['role'] ?? 'joueur'; // En fonction du role de la personne connectée, on affiche ou pas les liens vers cetaines pages?>
<ul class="navbar-nav ms-auto">
    <li class="nav-item">
        <a class="nav-link" href="/index.php"><i class="bi bi-house-door"></i> Accueil</a>
    </li>
    <li class="nav-item">
        <a class="nav-link" href="/Vue/Afficher/liste_joueurs.php"><i class="bi bi-people"></i> Joueurs</a>
    </li>
    <li class="nav-item">
        <a class="nav-link" href="/Vue/Afficher/afficher_match.php"><i class="bi bi-calendar3"></i> Matchs</a>
    </li>
    <?php if ($role === 'coach'): ?>
    <li class="nav-item">
        <a class="nav-link" href="/Vue/Afficher/statistiques.php"><i class="bi bi-graph-up"></i> Statistiques</a>
    </li>
    <?php endif; ?>
    <?php if ($role === 'joueur'): ?>
    <li class="nav-item">
        <a class="nav-link" href="/Vue/Afficher/statistiques.php"><i class="bi bi-graph-up"></i> Statistiques</a>
    </li>
    <?php endif; ?>
    <li class="nav-item">
        <a class="nav-link" href="/logout.php"><i class="bi bi-box-arrow-right"></i> Déconnexion</a>
    </li>
</ul>