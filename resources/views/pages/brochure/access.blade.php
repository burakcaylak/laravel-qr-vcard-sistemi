<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="noindex, nofollow">
    <title>{{ $brochure->name }} - {{ __('common.brochure') }}</title>

    <!-- dFlip yerel assetleri -->
    <link rel="stylesheet" href="{{ asset('vendor/dflip/assets/css/dflip.min.css') }}">
    <link rel="stylesheet" href="{{ asset('vendor/dflip/assets/css/themify-icons.min.css') }}">

    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        html, body {
            height: 100vh;
            overflow: hidden;
        }
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            background-color: {{ $brochure->background_type === 'color' ? ($brochure->background_color ?? '#f5f5f5') : 'transparent' }};
            @if($brochure->background_type === 'image' && $brochure->background_image_path)
            background-image: url('{{ asset('storage/' . $brochure->background_image_path) }}');
            backdrop-filter: blur(10px);
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            background-attachment: fixed;
            @endif
            min-height: 100vh;
            height: 100vh;
        }
        .wrapper {
            max-width: 1400px;
            margin: 0 auto;
            padding: 0;
            height: 100vh;
            display: flex;
            align-items: stretch;
        }

        /* dFlip UI Türkçe ve koyu tema */
        .df-ui, .df-ui * {
            font-family: 'Segoe UI', 'Inter', -apple-system, BlinkMacSystemFont, sans-serif !important;
        }
        .df-ui .df-ui-toolbar,
        .df-ui .df-ui-top-bar,
        .df-ui .df-ui-bottom-bar {
            background: rgba(0,0,0,0.78) !important;
            color: #e2e8f0 !important;
        }
        .df-ui .df-ui-btn,
        .df-ui .df-ui-btn:visited {
            color: #e2e8f0 !important;
        }
        .df-ui .df-ui-btn:hover {
            color: #38bdf8 !important;
            background: rgba(255,255,255,0.08) !important;
        }
        .df-ui .df-ui-separator {
            background: rgba(255,255,255,0.12) !important;
        }
        .df-ui .df-ui-thumbnail,
        .df-ui .df-ui-outline {
            background: #0f172a !important;
            color: #e2e8f0 !important;
        }
        .df-ui .df-ui-loading { color: #e2e8f0 !important; }
        .df-ui .df-ui-tooltip { font-size: 13px !important; color: #e2e8f0 !important; }
        /* Panel gölgeleri ve kenarlık */
        .df-ui .df-ui-toolbar,
        .df-ui .df-ui-top-bar,
        .df-ui .df-ui-bottom-bar,
        .df-ui .df-ui-menu {
            box-shadow: 0 12px 32px rgba(0,0,0,0.35) !important;
            border: 1px solid rgba(255,255,255,0.08) !important;
        }
    </style>
</head>
<body>
    <div class="wrapper">
        <div class="_df_book df-lite"
             source="{{ $pdfUrl }}"
             backgroundcolor="#ffffff"
             height="calc(100vh - 24px)"
             webgl="true"
             downloads="true"
             share="true"
             pageborder="true"
             >
        </div>
    </div>

    <!-- jQuery (dFlip bağımlılığı) -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <!-- dFlip PDF.js -->
    <script src="{{ asset('vendor/dflip/assets/js/libs/pdf.min.js') }}"></script>
    <script src="{{ asset('vendor/dflip/assets/js/dflip.min.js') }}"></script>
    <script>
        // PDF.js worker yolunu dFlip yüklemeden önce ayarla
        window.DEARVIEWER = window.DEARVIEWER || {};
        window.DEARVIEWER.location = "{{ asset('vendor/dflip/assets/') }}";
        if (window.pdfjsLib) {
            window.pdfjsLib.GlobalWorkerOptions.workerSrc = "{{ asset('vendor/dflip/assets/js/libs/pdf.worker.min.js') }}";
        }

        function localizeDflip() {
            const translations = {
                // Tam eşleşmeler
                'Zoom In': 'Yakınlaştır',
                'Zoom Out': 'Uzaklaştır',
                'Zoom in': 'Yakınlaştır',
                'Zoom out': 'Uzaklaştır',
                'Download': 'İndir',
                'Download PDF File': 'PDF İndir',
                'Download PDF': 'PDF İndir',
                'Fullscreen': 'Tam ekran',
                'Exit Fullscreen': 'Tam ekrandan çık',
                'Thumbnail': 'Küçük resimler',
                'Thumbnails': 'Küçük resimler',
                'Outline': 'İçindekiler',
                'Share': 'Paylaş',
                'share': 'Paylaş',
                'Print': 'Yazdır',
                'Sound': 'Ses',
                'Turn on/off Sound': 'Ses Aç/Kapat',
                'Turn on/off sound': 'Ses Aç/Kapat',
                'Turn sound on/off': 'Ses Aç/Kapat',
                'Single Page': 'Tek Sayfa',
                'Double Page': 'Çift Sayfa',
                'Single Page Mode': 'Tek Sayfa Modu',
                'Auto Play': 'Otomatik Oynat',
                'Pause': 'Duraklat',
                'Close': 'Kapat',
                'Goto First Page': 'İlk Sayfaya Git',
                'Goto Last Page': 'Son Sayfaya Git',
                'Keyboard Shortcuts': 'Klavye Kısayolları',
                'Loading...': 'Yükleniyor...',
                'Loading': 'Yükleniyor',
                'Help': 'Yardım',
                'Share on': 'Şurada paylaş',
                'Search': 'Ara',
                'Page': 'Sayfa',
                'of': ' / '
            };

            // Tüm attribute'ları çevir
            const translateAttribute = (attr) => {
                document.querySelectorAll(`[${attr}]`).forEach(el => {
                    const val = el.getAttribute(attr);
                    if (!val) return;
                    const trimmed = val.trim();
                    if (translations[trimmed]) {
                        el.setAttribute(attr, translations[trimmed]);
                    } else {
                        // Parçalı eşleşme
                        let updated = val;
                        Object.keys(translations).forEach(en => {
                            if (updated.includes(en)) {
                                updated = updated.replace(new RegExp(en.replace(/[.*+?^${}()|[\]\\]/g, '\\$&'), 'gi'), translations[en]);
                            }
                        });
                        if (updated !== val) el.setAttribute(attr, updated);
                    }
                });
            };

            // Text content'i çevir
            const translateText = () => {
                document.querySelectorAll('.df-ui, .df-ui *').forEach(el => {
                    // Sadece text node'ları işle (child elementleri atla)
                    if (el.children.length > 0) return;
                    const txt = el.textContent || '';
                    const trimmed = txt.trim();
                    if (!trimmed) return;
                    
                    // Tam eşleşme
                    if (translations[trimmed]) {
                        el.textContent = translations[trimmed];
                        return;
                    }
                    
                    // Parçalı eşleşme
                    let updated = txt;
                    Object.keys(translations).sort((a, b) => b.length - a.length).forEach(en => {
                        if (updated.includes(en)) {
                            updated = updated.replace(new RegExp(en.replace(/[.*+?^${}()|[\]\\]/g, '\\$&'), 'gi'), translations[en]);
                        }
                    });
                    if (updated !== txt) el.textContent = updated;
                });
            };

            translateAttribute('title');
            translateAttribute('aria-label');
            translateAttribute('data-title');
            translateText();
        }

        document.addEventListener('DOMContentLoaded', function () {
            // dFlip'i parse et
            if (window.DFLIP && window.DFLIP.parseBooks) {
                window.DFLIP.parseBooks();
                
                // İlk çeviri - dFlip yüklendikten sonra
                setTimeout(() => {
                    localizeDflip();
                    // Sürekli gözlemle
                    const observer = new MutationObserver(() => {
                        setTimeout(localizeDflip, 50);
                    });
                    const body = document.body;
                    observer.observe(body, { 
                        childList: true, 
                        subtree: true, 
                        characterData: true,
                        attributes: true,
                        attributeFilter: ['title', 'aria-label', 'data-title']
                    });
                }, 500);
            } else {
                console.error('dFlip yüklenemedi');
            }
        });
    </script>
</body>
</html>
