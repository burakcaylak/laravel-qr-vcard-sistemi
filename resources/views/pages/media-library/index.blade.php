<x-default-layout>

    @section('title')
        {{ __('common.media_library') }}
    @endsection

    @section('breadcrumbs')
        <div class="d-flex align-items-center flex-wrap me-3 mb-5 mb-lg-0">
            <span class="text-gray-500 fw-semibold fs-7">{{ __('common.media_library') }}</span>
        </div>
    @endsection

    @push('styles')
    <style>
        /* Önizleme alanı için güçlü kurallar - Universal selector */
        .media-files-grid .card-preview,
        .media-files-grid div.card-preview,
        .media-files-grid .card .card-preview,
        .media-files-grid .card-body .card-preview,
        .media-files-grid * .card-preview {
            width: 100% !important;
            height: 180px !important;
            min-width: 0 !important;
            min-height: 180px !important;
            max-width: 100% !important;
            max-height: 180px !important;
            flex-shrink: 0 !important;
            flex-grow: 0 !important;
            box-sizing: border-box !important;
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
        }
        .media-files-grid .card-preview img,
        .media-files-grid div.card-preview img,
        .media-files-grid .card .card-preview img,
        .media-files-grid .card-body .card-preview img,
        .media-files-grid * .card-preview img {
            width: 100% !important;
            height: 180px !important;
            min-width: 0 !important;
            min-height: 180px !important;
            max-width: 100% !important;
            max-height: 180px !important;
            object-fit: contain !important;
            display: block !important;
            box-sizing: border-box !important;
        }
        .media-files-grid .card-preview i,
        .media-files-grid .card-preview svg,
        .media-files-grid .card-preview .ki-duotone,
        .media-files-grid .card-preview .ki-solid,
        .media-files-grid div.card-preview i,
        .media-files-grid div.card-preview svg,
        .media-files-grid div.card-preview .ki-duotone,
        .media-files-grid div.card-preview .ki-solid,
        .media-files-grid * .card-preview i,
        .media-files-grid * .card-preview svg,
        .media-files-grid * .card-preview .ki-duotone,
        .media-files-grid * .card-preview .ki-solid {
            font-size: 48px !important;
            width: 48px !important;
            height: 48px !important;
            min-width: 48px !important;
            min-height: 48px !important;
            max-width: 48px !important;
            max-height: 48px !important;
            line-height: 48px !important;
            box-sizing: border-box !important;
        }
        /* Bootstrap Grid - 4 sütun */
        .media-files-grid {
            width: 100% !important;
        }
        .media-files-grid .card {
            display: flex !important;
            flex-direction: column !important;
            height: 100% !important;
            min-height: 300px !important;
        }
        .media-files-grid .card-body {
            display: flex !important;
            flex-direction: column !important;
            flex: 1 !important;
            padding: 1rem !important;
        }
        .media-files-grid .card-preview,
        .media-files-grid .card .card-preview,
        .media-files-grid .card-body .card-preview {
            width: 100% !important;
            height: 180px !important;
            min-width: 0 !important;
            min-height: 180px !important;
            max-width: 100% !important;
            max-height: 180px !important;
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
            background: #f5f8fa !important;
            border-radius: 0.5rem !important;
            margin: 0 0 0.75rem 0 !important;
            overflow: hidden !important;
            flex-shrink: 0 !important;
            flex-grow: 0 !important;
        }
        .media-files-grid .card-preview img,
        .media-files-grid .card .card-preview img,
        .media-files-grid .card-body .card-preview img {
            width: 100% !important;
            height: 180px !important;
            min-width: 180px !important;
            min-height: 180px !important;
            max-width: 180px !important;
            max-height: 180px !important;
            object-fit: contain !important;
            display: block !important;
        }
        .media-files-grid .card-preview i,
        .media-files-grid .card-preview svg,
        .media-files-grid .card-preview .ki-duotone,
        .media-files-grid .card-preview .ki-solid,
        .media-files-grid .card .card-preview i,
        .media-files-grid .card .card-preview svg,
        .media-files-grid .card .card-preview .ki-duotone,
        .media-files-grid .card .card-preview .ki-solid,
        .media-files-grid .card-body .card-preview i,
        .media-files-grid .card-body .card-preview svg,
        .media-files-grid .card-body .card-preview .ki-duotone,
        .media-files-grid .card-body .card-preview .ki-solid {
            font-size: 3rem !important;
            color: #6c757d !important;
            width: 3rem !important;
            height: 3rem !important;
            min-width: 3rem !important;
            min-height: 3rem !important;
            max-width: 3rem !important;
            max-height: 3rem !important;
            display: inline-block !important;
            flex-shrink: 0 !important;
        }
        .media-files-grid .card-preview .ki-duotone span,
        .media-files-grid .card-preview .ki-solid span {
            display: block !important;
        }
        .media-files-grid .card-title-section {
            flex: 1 !important;
            margin-bottom: 0.75rem !important;
        }
        .media-files-grid .card-title-section h6 {
            font-size: 0.875rem !important;
            font-weight: 600 !important;
            margin-bottom: 0.25rem !important;
            line-height: 1.3 !important;
            display: -webkit-box !important;
            -webkit-line-clamp: 2 !important;
            -webkit-box-orient: vertical !important;
            overflow: hidden !important;
        }
        .media-files-grid .card-meta {
            font-size: 0.75rem !important;
            color: #6c757d !important;
            margin-bottom: 0.25rem !important;
        }
        .media-files-grid .card-actions {
            margin-top: auto !important;
        }
    </style>
    @endpush

    <!--begin::Content container-->
    <div id="kt_app_content_container" class="app-container container-xxl">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <!--begin::Card-->
        <div class="card">
            <!--begin::Card header-->
            <div class="card-header border-0 pt-6">
                <div class="card-title">
                    <h3 class="fw-bold m-0">{{ __('common.uploaded_media') }}</h3>
                </div>
                <div class="card-toolbar">
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#kt_modal_media_upload">
                        {!! getIcon('plus', 'fs-2', '', 'i') !!}
                        {{ __('common.upload_file') }}
                    </button>
                </div>
            </div>
            <!--end::Card header-->

            <!--begin::Card body-->
            <div class="card-body pt-0">
                <!--begin::Filters-->
                <form method="GET" action="{{ route('media-library.index') }}" class="mb-10">
                    <div class="row g-3">
                        <div class="col-md-3">
                            <label class="form-label">{{ __('common.search') }}</label>
                            <input type="text" name="search" class="form-control" placeholder="{{ __('common.file_name_placeholder') }}" value="{{ $search }}">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">{{ __('common.type') }}</label>
                            <select name="type" class="form-select">
                                <option value="all" {{ $filterType === 'all' ? 'selected' : '' }}>{{ __('common.all') }}</option>
                                <option value="image" {{ $filterType === 'image' ? 'selected' : '' }}>{{ __('common.type_image') }}</option>
                                <option value="video" {{ $filterType === 'video' ? 'selected' : '' }}>{{ __('common.type_video') }}</option>
                                <option value="audio" {{ $filterType === 'audio' ? 'selected' : '' }}>{{ __('common.type_audio') }}</option>
                                <option value="document" {{ $filterType === 'document' ? 'selected' : '' }}>{{ __('common.type_document') }}</option>
                                <option value="other" {{ $filterType === 'other' ? 'selected' : '' }}>{{ __('common.type_other') }}</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">{{ __('common.category') }}</label>
                            <select name="category" class="form-select">
                                <option value="all" {{ ($filterCategory ?? 'all') === 'all' ? 'selected' : '' }}>{{ __('common.all') }}</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" {{ ($filterCategory ?? 'all') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3 d-flex align-items-end">
                            <button type="submit" class="btn btn-primary me-2">{{ __('common.filter') }}</button>
                            <a href="{{ route('media-library.index') }}" class="btn btn-light">{{ __('common.clear') }}</a>
                        </div>
                    </div>
                </form>
                <!--end::Filters-->
                @if(count($mediaFiles) > 0)
                    <div class="media-files-grid row g-3">
                        @foreach($mediaFiles as $file)
                            <div class="col-xl-3 col-lg-4 col-md-6 col-sm-6 col-12">
                                <div class="card card-flush h-100">
                                <div class="card-body">
                                    <div class="card-preview" style="width: 100% !important; height: 180px !important; min-width: 0 !important; min-height: 180px !important; max-width: 100% !important; max-height: 180px !important; display: flex !important; align-items: center !important; justify-content: center !important;">
                                        @if($file['type'] === 'image')
                                            <img src="{{ $file['url'] }}" alt="{{ $file['name'] }}" style="width: 100% !important; height: 180px !important; min-width: 0 !important; min-height: 180px !important; max-width: 100% !important; max-height: 180px !important; object-fit: contain !important; display: block !important;">
                                        @elseif($file['type'] === 'video')
                                            <i style="font-size: 48px !important; width: 48px !important; height: 48px !important; color: #6c757d !important;">{!! getIcon('video', '', 'duotone', 'i') !!}</i>
                                        @elseif($file['type'] === 'audio')
                                            <i style="font-size: 48px !important; width: 48px !important; height: 48px !important; color: #6c757d !important;">{!! getIcon('music', '', 'duotone', 'i') !!}</i>
                                        @elseif($file['type'] === 'document')
                                            <i style="font-size: 48px !important; width: 48px !important; height: 48px !important; color: #6c757d !important;">{!! getIcon('document', '', 'duotone', 'i') !!}</i>
                                        @else
                                            <i style="font-size: 48px !important; width: 48px !important; height: 48px !important; color: #6c757d !important;">{!! getIcon('file', '', 'duotone', 'i') !!}</i>
                                        @endif
                                    </div>
                                    
                                    <div class="card-title-section">
                                        <h6 class="text-gray-800 fw-bold" title="{{ $file['name'] }}">
                                            {{ Str::limit($file['name'], 25) }}
                                        </h6>
                                        <div class="card-meta">
                                            @php
                                                $bytes = $file['size'];
                                                $units = ['B', 'KB', 'MB', 'GB', 'TB'];
                                                $bytes = max($bytes, 0);
                                                $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
                                                $pow = min($pow, count($units) - 1);
                                                $bytes /= pow(1024, $pow);
                                                $formattedSize = round($bytes, 2) . ' ' . $units[$pow];
                                            @endphp
                                            {{ $formattedSize }}
                                        </div>
                                        @if(isset($file['category']) && $file['category'])
                                            <div class="mt-1">
                                                <span class="badge badge-light-info">{{ $file['category'] }}</span>
                                            </div>
                                        @endif
                                    </div>
                                    
                                    <div class="card-actions">
                                        <div class="d-flex gap-2">
                                            @if(isset($file['id']) && $file['id'])
                                                <a href="{{ route('media-library.show', $file['id']) }}" class="btn btn-sm btn-light-primary flex-grow-1">
                                                    {!! getIcon('eye', 'fs-5', '', 'i') !!}
                                                    {{ __('common.details') }}
                                                </a>
                                            @else
                                                <a href="{{ $file['url'] }}" target="_blank" class="btn btn-sm btn-light-primary flex-grow-1">
                                                    {!! getIcon('eye', 'fs-5', '', 'i') !!}
                                                    {{ __('common.view') }}
                                                </a>
                                            @endif
                                            <form action="{{ route('media-library.destroy', urlencode($file['path'])) }}" method="POST" class="d-inline" onsubmit="return confirm('{{ __('common.delete_media_confirm') }}');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-light-danger">
                                                    {!! getIcon('trash', 'fs-5', '', 'i') !!}
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-10">
                        <p class="text-gray-500 fs-6">{{ __('common.no_files_uploaded') }}</p>
                    </div>
                @endif
            </div>
            <!--end::Card body-->
        </div>
        <!--end::Card-->
    </div>
    <!--end::Content container-->

    <!--begin::Modal - Media Upload-->
    <div class="modal fade" id="kt_modal_media_upload" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered mw-650px">
            <div class="modal-content">
                <div class="modal-header">
                    <h2 class="fw-bold">{{ __('common.upload_file') }}</h2>
                    <div class="btn btn-icon btn-sm btn-active-icon-primary" data-bs-dismiss="modal">
                        {!! getIcon('cross', 'fs-1', '', 'i') !!}
                    </div>
                </div>
                <form id="kt_media_upload_form" action="{{ route('media-library.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="overwrite" id="overwrite_file" value="0">
                    <div class="modal-body scroll-y mx-5 mx-xl-15 my-7">
                        <div class="mb-5">
                            <label class="form-label required">{{ __('common.file') }}</label>
                            <input type="file" name="file" id="media_file_input" class="form-control" required accept="*/*">
                            <div class="form-text">{{ __('common.max_file_size') }}</div>
                        </div>
                        <div class="mb-5">
                            <label class="form-label required">{{ __('common.category') }}</label>
                            <select name="category_id" id="media_category_select" class="form-select" required>
                                <option value="">{{ __('common.select_category') }}</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                @endforeach
                            </select>
                            <div class="form-text">{{ __('common.category_required_for_upload') }}</div>
                        </div>
                        <div class="mb-5">
                            <label class="form-label">{{ __('common.file_name') }}</label>
                            <input type="text" name="name" id="media_file_name" class="form-control" placeholder="{{ __('common.file_name_optional_hint') }}">
                        </div>
                        <div class="mb-5">
                            <label class="form-label">{{ __('common.description') }}</label>
                            <textarea name="description" id="media_description" class="form-control" rows="3"></textarea>
                        </div>
                        <div class="mb-5">
                            <label class="form-check form-switch form-check-custom form-check-solid">
                                <input class="form-check-input" type="checkbox" name="is_public" id="media_is_public" value="1">
                                <span class="form-check-label">{{ __('common.public_file') }}</span>
                            </label>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">{{ __('common.cancel') }}</button>
                        <button type="submit" class="btn btn-primary" id="media_upload_submit">
                            <span class="indicator-label">{{ __('common.upload') }}</span>
                            <span class="indicator-progress">{{ __('common.loading') }}
                                <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                            </span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!--end::Modal - Media Upload-->

    @push('scripts')
    <style>
        .select2-dropdown-no-overflow {
            overflow: visible !important;
        }
        .select2-results__option {
            overflow: visible !important;
        }
        .select2-results__message {
            padding: 8px !important;
        }
    </style>
    <script>
        // Çeviri fonksiyonunu global scope'a taşı
        function getTranslationMedia(key) {
            if (typeof window.__ === 'function') {
                const translated = window.__(key);
                return translated !== key ? translated : key;
            }
            // Fallback çeviriler
            const fallbacks = {
                'common.select_category': '{{ __('common.select_category') }}',
                'common.add_new_category': '{{ __('common.add_new_category') }}',
                'common.new_category_name_prompt': '{{ __('common.new_category_name_prompt') }}',
                'common.category_create_error_message': '{{ __('common.category_create_error_message') }}',
                'common.category_created': '{{ __('common.category_created') }}'
            };
            return fallbacks[key] || key;
        }

        // Kategori Select2 başlatma ve yeni kategori ekleme
        document.addEventListener('DOMContentLoaded', function() {
            const categorySelect = document.getElementById('media_category_select');
            if (categorySelect && typeof $ !== 'undefined') {

                $(categorySelect).select2({
                    placeholder: getTranslationMedia('common.select_category'),
                    allowClear: true,
                    language: {
                        noResults: function() {
                            return '<div class="p-2"><button type="button" class="btn btn-sm btn-primary w-100" onclick="event.stopPropagation(); createNewCategoryMedia();">+ ' + getTranslationMedia('common.add_new_category') + '</button></div>';
                        }
                    },
                    escapeMarkup: function(markup) {
                        return markup;
                    },
                    dropdownCssClass: 'select2-dropdown-no-overflow'
                });

                // Select2 açıldığında "yeni ekle" seçeneğini ekle (sadece bir kez)
                $(categorySelect).on('select2:open', function() {
                    setTimeout(function() {
                        const results = $('.select2-results__options');
                        // Eğer zaten "yeni ekle" butonu varsa veya noResults gösteriliyorsa ekleme
                        if (results.find('.add-category-option').length === 0 && results.find('.select2-results__message').length === 0) {
                            results.prepend('<li class="select2-results__option add-category-option" style="cursor: pointer; padding: 8px;"><button type="button" class="btn btn-sm btn-primary w-100" onclick="event.stopPropagation(); createNewCategoryMedia();">+ ' + getTranslationMedia('common.add_new_category') + '</button></li>');
                        }
                    }, 100);
                });

                // Select2 kapandığında "yeni ekle" butonunu temizle
                $(categorySelect).on('select2:close', function() {
                    $('.add-category-option').remove();
                });
            }
        });

        function createNewCategoryMedia() {
            // Select2'yi kapat
            if (typeof $ !== 'undefined') {
                $('#media_category_select').select2('close');
            }
            
            const categoryName = prompt(getTranslationMedia('common.new_category_name_prompt'));
            if (categoryName && categoryName.trim()) {
                // FormData kullan (Laravel form request için)
                const formData = new FormData();
                formData.append('name', categoryName.trim());
                formData.append('_token', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));

                fetch('{{ route("categories.store") }}', {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(response => {
                    if (!response.ok) {
                        return response.json().then(err => {
                            throw new Error(err.message || getTranslationMedia('common.category_create_error_message'));
                        });
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.success || data.id) {
                        // Yeni kategoriyi select'e ekle
                        const categorySelect = document.getElementById('media_category_select');
                        const option = document.createElement('option');
                        option.value = data.id || data.category?.id;
                        option.textContent = categoryName.trim();
                        option.selected = true;
                        categorySelect.appendChild(option);
                        
                        // Select2'yi güncelle
                        if (typeof $ !== 'undefined') {
                            $(categorySelect).trigger('change');
                        }
                        
                        Swal.fire({
                            icon: 'success',
                            title: getTranslationMedia('common.success'),
                            text: getTranslationMedia('common.category_created'),
                            timer: 2000,
                            showConfirmButton: false
                        });
                    } else {
                        throw new Error(getTranslationMedia('common.category_create_error_message'));
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    Swal.fire({
                        icon: 'error',
                        title: getTranslationMedia('common.error'),
                        text: error.message || getTranslationMedia('common.category_create_error_message')
                    });
                });
            }
        }

        document.getElementById('kt_media_upload_form').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const form = this;
            const formData = new FormData(form);
            const submitBtn = document.getElementById('media_upload_submit');
            const indicator = submitBtn.querySelector('.indicator-progress');
            const label = submitBtn.querySelector('.indicator-label');
            
            // İlk gönderimde overwrite kontrolü yap
            if (formData.get('overwrite') === '0') {
                // AJAX ile dosya kontrolü yap
                fetch('{{ route("media-library.check-file") }}', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    },
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.file_exists) {
                        // Dosya mevcut, kullanıcıya sor
                        Swal.fire({
                            text: window.__('common.file_already_exists').replace(':name', data.file_name),
                            icon: 'warning',
                            showCancelButton: true,
                            confirmButtonText: window.__('common.overwrite_file'),
                            cancelButtonText: window.__('common.cancel'),
                            buttonsStyling: false,
                            customClass: {
                                confirmButton: 'btn btn-danger',
                                cancelButton: 'btn btn-secondary',
                            }
                        }).then((result) => {
                            if (result.isConfirmed) {
                                // Overwrite onayı verildi, tekrar gönder
                                document.getElementById('overwrite_file').value = '1';
                                submitBtn.setAttribute('data-kt-indicator', 'on');
                                label.style.display = 'none';
                                indicator.style.display = 'inline-block';
                                form.submit();
                            }
                        });
                    } else {
                        // Dosya yok, direkt gönder
                        submitBtn.setAttribute('data-kt-indicator', 'on');
                        label.style.display = 'none';
                        indicator.style.display = 'inline-block';
                        form.submit();
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    Swal.fire({
                        text: window.__('common.error_occurred'),
                        icon: 'error',
                        buttonsStyling: false,
                        confirmButtonText: window.__('common.ok'),
                        customClass: {
                            confirmButton: 'btn btn-primary'
                        }
                    });
                });
            } else {
                // Overwrite onayı verilmiş, direkt gönder
                submitBtn.setAttribute('data-kt-indicator', 'on');
                label.style.display = 'none';
                indicator.style.display = 'inline-block';
                form.submit();
            }
        });
    </script>
    
    <script>
        // Sayfa yüklendiğinde önizleme boyutlarını zorla uygula
        document.addEventListener('DOMContentLoaded', function() {
            function applyPreviewSizes() {
                const previews = document.querySelectorAll('.media-files-grid .card-preview');
                previews.forEach(preview => {
                    preview.style.setProperty('width', '100%', 'important');
                    preview.style.setProperty('height', '180px', 'important');
                    preview.style.setProperty('min-width', '0', 'important');
                    preview.style.setProperty('min-height', '180px', 'important');
                    preview.style.setProperty('max-width', '100%', 'important');
                    preview.style.setProperty('max-height', '180px', 'important');
                    preview.style.setProperty('display', 'flex', 'important');
                    preview.style.setProperty('align-items', 'center', 'important');
                    preview.style.setProperty('justify-content', 'center', 'important');
                    
                    const img = preview.querySelector('img');
                    if (img) {
                        img.style.setProperty('width', '100%', 'important');
                        img.style.setProperty('height', '180px', 'important');
                        img.style.setProperty('min-width', '0', 'important');
                        img.style.setProperty('min-height', '180px', 'important');
                        img.style.setProperty('max-width', '100%', 'important');
                        img.style.setProperty('max-height', '180px', 'important');
                        img.style.setProperty('object-fit', 'contain', 'important');
                        img.style.setProperty('display', 'block', 'important');
                    }
                    
                    const icons = preview.querySelectorAll('i, .ki-duotone, .ki-solid');
                    icons.forEach(icon => {
                        icon.style.setProperty('font-size', '48px', 'important');
                        icon.style.setProperty('width', '48px', 'important');
                        icon.style.setProperty('height', '48px', 'important');
                        icon.style.setProperty('min-width', '48px', 'important');
                        icon.style.setProperty('min-height', '48px', 'important');
                        icon.style.setProperty('max-width', '48px', 'important');
                        icon.style.setProperty('max-height', '48px', 'important');
                    });
                });
            }
            
            // İlk yüklemede uygula
            applyPreviewSizes();
            
            // MutationObserver ile dinamik eklenen elementleri de yakala
            const observer = new MutationObserver(function(mutations) {
                applyPreviewSizes();
            });
            
            const gridContainer = document.querySelector('.media-files-grid');
            if (gridContainer) {
                observer.observe(gridContainer, {
                    childList: true,
                    subtree: true
                });
            }
        });
    </script>
    @endpush

</x-default-layout>

