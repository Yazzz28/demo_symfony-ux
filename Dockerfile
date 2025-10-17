# Dockerfile pour Symfony UX Demo avec FrankenPHP
FROM dunglas/frankenphp:latest

# Installation de Node.js 20.x
RUN apt-get update && apt-get install -y \
    curl \
    git \
    unzip \
    && curl -fsSL https://deb.nodesource.com/setup_20.x | bash - \
    && apt-get install -y nodejs \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

# Installation de Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Définir le répertoire de travail
WORKDIR /app

# Copier les fichiers de configuration pour les dépendances
COPY composer.json composer.lock symfony.lock package.json package-lock.json ./

# Installation des dépendances PHP
RUN composer install --no-scripts --prefer-dist --no-dev --optimize-autoloader

# Installation des dépendances NPM (sans build, sera fait au démarrage)
RUN npm ci --force

# Copier le reste du code source
COPY . .

# Créer les dossiers nécessaires et configurer les permissions
RUN mkdir -p /app/var/cache /app/var/log /app/public/build \
    && chown -R www-data:www-data /app/var /app/public/build \
    && chmod -R 775 /app/var /app/public/build

# Copier le Caddyfile personnalisé
COPY Caddyfile /etc/caddy/Caddyfile

# Copier et rendre exécutable le script entrypoint
COPY entrypoint.sh /usr/local/bin/entrypoint.sh
RUN chmod +x /usr/local/bin/entrypoint.sh

# Exposer le port
EXPOSE 80

# Variables d'environnement
ENV SERVER_NAME=:80

# Utiliser le script entrypoint
ENTRYPOINT ["/usr/local/bin/entrypoint.sh"]
