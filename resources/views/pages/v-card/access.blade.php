<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $vCard->getLocalizedField('name') ?? 'vCard' }}</title>

    <!-- KeenIcons CSS -->
    <link rel="stylesheet" href="{{ asset('assets/plugins/global/plugins.bundle.css') }}">
    <!-- FontAwesome CSS (for social media icons) -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw==" crossorigin="anonymous" referrerpolicy="no-referrer" />

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: "Segoe UI", -apple-system, BlinkMacSystemFont, "Roboto", "Helvetica Neue", Arial, sans-serif;
            @php
                $backgroundImage = $vCard->template && $vCard->template->background_path 
                    ? asset('storage/' . $vCard->template->background_path)
                    : getLoginImage();
            @endphp
            @if($backgroundImage)
            background-image: url({{ $backgroundImage }});
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            @endif
            background-color: rgba(0, 51, 161, 0.6);
            backdrop-filter: blur(10px);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 16px;
        }

        .vcard-shell {
            width: 100%;
            max-width: 420px;
            background: #ffffff;
            border-radius: 0.3rem;
            box-shadow: 0 18px 45px rgba(15, 23, 42, 0.18);
            overflow: hidden;
        }

        .vcard-header {
            @php
                $headerColor = $vCard->template && $vCard->template->color 
                    ? $vCard->template->color 
                    : '#0033A1';
            @endphp
            background: {{ $headerColor }};
            padding: 32px 24px 64px;
            text-align: center;
            position: relative;
        }

        .vcard-logo {
            max-width: 160px;
            margin: 0 0 24px 0;
            display: block;
        }

        .vcard-lang-toggle {
            position: absolute;
            right: 24px;
            top: 20px;
            display: flex;
            gap: 6px;
            background: rgba(255, 255, 255, 0.18);
            padding: 4px;
            border-radius: 0.3rem;
            backdrop-filter: blur(6px);
        }

        .vcard-lang-btn {
            border: none;
            background: transparent;
            color: #fff;
            font-size: 11px;
            font-weight: 600;
            padding: 4px 10px;
            border-radius: 0.3rem;
            cursor: pointer;
            opacity: 0.8;
        }

        .vcard-lang-btn.active {
            background: #ffffff;
            color: #0033A1;
            opacity: 1;
        }

        .vcard-avatar-wrapper {
            position: absolute;
            left: 50%;
            bottom: -40px;
            transform: translateX(-50%);
            width: 120px;
            height: 120px;
            border-radius: 50%;
            background: #ffffff;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 10px 25px rgba(15, 23, 42, 0.35);
        }

        .vcard-avatar {
            width: 104px;
            height: 104px;
            border-radius: 50%;
            object-fit: cover;
        }

        .vcard-avatar-placeholder {
            width: 104px;
            height: 104px;
            border-radius: 50%;
            background: #0033A1;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #fff;
        }
        
        .vcard-avatar-placeholder i {
            font-size: 48px;
            line-height: 1;
        }

        .vcard-body {
            padding: 64px 20px 24px;
        }

        .vcard-name {
            font-size: 22px;
            font-weight: 700;
            color: #111827;
            text-align: center;
            margin-bottom: 4px;
        }

        .vcard-title {
            font-size: 14px;
            font-weight: 500;
            color: #6b7280;
            text-align: center;
            margin-bottom: 20px;
        }

        .vcard-info-list {
            border-radius: 0.3rem;
            background: #f9fafb;
            padding: 4px 0;
        }

        .vcard-info-item {
            display: none; /* JS ile açılacak */
            padding: 12px 18px;
            display: flex;
            align-items: center;
            gap: 12px;
            border-bottom: 1px solid #e5e7eb;
        }

        .vcard-info-item:last-child {
            border-bottom: none;
        }

        .vcard-info-icon {
            width: 32px;
            height: 32px;
            border-radius: 0.3rem;
            background: #ffffff;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #0033A1;
        }
        
        .vcard-info-icon i {
            font-size: 18px;
            line-height: 1;
        }

        .vcard-info-content {
            flex: 1;
        }

        .vcard-info-value {
            font-size: 14px;
            color: #111827;
            font-weight: 500;
            word-break: break-word;
        }

        .vcard-info-value a {
            color: #111827;
            text-decoration: none;
            transition: color 0.2s ease;
        }

        .vcard-info-value a:hover {
            color: #0033A1;
            text-decoration: none;
        }

        .vcard-social-media {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 12px;
            margin-top: 16px;
            margin-bottom: 16px;
        }

        .vcard-social-link {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: #f3f4f6;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #6b7280;
            text-decoration: none;
            transition: all 0.2s ease;
        }

        .vcard-social-link:hover {
            background: #e5e7eb;
            color: #374151;
            transform: translateY(-2px);
        }

        .vcard-social-link i {
            font-size: 18px;
        }

        .vcard-footer {
            margin-top: 16px;
            text-align: center;
        }

        .vcard-download-btn {
            border: none;
            @php
                $btnColor = $vCard->template && $vCard->template->color 
                    ? $vCard->template->color 
                    : '#0033A1';
            @endphp
            background: {{ $btnColor }};
            color: #ffffff;
            padding: 10px 20px;
            border-radius: 0.3rem;
            font-size: 13px;
            font-weight: 600;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .vcard-download-btn:hover {
            background: #002a85;
        }
        
        .vcard-download-btn i {
            font-size: 16px;
            line-height: 1;
        }

        @media (min-width: 768px) {
            body {
                padding: 32px;
            }

            .vcard-shell {
                max-width: 480px;
            }
        }
    </style>
</head>
<body>
    <div class="vcard-shell"
         data-name-tr="{{ $vCard->name_tr }}"
         data-name-en="{{ $vCard->name_en }}"
         data-title-tr="{{ $vCard->title_tr }}"
         data-title-en="{{ $vCard->title_en }}"
         data-email-tr="{{ $vCard->email_tr }}"
         data-email-en="{{ $vCard->email_en }}"
         data-email-common="{{ $vCard->email }}"
         data-mobile-tr="{{ $vCard->mobile_phone_tr }}"
         data-mobile-en="{{ $vCard->mobile_phone_en }}"
         data-mobile-common="{{ $vCard->mobile_phone }}"
         data-company-tr="{{ $vCard->company_tr }}"
         data-company-en="{{ $vCard->company_en }}"
         data-address-tr="{{ $vCard->address_tr }}"
         data-address-en="{{ $vCard->address_en }}"
         data-company-phone-tr="{{ $vCard->company_phone_tr }}"
         data-company-phone-en="{{ $vCard->company_phone_en }}"
         data-extension-tr="{{ $vCard->extension_tr }}"
         data-extension-en="{{ $vCard->extension_en }}"
         data-fax-tr="{{ $vCard->fax_tr }}"
         data-fax-en="{{ $vCard->fax_en }}"
         data-website-tr="{{ $vCard->website_tr }}"
         data-website-en="{{ $vCard->website_en }}"
         data-website-common="{{ $vCard->website }}">

        <div class="vcard-header">
            @php
                $logoUrl = ($vCard->template && $vCard->template->logo_path) 
                    ? asset('storage/' . $vCard->template->logo_path)
                    : (getLogo('light') ?? null);
            @endphp
            @if($logoUrl)
                <img src="{{ $logoUrl }}" alt="Logo" class="vcard-logo">
            @endif

            <div class="vcard-lang-toggle">
                <button type="button" class="vcard-lang-btn active" data-lang="tr">TR</button>
                <button type="button" class="vcard-lang-btn" data-lang="en">EN</button>
            </div>

            <div class="vcard-avatar-wrapper">
                @if($vCard->image_path)
                    <img src="{{ asset('storage/' . $vCard->image_path) }}" alt="Avatar" class="vcard-avatar">
                @else
                    <div class="vcard-avatar-placeholder">
                        <i class="ki-solid ki-user"></i>
                    </div>
                @endif
            </div>
        </div>

        <div class="vcard-body">
            <div id="vcard-name" class="vcard-name"></div>
            <div id="vcard-title" class="vcard-title"></div>

            <div class="vcard-info-list" id="vcard-info-list">
                <div class="vcard-info-item" data-field="email">
                    <div class="vcard-info-icon">
                        <i class="ki-solid ki-sms"></i>
                    </div>
                    <div class="vcard-info-content">
                        <div class="vcard-info-value">
                            <a href="#" id="vcard-email-link"></a>
                        </div>
                    </div>
                </div>

                <div class="vcard-info-item" data-field="company">
                    <div class="vcard-info-icon">
                        <i class="ki-solid ki-office-bag"></i>
                    </div>
                    <div class="vcard-info-content">
                        <div class="vcard-info-value">
                            <a href="#" id="vcard-company-link" target="_blank" rel="noopener"></a>
                        </div>
                    </div>
                </div>

                <div class="vcard-info-item" data-field="address">
                    <div class="vcard-info-icon">
                        <i class="ki-solid ki-geolocation"></i>
                    </div>
                    <div class="vcard-info-content">
                        <div class="vcard-info-value">
                            <a href="#" id="vcard-address-link" target="_blank" rel="noopener"></a>
                        </div>
                    </div>
                </div>

                <div class="vcard-info-item" data-field="mobile">
                    <div class="vcard-info-icon">
                        <i class="ki-solid ki-phone"></i>
                    </div>
                    <div class="vcard-info-content">
                        <div class="vcard-info-value">
                            <a href="#" id="vcard-mobile-link"></a>
                        </div>
                    </div>
                </div>

                <div class="vcard-info-item" data-field="company_phone">
                    <div class="vcard-info-icon">
                        <i class="ki-solid ki-phone"></i>
                    </div>
                    <div class="vcard-info-content">
                        <div class="vcard-info-value">
                            <a href="#" id="vcard-company-phone-link"></a>
                            <span id="vcard-extension-text" style="color: #6b7280; margin-left: 8px;"></span>
                        </div>
                    </div>
                </div>

                <div class="vcard-info-item" data-field="fax">
                    <div class="vcard-info-icon">
                        <i class="ki-solid ki-printer"></i>
                    </div>
                    <div class="vcard-info-content">
                        <div class="vcard-info-value">
                            <a href="#" id="vcard-fax-link"></a>
                        </div>
                    </div>
                </div>

                <div class="vcard-info-item" data-field="website">
                    <div class="vcard-info-icon">
                        <i class="ki-solid ki-click"></i>
                    </div>
                    <div class="vcard-info-content">
                        <div class="vcard-info-value">
                            <a href="#" id="vcard-website-link" target="_blank" rel="noopener"></a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sosyal Medya İkonları -->
            @if($vCard->template && ($vCard->template->facebook_url || $vCard->template->instagram_url || $vCard->template->x_url || $vCard->template->linkedin_url || $vCard->template->youtube_url))
            <div class="vcard-social-media">
                @if($vCard->template->facebook_url)
                    <a href="{{ $vCard->template->facebook_url }}" target="_blank" rel="noopener" class="vcard-social-link" title="Facebook">
                        <i class="fab fa-facebook"></i>
                    </a>
                @endif
                @if($vCard->template->instagram_url)
                    <a href="{{ $vCard->template->instagram_url }}" target="_blank" rel="noopener" class="vcard-social-link" title="Instagram">
                        <i class="fab fa-instagram"></i>
                    </a>
                @endif
                @if($vCard->template->x_url)
                    <a href="{{ $vCard->template->x_url }}" target="_blank" rel="noopener" class="vcard-social-link" title="X (Twitter)">
                        <i class="fab fa-x-twitter"></i>
                    </a>
                @endif
                @if($vCard->template->linkedin_url)
                    <a href="{{ $vCard->template->linkedin_url }}" target="_blank" rel="noopener" class="vcard-social-link" title="LinkedIn">
                        <i class="fab fa-linkedin"></i>
                    </a>
                @endif
                @if($vCard->template->youtube_url)
                    <a href="{{ $vCard->template->youtube_url }}" target="_blank" rel="noopener" class="vcard-social-link" title="YouTube">
                        <i class="fab fa-youtube"></i>
                    </a>
                @endif
            </div>
            @endif

            <div class="vcard-footer">
                <button class="vcard-download-btn" id="vcard-download-btn" type="button">
                    <i class="ki-solid ki-double-down"></i>
                    {{ __('common.download_vcard') }}
                </button>
            </div>
        </div>
    </div>

    <script>
        (function () {
            const shell = document.querySelector('.vcard-shell');
            if (!shell) return;

            const data = {
                tr: {
                    name: shell.dataset.nameTr || '',
                    title: shell.dataset.titleTr || '',
                    email: shell.dataset.emailTr || shell.dataset.emailCommon || '',
                    mobile: shell.dataset.mobileTr || shell.dataset.mobileCommon || '',
                    company: shell.dataset.companyTr || '',
                    address: shell.dataset.addressTr || '',
                    companyPhone: shell.dataset.companyPhoneTr || '',
                    extension: shell.dataset.extensionTr || '',
                    fax: shell.dataset.faxTr || '',
                    website: shell.dataset.websiteTr || shell.dataset.websiteCommon || '',
                },
                en: {
                    name: shell.dataset.nameEn || '',
                    title: shell.dataset.titleEn || '',
                    email: shell.dataset.emailEn || shell.dataset.emailCommon || '',
                    mobile: shell.dataset.mobileEn || shell.dataset.mobileCommon || '',
                    company: shell.dataset.companyEn || '',
                    address: shell.dataset.addressEn || '',
                    companyPhone: shell.dataset.companyPhoneEn || '',
                    extension: shell.dataset.extensionEn || '',
                    fax: shell.dataset.faxEn || '',
                    website: shell.dataset.websiteEn || shell.dataset.websiteCommon || '',
                }
            };

            let currentLang = data.tr.name || data.tr.title || data.tr.email ? 'tr' : 'en';

            const nameEl = document.getElementById('vcard-name');
            const titleEl = document.getElementById('vcard-title');

            const mobileItem = document.querySelector('[data-field=\"mobile\"]');
            const emailItem = document.querySelector('[data-field=\"email\"]');
            const websiteItem = document.querySelector('[data-field=\"website\"]');
            const addressItem = document.querySelector('[data-field=\"address\"]');
            const companyItem = document.querySelector('[data-field=\"company\"]');
            const companyPhoneItem = document.querySelector('[data-field=\"company_phone\"]');
            const faxItem = document.querySelector('[data-field=\"fax\"]');

            const mobileLink = document.getElementById('vcard-mobile-link');
            const emailLink = document.getElementById('vcard-email-link');
            const websiteLink = document.getElementById('vcard-website-link');
            const addressLink = document.getElementById('vcard-address-link');
            const companyLink = document.getElementById('vcard-company-link');
            const companyPhoneLink = document.getElementById('vcard-company-phone-link');
            const extensionText = document.getElementById('vcard-extension-text');
            const faxLink = document.getElementById('vcard-fax-link');

            function setVisibility(el, hasValue) {
                if (!el) return;
                el.style.display = hasValue ? 'flex' : 'none';
            }

            function formatWebsite(url) {
                if (!url) return '';
                if (!/^https?:\/\//i.test(url)) {
                    return 'http://' + url;
                }
                return url;
            }

            function formatPhone(phone) {
                if (!phone) return '';
                // Sadece rakamları al
                let cleaned = phone.replace(/\D/g, '');
                
                // +90 ile başlamıyorsa ekle
                if (cleaned.startsWith('90') && cleaned.length >= 12) {
                    cleaned = cleaned;
                } else if (!cleaned.startsWith('90') && cleaned.length >= 10) {
                    cleaned = '90' + cleaned;
                } else {
                    return phone; // Formatlanamazsa orijinalini döndür
                }
                
                // +90 XXX XXX XX XX formatına çevir
                if (cleaned.length === 12 && cleaned.startsWith('90')) {
                    const areaCode = cleaned.substring(2, 5); // 212, 532, vb.
                    const firstPart = cleaned.substring(5, 8); // 000
                    const secondPart = cleaned.substring(8, 10); // 00
                    const thirdPart = cleaned.substring(10, 12); // 00
                    return `+90 ${areaCode} ${firstPart} ${secondPart} ${thirdPart}`;
                }
                
                return phone; // Formatlanamazsa orijinalini döndür
            }

            function render(lang) {
                const d = data[lang] || data.tr;

                nameEl.textContent = d.name || '{{ $vCard->name_tr ?? $vCard->name_en ?? '' }}';
                titleEl.textContent = d.title || '';
                titleEl.style.display = d.title ? 'block' : 'none';

                if (d.mobile) {
                    const formattedMobile = formatPhone(d.mobile);
                    mobileLink.textContent = formattedMobile;
                    // tel: linki için formatlanmış halini kullan (yurtdışından aranabilir)
                    mobileLink.href = 'tel:' + formattedMobile;
                }
                setVisibility(mobileItem, !!d.mobile);

                if (d.email) {
                    emailLink.textContent = d.email;
                    emailLink.href = 'mailto:' + d.email;
                }
                setVisibility(emailItem, !!d.email);

                if (d.website) {
                    const formatted = formatWebsite(d.website);
                    websiteLink.textContent = d.website;
                    websiteLink.href = formatted;
                }
                setVisibility(websiteItem, !!d.website);

                if (d.address) {
                    addressLink.textContent = d.address;
                    addressLink.href = 'https://www.google.com/maps/search/?api=1&query=' + encodeURIComponent(d.address);
                }
                setVisibility(addressItem, !!d.address);

                if (d.company) {
                    companyLink.textContent = d.company;
                    companyLink.href = 'https://www.google.com/search?q=' + encodeURIComponent(d.company);
                }
                setVisibility(companyItem, !!d.company);

                if (d.companyPhone) {
                    const formattedCompanyPhone = formatPhone(d.companyPhone);
                    companyPhoneLink.textContent = formattedCompanyPhone;
                    // tel: linki için formatlanmış halini kullan (yurtdışından aranabilir)
                    companyPhoneLink.href = 'tel:' + formattedCompanyPhone;
                    if (d.extension) {
                        extensionText.textContent = '(Dahili: ' + d.extension + ')';
                    } else {
                        extensionText.textContent = '';
                    }
                } else {
                    extensionText.textContent = '';
                }
                setVisibility(companyPhoneItem, !!d.companyPhone);

                if (d.fax) {
                    const formattedFax = formatPhone(d.fax);
                    faxLink.textContent = formattedFax;
                    // tel: linki için formatlanmış halini kullan (yurtdışından aranabilir)
                    faxLink.href = 'tel:' + formattedFax;
                }
                setVisibility(faxItem, !!d.fax);

                document.querySelectorAll('.vcard-lang-btn').forEach(btn => {
                    btn.classList.toggle('active', btn.dataset.lang === lang);
                });
            }

            document.querySelectorAll('.vcard-lang-btn').forEach(btn => {
                btn.addEventListener('click', () => {
                    const lang = btn.dataset.lang;
                    currentLang = lang;
                    render(lang);
                });
            });

            const downloadBtn = document.getElementById('vcard-download-btn');
            if (downloadBtn) {
                downloadBtn.addEventListener('click', () => {
                    const href = 'data:text/vcard;charset=utf-8,{{ rawurlencode($vCardContent) }}';
                    const name = '{{ $vCard->name_tr ?? $vCard->name_en ?? 'vcard' }}.vcf';
                    const a = document.createElement('a');
                    a.href = href;
                    a.download = name;
                    document.body.appendChild(a);
                    a.click();
                    document.body.removeChild(a);
                });
            }

            render(currentLang);
        })();
    </script>
</body>
</html>
