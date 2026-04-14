Got it — here is the corrected version with **no coach/player roles** and the **only valid login updated**.

---

# ⚽ Liverpool FC Manager – Frontend

---

## 📌 Présentation du projet

Le Frontend Liverpool FC Manager est une interface web conçue pour la gestion d’une équipe de football.

Il permet aux utilisateurs d’interagir avec des API backend pour gérer :

* l’authentification 🔐
* les joueurs 👥
* les matchs ⚽
* les statistiques 📊

Ce projet fait partie d’un système distribué en 3 couches :

* 🖥️ Frontend (ce dépôt)
* ⚙️ Backend REST API
* 🔐 API d’authentification JWT

L’application est hébergée sur AlwaysData et communique avec des APIs sécurisées via JWT.

---

## 🚀 Application en ligne

* 🌐 Frontend : [https://liverpool.alwaysdata.net/](https://liverpool.alwaysdata.net/)
* 🔐 Auth API : [https://liverpoolapi.alwaysdata.net/authapi.php](https://liverpoolapi.alwaysdata.net/authapi.php)
* ⚙️ Backend API : [https://yeadonaye.alwaysdata.net/Routes/](https://yeadonaye.alwaysdata.net/Routes/)

---

## 🧑‍💻 Projet d’équipe

Développé dans le cadre d’un projet universitaire :

🎓 IUT Paul Sabatier – Toulouse

👥 Contributeurs

* Nathan HISABU
* Yeadonaye SENTAYEHU

---

## 🎯 Fonctionnalités principales

---

## 🔐 AUTHENTIFICATION (JWT)

* Connexion sécurisée via JWT
* Authentification simple (aucun rôle utilisateur)
* Validation des sessions via API
* Accès aux fonctionnalités selon la validité du token

---

## 👥 GESTION DES JOUEURS

* Liste des joueurs
* Ajout de joueurs
* Modification des informations
* Suppression de joueurs

📌 Données des joueurs :

* Nom / Prénom
* Date de naissance
* Taille / poids
* Statut (disponible, blessé, etc.)

---

## ⚽ GESTION DES MATCHS

* Voir tous les matchs
* Créer des matchs
* Modifier les résultats
* Supprimer des matchs

📌 Détails d’un match :

* Équipe adverse
* Score
* Date et heure
* Lieu

---

## 📋 FEUILLE DE MATCH

* Attribution des joueurs aux matchs
* Composition :

  * 11 titulaires
  * Remplaçants
  * Postes des joueurs
* Notes de performance
* Mise à jour dynamique

---

## 📊 TABLEAU DE STATISTIQUES

📌 Équipe :

* Nombre total de matchs
* Victoires / défaites / nuls
* Buts marqués / encaissés
* Taux de victoire

📌 Joueurs :

* Matchs joués
* Note moyenne
* Taux de participation
* Performance globale

---

## 🏗️ ARCHITECTURE DU SYSTÈME

Frontend (ce dépôt)
↓
Backend REST API (PHP)
↓
Base de données (MariaDB sur AlwaysData)

---

## 🔗 SERVICES CONNECTÉS

| Composant      | Description           | URL                                                                                                |
| -------------- | --------------------- | -------------------------------------------------------------------------------------------------- |
| 🖥️ Frontend   | Interface utilisateur | [https://liverpool.alwaysdata.net/](https://liverpool.alwaysdata.net/)                             |
| ⚙️ Backend API | Logique métier (CRUD) | [https://yeadonaye.alwaysdata.net/Routes/](https://yeadonaye.alwaysdata.net/Routes/)               |
| 🔐 Auth API    | Authentification JWT  | [https://liverpoolapi.alwaysdata.net/authapi.php](https://liverpoolapi.alwaysdata.net/authapi.php) |

---

## 🔐 FLUX D’AUTHENTIFICATION

1. L’utilisateur se connecte via le frontend
2. Les identifiants sont envoyés à l’Auth API
3. Un token JWT est généré
4. Le token est stocké dans le frontend
5. Chaque requête inclut :

Authorization: Bearer <JWT_TOKEN>

6. Le backend valide le token avant traitement

---

## 🧠 STACK TECHNIQUE

### Frontend

* HTML5
* CSS3
* JavaScript (Vanilla / framework selon implémentation)

### Backend Communication

* REST APIs (PHP)
* JSON
* JWT authentication

### DevOps

* AlwaysData hosting
* GitHub Actions (CI/CD)
* FTP auto-deployment

---

## 🔄 PIPELINE CI/CD

* Push sur la branche `main`
* GitHub Actions déclenche le workflow
* Déploiement automatique via FTP
* Mise à jour instantanée du site

---

## 🧪 COMPTES DE TEST

### 🔐 Compte admin

* login: admin
* password: $iutinfo

---

## 🔑 RESPONSABILITÉS DU FRONTEND

Ce dépôt gère :

* Interface utilisateur
* Communication API
* Stockage du token JWT
* Affichage des données
* Visualisation des statistiques

Il ne fait pas :

* Stockage de base de données
* Logique métier
* Vérification d’authentification côté client (backend only)

---

## 📌 EXEMPLES D’API

### Login Request

POST /authapi.php
Content-Type: application/json

{
"login": "admin",
"password": "$iutinfo"
}

---

### Authenticated Request

GET /Routes/joueurapi.php
Authorization: Bearer <JWT_TOKEN>

---

## ⚠️ REMARQUES IMPORTANTES

* Token valide : 1 heure
* Validation uniquement côté backend
* Frontend ne vérifie pas les tokens localement
* CORS activé uniquement pour le frontend

---

## 📊 OBJECTIFS DU PROJET

Ce projet démontre :

* Développement full-stack
* Intégration API REST
* Authentification JWT
* Architecture multi-repos
* CI/CD automatisé
* Organisation projet réel

---

## 📈 AMÉLIORATIONS FUTURES

* Migration React / Vue
* Amélioration UI/UX
* Live match updates
* WebSockets (temps réel)
* Dashboard analytics avancé

---

## 📫 AUTEURS

* Nathan HISABU
* Yeadonaye SENTAYEHU
