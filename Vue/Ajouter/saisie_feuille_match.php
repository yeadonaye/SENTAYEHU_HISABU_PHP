<?php include '../../Controleur/ajouter/saisie_feuille_match.php'; ?>

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
</head>
<body>
    <?php include '../Afficher/navbar.php'; ?>
    <!-- Page Header -->
    <div class="sheet-page-header">
        <div class="container-fluid">
            <h1>
                <i class="bi bi-clipboard2-data"></i> Saisie Feuille de Match
            </h1>
            <p>
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
                        $isIndispo = stripos($joueur['Statut'], 'bless') !== false || stripos($joueur['Statut'], 'suspend') !== false || stripos($joueur['Statut'], 'absent') !== false;
                        $badge = '';
                        if (stripos($joueur['Statut'], 'bless') !== false) {
                            $badge = 'BLESSÉ';
                        } elseif (stripos($joueur['Statut'], 'suspend') !== false) {
                            $badge = 'SUSPENDU';
                        } elseif (stripos($joueur['Statut'], 'absent') !== false) {
                            $badge = 'ABSENT';
                        }
                    ?>
                            <div class="joueur-card <?php echo $isIndispo ? 'injured' : ''; ?>" onclick="<?php echo !$isIndispo ? "toggleJoueur(this, 'titulaires', " . $joueurId . ")" : 'return false;'; ?>" <?php echo $isIndispo ? 'style="cursor: not-allowed;"' : ''; ?>>
                                <div class="flex-between">
                                    <input type="checkbox" name="titulaires[]" value="<?php echo $joueurId; ?>" 
                                        <?php echo $isTitulaire ? 'checked' : ''; ?> <?php echo $isIndispo ? 'disabled' : ''; ?>>
                                    <strong><?php echo htmlspecialchars($joueur['Nom'] . ' ' . $joueur['Prenom']); ?></strong>
                                    <?php if ($isIndispo): ?>
                                        <span class="injured-badge"><?php echo $badge; ?></span>
                                    <?php endif; ?>
                                </div>

                                <div class="joueur-info">
                                    <div><i class="bi bi-rulers"></i> Taille: <?php echo $joueur['Taille']; ?> m</div>
                                    <div><i class="bi bi-weight"></i> Poids: <?php echo $joueur['Poids']; ?> kg</div>
                                </div>

                                <select name="poste_titulaires[<?php echo $joueurId; ?>]" class="form-select poste-select" data-joueur-id="<?php echo $joueurId; ?>" data-type="titulaires">
                                    <option value="">-- Sélectionner un poste --</option>
                                    <?php foreach ($postes as $poste): ?>
                                        <option value="<?php echo $poste; ?>"
                                            <?php echo (isset($composition[$joueurId]) && $composition[$joueurId]['Poste'] === $poste) ? 'selected' : ''; ?>>
                                            <?php echo $poste; ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>

                                <div class="player-note-section">
                                    <label class="note-label">Note (0-5)</label>
                                    <input type="number" name="note[<?php echo $joueurId; ?>]" min="0" max="5" step="1" class="form-control" value="<?php echo isset($composition[$joueurId]) && $composition[$joueurId]['Note'] !== null ? (int)$composition[$joueurId]['Note'] : ''; ?>">
                                </div>

                                <?php if (isset($notes[$joueurId])): ?>
                                    <div class="note-container">
                                        <strong class="note-title">Évaluations:</strong>
                                        <?php foreach ($notes[$joueurId] as $note): ?>
                                            <span class="note-badge"><?php echo $note; ?>/5</span>
                                        <?php endforeach; ?>
                                    </div>
                                <?php endif; ?>

                                <?php if (isset($commentaires[$joueurId])): ?>
                                    <div class="note-container">
                                        <strong class="note-title">Commentaires récents:</strong>
                                        <?php foreach (array_slice($commentaires[$joueurId], 0, 2) as $com): ?>
                                            <div class="commentaire-item">
                                                "<?php echo htmlspecialchars(substr($com['Description'], 0, 60)); ?>..."
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                <?php endif; ?>
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
                        $isIndispo = stripos($joueur['Statut'], 'bless') !== false || stripos($joueur['Statut'], 'suspend') !== false || stripos($joueur['Statut'], 'absent') !== false;
                        $badge = '';
                        if (stripos($joueur['Statut'], 'bless') !== false) {
                            $badge = 'BLESSÉ';
                        } elseif (stripos($joueur['Statut'], 'suspend') !== false) {
                            $badge = 'SUSPENDU';
                        } elseif (stripos($joueur['Statut'], 'absent') !== false) {
                            $badge = 'ABSENT';
                        }
                    ?>
                            <div class="joueur-card <?php echo $isIndispo ? 'injured' : ''; ?>" onclick="<?php echo !$isIndispo ? "toggleJoueur(this, 'remplacants', " . $joueurId . ")" : 'return false;'; ?>" <?php echo $isIndispo ? 'style="cursor: not-allowed;"' : ''; ?>>
                                <div class="flex-between">
                                    <input type="checkbox" name="remplacants[]" value="<?php echo $joueurId; ?>" 
                                        <?php echo $isRemplacant ? 'checked' : ''; ?> <?php echo $isIndispo ? 'disabled' : ''; ?>>
                                    <strong><?php echo htmlspecialchars($joueur['Nom'] . ' ' . $joueur['Prenom']); ?></strong>
                                    <?php if ($isIndispo): ?>
                                        <span class="injured-badge"><?php echo $badge; ?></span>
                                    <?php endif; ?>
                                </div>

                                <div class="joueur-info">
                                    <div><i class="bi bi-rulers"></i> Taille: <?php echo $joueur['Taille']; ?> m</div>
                                    <div><i class="bi bi-weight"></i> Poids: <?php echo $joueur['Poids']; ?> kg</div>
                                </div>

                                <select name="poste_remplacants[<?php echo $joueurId; ?>]" class="form-select poste-select" data-joueur-id="<?php echo $joueurId; ?>" data-type="remplacants">
                                    <option value="">-- Sélectionner un poste --</option>
                                    <?php foreach ($postes as $poste): ?>
                                        <option value="<?php echo $poste; ?>"
                                            <?php echo (isset($composition[$joueurId]) && $composition[$joueurId]['Poste'] === $poste) ? 'selected' : ''; ?>>
                                            <?php echo $poste; ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>

                                <div class="player-note-section">
                                    <label class="note-label">Note (0-5)</label>
                                    <input type="number" name="note[<?php echo $joueurId; ?>]" min="0" max="5" step="1" class="form-control" value="<?php echo isset($composition[$joueurId]) && $composition[$joueurId]['Note'] !== null ? (int)$composition[$joueurId]['Note'] : ''; ?>">
                                </div>

                                <?php if (isset($notes[$joueurId])): ?>
                                    <div class="note-container">
                                        <strong class="note-title">Évaluations:</strong>
                                        <?php foreach ($notes[$joueurId] as $note): ?>
                                            <span class="note-badge"><?php echo $note; ?>/5</span>
                                        <?php endforeach; ?>
                                    </div>
                                <?php endif; ?>

                                <?php if (isset($commentaires[$joueurId])): ?>
                                    <div class="note-container">
                                        <strong class="note-title">Commentaires récents:</strong>
                                        <?php foreach (array_slice($commentaires[$joueurId], 0, 2) as $com): ?>
                                            <div class="commentaire-item">
                                                "<?php echo htmlspecialchars(substr($com['Description'], 0, 60)); ?>..."
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- ACTIONS -->
            <div style="display: flex; gap: 1rem; justify-content: space-between; margin-top: 2rem;">
                <a href="/Vue/Afficher/afficher_match.php" class="btn btn-secondary">
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

    <?php include '../Afficher/footer.php'; ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function toggleJoueur(element, type, joueurId) {
            const checkbox = element.querySelector('input[type="checkbox"]');
            const othersContainer = type === 'titulaires' ? document.getElementById('remplacants-container') : document.getElementById('titulaires-container');
            
            checkbox.checked = !checkbox.checked;
            updateCardStyle(element, checkbox.checked);
            updatePosteValidation(checkbox.checked, type, joueurId);
            
            // Si on sélectionne un joueur, le désactiver dans l'autre section
            if (checkbox.checked) {
                const otherCheckbox = othersContainer.querySelector(`input[value="${joueurId}"]`);
                if (otherCheckbox) {
                    otherCheckbox.checked = false;
                    const otherCard = otherCheckbox.closest('.joueur-card');
                    updateCardStyle(otherCard, false);
                    otherCard.classList.add('disabled-player');
                    const otherType = type === 'titulaires' ? 'remplacants' : 'titulaires';
                    updatePosteValidation(false, otherType, joueurId);
                }
            } else {
                const otherCheckbox = othersContainer.querySelector(`input[value="${joueurId}"]`);
                if (otherCheckbox) {
                    const otherCard = otherCheckbox.closest('.joueur-card');
                    otherCard.classList.remove('disabled-player');
                }
            }
            
            updateCounter();
            validateForm();
        }

        function updatePosteValidation(isSelected, type, joueurId) {
            const posteSelect = document.querySelector(`select[name="poste_${type}[${joueurId}]"]`);
            if (posteSelect) {
                if (isSelected) {
                    posteSelect.setAttribute('required', 'required');
                } else {
                    posteSelect.removeAttribute('required');
                    posteSelect.value = '';
                }
            }
        }

        function updateCardStyle(element, isChecked) {
            if (isChecked) {
                element.classList.add('selected');
            } else {
                element.classList.remove('selected');
            }
        }

        function updateCounter() {
            const titulaires = document.querySelectorAll('input[name="titulaires[]"]:checked').length;
            document.getElementById('count-value').textContent = titulaires;
        }

        function validateForm() {
            const titulaires = document.querySelectorAll('input[name="titulaires[]"]:checked').length;
            const submitBtn = document.getElementById('submit-btn');
            let posteValid = true;
            
            // Valider que tous les joueurs sélectionnés ont un poste
            document.querySelectorAll('input[type="checkbox"]:checked').forEach(checkbox => {
                const joueurId = checkbox.value;
                const type = checkbox.name;
                const posteSelect = document.querySelector(`select[name="poste_${type}[${joueurId}]"]`);
                if (posteSelect && posteSelect.value === '') {
                    posteValid = false;
                }
            });
            
            if (titulaires >= 11 && posteValid) {
                submitBtn.disabled = false;
                submitBtn.classList.remove('btn-danger');
            } else {
                submitBtn.disabled = true;
                submitBtn.classList.add('btn-danger');
            }
        }

        // Initialiser au chargement de la page
        document.addEventListener('DOMContentLoaded', () => {
            // Initialiser les cartes sélectionnées
            document.querySelectorAll('.joueur-card input[type="checkbox"]:checked').forEach(checkbox => {
                const joueurId = checkbox.value;
                const type = checkbox.name;
                updateCardStyle(checkbox.closest('.joueur-card'), true);
                updatePosteValidation(true, type, joueurId);
            });
            
            // Désactiver les doublons entre titulaires et remplaçants
            const titulairesContainer = document.getElementById('titulaires-container');
            const remplacants = document.getElementById('remplacants-container');
            
            document.querySelectorAll('input[name="titulaires[]"]:checked').forEach(checkbox => {
                const joueurId = checkbox.value;
                const otherCheckbox = remplacants.querySelector(`input[value="${joueurId}"]`);
                if (otherCheckbox) {
                    const otherCard = otherCheckbox.closest('.joueur-card');
                    otherCard.classList.add('disabled-player');
                }
            });
            
            // Ajouter les event listeners sur les checkboxes
            document.querySelectorAll('input[type="checkbox"]').forEach(checkbox => {
                checkbox.addEventListener('change', validateForm);
            });
            
            // Ajouter les event listeners sur les selects de poste
            document.querySelectorAll('select[class*="poste-select"]').forEach(select => {
                select.addEventListener('change', validateForm);
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
