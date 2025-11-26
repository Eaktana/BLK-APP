FROM php:8.2-cli

# ติดตั้ง extension ที่ Laravel ใช้บ่อย
RUN apt-get update && apt-get install -y \
    git unzip libzip-dev libpng-dev libonig-dev libxml2-dev sqlite3 libsqlite3-dev \
    && docker-php-ext-install pdo_mysql pdo_sqlite mbstring zip gd

# ติดตั้ง Composer (ดึงจาก image Composer โดยตรง)
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# โฟลเดอร์ทำงานใน container
WORKDIR /app

# ก๊อปโปรเจกต์เข้า container
COPY . .

# ติดตั้ง dependency ของ Laravel
RUN composer install --no-dev --optimize-autoloader

# คำสั่งตอน container รัน
# Render จะส่งค่า $PORT มาให้ เราก็เอามาใช้เลย
CMD php artisan serve --host 0.0.0.0 --port=${PORT:-8000}
