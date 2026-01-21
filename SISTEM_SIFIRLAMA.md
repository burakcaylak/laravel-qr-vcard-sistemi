# 🔄 Sistem Sıfırlama Rehberi

## ⚠️ ÖNEMLİ UYARI

Bu işlemler **TÜM VERİLERİ SİLECEKTİR**. Production ortamında kullanmayın!

## 📋 Adım Adım Sıfırlama

### 1. Database Bağlantısını Kontrol Edin

`.env` dosyasını kontrol edin ve database bilgilerinin doğru olduğundan emin olun:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=your_database_name
DB_USERNAME=your_username
DB_PASSWORD=your_password
```

**VEYA** SQLite kullanmak için:

```env
DB_CONNECTION=sqlite
DB_DATABASE=database/database.sqlite
```

### 2. Database'i Sıfırlayın

#### Seçenek A: ResetSystemSeeder Kullanarak (Önerilen)

```bash
php artisan db:seed --class=ResetSystemSeeder
```

Bu komut:
- ✅ Tüm tabloları temizler
- ✅ Storage dosyalarını siler
- ✅ Default kullanıcı oluşturur
- ✅ Rolleri ve izinleri korur

#### Seçenek B: Migration Fresh (Tamamen Sıfırlama)

```bash
# Tüm tabloları sil ve yeniden oluştur
php artisan migrate:fresh

# Rolleri ve izinleri oluştur
php artisan db:seed --class=RolesPermissionsSeeder

# Default kullanıcı oluştur (opsiyonel)
php artisan db:seed --class=UsersSeeder
```

### 3. Storage'ı Temizleyin

#### Otomatik Temizleme (Komut ile)

```bash
php artisan production:clean --force
```

#### Manuel Temizleme

Aşağıdaki klasörleri temizleyin (içindeki dosyaları silin):

```bash
# Windows PowerShell
Remove-Item -Recurse -Force "storage\app\public\files\*"
Remove-Item -Recurse -Force "storage\app\public\qr-codes\*"
Remove-Item -Recurse -Force "storage\app\public\brochures\*"
Remove-Item -Recurse -Force "storage\app\public\short-links\*"
Remove-Item -Recurse -Force "storage\app\public\settings\*"
Remove-Item -Recurse -Force "storage\app\public\vcard_images\*"

