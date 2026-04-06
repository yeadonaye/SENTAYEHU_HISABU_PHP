<?php
session_start();
require_once '../../routeClient.php';

if (!isset($_SESSION['token'])) {
    header('Location: ../../login.php');
    exit;
}

$token       = $_SESSION['token'];
$joueurId    = $_GET['id'] ?? null;
$error       = '';
$success     = '';
$commentaire = [];

// Vérifier que l'id du joueur existe
if (!$joueurId) {
    header('Location: /Vue/Afficher/liste_joueurs.php');
    exit;
}

// Charger le joueur pour afficher son nom
$responseJoueur = routeClient::getJoueurById((int)$joueurId, $token);
$joueurData = [];
if ($responseJoueur['status_code'] === 200) {
    $joueurData = $responseJoueur['data'] ?? [];
} else {
    $error = $responseJoueur['status_message'] ?? 'Impossible de charger le joueur';
}

// Soumission du formulaire
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $description = $_POST['description'] ?? '';
    $dateInput   = trim($_POST['date_commentaire'] ?? '');
    $dateForApi  = null; // On utilisera null si la date est vide

    // Conversion jj/mm/aaaa → YYYY-MM-DD
    if (!empty($dateInput)) {
        $parts = explode('/', $dateInput);
        if (count($parts) === 3) {
            [$jour, $mois, $annee] = $parts;
            if (checkdate((int)$mois, (int)$jour, (int)$annee)) {
                $dateForApi = sprintf('%04d-%02d-%02d', $annee, $mois, $jour);
            } else {
                $error = 'Date invalide (format attendu : jj/mm/aaaa)';
            }
        } else {
            $error = 'Date invalide (format attendu : jj/mm/aaaa)';
        }
    } else {
        // Si vide, mettre aujourd'hui
        $dateForApi = date('Y-m-d');
    }

    if (empty($description)) {
        $error = $error ?: 'Le commentaire est obligatoire.';
    }

    if (!$error) {
        $data = [
            'Id_Joueur'        => (int)$joueurId,
            'Description'      => $description,
            'Date_Commentaire' => $dateForApi
        ];

        // Appel API via routeClient
        $response = routeClient::addCommentaire($data, $token);

        if ($response['status_code'] === 200 || $response['status_code'] === 201) {
            $success = 'Commentaire ajouté avec succès !';
            $commentaire = $data; // garder les valeurs affichées
        } else {
            $error = $response['status_message'] ?? 'Erreur inconnue';
            $commentaire = $data;
        }
    }
}

// Pour affichage du champ date en jj/mm/aaaa
$displayDate = $_POST['date_commentaire'] ?? date('d/m/Y');
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajouter un commentaire</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css">
    <link rel="stylesheet" href="../CSS/common.css">
    <link rel="stylesheet" href="../CSS/joueurs.css">
</head>
<body>
    <?php include '../Afficher/navbar.php'; ?>
    <div class="container my-4">
        <h1 class="mb-3"><i class="bi bi-chat-left-text"></i> Ajouter un commentaire</h1>
        <p class="text-muted">Joueur : <strong><?php echo htmlspecialchars($joueurData['Nom'] ?? '') . ' ' . htmlspecialchars($joueurData['Prenom'] ?? ''); ?></strong></p>

        <?php if ($error): ?>
            <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>
        <?php if ($success): ?>
            <div class="alert alert-success"><?php echo htmlspecialchars($success); ?></div>
        <?php endif; ?>

        <form method="POST" action="">
            <div class="mb-3">
                <label class="form-label fw-bold" for="description">Commentaire *</label>
                <textarea class="form-control" id="description" name="description" rows="4" required><?php echo htmlspecialchars($_POST['description'] ?? $commentaire['Description'] ?? ''); ?></textarea>
            </div>
            <div class="mb-3">
                <label class="form-label fw-bold" for="date_commentaire">Date du commentaire</label>
                <input type="text" class="form-control" id="date_commentaire" name="date_commentaire" placeholder="jj/mm/aaaa" value="<?php echo htmlspecialchars($_POST['date_commentaire'] ?? $commentaire['Date_Commentaire'] ?? $displayDate); ?>">
            </div>
            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-success"><i class="bi bi-save me-2"></i>Enregistrer</button>
                <a href="/Vue/Afficher/afficher_commentaires.php?id=<?php echo $joueurId; ?>" class="btn btn-secondary">Annuler</a>
            </div>
        </form>
    </div>
    <?php include '../Afficher/footer.php'; ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>