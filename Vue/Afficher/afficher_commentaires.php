<?php
session_start();
require_once '../../routeClient.php';

// Redirige vers la page de login si l'utilisateur n'est pas connecté
if (!isset($_SESSION['token'])) {
    header('Location: ../../login.php');
    exit;
}

$token = $_SESSION['token'];

// Vérification du token auprès de l'API d'auth
$verify = routeClient::verifyToken($token);
if ($verify['status_code'] === 401) {
    session_destroy();
    header('Location: ../../login.php'); // Redirige si le token est expiré ou invalide
    exit;
}

// Récupération du rôle : priorité au token, sinon session, sinon 'joueur'
$role = $verify['data']['role'] ?? $_SESSION['role'] ?? 'joueur';

// Connexion directe à la base de données avec gestion des erreurs
try {
    $pdo = new PDO(
        'mysql:host=mysql-yeadonaye.alwaysdata.net;dbname=yeadonaye_bd_gestion_equipe;charset=utf8',
        'yeadonaye',
        'admin@gestionFoot'
    );
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); // Active le mode exception pour PDO
} catch (PDOException $e) {
    die('Erreur de connexion : ' . $e->getMessage());
}

// Récupération de l'ID du joueur passé en GET
$joueurId = $_GET['id'] ?? null;
$error    = '';
$success  = isset($_GET['success']) ? 'Commentaire ajouté avec succès !' : '';

// Redirection si aucun joueur n'est spécifié
if (!$joueurId) {
    header('Location: /Vue/Afficher/liste_joueurs.php');
    exit;
}

// Charger les informations du joueur depuis la table Joueur
$stmt = $pdo->prepare("SELECT Nom, Prenom FROM Joueur WHERE Id_Joueur = ?");
$stmt->execute([(int)$joueurId]);
$joueurData = $stmt->fetch(PDO::FETCH_ASSOC);

// Charger tous les commentaires associés à ce joueur, triés par date décroissante
$stmt = $pdo->prepare("SELECT * FROM Commentaire WHERE Id_Joueur = ? ORDER BY Date_Commentaire DESC");
$stmt->execute([(int)$joueurId]);
$commentaires = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Commentaires du joueur</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css">
    <link rel="stylesheet" href="../CSS/common.css">
    <link rel="stylesheet" href="../CSS/joueurs.css">
</head>
<body>
    <?php include 'navbar.php'; ?>

    <div class="container my-4">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <div>
                <h1 class="mb-1"><i class="bi bi-chat-dots"></i> Commentaires</h1>
                <p>Joueur : <strong><?php echo htmlspecialchars(($joueurData['Nom'] ?? '') . ' ' . ($joueurData['Prenom'] ?? '')); ?></strong></p>
            </div>
            <div class="d-flex gap-2">
                <a class="btn btn-success" href="/Vue/Ajouter/ajouter_commentaire.php?id=<?php echo $joueurId; ?>">
                    <i class="bi bi-plus-circle me-2"></i>Ajouter un commentaire
                </a>
                <a class="btn btn-secondary" href="/Vue/Afficher/liste_joueurs.php">
                    <i class="bi bi-arrow-left me-2"></i>Retour
                </a>
            </div>
        </div>

        <?php if ($error): ?>
            <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>
        <?php if ($success && !$error): ?>
            <div class="alert alert-success"><?php echo htmlspecialchars($success); ?></div>
        <?php endif; ?>

        <?php if (!empty($commentaires)): ?>
            <div class="list-group">
                <?php foreach ($commentaires as $com): ?>
                    <div class="list-group-item">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <p><?php echo nl2br(htmlspecialchars($com['Description'])); ?></p>
                            </div>
                            <small class="text-muted ms-3">
                                <?php 
                                    $dt = DateTime::createFromFormat('Y-m-d H:i:s', $com['Date_Commentaire']) ?: DateTime::createFromFormat('Y-m-d', $com['Date_Commentaire']);
                                    echo $dt ? $dt->format('d/m/Y') : htmlspecialchars($com['Date_Commentaire']);
                                ?>
                            </small>
                        </div>
                        <div class="mt-2 d-flex gap-2">
                            <a class="btn btn-sm btn-outline-primary" href="../Modifier/modifier_commentaire.php?id=<?php echo $com['Id_Commentaire']; ?>">
                                <i class="bi bi-pencil"></i> Modifier
                            </a>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div class="alert alert-info">Aucun commentaire pour ce joueur.</div>
        <?php endif; ?>
    </div>

    <?php include 'footer.php'; ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
