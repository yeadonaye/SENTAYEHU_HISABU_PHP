<?php
require_once '../../auth.php';
requireAuth();

$pdo = getDBConnection();
$error = '';
$success = '';

// Récupérer l'ID du match
$matchId = $_GET['id'] ?? null;
if (!$matchId) {
    header('Location: calendrier.php');
    exit;
}

// Récupérer les infos du match
$stmt = $pdo->prepare('SELECT * FROM `Match_` WHERE Id_Match = :id');
$stmt->execute([':id' => $matchId]);
$match = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$match) {
    header('Location: calendrier.php');
    exit;
}

// Récupérer tous les joueurs actifs
$stmt = $pdo->query("SELECT * FROM Joueur WHERE Statut = 'Actif' ORDER BY Nom, Prenom");
$joueurs = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Récupérer les postes possibles
$postes = ['Gardien', 'Défenseur', 'Milieu', 'Attaquant'];

// Récupérer la composition actuelle pour ce match
$stmt = $pdo->prepare('SELECT * FROM Participer WHERE Id_Match = :id');
$stmt->execute([':id' => $matchId]);
$participations = $stmt->fetchAll(PDO::FETCH_ASSOC);

$composition = [];
foreach ($participations as $p) {
    $composition[$p['Id_Joueur']] = $p;
}