# Linux/Mac
rm -rf storage/app/public/files/*
rm -rf storage/app/public/qr-codes/*
rm -rf storage/app/public/brochures/*
rm -rf storage/app/public/short-links/*
rm -rf storage/app/public/settings/*
rm -rf storage/app/public/vcard_images/*
```

**ÖNEMLİ:** `.gitignore` dosyalarını silmeyin!

### 4. Cache'leri Temizleyin

```bash
php artisan optimize:clear
```

### 5. Storage Link'ini Yeniden Oluşturun

```bash
# Mevcut link'i sil
rm public/storage  # Linux/Mac
# veya
Remove-Item public\storage  # Windows PowerShell

# Yeni link oluştur
php artisan storage:link
```

### 6. Cache'leri Yeniden Oluşturun

```bash
php artisan optimize
```

## 🔄 Tam Sıfırlama Komutları (Tek Seferde)

### Windows PowerShell

```powershell
# 1. Database sıfırla
php artisan migrate:fresh

# 2. Rolleri ve izinleri oluştur
php artisan db:seed --class=RolesPermissionsSeeder

# 3. Storage temizle
php artisan production:clean --force

# 4. Storage klasörlerini manuel temizle
Remove-Item -Recurse -Force "storage\app\public\files\*" -ErrorAction SilentlyContinue
Remove-Item -Recurse -Force "storage\app\public\qr-codes\*" -ErrorAction SilentlyContinue
Remove-Item -Recurse -Force "storage\app\public\brochures\*" -ErrorAction SilentlyContinue
Remove-Item -Recurse -Force "storage\app\public\short-links\*" -ErrorAction SilentlyContinue
Remove-Item -Recurse -Force "storage\app\public\settings\*" -ErrorAction SilentlyContinue
Remove-Item -Recurse -Force "storage\app\public\vcard_images\*" -ErrorAction SilentlyContinue

# 5. Storage link'ini yeniden oluştur
Remove-Item public\storage -ErrorAction SilentlyContinue
php artisan storage:link

# 6. Cache'leri temizle ve yeniden oluştur
php artisan optimize:clear
php artisan optimize
```

### Linux/Mac

```bash
# 1. Database sıfırla
php artisan migrate:fresh

# 2. Rolleri ve izinleri oluştur
php artisan db:seed --class=RolesPermissionsSeeder

# 3. Storage temizle
php artisan production:clean --force

# 4. Storage klasörlerini manuel temizle
rm -rf storage/app/public/files/*
rm -rf storage/app/public/qr-codes/*
rm -rf storage/app/public/brochures/*
rm -rf storage/app/public/short-links/*
rm -rf storage/app/public/settings/*
rm -rf storage/app/public/vcard_images/*

# 5. Storage link'ini yeniden oluştur
rm -f public/storage
php artisan storage:link

# 6. Cache'leri temizle ve yeniden oluştur
php artisan optimize:clear
php artisan optimize
```

## ✅ Sıfırlama Sonrası Kontrol

1. **Database Kontrolü:**
   ```bash
   php artisan tinker --execute="echo 'Users: ' . \App\Models\User::count();"
   ```

2. **Storage Kontrolü:**
   ```bash
   ls storage/app/public  # Linux/Mac
   dir storage\app\public  # Windows
   ```

3. **Default Kullanıcı:**
   - Email: `admin@system.local`
   - Şifre: `password`
   - Account ID: `0`
   - Rol: `superadmin`

## 🚨 Sorun Giderme

### Database Bağlantı Hatası

Eğer `Access denied` hatası alıyorsanız:

1. `.env` dosyasındaki database bilgilerini kontrol edin
2. Database kullanıcısının gerekli izinlere sahip olduğundan emin olun
3. Database'in var olduğundan emin olun

### Storage Link Hatası

Eğer `link already exists` hatası alıyorsanız:

```bash
# Windows
Remove-Item public\storage -Force
php artisan storage:link

# Linux/Mac
rm -f public/storage
php artisan storage:link
```

### ResetSystemSeeder Çalışmıyor

Eğer production ortamındaysanız, seeder çalışmaz (güvenlik koruması). Bunun yerine:

```bash
php artisan migrate:fresh
php artisan db:seed --class=RolesPermissionsSeeder
```

## 📝 Notlar

- ✅ ResetSystemSeeder production'da çalışmaz (güvenlik koruması)
- ✅ `migrate:fresh` tüm tabloları siler ve yeniden oluşturur
- ✅ Storage dosyaları manuel olarak silinmelidir
- ✅ Cache'ler temizlenmeli ve yeniden oluşturulmalıdır
- ✅ Storage link'i yeniden oluşturulmalıdır

## 🎯 Hızlı Sıfırlama (Tek Komut - Windows)

```powershell
php artisan migrate:fresh && php artisan db:seed --class=RolesPermissionsSeeder && php artisan production:clean --force && Remove-Item -Recurse -Force "storage\app\public\files\*","storage\app\public\qr-codes\*","storage\app\public\brochures\*","storage\app\public\short-links\*","storage\app\public\settings\*","storage\app\public\vcard_images\*" -ErrorAction SilentlyContinue && Remove-Item public\storage -ErrorAction SilentlyContinue && php artisan storage:link && php artisan optimize:clear && php artisan optimize
```

## 🎯 Hızlı Sıfırlama (Tek Komut - Linux/Mac)

```bash
php artisan migrate:fresh && php artisan db:seed --class=RolesPermissionsSeeder && php artisan production:clean --force && rm -rf storage/app/public/files/* storage/app/public/qr-codes/* storage/app/public/brochures/* storage/app/public/short-links/* storage/app/public/settings/* storage/app/public/vcard_images/* && rm -f public/storage && php artisan storage:link && php artisan optimize:clear && php artisan optimize
```
