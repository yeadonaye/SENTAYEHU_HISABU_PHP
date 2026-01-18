# ReadMe

## 📋 À propos du projet

Application de gestion d'une équipe de football développée en PHP. L'application permet de gérer les joueurs, les matchs, les performances, les commentaires, et de consulter des statistiques détaillées pour aider l'entraîneur dans la prise de décision.

## 👥 Membres du groupe

- **HISABU Nathan Tekeste**
- **SENTAYEHU Yeadonaye Ashenafi**
- **ASHENAFI Magadiyev Imam**

## 🌐 Liens

- **Site Web** : yeadonaye.alwaysdata.net
- **GitHub** : (https://github.com/yeadonaye/SENTAYEHU_HISABU_PHP.git)

## 🛠️ Environnement technique

- **Langage** : PHP 8+
- **Architecture** : MVC (Modèle-Vue-Contrôleur)
- **Base de données** : MySQL via PDO
- **Interface** : Web responsive avec Bootstrap 5

## 📁 Structure du projet

```
SENTAYEHU_HISABU_PHP/
├── Controleur/
│   ├── afficher       # Contrôleurs pour afficher les données
│   ├── ajouter        # Contrôleurs pour ajouter des données
│   ├── modifier       # Contrôleurs pour modifier des données
│   └── suppirmer      # Contrôleurs pour supprimer des données
├── data               # Fichiers de base de données ou exports
├── Modele/
│   └── DAO            # Classes DAO pour accès aux données
└── Vue/
    ├── Afficher       # Pages de vue pour l'affichage
    ├── Ajouter        # Pages de vue pour l'ajout
    ├── CSS            # Fichiers CSS
    ├── img            # Images utilisées
    └── Modifier       # Pages de vue pour la modification
```

## 🎯 Fonctionnalités principales

- Gestion des joueurs (ajout, modification, suppression, affichage)
- Gestion des matchs (ajout, modification, résultat)
- Ajout de commentaires sur les joueurs et suivi de leur statut (Actif, Blessé, Suspendu, Absent)
- Constitution des feuilles de matchs avec titulaires et remplaçants
- Évaluation des performances des joueurs après chaque match
- Statistiques globales et individuelles pour aider l'entraîneur

## 🚀 Notes

- Les dates doivent être saisies au format `jj/mm/aaaa`
- L'accès à l'application nécessite une authentification
- L'application utilise le pattern MVC pour séparer la logique métier, la présentation et le contrôle des actions