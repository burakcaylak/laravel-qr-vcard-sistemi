# Dosya & QR Yönetim Sistemi

Laravel tabanlı kapsamlı dosya yönetimi, QR kod oluşturma, link kısaltma ve dijital içerik yönetim sistemi.

## 🚀 Özellikler

### 📁 Temel Modüller

- **📁 Dosya Yönetimi (Media Library)** - Gelişmiş dosya yükleme ve yönetim sistemi
- **📱 QR Kod Oluşturma ve Yönetimi** - Çoklu tip QR kod desteği (URL, Dosya, Multi-File, Text, Email, Phone, WiFi, vCard)
- **🔗 Link Kısaltma (URL Shortener)** - Gelişmiş link yönetimi ve analitik
- **📄 Kitapçık (Brochure) Yönetimi** - PDF Flipbook sistemi ile interaktif kitapçık görüntüleme
- **👤 vCard Oluşturma ve Paylaşımı** - Dijital kartvizit oluşturma ve paylaşım
- **🎨 vCard Şablon Yönetimi** - Özelleştirilebilir vCard şablonları
- **👥 Kullanıcı ve Rol Yönetimi** - Spatie Permission ile gelişmiş yetkilendirme
- **📊 Kategori Yönetimi** - Esnek kategori sistemi
- **📈 Analytics & Reporting** - Detaylı analitik ve raporlama modülü
- **🔐 API Management** - RESTful API ve token yönetimi
- **📝 Aktivite Logları** - Kapsamlı aktivite takibi
- **🌐 Çoklu Dil Desteği** - TR/EN dil desteği

### 🔗 Link Kısaltma Özellikleri

- ✅ URL kısaltma ve yönetimi
- ✅ Özel kısa kod belirleme veya otomatik oluşturma
- ✅ **QR Kod entegrasyonu** - Her link için otomatik QR kod oluşturma ve indirme
- ✅ **Gelişmiş istatistikler** - Detaylı tıklama geçmişi (IP, tarayıcı, cihaz, platform, referer, coğrafi konum)
- ✅ **Şifre koruması** - Link'lere şifre ekleme ve koruma
- ✅ **Link önizleme sayfası** - Tıklamadan önce önizleme ve sosyal medya paylaşımı
- ✅ **Toplu işlemler** - Çoklu seçim ile toplu silme, aktif/pasif yapma
- ✅ **CSV Export** - Link listesini CSV formatında dışa aktarma
- ✅ **Gelişmiş filtreleme** - Tarih aralığı, kategori, durum filtreleme
- ✅ **Sıralama** - Tıklama sayısına göre sıralama
- ✅ **API desteği** - REST API ile programatik link oluşturma ve yönetimi
- ✅ **Özel domain** - Kendi domain'inizle link kısaltma (Ayarlardan yapılandırılabilir)
- ✅ **Link geçmişi** - Tüm değişikliklerin versiyon takibi
- ✅ Kategori desteği
- ✅ Süre sınırlaması (expires_at) desteği
- ✅ Aktif/Pasif durum yönetimi
- ✅ Herkese açık erişim (`/l/{shortCode}`)

### 📱 QR Kod Özellikleri

- ✅ Çoklu tip desteği (URL, Dosya, Multi-File, Text, Email, Phone, WiFi, vCard)
- ✅ Özelleştirilebilir boyut ve format (PNG, SVG)
- ✅ Şifre koruması
- ✅ Toplu işlemler (aktif/pasif, silme)
- ✅ Gelişmiş filtreleme ve sıralama
- ✅ Tarama istatistikleri
- ✅ API desteği

### 📄 Kitapçık (Brochure) Özellikleri

- ✅ PDF dosyası yükleme (Media Library entegrasyonu)
- ✅ Arkaplan görseli veya renk seçimi
- ✅ QR kod ile herkese açık paylaşım
- ✅ dFlip ile interaktif flipbook görüntüleme
- ✅ Sayfa geçişleri, zoom, tam ekran desteği
- ✅ Şifre koruması
- ✅ View ve download istatistikleri
- ✅ Toplu işlemler
- ✅ Gelişmiş filtreleme
- ✅ Süre sınırlaması (expires_at) desteği
- ✅ API desteği

### 👤 vCard Özellikleri

