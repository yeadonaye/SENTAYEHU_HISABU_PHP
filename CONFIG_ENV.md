# Configuration des variables d'environnement

## Configuration pour le développement local (XAMPP)

Les valeurs par défaut dans `config.php` sont configurées pour XAMPP :
- Host: 127.0.0.1
- User: root
- Password: (vide)
- Database: gestion_joueurs

Aucune configuration supplémentaire n'est nécessaire pour le développement local.

## Configuration pour la production (AlwaysData)

Pour déployer sur AlwaysData ou tout autre serveur de production, vous devez définir les variables d'environnement suivantes :

### Option 1 : Variables d'environnement du serveur

Sur AlwaysData, allez dans votre panneau de configuration et définissez les variables d'environnement :

```
DB_TYPE=mysql
DB_HOST=mysql-yeadonaye.alwaysdata.net
DB_PORT=3306
DB_NAME=yeadonaye_bd_gestion_equipe
DB_USER=yeadonaye
DB_PASS=votre_mot_de_passe_securise_ici
DB_CHARSET=utf8mb4
```

### Option 2 : Fichier .env (non recommandé pour la production)

1. Copiez le fichier `.env.example` en `.env`
2. Modifiez les valeurs dans `.env` avec vos informations de production
3. Le fichier `.env` est dans `.gitignore` et ne sera pas commité

## Sécurité

⚠️ **IMPORTANT** : Ne commitez jamais vos identifiants de base de données dans le code source. Utilisez toujours des variables d'environnement pour les informations sensibles.
