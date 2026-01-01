<?php
require_once '../../auth.php';
requireAuth();

$pdo = getDBConnection();
$joueur = [];
$postes = ['Gardien', 'Défenseur', 'Milieu de terrain', 'Attaquant'];
$error = '';
$success = '';

$id = $_GET['id'] ?? null;

if ($id) {
    try {
        $stmt = $pdo->prepare('SELECT * FROM Joueur WHERE Id_Joueur = ?');
        $stmt->execute([$id]);
        $joueur = $stmt->fetch(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        $error = 'Erreur lors du chargement du joueur';
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $numLicence = $_POST['numLicence'] ?? '';
    $nom = $_POST['nom'] ?? '';
    $prenom = $_POST['prenom'] ?? '';
    $poste = $_POST['poste'] ?? '';
    $numero = $_POST['numero'] ?? '';
    $dateNaissance = $_POST['dateNaissance'] ?? '';

    // Basic required fields
    if (empty($numLicence) || empty($nom) || empty($prenom)) {
        $error = 'Le numéro de licence, le nom et le prénom sont obligatoires';
    } else {
        // Validate Num_Licence: exactly 5 digits
        if (!preg_match('/^\d{5}$/', $numLicence)) {
            $error = 'Le numéro de licence doit contenir exactement 5 chiffres (pas de lettres).';
        }

        // Validate numero format if provided
        if (!$error && $numero !== '') {
            if (!preg_match('/^\d{1,2}$/', (string)$numero) || (int)$numero < 1 || (int)$numero > 99) {
                $error = 'Le numéro de maillot doit être un nombre entier entre 1 et 99.';
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

                // Check Numero uniqueness if provided
                if (!$error && $numero !== '') {
                    $stmt = $pdo->prepare('SELECT Id_Joueur FROM Joueur WHERE Numero = ? LIMIT 1');
                    $stmt->execute([(int)$numero]);
                    $existingNum = $stmt->fetch(PDO::FETCH_ASSOC);
                    if ($existingNum && (!$id || $existingNum['Id_Joueur'] != $id)) {
                        $error = 'Ce numéro de maillot est déjà pris par un autre joueur. Veuillez choisir un autre numéro.';
                    }
                }
            } catch (PDOException $e) {
                $error = 'Erreur lors de la vérification des données: ' . $e->getMessage();
            }
        }

        if (!$error) {
            try {
                if ($id) {
                    // Modification
                    $stmt = $pdo->prepare("UPDATE Joueur SET Num_Licence = ?, Nom = ?, Prenom = ?, Poste = ?, Numero = ?, DateNaissance = ? WHERE Id_Joueur = ?");
                    $stmt->execute([$numLicence, $nom, $prenom, $poste, $numero !== '' ? $numero : null, $dateNaissance, $id]);
                    $success = 'Joueur modifié avec succès!';
                } else {
                    // Ajout
                    $stmt = $pdo->prepare("INSERT INTO Joueur (Num_Licence, Nom, Prenom, Poste, Numero, DateNaissance) VALUES (?, ?, ?, ?, ?, ?)");
                    $stmt->execute([$numLicence, $nom, $prenom, $poste, $numero !== '' ? $numero : null, $dateNaissance]);
                    $success = 'Joueur ajouté avec succès!';
                    $id = $pdo->lastInsertId();
                    $joueur = compact('nom', 'prenom', 'poste', 'numero', 'dateNaissance');
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
                    <label for="numLicence" class="form-label fw-bold">Numéro de Licence *</label>
                    <input
                        type="number"
                        class="form-control"
                        id="numLicence"
                        name="numLicence"
                        value="<?php echo htmlspecialchars($joueur['Num_Licence'] ?? $joueur['Num_Licence'] ?? ''); ?>"
                        required
                        placeholder="Entrez le numéro de licence"
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
                    <label for="poste" class="form-label fw-bold">Poste</label>
                    <select class="form-control" id="poste" name="poste">
                        <option value="">Sélectionner un poste</option>
                        <?php foreach ($postes as $p): ?>
                            <option value="<?php echo htmlspecialchars($p); ?>" <?php echo ($joueur['Poste'] ?? '') === $p ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($p); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="mb-3">
                    <label for="numero" class="form-label fw-bold">Numéro de Maillot</label>
                    <input 
                        type="number" 
                        class="form-control" 
                        id="numero" 
                        name="numero" 
                        value="<?php echo htmlspecialchars($joueur['Numero'] ?? ''); ?>"
                        min="1"
                        max="99"
                        placeholder="Ex: 7"
                    >
                </div>

                <div class="mb-3">
                    <label for="dateNaissance" class="form-label fw-bold">Date de Naissance</label>
                    <input 
                        type="date" 
                        class="form-control" 
                        id="dateNaissance" 
                        name="dateNaissance" 
                        value="<?php echo htmlspecialchars($joueur['DateNaissance'] ?? ''); ?>"
                    >
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
                const numero = document.getElementById('numero').value.trim();
                const licenceRegex = /^\d{5}$/;
                if (!licenceRegex.test(numLicence)) {
                    e.preventDefault();
                    alert('Le numéro de licence doit contenir exactement 5 chiffres (pas de lettres).');
                    return false;
                }
                if (numero !== '') {
                    const n = parseInt(numero, 10);
                    if (isNaN(n) || n < 1 || n > 99) {
                        e.preventDefault();
                        alert('Le numéro de maillot doit être un nombre entier entre 1 et 99.');
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
