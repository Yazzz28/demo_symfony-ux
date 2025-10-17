# Symfony UX Demo - Docker avec FrankenPHP

Ce projet est une démonstration des composants Symfony UX (Turbo, Chart.js, Map, Stimulus) fonctionnant avec FrankenPHP dans un container Docker.

## Prérequis

- Docker
- Docker Compose

## Services disponibles

Le projet utilise Docker Compose avec les services suivants:

- **app** : Application Symfony avec FrankenPHP (PHP 8.2)
- **db** : Base de données MySQL 8.0
- **phpmyadmin** : Interface web pour gérer la base de données

## Installation et démarrage

### 1. Construire et démarrer les containers

```bash
docker-compose up -d --build
```

### 2. Accéder à l'application

- **Application Symfony UX** : http://localhost:8000
- **phpMyAdmin** : http://localhost:8080

### 3. Pages de démonstration

Une fois l'application démarrée, vous pouvez accéder aux pages suivantes:

- **Accueil** : http://localhost:8000/ux-demo
- **Chart.js** : http://localhost:8000/ux-demo/chartjs
- **UX Map** : http://localhost:8000/ux-demo/map
- **Turbo** : http://localhost:8000/ux-demo/turbo
- **Stimulus** : http://localhost:8000/ux-demo/stimulus

## Commandes utiles

### Arrêter les containers

```bash
docker-compose down
```

### Voir les logs

```bash
# Tous les services
docker-compose logs -f

# Uniquement l'application
docker-compose logs -f app
```

### Accéder au shell du container

```bash
docker-compose exec app sh
```

### Rebuilder les assets

Si vous modifiez les fichiers JavaScript ou CSS:

```bash
docker-compose exec app npm run build
```

### Exécuter des commandes Symfony

```bash
# Cache clear
docker-compose exec app php bin/console cache:clear

# Liste des routes
docker-compose exec app php bin/console debug:router
```

## Structure du projet

```
.
├── Dockerfile              # Image FrankenPHP avec Node.js
├── docker-compose.yml      # Configuration des services
├── Caddyfile              # Configuration du serveur Caddy/FrankenPHP
├── .dockerignore          # Fichiers à exclure du build
├── src/
│   └── Controller/
│       └── UxDemoController.php
├── templates/
│   └── ux_demo/
│       ├── index.html.twig
│       ├── chartjs.html.twig
│       ├── map.html.twig
│       ├── turbo.html.twig
│       └── stimulus.html.twig
└── assets/
    └── controllers/
        ├── counter_controller.js
        ├── slideshow_controller.js
        └── accordion_controller.js
```

## Fonctionnalités démontrées

### Chart.js
- Graphiques en ligne
- Graphiques en barres
- Graphiques circulaires
- Graphiques en anneau

### UX Map
- Carte interactive avec Leaflet
- Marqueurs personnalisés
- Info-bulles sur les points d'intérêt

### Turbo
- Navigation rapide sans rechargement complet
- Turbo Frames pour chargement dynamique
- Démonstration de Turbo Drive

### Stimulus
- Compteur interactif
- Slideshow
- Accordéon

## Technologies utilisées

- **Symfony 6.4** : Framework PHP
- **FrankenPHP** : Serveur d'application PHP moderne
- **Caddy** : Serveur web intégré
- **Node.js 20** : Pour la compilation des assets
- **Webpack Encore** : Bundler d'assets
- **Symfony UX** : Composants UI modernes
- **MySQL 8.0** : Base de données

## Développement

Pour le développement, vous pouvez utiliser le mode watch pour recompiler automatiquement les assets:

```bash
docker-compose exec app npm run watch
```

## Production

Pour un déploiement en production, modifiez le fichier `.env`:

```env
APP_ENV=prod
APP_DEBUG=0
```

Et reconstruisez les containers:

```bash
docker-compose up -d --build
```

## Dépannage

### Les assets ne se chargent pas

Vérifiez que les assets ont été compilés:

```bash
docker-compose exec app ls -la public/build
```

Si le dossier est vide, recompilez:

```bash
docker-compose exec app npm run build
```

### Erreur de permissions

Si vous rencontrez des erreurs de permissions:

```bash
docker-compose exec app chown -R www-data:www-data /app/var
docker-compose exec app chmod -R 775 /app/var
```

### La base de données ne se connecte pas

Assurez-vous que le service MySQL est démarré:

```bash
docker-compose ps
```

Vérifiez les logs:

```bash
docker-compose logs db
```

## Support

Pour toute question ou problème, consultez la documentation Symfony UX:
- https://ux.symfony.com/
