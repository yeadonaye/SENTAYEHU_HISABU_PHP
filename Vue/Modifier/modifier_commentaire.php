<?php
session_start();

if (!isset($_SESSION['token'])) {
    header('Location: ../../login.php');
    exit;
}

// Connexion directe à la BDD
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

$id    = $_GET['id'] ?? null;
$error = '';

if (!$id) {
    header('Location: /Vue/Afficher/liste_joueurs.php');
    exit;
}

// Charger le commentaire
$stmt = $pdo->prepare("SELECT * FROM Commentaire WHERE Id_Commentaire = ?");
$stmt->execute([(int)$id]);
$comment = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$comment) {
    $error = 'Commentaire introuvable.';
}

// Pré-remplir la date pour affichage
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $displayDate = $_POST['date_commentaire'] ?? '';
} else {
    $dt = DateTime::createFromFormat('Y-m-d', substr($comment['Date_Commentaire'], 0, 10)) 
          ?: DateTime::createFromFormat('Y-m-d H:i:s', $comment['Date_Commentaire']);
    $displayDate = $dt ? $dt->format('d/m/Y') : date('d/m/Y');
}

// Soumission du formulaire
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $comment) {
    $description = $_POST['description'] ?? '';
    $dateInput   = trim($_POST['date_commentaire'] ?? '');
    $dateForDb   = substr($comment['Date_Commentaire'], 0, 10); // default = existing DB value

    // Conversion jj/mm/aaaa → YYYY-MM-DD
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

    if (empty($description)) {
        $error = $error ?: 'Le commentaire est obligatoire.';
    }

    if (!$error) {
        try {
            $stmt = $pdo->prepare("UPDATE Commentaire SET Description = ?, Date_Commentaire = ? WHERE Id_Commentaire = ?");
            $stmt->execute([$description, $dateForDb, (int)$id]);
            header('Location: /Vue/Afficher/afficher_commentaires.php?id=' . $comment['Id_Joueur'] . '&success=modified');
            exit;
        } catch (PDOException $e) {
            $error = 'Erreur lors de la modification : ' . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier un commentaire</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css">
    <link rel="stylesheet" href="../CSS/common.css">
    <link rel="stylesheet" href="../CSS/joueurs.css">
</head>
<body>
    <?php include '../Afficher/navbar.php'; ?>
    <div class="container my-4">
        <h1 class="mb-3"><i class="bi bi-pencil-square"></i> Modifier le commentaire</h1>

        <?php if ($error): ?>
            <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>

        <?php if ($comment): ?>
            <form method="POST" action="">
                <div class="mb-3">
                    <label class="form-label fw-bold" for="description">Commentaire *</label>
                    <textarea class="form-control" id="description" name="description" rows="4" required><?php echo htmlspecialchars($_POST['description'] ?? $comment['Description']); ?></textarea>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-bold" for="date_commentaire">Date du commentaire</label>
                    <input type="text" class="form-control" id="date_commentaire" name="date_commentaire" placeholder="jj/mm/aaaa" value="<?php echo htmlspecialchars($displayDate); ?>">
                    <small class="text-muted">Format attendu : jj/mm/aaaa. Laisser vide pour conserver la date actuelle du commentaire.</small>
                </div>
                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary"><i class="bi bi-save me-2"></i>Enregistrer</button>
                    <a href="../Afficher/afficher_commentaires.php?id=<?php echo $comment['Id_Joueur']; ?>" class="btn btn-secondary">Annuler</a>
                </div>
            </form>
        <?php endif; ?>
    </div>
    <?php include '../Afficher/footer.php'; ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>