- ✅ Çoklu dil desteği (TR/EN)
- ✅ Şablon sistemi
- ✅ QR kod ile paylaşım
- ✅ Görsel yükleme
- ✅ Toplu işlemler
- ✅ Gelişmiş filtreleme
- ✅ API desteği

### 📈 Analytics & Reporting Modülü

- ✅ Genel dashboard istatistikleri
- ✅ ShortLink detaylı analizi (tıklama geçmişi, coğrafi dağılım, referrer analizi)
- ✅ QR Code analizi (tarama istatistikleri)
- ✅ Brochure analizi (görüntüleme istatistikleri)
- ✅ VCard analizi (tarama istatistikleri)
- ✅ Zaman bazlı grafikler
- ✅ Coğrafi dağılım analizi
- ✅ Cihaz/Browser istatistikleri
- ✅ CSV export desteği

### 🔐 API Management

- ✅ **RESTful API** - Tüm modüller için API desteği
  - ShortLink API (`/api/v1/short-links`)
  - QR Code API (`/api/v1/qr-codes`)
  - Brochure API (`/api/v1/brochures`)
  - VCard API (`/api/v1/vcards`)
  - File API (`/api/v1/files`)
- ✅ **API Token Yönetimi** - Sanctum ile güvenli token yönetimi
- ✅ **Rate Limiting** - API ve web route'ları için özel rate limiting
- ✅ **API Dokümantasyonu** - Entegre API dokümantasyonu

### ⚡ Performans ve Güvenlik

- ✅ **Cache Stratejisi** - Kategori, ayar ve dashboard istatistikleri için cache
- ✅ **Queue Job'ları** - Büyük dosya işlemleri için asenkron işleme
  - Image optimization (WebP dönüşümü)
  - Video processing (thumbnail oluşturma)
  - PDF processing (thumbnail oluşturma)
  - Bulk delete operations
- ✅ **Rate Limiting** - Farklı endpoint'ler için özel limitler
  - Web: 120 requests/minute
  - Public Access: 100 requests/minute
  - File Upload: 20 requests/10 minutes
  - Analytics: 60 requests/minute
  - API: 60 requests/minute
- ✅ **Güvenlik** - Şifre koruması, CSRF koruması, XSS koruması

### 🎨 Kullanıcı Deneyimi

- ✅ **Toplu İşlemler** - Tüm modüllerde toplu silme, aktif/pasif yapma
- ✅ **Select All Checkbox** - Tüm modüllerde "Tümünü Seç" özelliği
- ✅ **Gelişmiş Filtreleme** - Tarih aralığı, kategori, durum filtreleme
- ✅ **Sıralama** - Tıklama/tarama sayısına göre sıralama
- ✅ **DataTable Entegrasyonu** - Gelişmiş tablo özellikleri
- ✅ **Responsive Design** - Mobil uyumlu tasarım
- ✅ **Breadcrumb Navigation** - Kolay navigasyon

## 📋 Gereksinimler

- PHP >= 8.1
- Composer
- Node.js & NPM
- MySQL/MariaDB veya SQLite
- Web Server (Apache/Nginx)
- GD Library veya Imagick (görsel işleme için)
- FFmpeg (video işleme için - opsiyonel)

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
APP_NAME="Dosya&QR Yönetimi"
APP_ENV=local
APP_DEBUG=true
APP_URL=http://localhost

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=your_database
DB_USERNAME=your_username
DB_PASSWORD=your_password

# Queue Configuration (Opsiyonel - Büyük dosya işlemleri için)
QUEUE_CONNECTION=database
# veya
QUEUE_CONNECTION=redis

# FFmpeg Path (Opsiyonel - Video işleme için)
FFMPEG_PATH=ffmpeg

# Cache Driver
CACHE_DRIVER=file
# veya production için
CACHE_DRIVER=redis
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

7. **Queue tablolarını oluşturun (Queue kullanacaksanız):**
```bash
php artisan queue:table
php artisan migrate
```

8. **Assets'i derleyin:**
```bash
npm run dev
# veya production için:
npm run production
```

9. **Sunucuyu başlatın:**
```bash
php artisan serve
```

