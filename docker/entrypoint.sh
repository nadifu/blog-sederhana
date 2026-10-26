#!/bin/sh
set -e

echo "=== Memulai Inisialisasi Container Laravel Blog ==="

# 1. Pastikan APP_KEY sudah diisi
if [ -z "$APP_KEY" ]; then
    echo "[Entrypoint] APP_KEY belum disetel. Membuat APP_KEY otomatis..."
    php artisan key:generate --force
fi

# 2. Membuat direktori storage & mengatur permission
echo "[Entrypoint] Menyiapkan direktori penyimpanan..."
mkdir -p /var/www/html/storage/framework/cache/data \
         /var/www/html/storage/framework/sessions \
         /var/www/html/storage/framework/views \
         /var/www/html/storage/logs \
         /var/www/html/bootstrap/cache

chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache
chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# 3. Menunggu MySQL siap menerima koneksi
DB_HOST="${DB_HOST:-database}"
DB_PORT="${DB_PORT:-3306}"
RETRIES="${DB_WAIT_RETRIES:-30}"
INTERVAL="${DB_WAIT_INTERVAL:-2}"

echo "[Entrypoint] Menunggu database MySQL pada $DB_HOST:$DB_PORT..."
until nc -z "$DB_HOST" "$DB_PORT"; do
    RETRIES=$((RETRIES - 1))
    if [ "$RETRIES" -le 0 ]; then
        echo "[Entrypoint] ERROR: Timeout menunggu MySQL pada $DB_HOST:$DB_PORT."
        exit 1
    fi
    echo "[Entrypoint] Menunggu MySQL siap... ($RETRIES percobaan tersisa)"
    sleep "$INTERVAL"
done
echo "[Entrypoint] Database MySQL siap dan terhubung!"

# 4. Membersihkan cache lama
echo "[Entrypoint] Membersihkan cache aplikasi..."
php artisan config:clear || true
php artisan route:clear || true
php artisan view:clear || true

# 5. Menjalankan migrasi apabila RUN_MIGRATIONS=true
if [ "$RUN_MIGRATIONS" = "true" ]; then
    echo "[Entrypoint] Menjalankan migrasi database..."
    php artisan migrate --force
fi

# 6. Menjalankan seeder apabila RUN_SEEDERS=true
if [ "$RUN_SEEDERS" = "true" ]; then
    echo "[Entrypoint] Menjalankan seeder database..."
    if [ -n "$SEEDER_CLASS" ]; then
        php artisan db:seed --class="$SEEDER_CLASS" --force
    else
        php artisan db:seed --force
    fi
fi

# 7. Membuat symbolic link public/storage
echo "[Entrypoint] Membuat symlink storage..."
php artisan storage:link --force || true

# 8. Membuat cache konfigurasi, route, dan view jika environment production
if [ "$APP_ENV" = "production" ]; then
    echo "[Entrypoint] Mengoptimalkan cache untuk mode production..."
    php artisan config:cache || true
    php artisan route:cache || true
    php artisan view:cache || true
fi

echo "=== Inisialisasi Selesai, Menjalankan Layanan ==="

# 9. Menjalankan Supervisor
exec "$@"
