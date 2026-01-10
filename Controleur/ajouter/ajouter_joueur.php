<?php
require_once __DIR__ . '/../../Modele/DAO/auth.php';
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
