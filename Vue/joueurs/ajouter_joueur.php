<?php
require_once '../../auth.php';
requireAuth();

$pdo = getDBConnection();
$joueur = [];
$statuts = ['Actif', 'Blessé'];
$error = '';
$success = '';

$id = $_GET['id'] ?? null;

if ($id) {
    try {
        $stmt = $pdo->prepare('SELECT * FROM Joueur WHERE Id_Joueur = ?');
        $stmt->execute([$id]);
        $joueur = $stmt->fetch(PDO::FETCH_ASSOC);
            // Normalize keys: keep original keys and lowercase variants
            if ($joueur) {
                $normalized = [];
                foreach ($joueur as $k => $v) {
                    $normalized[$k] = $v;
                    $normalized[strtolower($k)] = $v;
                }
                $joueur = $normalized;
            }
    } catch (PDOException $e) {
        $error = 'Erreur lors du chargement du joueur';
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $numLicence = $_POST['numLicence'] ?? '';
    $nom = $_POST['nom'] ?? '';
    $prenom = $_POST['prenom'] ?? '';
    $dateNaissance = $_POST['dateNaissance'] ?? '';
    $taille = $_POST['taille'] ?? '';
    $poids = $_POST['poids'] ?? '';
    $statut = $_POST['statut'] ?? '';

    // Basic required fields
    if (empty($numLicence) || empty($nom) || empty($prenom) || empty($statut)) {
        $error = 'Le numéro de licence, le nom, le prénom et le statut sont obligatoires';
    } else {
        // Validate taille and poids if provided
        if (!$error && $taille !== '') {
            if (!is_numeric($taille) || (float)$taille <= 0) {
                $error = 'La taille doit être un nombre positif.';
            }
        }

        if (!$error && $poids !== '') {
            if (!is_numeric($poids) || (float)$poids <= 0) {
                $error = 'Le poids doit être un nombre positif.';
            }
        }

        // Check uniqueness constraints
        if (!$error) {
            try {
                // Check Num_Licence uniqueness
                $stmt = $pdo->prepare('SELECT Id_Joueur FROM Joueur WHERE Num_Licence = ? LIMIT 1');
                $stmt->execute([$numLicence]);
                $existing = $stmt->fetch(PDO::FETCH_ASSOC);
                if ($existing && (!$id || $existing['Id_Joueur'] != $id)) {
                    $error = 'Ce numéro de licence est déjà utilisé par un autre joueur.';
                }
            } catch (PDOException $e) {
                $error = 'Erreur lors de la vérification des données: ' . $e->getMessage();
            }
        }

        if (!$error) {
            try {
                // Convert values to proper types
                $taille_value = !empty($taille) ? (float)$taille : null;
                $poids_value = !empty($poids) ? (float)$poids : null;
                $dateNaissance_value = !empty($dateNaissance) ? $dateNaissance : null;
                
                if ($id) {
                    // Modification
                    $stmt = $pdo->prepare("UPDATE Joueur SET Num_Licence = ?, Nom = ?, Prenom = ?, Date_Naissance = ?, Taille = ?, Poids = ?, Statut = ? WHERE Id_Joueur = ?");
                    $stmt->execute([$numLicence, $nom, $prenom, $dateNaissance_value, $taille_value, $poids_value, $statut, $id]);
                    $success = 'Joueur modifié avec succès!';
                } else {
                    // Ajout
                    $stmt = $pdo->prepare("INSERT INTO Joueur (Num_Licence, Nom, Prenom, Date_Naissance, Taille, Poids, Statut) VALUES (?, ?, ?, ?, ?, ?, ?)");
                    $stmt->execute([$numLicence, $nom, $prenom, $dateNaissance_value, $taille_value, $poids_value, $statut]);
                    $success = 'Joueur ajouté avec succès!';
                    $id = $pdo->lastInsertId();
                    $joueur = compact('nom', 'prenom', 'dateNaissance', 'taille', 'poids', 'statut');
                    $joueur['Id_Joueur'] = $id;
                    $joueur['Num_Licence'] = $numLicence;
                }
            } catch (PDOException $e) {
                $error = 'Erreur lors de l\'enregistrement: ' . $e->getMessage();
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $id ? 'Modifier un Joueur' : 'Ajouter un Joueur'; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css">
    <link rel="stylesheet" href="../../Vue/CSS/common.css">
    <link rel="stylesheet" href="../../Vue/CSS/joueurs.css">
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark sticky-top navbar-custom">
        <div class="container-fluid">
            <a class="navbar-brand fw-bold" href="/SENTAYEHU_HISABU_PHP/index.php"><i class="bi bi-shield-check"></i> Gestion des Joueurs</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="/SENTAYEHU_HISABU_PHP/index.php"><i class="bi bi-house-door"></i> Accueil</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="/SENTAYEHU_HISABU_PHP/Vue/joueurs/liste_joueurs.php"><i class="bi bi-people"></i> Joueurs</a>
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

    <!-- Page Header -->
    <div style="background: linear-gradient(135deg, #C8102E 0%, #E8283C 100%); color: white; padding: 2rem 0; margin-bottom: 2rem;">
        <div class="container-fluid">
            <div style="display: flex; justify-content: space-between; align-items: center;">
                <div>
                    <h1 style="font-size: 2rem; font-weight: 700; margin: 0;">
                        <i class="bi <?php echo $id ? 'bi-pencil' : 'bi-plus-circle'; ?>"></i> 
                        <?php echo $id ? 'Modifier un Joueur' : 'Ajouter un Joueur'; ?>
                    </h1>
                </div>
                <a href="liste_joueurs.php" class="btn btn-light" style="font-weight: 600;">
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
                    <label for="numLicence" class="form-label fw-bold">Numéro de Licence *</label>
                    <input
                        type="text"
                        class="form-control"
                        id="numLicence"
                        name="numLicence"
                        value="<?php echo htmlspecialchars($joueur['Num_Licence'] ?? ''); ?>"
                        required
                        placeholder="Entrez le numéro de licence"
                    >
                </div>

                <div class="mb-3">
                    <label for="nom" class="form-label fw-bold">Nom *</label>
                    <input 
                        type="text" 
                        class="form-control" 
                        id="nom" 
                        name="nom" 
                        value="<?php echo htmlspecialchars($joueur['Nom'] ?? ''); ?>"
                        required
                        placeholder="Entrez le nom"
                    >
                </div>

                <div class="mb-3">
                    <label for="prenom" class="form-label fw-bold">Prénom *</label>
                    <input 
                        type="text" 
                        class="form-control" 
                        id="prenom" 
                        name="prenom" 
                        value="<?php echo htmlspecialchars($joueur['Prenom'] ?? ''); ?>"
                        required
                        placeholder="Entrez le prénom"
                    >
                </div>

                <div class="mb-3">
                    <label for="dateNaissance" class="form-label fw-bold">Date de Naissance</label>
                    <input 
                        type="date" 
                        class="form-control" 
                        id="dateNaissance" 
                        name="dateNaissance" 
                        value="<?php echo htmlspecialchars($joueur['Date_Naissance'] ?? ''); ?>"
                    >
                </div>

                <div class="mb-3">
                    <label for="taille" class="form-label fw-bold">Taille (cm)</label>
                    <input 
                        type="number" 
                        class="form-control" 
                        id="taille" 
                        name="taille" 
                        value="<?php echo htmlspecialchars($joueur['Taille'] ?? ''); ?>"
                        min="1"
                        placeholder="Ex: 180"
                    >
                </div>

                <div class="mb-3">
                    <label for="poids" class="form-label fw-bold">Poids (kg)</label>
                    <input 
                        type="number" 
                        class="form-control" 
                        id="poids" 
                        name="poids" 
                        value="<?php echo htmlspecialchars($joueur['Poids'] ?? ''); ?>"
                        min="1"
                        step="0.1"
                        placeholder="Ex: 75"
                    >
                </div>

                <div class="mb-3">
                    <label for="statut" class="form-label fw-bold">Statut *</label>
                    <select class="form-control" id="statut" name="statut" required>
                        <option value="">Sélectionner un statut</option>
                        <?php foreach ($statuts as $s): ?>
                            <option value="<?php echo htmlspecialchars($s); ?>" <?php echo ($joueur['Statut'] ?? '') === $s ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($s); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div style="display: flex; gap: 1rem; margin-top: 2rem;">
                    <button type="submit" class="btn btn-primary" style="flex: 1; font-weight: 600; padding: 0.75rem;">
                        <i class="bi bi-check-circle me-2"></i>
                        <?php echo $id ? 'Modifier' : 'Ajouter'; ?>
                    </button>
                    <a href="liste_joueurs.php" class="btn btn-secondary" style="flex: 1; font-weight: 600; padding: 0.75rem; text-decoration: none; display: flex; align-items: center; justify-content: center;">
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

    <script>
        // Client-side validation for immediate feedback
        document.addEventListener('DOMContentLoaded', function () {
            const form = document.querySelector('form');
            form.addEventListener('submit', function (e) {
                const numLicence = document.getElementById('numLicence').value.trim();
                const statut = document.getElementById('statut').value.trim();
                const taille = document.getElementById('taille').value.trim();
                const poids = document.getElementById('poids').value.trim();
                if (statut === '') {
                    e.preventDefault();
                    alert('Le statut est obligatoire.');
                    return false;
                }
                
                if (taille !== '') {
                    const t = parseFloat(taille);
                    if (isNaN(t) || t <= 0) {
                        e.preventDefault();
                        alert('La taille doit être un nombre positif.');
                        return false;
                    }
                }
                
                if (poids !== '') {
                    const p = parseFloat(poids);
                    if (isNaN(p) || p <= 0) {
                        e.preventDefault();
                        alert('Le poids doit être un nombre positif.');
                        return false;
                    }
                }
                
                return true;
            });
        });
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
