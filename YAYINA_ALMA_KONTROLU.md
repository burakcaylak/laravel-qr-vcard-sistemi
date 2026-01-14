# Yayına Alma Kontrol Listesi ✅

## 🔒 Güvenlik Kontrolleri

### ✅ Tamamlananlar

1. **robots.txt** ✅
   - Tüm arama motorları engellendi: `Disallow: /`
   - Dosya: `public/robots.txt`

2. **Meta Robots Tag** ✅
   - `noindex, nofollow, noarchive, nosnippet` eklendi
   - Dosya: `resources/views/layout/master.blade.php`

3. **Seeder Production Koruması** ✅
   - `ResetSystemSeeder`: Production'da çalıştırılamaz
   - `DatabaseSeeder`: Production'da sadece gerekli seeder'lar çalışır
   - `UsersSeeder`: Production'da demo kullanıcıları oluşturulmaz

4. **.env Dosyası** ✅
   - `.gitignore`'da mevcut (commit edilmeyecek)

5. **Debug Mode** ✅
   - `config/app.php`: `APP_DEBUG` varsayılan `false`
   - Production'da `APP_DEBUG=false` olmalı

## 📋 Yayına Almadan Önce Yapılması Gerekenler

### 1. .env Dosyası Ayarları

Production sunucuda `.env` dosyasını oluşturun ve şu ayarları yapın:

```env
APP_NAME="WM Dosya&QR Yönetimi"
APP_ENV=production
APP_DEBUG=false
APP_URL=https://yourdomain.com

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=your_database
DB_USERNAME=your_username
DB_PASSWORD=your_password

# Güvenlik
APP_KEY=base64:... (php artisan key:generate ile oluşturun)

# Session
SESSION_DRIVER=database
SESSION_LIFETIME=120
SESSION_ENCRYPT=true
SESSION_EXPIRE_ON_CLOSE=false

# Cache
CACHE_DRIVER=file
QUEUE_CONNECTION=database

# Mail (gerekirse)
MAIL_MAILER=smtp
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=null
MAIL_PASSWORD=null
MAIL_ENCRYPTION=null
MAIL_FROM_ADDRESS="noreply@yourdomain.com"
MAIL_FROM_NAME="${APP_NAME}"
```

### 2. Composer ve NPM Bağımlılıkları

```bash
# Production bağımlılıklarını yükle (dev paketleri hariç)
composer install --optimize-autoloader --no-dev

# NPM bağımlılıklarını yükle
npm install --production

# Assets'i derle
npm run production
```

### 3. Laravel Optimizasyonları

```bash
# Config cache
php artisan config:cache

# Route cache
php artisan route:cache

# View cache
php artisan view:cache

# Event cache (eğer varsa)
php artisan event:cache
```

### 4. Dosya İzinleri

```bash
# Storage ve cache klasörlerine yazma izni ver
chmod -R 775 storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache
```

### 5. Database Migration

```bash
# Migration'ları çalıştır (seeders çalıştırma!)
php artisan migrate --force
```

### 6. Storage Link

```bash
# Public storage link'i oluştur
php artisan storage:link
```

### 7. Queue Worker (Opsiyonel)

Eğer queue kullanacaksanız, supervisor veya systemd ile queue worker'ı başlatın:

```bash
php artisan queue:work --daemon
```

### 8. SSL Sertifikası

- HTTPS kullanın
- `.env` dosyasında `APP_URL=https://yourdomain.com` olmalı

### 9. Güvenlik Kontrolleri

- [ ] `.env` dosyası public klasöründe değil
- [ ] `APP_DEBUG=false`
- [ ] `APP_ENV=production`
- [ ] Güçlü `APP_KEY` oluşturuldu
- [ ] Database şifreleri güçlü
- [ ] Default kullanıcı şifresi değiştirildi
- [ ] Gereksiz dosyalar silindi (test, demo, vb.)

### 10. Performans

- [ ] Config cache aktif
- [ ] Route cache aktif
- [ ] View cache aktif
- [ ] Opcache aktif (PHP)
- [ ] CDN kullanımı (opsiyonel)

## 🚫 Production'da YAPILMAMASI Gerekenler

1. ❌ `php artisan db:seed` (test verileri oluşturur)
2. ❌ `php artisan db:seed --class=ResetSystemSeeder` (sistem sıfırlar)
3. ❌ `php artisan db:seed --class=UsersSeeder` (demo kullanıcıları oluşturur)
4. ❌ `APP_DEBUG=true` (güvenlik riski)
5. ❌ `.env` dosyasını commit etmek
6. ❌ Test dosyalarını production'a yüklemek

## 📝 Notlar

- Seeder'lar production'da korumalıdır
- robots.txt tüm arama motorlarını engeller
- Meta robots tag ile ekstra koruma sağlanır
- Production'da sadece gerekli seeder'lar çalışır (RolesPermissionsSeeder)

## ✅ Sistem Hazır!

Tüm güvenlik kontrolleri tamamlandı. Sistem yayına hazır.
