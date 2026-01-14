<x-default-layout>

    @section('title')
        {{ __('common.create_v_card') }}
    @endsection

    @section('breadcrumbs')
        {{ Breadcrumbs::render('v-card.create') }}
    @endsection

    <div class="card">
        <div class="card-header">
            <div class="card-title">
                <span class="svg-icon svg-icon-success svg-icon-2 me-2">
                    <i class="ki-solid ki-profile-user fs-2"></i>
                </span>
                <h3 class="fw-bold m-0 d-inline">{{ __('common.create_v_card') }}</h3>
            </div>
        </div>
        <div class="card-body">
            <form action="{{ route('v-card.store') }}" method="POST" id="v_card_form" enctype="multipart/form-data">
                @csrf
                
                <!-- vCard Şablonu Seçimi -->
                <div class="mb-5">
                    <label class="form-label">{{ __('common.vcard_template') }}</label>
                    <select name="template_id" class="form-select" data-control="select2" data-placeholder="{{ __('common.select_template') }}">
                        <option value="">{{ __('common.select_template') }}</option>
                        @foreach($templates as $template)
                            <option value="{{ $template->id }}" {{ old('template_id') == $template->id ? 'selected' : '' }}>{{ $template->name }}</option>
                        @endforeach
                    </select>
                    <small class="form-text text-muted">{{ __('common.template_hint') }}</small>
                </div>

                <!-- Kategori Seçimi -->
                <div class="mb-5">
                    <label class="form-label">{{ __('common.category') }}</label>
                    <select name="category_id" class="form-select" data-control="select2" data-placeholder="{{ __('common.select_category') }}">
                        <option value="">{{ __('common.select_category') }}</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Görsel Yükleme -->
                <div class="mb-5">
                    <label class="form-label">{{ __('common.image') }}</label>
                    <input type="file" name="image" class="form-control" accept="image/*">
                    <small class="form-text text-muted">{{ __('common.image_hint') }}</small>
                </div>

                <!-- Türkçe Alanlar -->
                <div class="separator separator-dashed my-5"></div>
                <h4 class="mb-5">{{ __('common.turkish') }} {{ __('common.fields') }}</h4>
                
                <div class="row">
                    <div class="col-md-6 mb-5">
                        <label class="form-label">{{ __('common.name_tr') }}</label>
                        <input type="text" name="name_tr" class="form-control" value="{{ old('name_tr') }}">
                    </div>
                    <div class="col-md-6 mb-5">
                        <label class="form-label">{{ __('common.title_tr') }}</label>
                        <input type="text" name="title_tr" class="form-control" value="{{ old('title_tr') }}">
                    </div>
                </div>

                <div class="mb-5">
                    <label class="form-label">{{ __('common.email_tr') }}</label>
                    <input type="email" name="email_tr" class="form-control" value="{{ old('email_tr') }}">
                </div>

                <div class="mb-5">
                    <label class="form-label">{{ __('common.company_tr') }}</label>
                    <input type="text" name="company_tr" class="form-control" value="{{ old('company_tr') }}">
                </div>

                <div class="mb-5">
                    <label class="form-label">{{ __('common.address_tr') }}</label>
                    <textarea name="address_tr" class="form-control" rows="3">{{ old('address_tr') }}</textarea>
                </div>

                <div class="row">
                    <div class="col-md-4 mb-5">
                        <label class="form-label">{{ __('common.company_phone_tr') }}</label>
                        <input type="text" name="company_phone_tr" class="form-control phone-input" placeholder="{{ __('common.phone_placeholder') }}" value="{{ old('company_phone_tr') }}">
                        <small class="form-text text-muted">{{ __('common.phone_format_hint') }}</small>
                    </div>
                    <div class="col-md-4 mb-5">
                        <label class="form-label">{{ __('common.extension_tr') }}</label>
                        <input type="text" name="extension_tr" class="form-control" value="{{ old('extension_tr') }}">
                    </div>
                    <div class="col-md-4 mb-5">
                        <label class="form-label">{{ __('common.fax_tr') }}</label>
                        <input type="text" name="fax_tr" class="form-control phone-input" placeholder="{{ __('common.phone_placeholder') }}" value="{{ old('fax_tr') }}">
                        <small class="form-text text-muted">{{ __('common.phone_format_hint') }}</small>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-5">
                        <label class="form-label">{{ __('common.mobile_phone_tr') }}</label>
                        <input type="text" name="mobile_phone_tr" class="form-control phone-input" placeholder="{{ __('common.phone_placeholder') }}" value="{{ old('mobile_phone_tr') }}">
                        <small class="form-text text-muted">{{ __('common.phone_format_hint') }}</small>
                    </div>
                    <div class="col-md-6 mb-5">
                        <label class="form-label">{{ __('common.website_tr') }}</label>
                        <input type="url" name="website_tr" class="form-control" value="{{ old('website_tr') }}">
                    </div>
                </div>

                <!-- İngilizce Alanlar -->
                <div class="separator separator-dashed my-5"></div>
                <h4 class="mb-5">{{ __('common.english') }} {{ __('common.fields') }}</h4>
                
                <div class="row">
                    <div class="col-md-6 mb-5">
                        <label class="form-label">{{ __('common.name_en') }}</label>
                        <input type="text" name="name_en" class="form-control" value="{{ old('name_en') }}">
                    </div>
                    <div class="col-md-6 mb-5">
                        <label class="form-label">{{ __('common.title_en') }}</label>
                        <input type="text" name="title_en" class="form-control" value="{{ old('title_en') }}">
                    </div>
                </div>

                <div class="mb-5">
                    <label class="form-label">{{ __('common.email_en') }}</label>
                    <input type="email" name="email_en" class="form-control" value="{{ old('email_en') }}">
                </div>

                <div class="mb-5">
                    <label class="form-label">{{ __('common.company_en') }}</label>
                    <input type="text" name="company_en" class="form-control" value="{{ old('company_en') }}">
                </div>

                <div class="mb-5">
                    <label class="form-label">{{ __('common.address_en') }}</label>
                    <textarea name="address_en" class="form-control" rows="3">{{ old('address_en') }}</textarea>
                </div>

                <div class="row">
                    <div class="col-md-4 mb-5">
                        <label class="form-label">{{ __('common.company_phone_en') }}</label>
                        <input type="text" name="company_phone_en" class="form-control phone-input" placeholder="{{ __('common.phone_placeholder') }}" value="{{ old('company_phone_en') }}">
                        <small class="form-text text-muted">{{ __('common.phone_format_hint') }}</small>
                    </div>
                    <div class="col-md-4 mb-5">
                        <label class="form-label">{{ __('common.extension_en') }}</label>
                        <input type="text" name="extension_en" class="form-control" value="{{ old('extension_en') }}">
                    </div>
                    <div class="col-md-4 mb-5">
                        <label class="form-label">{{ __('common.fax_en') }}</label>
                        <input type="text" name="fax_en" class="form-control phone-input" placeholder="{{ __('common.phone_placeholder') }}" value="{{ old('fax_en') }}">
                        <small class="form-text text-muted">{{ __('common.phone_format_hint') }}</small>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-5">
                        <label class="form-label">{{ __('common.mobile_phone_en') }}</label>
                        <input type="text" name="mobile_phone_en" class="form-control phone-input" placeholder="{{ __('common.phone_placeholder') }}" value="{{ old('mobile_phone_en') }}">
                        <small class="form-text text-muted">{{ __('common.phone_format_hint') }}</small>
                    </div>
                    <div class="col-md-6 mb-5">
                        <label class="form-label">{{ __('common.website_en') }}</label>
                        <input type="url" name="website_en" class="form-control" value="{{ old('website_en') }}">
                    </div>
                </div>

                <!-- Ortak Alanlar -->
                <div class="separator separator-dashed my-5"></div>
                <h4 class="mb-5">{{ __('common.common_fields') }}</h4>
                
                <div class="mb-5">
                    <label class="form-label">{{ __('common.email') }}</label>
                    <input type="email" name="email" class="form-control" value="{{ old('email') }}">
                    <small class="form-text text-muted">{{ __('common.common_fields_hint') }}</small>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-5">
                        <label class="form-label">{{ __('common.mobile_phone') }}</label>
                        <input type="text" name="mobile_phone" class="form-control phone-input" placeholder="{{ __('common.phone_placeholder') }}" value="{{ old('mobile_phone') }}">
                        <small class="form-text text-muted">{{ __('common.phone_format_hint') }}</small>
                    </div>
                    <div class="col-md-6 mb-5">
                        <label class="form-label">{{ __('common.website') }}</label>
                        <input type="url" name="website" class="form-control" value="{{ old('website') }}">
                        <small class="form-text text-muted">{{ __('common.common_fields_hint') }}</small>
                    </div>
                </div>

                <!-- Durum -->
                <div class="separator separator-dashed my-5"></div>
                
                <div class="mb-5">
                    <label class="form-check form-switch form-check-custom form-check-solid">
                        <input class="form-check-input" type="checkbox" name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }}>
                        <span class="form-check-label">{{ __('common.active') }}</span>
                    </label>
                </div>

                <div class="d-flex justify-content-end">
                    <a href="{{ route('v-card.index') }}" class="btn btn-light me-3">{{ __('common.cancel') }}</a>
                    <button type="submit" class="btn btn-primary">{{ __('common.save') }}</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        (function() {
            // Telefon formatlama fonksiyonu
            function formatPhoneInput(input) {
                let value = input.value.replace(/\D/g, '');
                
                if (value.length === 0) {
                    input.value = '';
                    return;
                }
                
                // Zaten formatlanmışsa (boşluk içeriyorsa) tekrar formatlama
                if (input.value.includes(' ') && input.value.startsWith('+90')) {
                    return;
                }
                
                // +90 ile başlamıyorsa ekle
                if (value.startsWith('90') && value.length >= 12) {
                    value = value;
                } else if (!value.startsWith('90') && value.length >= 10) {
                    value = '90' + value;
                } else {
                    input.value = value;
                    return;
                }
                
                // +90 XXX XXX XX XX formatına çevir
                if (value.length === 12 && value.startsWith('90')) {
                    const areaCode = value.substring(2, 5);
                    const firstPart = value.substring(5, 8);
                    const secondPart = value.substring(8, 10);
                    const thirdPart = value.substring(10, 12);
                    input.value = `+90 ${areaCode} ${firstPart} ${secondPart} ${thirdPart}`;
                } else {
                    input.value = value;
                }
            }
            
            // Telefon inputuna focus olduğunda +90 ekle
            function handlePhoneFocus(input) {
                if (!input.value) {
                    input.value = '+90 ';
                } else if (!input.value.startsWith('+90')) {
                    let value = input.value.replace(/\D/g, '');
                    if (value.length >= 10) {
                        if (!value.startsWith('90')) {
                            value = '90' + value;
                        }
                        if (value.length === 12) {
                            const areaCode = value.substring(2, 5);
                            const firstPart = value.substring(5, 8);
                            const secondPart = value.substring(8, 10);
                            const thirdPart = value.substring(10, 12);
                            input.value = `+90 ${areaCode} ${firstPart} ${secondPart} ${thirdPart}`;
                        } else {
                            input.value = '+90 ' + value.substring(2);
                        }
                    } else {
                        input.value = '+90 ' + value;
                    }
                }
            }
            
            // Website formatlama fonksiyonu
            function formatWebsiteInput(input) {
                let value = input.value.trim();
                if (!value) return;
                
                // http:// veya https:// yoksa ekle
                if (!/^https?:\/\//i.test(value)) {
                    input.value = 'http://' + value;
                }
            }
            
            // Email formatlama (trim ve lowercase)
            function formatEmailInput(input) {
                input.value = input.value.trim().toLowerCase();
            }
            
            // Telefon inputlarını bul ve event listener ekle
            const phoneInputs = document.querySelectorAll('.phone-input, input[name*="phone"], input[name*="fax"]');
            phoneInputs.forEach(input => {
                // Focus olduğunda +90 ekle
                input.addEventListener('focus', function() {
                    handlePhoneFocus(this);
                });
                
                // Blur olduğunda formatla
                input.addEventListener('blur', function() {
                    formatPhoneInput(this);
                });
                
                // Yazarken sadece rakam ve + işaretine izin ver
                input.addEventListener('input', function(e) {
                    // Eğer +90 ile başlıyorsa, sadece rakamları ve boşlukları koru
                    if (this.value.startsWith('+90')) {
                        this.value = '+90 ' + this.value.substring(4).replace(/[^\d\s]/g, '');
                    } else {
                        // +90 yoksa, sadece rakam ve + işaretine izin ver
                        this.value = this.value.replace(/[^\d+]/g, '');
                    }
                });
            });
            
            // Website inputlarını bul ve event listener ekle
            const websiteInputs = document.querySelectorAll('input[name*="website"]');
            websiteInputs.forEach(input => {
                input.addEventListener('blur', function() {
                    formatWebsiteInput(this);
                });
            });
            
            // Email inputlarını bul ve event listener ekle
            const emailInputs = document.querySelectorAll('input[type="email"]');
            emailInputs.forEach(input => {
                input.addEventListener('blur', function() {
                    formatEmailInput(this);
                });
            });
            
            // Sayfa yüklendiğinde mevcut telefon numaralarını formatla
            phoneInputs.forEach(input => {
                if (input.value) {
                    formatPhoneInput(input);
                }
            });
            
            // Sayfa yüklendiğinde mevcut website'leri formatla
            websiteInputs.forEach(input => {
                if (input.value && !/^https?:\/\//i.test(input.value)) {
                    formatWebsiteInput(input);
                }
            });
        })();
    </script>

</x-default-layout>
