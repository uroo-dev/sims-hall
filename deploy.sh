#!/bin/bash

set -e

APP_DIR="${APP_DIR:-/home/gajagohosting/apps/sims-hall}"
PHP="/usr/local/apps/php84/bin/php"
if [ ! -x "$PHP" ]; then
    PHP=$(command -v php)
fi

COMPOSER="/home/gajagohosting/composer.phar"
if [ ! -f "$COMPOSER" ]; then
    COMPOSER=$(command -v composer)
fi

WEB_ROOT="${WEB_ROOT:-/home/gajagohosting/public_html}"
TARGET_BRANCH="${1:-${DEPLOY_BRANCH:-main}}"

cd "$APP_DIR"

echo "=== DEPLOY SIMS-HALL (Branch: $TARGET_BRANCH) ==="

echo ">> Maintenance mode"
"$PHP" artisan down || true

cleanup() {
    echo ">> Disable maintenance mode"
    "$PHP" artisan up || true
}

trap cleanup EXIT

echo ">> Update source from origin/$TARGET_BRANCH"
git fetch origin "$TARGET_BRANCH"
git switch -C "$TARGET_BRANCH" "origin/$TARGET_BRANCH"

echo ">> Install Composer dependencies"
if [ -f "$COMPOSER" ] && [[ "$COMPOSER" == *.phar ]]; then
    "$PHP" "$COMPOSER" install --no-dev --no-interaction --prefer-dist --optimize-autoloader
else
    composer install --no-dev --no-interaction --prefer-dist --optimize-autoloader
fi

echo ">> Run migrations"
"$PHP" artisan migrate --force

echo ">> Ensure storage link"
"$PHP" artisan storage:link || true

echo ">> Clear cache"
"$PHP" artisan optimize:clear

echo ">> Cache Laravel config, events, views"
"$PHP" artisan config:cache
"$PHP" artisan event:cache
"$PHP" artisan view:cache
rm -f bootstrap/cache/routes-v7.php

echo ">> Reset Web OPcache & Sync Web Root"
if [ -d "$WEB_ROOT" ]; then
    RESET_TOKEN=$(head -c 16 /dev/urandom | od -An -tx1 | tr -d ' \n')
    echo "<?php if (isset(\$_GET['token']) && \$_GET['token'] === '$RESET_TOKEN') { opcache_reset(); echo 'OK'; } unlink(__FILE__);" > "$WEB_ROOT/_opcache_reset_$RESET_TOKEN.php"
    curl -s -k "https://gajagohosting.my.id/_opcache_reset_$RESET_TOKEN.php?token=$RESET_TOKEN" >/dev/null 2>&1 || true
    rm -f "$WEB_ROOT/_opcache_reset_$RESET_TOKEN.php"

    if [ -f "$APP_DIR/public/robots.txt" ]; then
        cp "$APP_DIR/public/robots.txt" "$WEB_ROOT/robots.txt"
    fi
fi

echo ">> Verifikasi route kunci terdaftar"
"$PHP" -r '
require "vendor/autoload.php";
$app = require "bootstrap/app.php";
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();
$missing = [];
foreach (["login", "registrasi", "dashboard"] as $name) {
    if ($app->make("router")->getRoutes()->getByName($name) === null) { $missing[] = $name; }
}
if ($missing) {
    fwrite(STDERR, "FATAL: route hilang: ".implode(", ", $missing).PHP_EOL);
    exit(1);
}
echo "OK: route login, registrasi, dashboard terdaftar".PHP_EOL;
'

echo "=== DEPLOY SELESAI ==="
