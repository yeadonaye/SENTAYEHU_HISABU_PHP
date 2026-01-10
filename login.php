<?php
// auth.php lives in Modele/DAO
require_once __DIR__ . '/Modele/DAO/auth.php';

if (isAuthenticated()) {
    header('Location: index.php');
    exit;
}

$error = '';
$redirect = $_GET['redirect'] ?? 'index.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $identifiant = $_POST['identifiant'] ?? '';
    $password = $_POST['password'] ?? '';
    
    if (authenticate($identifiant, $password)) {
        header('Location: ' . $redirect);
        exit;
    } else {
        $error = 'Identifiant ou mot de passe incorrect!';
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion - Gestion des Joueurs</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css">
    <link rel="stylesheet" href="Vue/CSS/login.css">
</head>
<body>
    <div class="login-container">
        <div class="login-header">
            <div class="login-icon">
                <i class="bi bi-shield-check"></i>
            </div>
            <h1>Gestion des Joueurs</h1>
            <p>Système de Gestion</p>
        </div>

        <?php if ($error): ?>
            <div class="alert alert-danger">
                <i class="bi bi-exclamation-circle me-2"></i>
                <?php echo htmlspecialchars($error); ?>
            </div>
        <?php endif; ?>

        <form method="POST" action="">
            <div class="form-group">
                <label for="identifiant" class="form-label">Identifiant</label>
                <input
                    type="text"
                    class="form-control"
                    id="identifiant"
                    name="identifiant"
                    placeholder="Entrez l'identifiant"
                    value="<?php echo htmlspecialchars($_POST['identifiant'] ?? ''); ?>"
                    required
                    autofocus
                >
            </div>

            <div class="form-group">
                <label for="password" class="form-label">Mot de passe</label>
                <div class="input-group">
                    <input 
                        type="password" 
                        class="form-control" 
                        id="password" 
                        name="password" 
                        placeholder="Entrez le mot de passe"
                        required
                    >
                    <button 
                        class="btn btn-outline-secondary" 
                        type="button" 
                        onclick="togglePassword()"
                        style="border: 2px solid #ecf0f1;"
                    >
                        <i class="bi bi-eye" id="toggleIcon"></i>
                    </button>
                </div>
            </div>

            <button type="submit" class="btn btn-login">
                <i class="bi bi-box-arrow-in-right me-2"></i>
                Se Connecter
            </button>
        </form>

        <hr style="margin: 1.5rem 0; color: #ecf0f1;">

        <p style="text-align: center; color: #7f8c8d; font-size: 0.9rem; margin: 0;">
            <i class="bi bi-info-circle me-2"></i>
            Mot de passe requis pour accéder à l'application
        </p>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function togglePassword() {
            const passwordInput = document.getElementById('password');
            const toggleIcon = document.getElementById('toggleIcon');
            
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                toggleIcon.classList.remove('bi-eye');
                toggleIcon.classList.add('bi-eye-slash');
            } else {
                passwordInput.type = 'password';
                toggleIcon.classList.remove('bi-eye-slash');
                toggleIcon.classList.add('bi-eye');
            }
        }
    </script>
</body>
</html>