10. **Queue Worker'ı başlatın (Queue kullanacaksanız):**
```bash
php artisan queue:work
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
- `php artisan optimize`

## 📚 API Kullanımı

### API Token Oluşturma

1. `/api-tokens` sayfasına gidin
2. Token adı girin ve "Token Oluştur" butonuna tıklayın
3. Oluşturulan token'ı kopyalayın (bir daha gösterilmeyecek)

### API İstek Örneği

```bash
# ShortLink listesi
curl -X GET "http://your-domain.com/api/v1/short-links" \
  -H "Authorization: Bearer YOUR_TOKEN_HERE" \
  -H "Accept: application/json"

# Yeni ShortLink oluşturma
curl -X POST "http://your-domain.com/api/v1/short-links" \
  -H "Authorization: Bearer YOUR_TOKEN_HERE" \
  -H "Content-Type: application/json" \
  -d '{
    "original_url": "https://example.com",
    "title": "Example Link",
    "is_active": true
  }'
```

### Mevcut API Endpoint'leri

- `GET /api/v1/short-links` - ShortLink listesi
- `POST /api/v1/short-links` - Yeni ShortLink oluştur
- `GET /api/v1/short-links/{id}` - ShortLink detayı
- `PUT /api/v1/short-links/{id}` - ShortLink güncelle
- `DELETE /api/v1/short-links/{id}` - ShortLink sil
- `GET /api/v1/short-links/{id}/stats` - ShortLink istatistikleri

Aynı endpoint yapısı QR Code, Brochure, VCard ve File modülleri için de mevcuttur.

## 📚 Teknolojiler

- **Backend:** Laravel 11
- **Frontend:** KeenThemes Metronic
- **Database:** MySQL/SQLite
- **Authentication:** Laravel Sanctum
- **Authorization:** Spatie Permission
- **Icons:** KeenIcons & FontAwesome
- **QR Code:** SimpleSoftwareIO/simple-qrcode
- **Image Processing:** Intervention Image
- **DataTables:** Yajra DataTables
- **PDF Flipbook:** dFlip
- **Queue:** Laravel Queue (Database/Redis)

## 🗂️ Proje Yapısı

```
app/
├── Http/
│   ├── Controllers/
│   │   ├── Api/              # API Controller'ları
│   │   ├── Analytics/        # Analytics Controller'ları
│   │   └── ...               # Diğer Controller'lar
│   └── Requests/             # Form Request Validation
├── Models/                   # Eloquent Modelleri
├── DataTables/               # DataTable Sınıfları
├── Jobs/                     # Queue Job'ları
├── Helpers/                  # Helper Sınıfları
└── ...

resources/
├── views/
│   ├── pages/
│   │   ├── analytics/        # Analytics sayfaları
│   │   ├── api-tokens/       # API Token yönetim sayfası
│   │   └── ...               # Diğer sayfalar
│   └── layout/               # Layout dosyaları
└── lang/                     # Dil dosyaları (TR/EN)
```

## 🔄 Queue Job'ları

Sistem aşağıdaki queue job'larını içerir:

- **ProcessFileUploadJob** - Dosya yükleme sonrası thumbnail oluşturma
- **OptimizeImageJob** - Büyük görselleri optimize etme (WebP dönüşümü)
- **ProcessVideoJob** - Video dosyaları için thumbnail oluşturma
- **ProcessPdfJob** - PDF dosyaları için thumbnail oluşturma
- **BulkDeleteFilesJob** - Toplu dosya silme işlemleri

Queue kullanmak için:
```bash
php artisan queue:work
```

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

## 🎯 Son Güncellemeler

- ✅ Analytics & Reporting modülü eklendi
- ✅ API Management modülü eklendi (QR Code, Brochure, VCard, File API)
- ✅ API Token yönetim sayfası eklendi
- ✅ Rate limiting yapılandırması eklendi
- ✅ Cache stratejisi eklendi (CacheHelper)
- ✅ Queue job'ları eklendi (büyük dosya işlemleri için)
- ✅ Gelişmiş filtreleme ve sıralama eklendi (tüm modüllerde)
- ✅ Toplu işlemler eklendi (tüm modüllerde)
- ✅ Select all checkbox eklendi (tüm modüllerde)
- ✅ Şifre koruması eklendi (ShortLink, QR Code, Brochure)
