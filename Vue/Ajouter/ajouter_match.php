<?php
session_start();
require_once '../../routeClient.php';

if (!isset($_SESSION['token'])) {
    header('Location: ../../login.php');
    exit;
}

$token = $_SESSION['token'];

// Vérification du token auprès de l'API d'auth
$verify = routeClient::verifyToken($token);
if ($verify['status_code'] === 401) {
    session_destroy();
    header('Location: ../../login.php');
    exit;
}

$role = $verify['data']['role'] ?? $_SESSION['role'] ?? 'joueur';

$token   = $_SESSION['token'];
$id      = $_GET['id'] ?? null;
$error   = '';
$success = '';
$match   = [];

// Si modification — charger les données du match via l'API
if ($id) {
    $response = routeClient::getMatchById((int)$id, $token);
    if ($response['status_code'] === 200) {
        $match = $response['data'] ?? [];
        // Formater la date pour le champ input jj/mm/aaaa
        if (!empty($match['Date_Rencontre']) && $match['Date_Rencontre'] !== '0000-00-00') {
            $d = DateTime::createFromFormat('Y-m-d', substr($match['Date_Rencontre'],0,10));
            $match['Date_Rencontre'] = $d ? $d->format('d/m/Y') : '';
        } else {
            $match['Date_Rencontre'] = '';
        }
    } else {
        $error = $response['status_message'] ?? 'Erreur lors du chargement du match';
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $dateInput = $_POST['dateRencontre'] ?? '';
    $dateConverted = '';

    // Conversion date jj/mm/aaaa → YYYY-MM-DD pour l'API
    if (!empty($dateInput)) {
        $dt = DateTime::createFromFormat('d/m/Y', $dateInput);
        if ($dt) {
            $dateConverted = $dt->format('Y-m-d');
        } else {
            $error = 'Date invalide, format attendu : jj/mm/aaaa';
        }
    }

    // Conversion heure HH:MM:SS → HH:MM
    $heureRaw = $_POST['heure'] ?? '';
    $heureConverted = substr($heureRaw, 0, 5); // garde seulement HH:MM

    // Vérifier si le match est dans le futur côté serveur aussi
    $matchDateTime = new DateTime($dateConverted . ' ' . $heureConverted);
    $isFutur = $matchDateTime > new DateTime();

    $data = [
        'Nom_Equipe_Adverse' => $_POST['nomEquipeAdverse'] ?? '',
        'Date_Rencontre'     => $dateConverted,
        'Heure'              => $heureConverted,
        'Lieu'               => $_POST['lieu'] ?? '',
        'Resultat'           => $isFutur ? '' : ($_POST['resultat'] ?? ''),
        'Score_Nous'         => $isFutur ? 0 : (isset($_POST['scoreNous']) && $_POST['scoreNous'] !== '' ? (int)$_POST['scoreNous'] : 0),
        'Score_Adversaire'   => $isFutur ? 0 : (isset($_POST['scoreAdverse']) && $_POST['scoreAdverse'] !== '' ? (int)$_POST['scoreAdverse'] : 0),
    ];

    if (empty($dateConverted)) {
        $error = $error ?: 'Date invalide, format attendu : jj/mm/aaaa';
    } else {
        if ($id) {
            $response = routeClient::updateMatch((int)$id, $data, $token);
        } else {
            $response = routeClient::addMatch($data, $token);
        }

        if ($response['status_code'] === 200 || $response['status_code'] === 201) {
            $success = $id ? 'Match modifié avec succès !' : 'Match ajouté avec succès !';
            // Pour garder la date affichée en jj/mm/aaaa dans le formulaire
            $match = $data;
            $d = DateTime::createFromFormat('Y-m-d', $data['Date_Rencontre']);
            $match['Date_Rencontre'] = $d ? $d->format('d/m/Y') : '';
        } else {
            $error = $response['status_message'] ?? 'Erreur inconnue';
            $match = $data;
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
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css">
    <link rel="stylesheet" href="../CSS/common.css">
    <link rel="stylesheet" href="../CSS/matchs.css">
</head>
<body>
        <?php include '../Afficher/navbar.php'; ?>
    <!-- Page Header -->
    <div class="page-header-gradient">
        <div class="container-fluid">
            <div class="page-header-content">
                <div>
                    <h1 class="page-header-title">
                        <i class="bi <?php echo $id ? 'bi-pencil' : 'bi-plus-circle'; ?>"></i> 
                        <?php echo $id ? 'Modifier un Match' : 'Planifier un Match'; ?>
                    </h1>
                </div>
                <a href="/Vue/Afficher/afficher_match.php" class="btn btn-light btn-retour">
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

        <div class="form-container">
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
                            type="text" 
                            class="form-control" 
                            id="dateRencontre" 
                            name="dateRencontre" 
                            value="<?php echo htmlspecialchars($match['Date_Rencontre'] ?? ''); ?>"
                            pattern="\d{2}/\d{2}/\d{4}"
                            inputmode="numeric"
                            placeholder="jj/mm/aaaa"
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
                            step="60"
                            required
                        >
                    </div>
                </div>

                <div class="mb-3">
                    <label for="lieu" class="form-label fw-bold">Lieu de rencontre</label>
                    <select class="form-control" id="lieu" name="lieu" required>
                        <option value="">-- Sélectionner --</option>
                        <option value="Domicile" <?php echo ($match['Lieu'] ?? '') === 'Domicile' ? 'selected' : ''; ?>>Domicile</option>
                        <option value="Extérieur" <?php echo ($match['Lieu'] ?? '') === 'Extérieur' ? 'selected' : ''; ?>>Extérieur</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label for="resultat" class="form-label fw-bold">Résultat</label>
                    <select class="form-control" id="resultat" name="resultat">
                        <option value="">-- Sélectionner un résultat --</option>
                        <option value="Victoire" <?php echo ($match['Resultat'] ?? '') === 'Victoire' ? 'selected' : ''; ?>>Victoire</option>
                        <option value="Nul"      <?php echo ($match['Resultat'] ?? '') === 'Nul'      ? 'selected' : ''; ?>>Nul</option>
                        <option value="Défaite"  <?php echo ($match['Resultat'] ?? '') === 'Défaite'  ? 'selected' : ''; ?>>Défaite</option>
                    </select>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="scoreNous" class="form-label fw-bold">Notre Score</label>
                        <input 
                            type="number" 
                            class="form-control score-input" 
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
                            class="form-control score-input" 
                            id="scoreAdverse" 
                            name="scoreAdverse" 
                            value="<?php echo htmlspecialchars($match['Score_Adversaire'] ?? ''); ?>"
                            min="0"
                            placeholder="0"
                        >
                    </div>
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-check-circle me-2"></i>
                        <?php echo $id ? 'Modifier' : 'Ajouter'; ?>
                    </button>
                    <a href="/Vue/Afficher/afficher_match.php" class="btn btn-secondary">
                        <i class="bi bi-x-circle me-2"></i>Annuler
                    </a>
                </div>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
        <?php include '../Afficher/footer.php'; ?>
    <script>
    // Parse French date format dd/mm/yyyy
    function parseDateFr(value) {
        const parts = value.split('/');
        if (parts.length !== 3) return null;
        const [jour, mois, annee] = parts.map(Number);
        if (!jour || !mois || !annee) return null;

        const d = new Date(annee, mois - 1, jour); // months are 0-based
        if (d.getFullYear() !== annee || d.getMonth() !== mois - 1 || d.getDate() !== jour) return null;

        return d;
    }

    // Check the match date and enable/disable score/result inputs
    function checkMatchDate() {
        const dateInput = document.getElementById('dateRencontre').value;
        const heureInput = document.getElementById('heure').value;
        const parsedDate = parseDateFr(dateInput);
        const scoreNous = document.getElementById('scoreNous');
        const scoreAdverse = document.getElementById('scoreAdverse');
        const resultat = document.getElementById('resultat');

        // Disable if date or time not set
        if (!parsedDate || !heureInput) {
            scoreNous.disabled = true;
            scoreAdverse.disabled = true;
            resultat.disabled = true;
            return;
        }

        // Split heureInput "HH:MM" into hours and minutes
        const [h, m] = heureInput.split(':').map(Number);
        if (Number.isNaN(h) || Number.isNaN(m)) {
            scoreNous.disabled = true;
            scoreAdverse.disabled = true;
            resultat.disabled = true;
            return;
        }

        parsedDate.setHours(h, m, 0, 0); // set the correct match time

        const now = new Date();

        if (parsedDate > now) {
            // Future match – disable fields
            scoreNous.disabled = true;
            scoreAdverse.disabled = true;
            resultat.disabled = true;
            scoreNous.title = 'Disponible uniquement après le match';
            scoreAdverse.title = 'Disponible uniquement après le match';
            resultat.title = 'Disponible uniquement après le match';
        } else {
            // Past or ongoing match – enable fields
            scoreNous.disabled = false;
            scoreAdverse.disabled = false;
            resultat.disabled = false;
            scoreNous.title = '';
            scoreAdverse.title = '';
            resultat.title = '';
        }
    }

    // Run on page load and when inputs change
    window.addEventListener('load', () => {
        checkMatchDate();

        document.getElementById('dateRencontre').addEventListener('change', checkMatchDate);
        document.getElementById('heure').addEventListener('change', checkMatchDate);
    });
</script>
</body>
</html>