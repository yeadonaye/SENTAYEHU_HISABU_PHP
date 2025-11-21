<?php
session_start();
require_once 'config.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';
    
    // Simple validation
    if (empty($username) || empty($password)) {
        $error = 'Veuillez remplir tous les champs';
    } else {
        // In a real application, you would verify credentials against a database
        // This is just a basic example
        if ($username === 'admin' && $password === 'password') {
            $_SESSION['user'] = [
                'id' => 1,
                'username' => $username,
                'role' => 'admin'
            ];
            header('Location: players.php');
            exit();
        } else {
            $error = 'Identifiant ou mot de passe incorrect';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion - Gestion d'équipe</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="styles.css">
    <style>
        .login-container {
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 80vh;
            padding: 2rem 0;
        }
        
        .login-card {
            background: white;
            border-radius: 16px;
            box-shadow: var(--box-shadow);
            padding: 2.5rem;
            width: 100%;
            max-width: 450px;
            position: relative;
            overflow: hidden;
            animation: fadeIn 0.6s ease-out;
        }
        
        .login-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 6px;
            background: linear-gradient(90deg, var(--primary-color), var(--accent-color));
        }
        
        .login-header {
            text-align: center;
            margin-bottom: 2rem;
        }
        
        .login-header i {
            font-size: 2.5rem;
            color: var(--primary-color);
            margin-bottom: 1rem;
            background: rgba(67, 97, 238, 0.1);
            width: 80px;
            height: 80px;
            border-radius: 50%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }
        
        .login-header h2 {
            color: var(--dark-color);
            margin-bottom: 0.5rem;
            font-size: 1.8rem;
        }
        
        .login-header p {
            color: var(--text-light);
            font-size: 0.95rem;
        }
        
        .form-group {
            margin-bottom: 1.5rem;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 500;
            color: var(--dark-color);
            font-size: 0.95rem;
        }
        
        .form-group input {
            width: 100%;
            padding: 0.9rem 1.2rem;
            border: 1px solid #e0e0e0;
            border-radius: 8px;
            font-size: 1rem;
            transition: var(--transition);
            background-color: #f8f9fa;
        }
        
        .form-group input:focus {
            outline: none;
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(67, 97, 238, 0.1);
            background-color: white;
        }
        
        .forgot-password {
            display: block;
            text-align: center;
            margin: 1.5rem 0;
            color: var(--primary-color);
            text-decoration: none;
            font-size: 0.9rem;
            transition: var(--transition);
        }
        
        .forgot-password:hover {
            text-decoration: underline;
            color: var(--secondary-color);
        }
        
        .btn-login {
            width: 100%;
            padding: 1rem;
            background: var(--primary-color);
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: var(--transition);
            margin-top: 0.5rem;
        }
        
        .btn-login:hover {
            background: var(--secondary-color);
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(67, 97, 238, 0.3);
        }
        
        .error-message {
            background-color: #fde8e8;
            color: #e74c3c;
            padding: 0.9rem 1.2rem;
            border-radius: 8px;
            margin-bottom: 1.5rem;
            font-size: 0.9rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            animation: fadeIn 0.3s ease-out;
        }
        
        .error-message i {
            font-size: 1.2rem;
        }
        
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        @media (max-width: 576px) {
            .login-card {
                padding: 2rem 1.5rem;
            }
            
            .login-header h2 {
                font-size: 1.5rem;
            }
        }
    </style>
</head>
<body>
    <div class="hero">
        <header>
            <div class="logo">
                <i class="fas fa-futbol"></i>
                <h1>Gestion d'équipe</h1>
            </div>
            <nav>
                <a href="index.php" class="btn btn-login"><i class="fas fa-home"></i> Accueil</a>
            </nav>
        </header>
    </div>

    <div class="login-container">
        <div class="login-card">
            <div class="login-header">
                <i class="fas fa-user-shield"></i>
                <h2>Connexion</h2>
                <p>Entrez vos identifiants pour accéder à votre espace</p>
            </div>
            
            <?php if ($error): ?>
                <div class="error-message">
                    <i class="fas fa-exclamation-circle"></i>
                    <span><?php echo htmlspecialchars($error); ?></span>
                </div>
            <?php endif; ?>
            
            <form method="POST" action="login.php">
                <div class="form-group">
                    <label for="username">Identifiant</label>
                    <input 
                        type="text" 
                        id="username" 
                        name="username" 
                        required 
                        placeholder="Entrez votre identifiant"
                        autocomplete="username"
                    >
                </div>
                
                <div class="form-group">
                    <label for="password">Mot de passe</label>
                    <input 
                        type="password" 
                        id="password" 
                        name="password" 
                        required 
                        placeholder="••••••••"
                        autocomplete="current-password"
                    >
                </div>
                
                <a href="forgot-password.php" class="forgot-password">
                    <i class="fas fa-key"></i> Mot de passe oublié ?
                </a>
                
                <button type="submit" class="btn-login">
                    <i class="fas fa-sign-in-alt"></i> Se connecter
                </button>
            </form>
        </div>
    </div>
</body>
</html>
