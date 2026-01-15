# WM Dosya & QR Yönetim Sistemi

Laravel tabanlı dosya yönetimi ve QR kod oluşturma sistemi.

## 🚀 Özellikler

- 📁 Dosya Yönetimi (Media Library)
- 📱 QR Kod Oluşturma ve Yönetimi
- 📄 Kitapçık (Brochure) Yönetimi - PDF Flipbook Sistemi
- 👤 vCard Oluşturma ve Paylaşımı
- 🎨 vCard Şablon Yönetimi
- 👥 Kullanıcı ve Rol Yönetimi
- 📊 Kategori Yönetimi
- 🔐 Güvenli Kimlik Doğrulama
- 📝 Aktivite Logları
- 🌐 Çoklu Dil Desteği (TR/EN)

### 📄 Kitapçık (Brochure) Özellikleri

- PDF dosyası yükleme (Media Library entegrasyonu)
- Arkaplan görseli veya renk seçimi
- QR kod ile herkese açık paylaşım
- dFlip ile interaktif flipbook görüntüleme
- Sayfa geçişleri, zoom, tam ekran desteği
- Türkçe arayüz
- View ve download istatistikleri
- Süre sınırlaması (expires_at) desteği

## 📋 Gereksinimler

- PHP >= 8.1
- Composer
- Node.js & NPM
- MySQL/MariaDB veya SQLite
- Web Server (Apache/Nginx)

## 🔧 Kurulum

1. **Repository'yi klonlayın:**
```bash
git clone https://github.com/yourusername/dosya-yonetimi.git
cd dosya-yonetimi
```

2. **Bağımlılıkları yükleyin:**
```bash
composer install
npm install
```

3. **Environment dosyasını oluşturun:**
```bash
cp .env.example .env
php artisan key:generate
```

4. **.env dosyasını düzenleyin:**
```env
APP_NAME="WM Dosya&QR Yönetimi"
APP_ENV=local
APP_DEBUG=true
APP_URL=http://localhost

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=your_database
DB_USERNAME=your_username
DB_PASSWORD=your_password
```

5. **Database migration'larını çalıştırın:**
```bash
php artisan migrate
php artisan db:seed --class=RolesPermissionsSeeder
```

6. **Storage link'i oluşturun:**
```bash
php artisan storage:link
```

7. **Assets'i derleyin:**
```bash
npm run dev
# veya production için:
npm run production
```

8. **Sunucuyu başlatın:**
```bash
php artisan serve
```

## 👤 Varsayılan Kullanıcı

İlk kurulumdan sonra default kullanıcı oluşturulur:

```
Email: admin@system.local
Şifre: password
Account ID: 0
Rol: Superadmin
```

**⚠️ ÖNEMLİ:** İlk girişten sonra şifreyi mutlaka değiştirin!

## 🔒 Production Kurulumu

Production ortamı için detaylı bilgi için `YAYINA_ALMA_KONTROLU.md` dosyasına bakın.

Özet:
- `APP_ENV=production`
- `APP_DEBUG=false`
- `composer install --no-dev`
- `php artisan config:cache`
- `php artisan route:cache`
- `php artisan view:cache`

## 📚 Teknolojiler

- **Backend:** Laravel 11
- **Frontend:** KeenThemes Metronic
- **Database:** MySQL/SQLite
- **Authentication:** Laravel Sanctum
- **Authorization:** Spatie Permission
- **Icons:** KeenIcons & FontAwesome

## 📝 Lisans

Bu proje özel bir projedir.

## 🤝 Katkıda Bulunma

Katkılarınızı bekliyoruz! Lütfen pull request göndermeden önce:

1. Fork edin
2. Feature branch oluşturun (`git checkout -b feature/amazing-feature`)
3. Değişikliklerinizi commit edin (`git commit -m 'Add amazing feature'`)
4. Branch'inizi push edin (`git push origin feature/amazing-feature`)
5. Pull Request açın

## 📞 İletişim

Sorularınız için issue açabilirsiniz.
