#!/bin/bash
set -e

echo "================================================"
echo "Starting Symfony UX Demo Container"
echo "================================================"

# Vérifier si node_modules existe, sinon installer les dépendances
if [ ! -d "node_modules" ]; then
    echo "📦 Installing npm dependencies..."
    npm install
else
    echo "✅ node_modules already exists"
fi

# Vérifier si le fichier entrypoints.json existe, sinon compiler les assets
if [ ! -f "public/build/entrypoints.json" ]; then
    echo "🔨 Building assets with Webpack Encore..."
    npm run build
else
    echo "✅ Assets already compiled"
fi

# Vérifier si le dossier var existe et définir les permissions
if [ -d "var" ]; then
    echo "🔧 Setting permissions for var directory..."
    chown -R www-data:www-data var
    chmod -R 775 var
fi

# Clear and warm up the Symfony cache
if [ "$APP_ENV" != "prod" ]; then
    echo "🧹 Clearing Symfony cache..."
    php bin/console cache:clear --no-warmup || true
    echo "🔥 Warming up Symfony cache..."
    php bin/console cache:warmup || true
fi

echo "================================================"
echo "🚀 Starting FrankenPHP server..."
echo "================================================"

# Démarrer FrankenPHP
exec frankenphp run --config /etc/caddy/Caddyfile