// Traitement du formulaire de soumission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $titulaires = $_POST['titulaires'] ?? [];
    $remplacants = $_POST['remplacants'] ?? [];
    $posteTitulaires = $_POST['poste_titulaires'] ?? [];
    $posteRemplacants = $_POST['poste_remplacants'] ?? [];
    $notesPost = $_POST['note'] ?? [];

    // Validation du nombre minimum de joueurs (11 titulaires)
    if (count($titulaires) < 11) {
        $error = 'Vous devez sélectionner au moins 11 titulaires.';
    } else {
        try {
            // Supprimer les participations existantes
            $stmt = $pdo->prepare('DELETE FROM Participer WHERE Id_Match = :id');
            $stmt->execute([':id' => $matchId]);

            // Insérer les titulaires
            foreach ($titulaires as $joueurId) {
                $poste = $posteTitulaires[$joueurId] ?? null;
                if ($poste) {
                    $noteVal = isset($notesPost[$joueurId]) && $notesPost[$joueurId] !== '' ? (int)$notesPost[$joueurId] : null;
                    $stmt = $pdo->prepare('
                        INSERT INTO Participer (Id_Joueur, Id_Match, Poste, Titulaire_ou_pas, Note)
                        VALUES (:joueur, :match, :poste, 1, :note)
                    ');
                    $stmt->execute([
                        ':joueur' => $joueurId,
                        ':match' => $matchId,
                        ':poste' => $poste,
                        ':note' => $noteVal
                    ]);
                }
            }

            // Insérer les remplaçants
            foreach ($remplacants as $joueurId) {
                $poste = $posteRemplacants[$joueurId] ?? null;
                if ($poste) {
                    $noteVal = isset($notesPost[$joueurId]) && $notesPost[$joueurId] !== '' ? (int)$notesPost[$joueurId] : null;
                    $stmt = $pdo->prepare('
                        INSERT INTO Participer (Id_Joueur, Id_Match, Poste, Titulaire_ou_pas, Note)
                        VALUES (:joueur, :match, :poste, 0, :note)
                    ');
                    $stmt->execute([
                        ':joueur' => $joueurId,
                        ':match' => $matchId,
                        ':poste' => $poste,
                        ':note' => $noteVal
                    ]);
                }
            }

            $success = 'Feuille de match sauvegardée avec succès.';
        } catch (PDOException $e) {
            $error = 'Erreur lors de la sauvegarde: ' . $e->getMessage();
        }
    }
}

// Récupérer les commentaires et notes pour les joueurs
$stmt = $pdo->query('
    SELECT Id_Joueur, Description, Date_ FROM Commentaire ORDER BY Date_ DESC
');
$commentaires = [];
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $joueurId = $row['Id_Joueur'];
    if (!isset($commentaires[$joueurId])) {
        $commentaires[$joueurId] = [];
    }
    $commentaires[$joueurId][] = $row;
}

// Récupérer les notes pour les joueurs
$stmt = $pdo->query('
    SELECT Id_Joueur, Note FROM Participer WHERE Note IS NOT NULL
');
$notes = [];
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $joueurId = $row['Id_Joueur'];
    if (!isset($notes[$joueurId])) {
        $notes[$joueurId] = [];
    }
    $notes[$joueurId][] = $row['Note'];
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Saisie Feuille de Match</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css">
    <link rel="stylesheet" href="../CSS/common.css">
    <link rel="stylesheet" href="../CSS/matchs.css">
    <style>
        .joueur-card {
            border: 2px solid #ecf0f1;
            border-radius: 8px;
            padding: 1rem;
            margin-bottom: 1rem;
            transition: all 0.3s ease;
            cursor: pointer;
        }
        .joueur-card:hover {
            border-color: #E8283C;
            box-shadow: 0 4px 12px rgba(232, 40, 60, 0.1);
        }
        .joueur-card.selected {
            border-color: #E8283C;
            background-color: rgba(232, 40, 60, 0.05);
        }
        .joueur-info {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 0.5rem;
            font-size: 0.9rem;
            color: #666;
        }
        .poste-select {
            margin-top: 0.5rem;
        }
        .composition-section {
            background: #f8f9fa;
            padding: 1.5rem;
            border-radius: 8px;
            margin-bottom: 2rem;
        }
        .composition-title {
            font-size: 1.2rem;
            font-weight: 600;
            color: #C8102E;
            margin-bottom: 1rem;
        }
        .commentaire-item {
            padding: 0.5rem;
            background: white;
            border-left: 3px solid #E8283C;
            margin: 0.25rem 0;
            font-size: 0.85rem;
        }
        .note-badge {
            display: inline-block;
            background: #E8283C;
            color: white;
            padding: 0.25rem 0.5rem;
            border-radius: 4px;
            font-size: 0.8rem;
            margin: 0.25rem 0.25rem 0.25rem 0;
        }
        .selection-counter {
            position: fixed;
            bottom: 20px;
            right: 20px;
            background: #C8102E;
            color: white;
            padding: 1rem;
            border-radius: 50%;
            width: 60px;
            height: 60px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            font-weight: bold;
            box-shadow: 0 4px 12px rgba(0,0,0,0.2);
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark sticky-top navbar-custom">
        <div class="container-fluid">
            <a class="navbar-brand fw-bold" href="/SENTAYEHU_HISABU_PHP/index.php"><img src="../img/logo.png" alt="Logo Liverpool FC"> Liverpool FC</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="/SENTAYEHU_HISABU_PHP/index.php"><i class="bi bi-house-door"></i> Accueil</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/SENTAYEHU_HISABU_PHP/Vue/joueurs/liste_joueurs.php"><i class="bi bi-people"></i> Joueurs</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="/SENTAYEHU_HISABU_PHP/Vue/matchs/calendrier.php"><i class="bi bi-calendar3"></i> Matchs</a>
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
            <h1 style="font-size: 2rem; font-weight: 700; margin: 0;">
                <i class="bi bi-clipboard2-data"></i> Saisie Feuille de Match
            </h1>
            <p style="margin: 0.5rem 0 0; opacity: 0.9;">
                <?php echo htmlspecialchars($match['Nom_Equipe_Adverse']); ?> - 
                <?php echo date('d/m/Y \à H:i', strtotime($match['Date_Rencontre'] . ' ' . $match['Heure'])); ?>
            </p>
        </div>
    </div>

    <div class="container-fluid mb-5">
        <?php if ($error): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="bi bi-exclamation-circle me-2"></i>
                <?php echo htmlspecialchars($error); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <?php if ($success): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="bi bi-check-circle me-2"></i>
                <?php echo htmlspecialchars($success); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <form method="POST" action="">
            <!-- TITULAIRES -->
            <div class="composition-section">
                <div class="composition-title">
                    <i class="bi bi-people-fill"></i> Titulaires (11 minimum requis)
                </div>
                <div class="row g-3" id="titulaires-container">
                    <?php foreach ($joueurs as $joueur):
                        $joueurId = $joueur['Id_Joueur'];
                        $isTitulaire = isset($composition[$joueurId]) && $composition[$joueurId]['Titulaire_ou_pas'];
                    ?>
                        <div class="col-lg-4 col-md-6">
                            <div class="joueur-card" onclick="toggleJoueur(this, 'titulaires', <?php echo $joueurId; ?>)">
                                <div style="display: flex; align-items: center; margin-bottom: 0.5rem;">
                                    <input type="checkbox" name="titulaires" value="<?php echo $joueurId; ?>" 
                                        <?php echo $isTitulaire ? 'checked' : ''; ?> style="margin-right: 0.5rem;">
                                    <strong><?php echo htmlspecialchars($joueur['Nom'] . ' ' . $joueur['Prenom']); ?></strong>
                                </div>

                                <div class="joueur-info">
                                    <div><i class="bi bi-rulers"></i> Taille: <?php echo $joueur['Taille']; ?> m</div>
                                    <div><i class="bi bi-weight"></i> Poids: <?php echo $joueur['Poids']; ?> kg</div>
                                </div>

                                <select name="poste_titulaires[<?php echo $joueurId; ?>]" class="form-select poste-select" required>
                                    <option value="">-- Sélectionner un poste --</option>
                                    <?php foreach ($postes as $poste): ?>
                                        <option value="<?php echo $poste; ?>"
                                            <?php echo (isset($composition[$joueurId]) && $composition[$joueurId]['Poste'] === $poste) ? 'selected' : ''; ?>>
                                            <?php echo $poste; ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>

                                <div style="margin-top:0.5rem;">
                                    <label style="font-size:0.85rem; font-weight:600;">Note (0-5)</label>
                                    <input type="number" name="note[<?php echo $joueurId; ?>]" min="0" max="5" step="1" class="form-control" value="<?php echo isset($composition[$joueurId]) && $composition[$joueurId]['Note'] !== null ? (int)$composition[$joueurId]['Note'] : ''; ?>">
                                </div>

                                <?php if (isset($notes[$joueurId])): ?>
                                    <div style="margin-top: 0.5rem;">
                                        <strong style="font-size: 0.85rem;">Évaluations:</strong>
                                        <?php foreach ($notes[$joueurId] as $note): ?>
                                            <span class="note-badge"><?php echo $note; ?>/5</span>
                                        <?php endforeach; ?>
                                    </div>
                                <?php endif; ?>

                                <?php if (isset($commentaires[$joueurId])): ?>
                                    <div style="margin-top: 0.5rem;">
                                        <strong style="font-size: 0.85rem;">Commentaires récents:</strong>
                                        <?php foreach (array_slice($commentaires[$joueurId], 0, 2) as $com): ?>
                                            <div class="commentaire-item">
                                                "<?php echo htmlspecialchars(substr($com['Description'], 0, 60)); ?>..."
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- REMPLAÇANTS -->
            <div class="composition-section">
                <div class="composition-title">
                    <i class="bi bi-person-check"></i> Remplaçants
                </div>
                <div class="row g-3" id="remplacants-container">
                    <?php foreach ($joueurs as $joueur):
                        $joueurId = $joueur['Id_Joueur'];
                        $isRemplacant = isset($composition[$joueurId]) && !$composition[$joueurId]['Titulaire_ou_pas'];
                    ?>
                        <div class="col-lg-4 col-md-6">
                            <div class="joueur-card" onclick="toggleJoueur(this, 'remplacants', <?php echo $joueurId; ?>)">
                                <div style="display: flex; align-items: center; margin-bottom: 0.5rem;">
                                    <input type="checkbox" name="remplacants" value="<?php echo $joueurId; ?>" 
                                        <?php echo $isRemplacant ? 'checked' : ''; ?> style="margin-right: 0.5rem;">
                                    <strong><?php echo htmlspecialchars($joueur['Nom'] . ' ' . $joueur['Prenom']); ?></strong>
                                </div>

                                <div class="joueur-info">
                                    <div><i class="bi bi-rulers"></i> Taille: <?php echo $joueur['Taille']; ?> m</div>
                                    <div><i class="bi bi-weight"></i> Poids: <?php echo $joueur['Poids']; ?> kg</div>
                                </div>

                                <select name="poste_remplacants[<?php echo $joueurId; ?>]" class="form-select poste-select">
                                    <option value="">-- Sélectionner un poste --</option>
                                    <?php foreach ($postes as $poste): ?>
                                        <option value="<?php echo $poste; ?>"
                                            <?php echo (isset($composition[$joueurId]) && $composition[$joueurId]['Poste'] === $poste) ? 'selected' : ''; ?>>
                                            <?php echo $poste; ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>

                                <div style="margin-top:0.5rem;">
                                    <label style="font-size:0.85rem; font-weight:600;">Note (0-5)</label>
                                    <input type="number" name="note[<?php echo $joueurId; ?>]" min="0" max="5" step="1" class="form-control" value="<?php echo isset($composition[$joueurId]) && $composition[$joueurId]['Note'] !== null ? (int)$composition[$joueurId]['Note'] : ''; ?>">
                                </div>

                                <?php if (isset($notes[$joueurId])): ?>
                                    <div style="margin-top: 0.5rem;">
                                        <strong style="font-size: 0.85rem;">Évaluations:</strong>
                                        <?php foreach ($notes[$joueurId] as $note): ?>
                                            <span class="note-badge"><?php echo $note; ?>/5</span>
                                        <?php endforeach; ?>
                                    </div>
                                <?php endif; ?>

                                <?php if (isset($commentaires[$joueurId])): ?>
                                    <div style="margin-top: 0.5rem;">
                                        <strong style="font-size: 0.85rem;">Commentaires récents:</strong>
                                        <?php foreach (array_slice($commentaires[$joueurId], 0, 2) as $com): ?>
                                            <div class="commentaire-item">
                                                "<?php echo htmlspecialchars(substr($com['Description'], 0, 60)); ?>..."
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- ACTIONS -->
            <div style="display: flex; gap: 1rem; justify-content: space-between; margin-top: 2rem;">
                <a href="calendrier.php" class="btn btn-secondary">
                    <i class="bi bi-arrow-left"></i> Retour
                </a>
                <button type="submit" class="btn btn-primary" id="submit-btn">
                    <i class="bi bi-check-circle"></i> Valider la Sélection
                </button>
            </div>
        </form>
    </div>

    <div class="selection-counter" id="counter">
        <span id="count-value">0</span>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function toggleJoueur(element, type, joueurId) {
            const checkbox = element.querySelector('input[type="checkbox"]');
            checkbox.checked = !checkbox.checked;
            updateCardStyle(element, checkbox.checked);
            updateCounter();
            validateForm();
        }

        function updateCardStyle(element, isChecked) {
            if (isChecked) {
                element.classList.add('selected');
            } else {
                element.classList.remove('selected');
            }
        }

        function updateCounter() {
            const titulaires = document.querySelectorAll('input[name="titulaires"]:checked').length;
            document.getElementById('count-value').textContent = titulaires;
        }

        function validateForm() {
            const titulaires = document.querySelectorAll('input[name="titulaires"]:checked').length;
            const submitBtn = document.getElementById('submit-btn');
            
            if (titulaires >= 11) {
                submitBtn.disabled = false;
                submitBtn.classList.remove('btn-danger');
            } else {
                submitBtn.disabled = true;
                submitBtn.classList.add('btn-danger');
            }
        }

        // Initialiser au chargement de la page
        document.addEventListener('DOMContentLoaded', () => {
            document.querySelectorAll('.joueur-card input[type="checkbox"]:checked').forEach(checkbox => {
                updateCardStyle(checkbox.closest('.joueur-card'), true);
            });
            updateCounter();
            validateForm();
        });

        // Émettre validation au changement de sélection
        document.querySelectorAll('input[type="checkbox"]').forEach(checkbox => {
            checkbox.addEventListener('change', validateForm);
        });
    </script>
</body>
</html>
