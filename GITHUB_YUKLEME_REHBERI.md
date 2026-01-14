# GitHub'a Yükleme Rehberi

## ✅ Hazırlık Tamamlandı

- ✅ Git repository oluşturuldu
- ✅ `.gitignore` güncellendi (`.env`, `vendor`, `node_modules` vb. hariç)
- ✅ `README.md` oluşturuldu
- ✅ Tüm dosyalar staging area'ya eklendi

## 🚀 GitHub'a Yükleme Adımları

### 1. GitHub'da Yeni Repository Oluşturun

1. GitHub'a giriş yapın: https://github.com
2. Sağ üstteki **"+"** butonuna tıklayın
3. **"New repository"** seçin
4. Repository adını girin (örn: `dosya-yonetimi`)
5. **Public** veya **Private** seçin
6. **"Initialize this repository with a README"** seçeneğini **İŞARETLEMEYİN** (zaten README var)
7. **"Create repository"** butonuna tıklayın

### 2. İlk Commit'i Yapın

```bash
git commit -m "Initial commit: WM Dosya & QR Yönetim Sistemi"
```

### 3. GitHub Repository'sini Remote Olarak Ekleyin

GitHub'da oluşturduğunuz repository'nin URL'sini kopyalayın (örn: `https://github.com/kullaniciadi/dosya-yonetimi.git`)

```bash
git remote add origin https://github.com/KULLANICI_ADI/REPO_ADI.git
```

**Örnek:**
```bash
git remote add origin https://github.com/ahmetcaylak/dosya-yonetimi.git
```

### 4. Ana Branch'i `main` Olarak Ayarlayın (Gerekirse)

```bash
git branch -M main
```

### 5. GitHub'a Push Edin

```bash
git push -u origin main
```

## 📋 Tam Komut Listesi

```bash
# 1. Commit yap
git commit -m "Initial commit: WM Dosya & QR Yönetim Sistemi"

# 2. Remote ekle (GitHub URL'nizi buraya yazın)
git remote add origin https://github.com/KULLANICI_ADI/REPO_ADI.git

# 3. Branch'i main olarak ayarla
git branch -M main

# 4. GitHub'a push et
git push -u origin main
```

## ⚠️ Önemli Notlar

### Güvenlik Kontrolleri

- ✅ `.env` dosyası `.gitignore`'da (commit edilmeyecek)
- ✅ `vendor` klasörü `.gitignore`'da
- ✅ `node_modules` klasörü `.gitignore`'da
- ✅ Database dosyaları (`.sqlite`, `.db`) `.gitignore`'da
- ✅ Log dosyaları `.gitignore`'da

### Commit Etmeden Önce Kontrol

```bash
# Hangi dosyalar commit edilecek kontrol et
git status

# .env dosyasının commit edilmediğinden emin ol
git status | grep .env
# (Hiçbir sonuç çıkmamalı)
```

## 🔄 Sonraki Güncellemeler İçin

Projede değişiklik yaptıktan sonra:

```bash
# Değişiklikleri kontrol et
git status

# Değişiklikleri ekle
git add .

# Commit yap
git commit -m "Açıklayıcı commit mesajı"

# GitHub'a push et
git push
```

## 📝 Commit Mesajı Örnekleri

```bash
git commit -m "feat: Yeni özellik eklendi"
git commit -m "fix: Bug düzeltildi"
git commit -m "docs: Dokümantasyon güncellendi"
git commit -m "refactor: Kod refactor edildi"
git commit -m "style: Kod formatı düzeltildi"
```

## 🆘 Sorun Giderme

### Eğer "fatal: remote origin already exists" hatası alırsanız:

```bash
# Mevcut remote'u kaldır
git remote remove origin

# Tekrar ekle
git remote add origin https://github.com/KULLANICI_ADI/REPO_ADI.git
```

### Eğer "error: failed to push some refs" hatası alırsanız:

```bash
# Önce pull yap (eğer GitHub'da README varsa)
git pull origin main --allow-unrelated-histories

# Sonra push yap
git push -u origin main
```

### Eğer büyük dosyalar için sorun yaşarsanız:

`.gitignore` dosyasına ekleyin:
```
# Büyük dosyalar
*.zip
*.rar
*.tar.gz
```

## ✅ Başarılı Yükleme Sonrası

GitHub repository sayfanızda şunları görmelisiniz:
- ✅ Tüm proje dosyaları
- ✅ README.md dosyası
- ✅ .gitignore dosyası
- ✅ Commit geçmişi

## 🔐 Güvenlik İpuçları

1. **Private Repository Kullanın:** Eğer kodunuz özelse, repository'yi private yapın
2. **.env Dosyasını Kontrol Edin:** `.env` dosyasının commit edilmediğinden emin olun
3. **Sensitive Data:** Şifreler, API key'ler vb. asla commit etmeyin
4. **Branch Protection:** Production branch'ini koruyun (Settings > Branches)

## 📞 Yardım

Sorun yaşarsanız:
- Git dokümantasyonu: https://git-scm.com/doc
- GitHub dokümantasyonu: https://docs.github.com
- Git komutları: `git help <komut>`
