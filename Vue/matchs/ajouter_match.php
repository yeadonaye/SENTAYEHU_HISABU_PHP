<?php
require_once '../../auth.php';
requireAuth();

$pdo = getDBConnection();
$match = [];
$error = '';
$success = '';

$id = $_GET['id'] ?? null;

if ($id) {
    try {
        $stmt = $pdo->prepare('SELECT * FROM `Match_` WHERE Id_Match = ?');
        $stmt->execute([$id]);
        $match = $stmt->fetch(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        $error = 'Erreur lors du chargement du match';
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nomEquipeAdverse = $_POST['nomEquipeAdverse'] ?? '';
    $dateRencontre = $_POST['dateRencontre'] ?? '';
    $heure = $_POST['heure'] ?? '';
    $lieu = $_POST['lieu'] ?? '';
    $scoreNous = $_POST['scoreNous'] ?? '';
    $scoreAdverse = $_POST['scoreAdverse'] ?? '';

    if (empty($nomEquipeAdverse) || empty($dateRencontre) || empty($heure)) {
        $error = 'Les champs avec * sont obligatoires';
    } else {
        try {
            if ($id) {
                // Modification
                $stmt = $pdo->prepare('
                    UPDATE `Match_` 
                    SET Nom_Equipe_Adverse = ?, Date_Rencontre = ?, Heure = ?, Lieu = ?, Score_Nous = ?, Score_Adverse = ? 
                    WHERE Id_Match = ?
                ');
                $stmt->execute([$nomEquipeAdverse, $dateRencontre, $heure, $lieu, $scoreNous ?: null, $scoreAdverse ?: null, $id]);
                $success = 'Match modifié avec succès!';
            } else {
                // Ajout
                $stmt = $pdo->prepare('
                    INSERT INTO `Match_` (Nom_Equipe_Adverse, Date_Rencontre, Heure, Lieu, Score_Nous, Score_Adverse) 
                    VALUES (?, ?, ?, ?, ?, ?)
                ');
                $stmt->execute([$nomEquipeAdverse, $dateRencontre, $heure, $lieu, $scoreNous ?: null, $scoreAdverse ?: null]);
                $success = 'Match ajouté avec succès!';
                $id = $pdo->lastInsertId();
            }
        } catch (PDOException $e) {
            $error = 'Erreur lors de l\'enregistrement: ' . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $id ? 'Modifier un Match' : 'Planifier un Match'; ?></title>
    <link rel="stylesheet" href="../../Vue/CSS/style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css">
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark sticky-top navbar-custom">
        <div class="container-fluid">
            <a class="navbar-brand fw-bold" href="../../index.php"><i class="bi bi-shield-check"></i> Gestion des Joueurs</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="../../index.php"><i class="bi bi-house-door"></i> Accueil</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="../joueurs/liste_joueurs.php"><i class="bi bi-people"></i> Joueurs</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="calendrier.php"><i class="bi bi-calendar3"></i> Matchs</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="../../logout.php"><i class="bi bi-box-arrow-right"></i> Déconnexion</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Page Header -->
    <div style="background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%); color: white; padding: 2rem 0; margin-bottom: 2rem;">
        <div class="container-fluid">
            <div style="display: flex; justify-content: space-between; align-items: center;">
                <div>
                    <h1 style="font-size: 2rem; font-weight: 700; margin: 0;">
                        <i class="bi <?php echo $id ? 'bi-pencil' : 'bi-plus-circle'; ?>"></i> 
                        <?php echo $id ? 'Modifier un Match' : 'Planifier un Match'; ?>
                    </h1>
                </div>
                <a href="calendrier.php" class="btn btn-light" style="font-weight: 600;">
                    <i class="bi bi-arrow-left me-2"></i>Retour
                </a>
            </div>
        </div>
    </div>

    <div class="container">
        <?php if ($error): ?>
            <div class="alert alert-danger" role="alert">
                <i class="bi bi-exclamation-circle me-2"></i>
                <?php echo htmlspecialchars($error); ?>
            </div>
        <?php endif; ?>

        <?php if ($success): ?>
            <div class="alert alert-success" role="alert">
                <i class="bi bi-check-circle me-2"></i>
                <?php echo htmlspecialchars($success); ?>
            </div>
        <?php endif; ?>

        <div style="background: white; border-radius: 12px; box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08); padding: 2rem; max-width: 600px; margin: 0 auto;">
            <form method="POST" action="">
                <div class="mb-3">
                    <label for="nomEquipeAdverse" class="form-label fw-bold">Équipe Adverse *</label>
                    <input 
                        type="text" 
                        class="form-control" 
                        id="nomEquipeAdverse" 
                        name="nomEquipeAdverse" 
                        value="<?php echo htmlspecialchars($match['Nom_Equipe_Adverse'] ?? ''); ?>"
                        required
                        placeholder="Nom de l'équipe adverse"
                    >
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="dateRencontre" class="form-label fw-bold">Date *</label>
                        <input 
                            type="date" 
                            class="form-control" 
                            id="dateRencontre" 
                            name="dateRencontre" 
                            value="<?php echo htmlspecialchars($match['Date_Rencontre'] ?? ''); ?>"
                            required
                        >
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="heure" class="form-label fw-bold">Heure *</label>
                        <input 
                            type="time" 
                            class="form-control" 
                            id="heure" 
                            name="heure" 
                            value="<?php echo htmlspecialchars($match['Heure'] ?? ''); ?>"
                            required
                        >
                    </div>
                </div>

                <div class="mb-3">
                    <label for="lieu" class="form-label fw-bold">Lieu</label>
                    <input 
                        type="text" 
                        class="form-control" 
                        id="lieu" 
                        name="lieu" 
                        value="<?php echo htmlspecialchars($match['Lieu'] ?? ''); ?>"
                        placeholder="Lieu du match"
                    >
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="scoreNous" class="form-label fw-bold">Notre Score</label>
                        <input 
                            type="number" 
                            class="form-control" 
                            id="scoreNous" 
                            name="scoreNous" 
                            value="<?php echo htmlspecialchars($match['Score_Nous'] ?? ''); ?>"
                            min="0"
                            placeholder="0"
                        >
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="scoreAdverse" class="form-label fw-bold">Score Adverse</label>
                        <input 
                            type="number" 
                            class="form-control" 
                            id="scoreAdverse" 
                            name="scoreAdverse" 
                            value="<?php echo htmlspecialchars($match['Score_Adverse'] ?? ''); ?>"
                            min="0"
                            placeholder="0"
                        >
                    </div>
                </div>

                <div style="display: flex; gap: 1rem; margin-top: 2rem;">
                    <button type="submit" class="btn btn-primary" style="flex: 1; font-weight: 600; padding: 0.75rem;">
                        <i class="bi bi-check-circle me-2"></i>
                        <?php echo $id ? 'Modifier' : 'Ajouter'; ?>
                    </button>
                    <a href="calendrier.php" class="btn btn-secondary" style="flex: 1; font-weight: 600; padding: 0.75rem; text-decoration: none; display: flex; align-items: center; justify-content: center;">
                        <i class="bi bi-x-circle me-2"></i>Annuler
                    </a>
                </div>
            </form>
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
