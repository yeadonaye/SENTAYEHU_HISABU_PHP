<?php
session_start();
require_once '../../routeClient.php';

// Redirection si utilisateur non connecté
if (!isset($_SESSION['token'])) {
    header('Location: ../../login.php');
    exit;
}

$token = $_SESSION['token'];

// Vérification du token auprès de l'API d'authentification
$verify = routeClient::verifyToken($token);
if ($verify['status_code'] === 401) {
    // Token invalide : destruction de session et redirection
    session_destroy();
    header('Location: ../../login.php');
    exit;
}

// Détermination du rôle de l'utilisateur
$role = $verify['data']['role'] ?? $_SESSION['role'] ?? 'joueur';

// Connexion à la base de données avec gestion d'erreurs
try {
    $pdo = new PDO(
        'mysql:host=mysql-yeadonaye.alwaysdata.net;dbname=yeadonaye_bd_gestion_equipe;charset=utf8',
        'yeadonaye',
        'admin@gestionFoot'
    );
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die('Erreur de connexion : ' . $e->getMessage());
}

// Récupération de l'ID du joueur depuis l'URL
$joueurId = $_GET['id'] ?? null;
$error    = '';

// Redirection si aucun ID fourni
if (!$joueurId) {
    header('Location: /Vue/Afficher/liste_joueurs.php');
    exit;
}

// Chargement des informations du joueur pour affichage
$stmt = $pdo->prepare("SELECT Nom, Prenom FROM Joueur WHERE Id_Joueur = ?");
$stmt->execute([(int)$joueurId]);
$joueurData = $stmt->fetch(PDO::FETCH_ASSOC);

// --- Gestion de la date par défaut pour l'affichage du formulaire ---
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $displayDate = $_POST['date_commentaire'] ?? date('d/m/Y');
} else {
    $displayDate = date('d/m/Y'); // Aujourd'hui si nouveau commentaire
}

// --- Soumission du formulaire et validation ---
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $description = $_POST['description'] ?? '';
    $dateInput   = trim($_POST['date_commentaire'] ?? '');
    $dateForDb   = date('Y-m-d'); // valeur par défaut : aujourd'hui

    // Conversion de la date format jj/mm/aaaa → YYYY-MM-DD pour la BDD
    if (!empty($dateInput)) {
        $parts = explode('/', $dateInput);
        if (count($parts) === 3) {
            [$jour, $mois, $annee] = $parts;
            if (checkdate((int)$mois, (int)$jour, (int)$annee)) {
                $dateForDb = sprintf('%04d-%02d-%02d', $annee, $mois, $jour);
            } else {
                $error = 'Date invalide (format attendu : jj/mm/aaaa)';
            }
        } else {
            $error = 'Date invalide (format attendu : jj/mm/aaaa)';
        }
    }

    // Validation du champ description
    if (empty($description)) {
        $error = $error ?: 'Le commentaire est obligatoire.';
    }

    // Insertion en base si aucune erreur
    if (!$error) {
        try {
            $stmt = $pdo->prepare("INSERT INTO Commentaire (Id_Joueur, Description, Date_Commentaire) VALUES (?, ?, ?)");
            $stmt->execute([(int)$joueurId, $description, $dateForDb]);
            // Redirection vers la page des commentaires avec message de succès
            header('Location: /Vue/Afficher/afficher_commentaires.php?id=' . $joueurId . '&success=added');
            exit;
        } catch (PDOException $e) {
            $error = 'Erreur lors de l\'enregistrement : ' . $e->getMessage();
        }
    }
}
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

        <form method="POST" action="">
            <div class="mb-3">
                <label class="form-label fw-bold" for="description">Commentaire *</label>
                <textarea class="form-control" id="description" name="description" rows="4" required><?php echo htmlspecialchars($_POST['description'] ?? ''); ?></textarea>
            </div>
            <div class="mb-3">
                <label class="form-label fw-bold" for="date_commentaire">Date du commentaire</label>
                <input type="text" class="form-control" id="date_commentaire" name="date_commentaire" placeholder="jj/mm/aaaa" value="<?php echo htmlspecialchars($displayDate); ?>">
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