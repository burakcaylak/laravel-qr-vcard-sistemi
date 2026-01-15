<x-default-layout>

    @section('title')
        {{ __('common.create_brochure') }}
    @endsection

    @section('breadcrumbs')
        {{ Breadcrumbs::render('brochure.create') }}
    @endsection

    <div class="card">
        <div class="card-header">
            <div class="card-title">
                <span class="svg-icon svg-icon-success svg-icon-2 me-2">
                    <i class="ki-solid ki-document fs-2"></i>
                </span>
                <h3 class="fw-bold m-0 d-inline">{{ __('common.create_brochure') }}</h3>
            </div>
        </div>
        <div class="card-body">
            <form action="{{ route('brochure.store') }}" method="POST" id="brochure_form" enctype="multipart/form-data">
                @csrf
                
                <!-- Ad -->
                <div class="mb-5">
                    <label class="form-label required">{{ __('common.name') }}</label>
                    <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" required>
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Açıklama -->
                <div class="mb-5">
                    <label class="form-label">{{ __('common.description') }}</label>
                    <textarea name="description" class="form-control @error('description') is-invalid @enderror" rows="3">{{ old('description') }}</textarea>
                    @error('description')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Kategori Seçimi -->
                <div class="mb-5">
                    <label class="form-label">{{ __('common.category') }}</label>
                    <select name="category_id" id="category_id" class="form-select @error('category_id') is-invalid @enderror" data-control="select2" data-placeholder="{{ __('common.select_category') }}">
                        <option value="">{{ __('common.select_category') }}</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                        @endforeach
                    </select>
                    @error('category_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- PDF Dosyası -->
                <div class="mb-5">
                    <label class="form-label required">{{ __('common.pdf_file') }}</label>
                    <input type="hidden" name="file_id" id="file_id_input" value="{{ old('file_id') }}">
                    <div id="file_id_preview" class="mb-3">
                        @if(old('file_id'))
                            @php
                                $selectedFile = $files->firstWhere('id', old('file_id'));
                            @endphp
                            @if($selectedFile)
                                <div class="alert alert-info d-flex align-items-center justify-content-between">
                                    <span>{{ $selectedFile->name }} ({{ $selectedFile->size_human }})</span>
                                    <button type="button" class="btn btn-sm btn-light-danger" onclick="clearFileSelection()">
                                        <i class="ki-solid ki-cross fs-6"></i>
                                    </button>
                                </div>
                            @endif
                        @endif
                    </div>
                    <div class="d-flex gap-2">
                        <button type="button" class="btn btn-primary btn-sm" data-media-select="file_id">
                            <i class="ki-duotone ki-folder fs-5"><span class="path1"></span><span class="path2"></span></i>
                            {{ __('common.select_file') }}
                        </button>
                    </div>
                    <small class="form-text text-muted">{{ __('common.pdf_file_hint') }}</small>
                    @error('file_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    @error('pdf_file')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Arkaplan Ayarları -->
                <div class="separator separator-dashed my-5"></div>
                <h4 class="mb-5">{{ __('common.background_settings') }}</h4>

                <div class="row">
                    <!-- Sol: Renk Seçimi -->
                    <div class="col-md-6 mb-5" id="background_color_section">
                        <label class="form-label">{{ __('common.background_color') }}</label>
                        <div class="d-flex align-items-center gap-3">
                            <input type="color" name="background_color" class="form-control form-control-color w-100px h-50px @error('background_color') is-invalid @enderror" value="{{ old('background_color', '#ffffff') }}">
                            <input type="text" name="background_color_text" class="form-control @error('background_color') is-invalid @enderror" value="{{ old('background_color', '#ffffff') }}" pattern="^#([A-Fa-f0-9]{6}|[A-Fa-f0-9]{3})$" placeholder="#ffffff">
                        </div>
                        @error('background_color')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Sağ: Görsel Seçimi -->
                    <div class="col-md-6 mb-5" id="background_image_section">
                        <label class="form-label">{{ __('common.background_image') }}</label>
                        <input type="hidden" name="background_image_file_id" id="background_image_file_id_input" value="{{ old('background_image_file_id') }}">
                        <input type="hidden" name="background_type" id="background_type_input" value="{{ old('background_image_file_id') ? 'image' : 'color' }}">
                        <div id="background_image_preview" class="mb-3">
                            @if(old('background_image_file_id'))
                                @php
                                    $selectedBgFile = $files->firstWhere('id', old('background_image_file_id'));
                                @endphp
                                @if($selectedBgFile && in_array($selectedBgFile->type, ['image']))
                                    <div class="alert alert-info d-flex align-items-center justify-content-between">
                                        <span>
                                            <img src="{{ asset('storage/' . $selectedBgFile->path) }}" alt="Preview" class="img-thumbnail me-2" style="max-width: 50px; max-height: 50px;">
                                            {{ $selectedBgFile->name }}
                                        </span>
                                        <button type="button" class="btn btn-sm btn-light-danger" onclick="clearBackgroundImageSelection()">
                                            <i class="ki-solid ki-cross fs-6"></i>
                                        </button>
                                    </div>
                                @endif
                            @endif
                        </div>
                        <div class="d-flex gap-2">
                            <button type="button" class="btn btn-primary btn-sm" data-media-select="background_image">
                                <i class="ki-duotone ki-folder fs-5"><span class="path1"></span><span class="path2"></span></i>
                                {{ __('common.select_file') }}
                            </button>
                        </div>
                        <small class="form-text text-muted">{{ __('common.background_image_hint') }}</small>
                        @error('background_image')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
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

                <div class="mb-5">
                    <label class="form-label">{{ __('common.expires_at') }}</label>
                    <input type="date" name="expires_at" class="form-control" value="{{ old('expires_at') }}" min="{{ date('Y-m-d') }}">
                    <small class="form-text text-muted">{{ __('common.expires_at_hint') }}</small>
                </div>

                <div class="d-flex justify-content-end">
                    <a href="{{ route('brochure.index') }}" class="btn btn-light me-3">{{ __('common.cancel') }}</a>
                    <button type="submit" class="btn btn-primary">{{ __('common.save') }}</button>
                </div>
            </form>
        </div>
    </div>

    <!--begin::Modal - File Selector (PDF)-->
    <div class="modal fade" id="kt_modal_file_selector" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h2 class="fw-bold">{{ __('common.select_file') }}</h2>
                    <div class="btn btn-icon btn-sm btn-active-icon-primary" data-bs-dismiss="modal">
                        {!! getIcon('cross', 'fs-1', '', 'i') !!}
                    </div>
                </div>
                <div class="modal-body scroll-y mx-3 mx-xl-5 my-5">
                    <!--begin::Upload Option-->
                    <div class="mb-5">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">{{ __('common.upload_from_computer') }}</label>
                                <input type="file" id="file_upload_input" class="form-control" accept=".pdf,application/pdf">
                                <small class="form-text text-muted">{{ __('common.max_file_size') }}</small>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">{{ __('common.category') }}</label>
                                <select id="upload_category_select" class="form-select" data-control="select2" data-placeholder="{{ __('common.select_category') }}">
                                    <option value="">{{ __('common.select_category') }}</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <!--end::Upload Option-->

                    <!--begin::Filters-->
                    <div class="row g-3 mb-5">
                        <div class="col-md-4">
                            <label class="form-label">{{ __('common.search') }}</label>
                            <input type="text" id="file_search_input" class="form-control" placeholder="{{ __('common.file_name_placeholder') }}">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">{{ __('common.type') }}</label>
                            <select id="file_type_select" class="form-select">
                                <option value="all">{{ __('common.all') }}</option>
                                <option value="document" selected>{{ __('common.type_document') }}</option>
                                <option value="image">{{ __('common.type_image') }}</option>
                                <option value="video">{{ __('common.type_video') }}</option>
                                <option value="audio">{{ __('common.type_audio') }}</option>
                                <option value="other">{{ __('common.type_other') }}</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">{{ __('common.category') }}</label>
                            <select id="file_category_select" class="form-select">
                                <option value="all">{{ __('common.all') }}</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <!--end::Filters-->

                    <!--begin::Files List-->
                    <div id="files_list_container" class="row g-3">
                        <div class="col-12 text-center py-10">
                            <div class="spinner-border text-primary" role="status">
                                <span class="visually-hidden">{{ __('common.loading') }}</span>
                            </div>
                        </div>
                    </div>
                    <!--end::Files List-->
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">{{ __('common.cancel') }}</button>
                </div>
            </div>
        </div>
    </div>
    <!--end::Modal - File Selector (PDF)-->

    <!--begin::Modal - Background Image Selector-->
    <div class="modal fade" id="kt_modal_background_image_selector" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h2 class="fw-bold">{{ __('common.select_background_image') }}</h2>
                    <div class="btn btn-icon btn-sm btn-active-icon-primary" data-bs-dismiss="modal">
                        {!! getIcon('cross', 'fs-1', '', 'i') !!}
                    </div>
                </div>
                <div class="modal-body scroll-y mx-3 mx-xl-5 my-5">
                    <!--begin::Upload Option-->
                    <div class="mb-5">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">{{ __('common.upload_from_computer') }}</label>
                                <input type="file" id="bg_image_upload_input" class="form-control" accept="image/*">
                                <small class="form-text text-muted">{{ __('common.max_file_size') }}</small>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">{{ __('common.category') }}</label>
                                <select id="bg_upload_category_select" class="form-select" data-control="select2" data-placeholder="{{ __('common.select_category') }}">
                                    <option value="">{{ __('common.select_category') }}</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <!--end::Upload Option-->

                    <!--begin::Filters-->
                    <div class="row g-3 mb-5">
                        <div class="col-md-4">
                            <label class="form-label">{{ __('common.search') }}</label>
                            <input type="text" id="bg_image_search_input" class="form-control" placeholder="{{ __('common.file_name_placeholder') }}">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">{{ __('common.type') }}</label>
                            <select id="bg_image_type_select" class="form-select">
                                <option value="all">{{ __('common.all') }}</option>
                                <option value="image" selected>{{ __('common.type_image') }}</option>
                                <option value="document">{{ __('common.type_document') }}</option>
                                <option value="video">{{ __('common.type_video') }}</option>
                                <option value="audio">{{ __('common.type_audio') }}</option>
                                <option value="other">{{ __('common.type_other') }}</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">{{ __('common.category') }}</label>
                            <select id="bg_image_category_select" class="form-select">
                                <option value="all">{{ __('common.all') }}</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <!--end::Filters-->

                    <!--begin::Files List-->
                    <div id="bg_image_list_container" class="row g-3">
                        <div class="col-12 text-center py-10">
                            <div class="spinner-border text-primary" role="status">
                                <span class="visually-hidden">{{ __('common.loading') }}</span>
                            </div>
                        </div>
                    </div>
                    <!--end::Files List-->
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">{{ __('common.cancel') }}</button>
                </div>
            </div>
        </div>
    </div>
    <!--end::Modal - Background Image Selector-->

    @push('styles')
    <style>
        .select2-dropdown-no-overflow {
            overflow: visible !important;
        }
        #kt_modal_file_selector .modal-body {
            overflow-x: hidden !important;
            max-height: 70vh !important;
        }
        #kt_modal_file_selector .modal-content {
            overflow: hidden !important;
        }
        #files_list_container .card-preview,
        #files_list_container div.card-preview,
        #files_list_container .card .card-preview,
        #files_list_container .card-body .card-preview,
        #files_list_container * .card-preview {
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
        #files_list_container .card-preview img {
            width: 100% !important;
            height: 180px !important;
            object-fit: contain !important;
            display: block !important;
        }
        #files_list_container .card-preview i,
        #files_list_container .card-preview .ki-duotone,
        #files_list_container .card-preview .ki-solid {
            font-size: 48px !important;
            width: 48px !important;
            height: 48px !important;
        }
    </style>
    @endpush

    @push('scripts')
    <script>
        let files = @json($files);
        let selectedFileId = null;
        let selectedMediaPath = null;
        let currentFileField = null;
        let mediaFiles = [];
        
        // Arkaplan görseli için
        let selectedBackgroundImageId = null;
        let selectedBackgroundImagePath = null;
        let backgroundImageFiles = [];
        
        // Translation helper
        window.__ = function(key) {
            const translations = {
                'common.loading': '{{ __('common.loading') }}',
                'common.selected': '{{ __('common.selected') }}',
                'common.select': '{{ __('common.select') }}',
                'common.success': '{{ __('common.success') }}',
                'common.file_uploaded': '{{ __('common.file_uploaded') }}',
                'common.select_file': '{{ __('common.select_file') }}',
                'common.add_new_category': '{{ __('common.add_new_category') }}',
                'common.new_category_name_prompt': '{{ __('common.new_category_name_prompt') }}',
                'common.category_create_error_message': '{{ __('common.category_create_error_message') }}',
                'common.category_created': '{{ __('common.category_created') }}'
            };
            return translations[key] || key;
        };
        
        // Kategori ekleme fonksiyonları
        function createNewCategory() {
            if (typeof $ !== 'undefined') {
                $('#category_id').select2('close');
            }
            
            const categoryName = prompt(window.__('common.new_category_name_prompt'));
            if (categoryName && categoryName.trim()) {
                createCategoryAndAddToSelects(categoryName.trim(), 'category_id');
            }
        }

        function createCategoryAndAddToSelects(categoryName, targetSelectId) {
            const formData = new FormData();
            formData.append('name', categoryName);
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
                        throw new Error(err.message || window.__('common.category_create_error_message'));
                    });
                }
                return response.json();
            })
            .then(data => {
                if (data.success || data.id) {
                    const categoryId = data.id || data.category?.id;
                    const categoryName = data.name || data.category?.name;
                    
                    // Select2'ye yeni kategoriyi ekle
                    if (typeof $ !== 'undefined') {
                        const $select = $('#' + targetSelectId);
                        const option = new Option(categoryName, categoryId, true, true);
                        $select.append(option).trigger('change');
                    }
                    
                    // Başarı mesajı
                    if (typeof Swal !== 'undefined') {
                        Swal.fire({
                            icon: 'success',
                            title: '{{ __('common.success') }}',
                            text: '{{ __('common.category_created') }}',
                            timer: 2000,
                            showConfirmButton: false
                        });
                    }
                } else {
                    throw new Error(data.message || window.__('common.category_create_error_message'));
                }
            })
            .catch(error => {
                console.error('Category creation error:', error);
                alert(error.message || window.__('common.category_create_error_message'));
            });
        }

        // Debounce fonksiyonu
        function debounce(func, wait) {
            let timeout;
            return function executedFunction(...args) {
                const later = () => {
                    clearTimeout(timeout);
                    func(...args);
                };
                clearTimeout(timeout);
                timeout = setTimeout(later, wait);
            };
        }

        // Dosya seçimi modalı
        document.addEventListener('DOMContentLoaded', function() {
            // Kategori seçimi için Select2 başlatma ve yeni kategori ekleme özelliği
            const categorySelect = document.getElementById('category_id');
            if (categorySelect && typeof $ !== 'undefined') {
                $(categorySelect).select2({
                    placeholder: '{{ __('common.select_category') }}',
                    allowClear: true,
                    language: {
                        noResults: function() {
                            return '<div class="p-2"><button type="button" class="btn btn-sm btn-primary w-100" onclick="event.stopPropagation(); createNewCategory();">+ ' + window.__('common.add_new_category') + '</button></div>';
                        }
                    },
                    escapeMarkup: function(markup) {
                        return markup;
                    },
                    dropdownCssClass: 'select2-dropdown-no-overflow'
                });

                $(categorySelect).on('select2:open', function() {
                    setTimeout(function() {
                        const results = $('.select2-results__options');
                        if (results.find('.add-category-option').length === 0 && results.find('.select2-results__message').length === 0) {
                            results.prepend('<li class="select2-results__option add-category-option" style="cursor: pointer; padding: 8px;"><button type="button" class="btn btn-sm btn-primary w-100" onclick="event.stopPropagation(); createNewCategory();">+ ' + window.__('common.add_new_category') + '</button></li>');
                        }
                    }, 100);
                });

                $(categorySelect).on('select2:close', function() {
                    $('.add-category-option').remove();
                });
            }

            // PDF dosya seç butonuna tıklandığında
            document.querySelectorAll('[data-media-select="file_id"]').forEach(function(button) {
                button.addEventListener('click', function(e) {
                    e.preventDefault();
                    currentFileField = 'file_id';
                    openFileSelectorModal();
                });
            });

            // Arkaplan görseli seç butonuna tıklandığında
            document.querySelectorAll('[data-media-select="background_image"]').forEach(function(button) {
                button.addEventListener('click', function(e) {
                    e.preventDefault();
                    currentFileField = 'background_image';
                    openBackgroundImageSelectorModal();
                });
            });

            // Modal açıldığında dosyaları yükle
            const modalElement = document.getElementById('kt_modal_file_selector');
            if (modalElement) {
                modalElement.addEventListener('show.bs.modal', function() {
                    const fileIdInput = document.getElementById('file_id_input');
                    if (fileIdInput && fileIdInput.value) {
                        selectedFileId = fileIdInput.value;
                    } else {
                        selectedFileId = null;
                    }
                    selectedMediaPath = null;
                    loadFiles();
                });

                // Filtre değiştiğinde dosyaları yeniden yükle
                const searchInput = document.getElementById('file_search_input');
                const typeSelect = document.getElementById('file_type_select');
                const categorySelect = document.getElementById('file_category_select');
                const uploadInput = document.getElementById('file_upload_input');
                
                if (searchInput) {
                    searchInput.addEventListener('input', debounce(loadFiles, 300));
                }
                if (typeSelect) {
                    typeSelect.addEventListener('change', loadFiles);
                }
                if (categorySelect) {
                    categorySelect.addEventListener('change', loadFiles);
                }
                if (uploadInput) {
                    uploadInput.addEventListener('change', function(e) {
                        const file = e.target.files[0];
                        if (file) {
                            uploadFile(file);
                        }
                    });
                }
            }

            // Arkaplan görseli modalı
            const bgModalElement = document.getElementById('kt_modal_background_image_selector');
            if (bgModalElement) {
                bgModalElement.addEventListener('show.bs.modal', function() {
                    const bgFileIdInput = document.getElementById('background_image_file_id_input');
                    if (bgFileIdInput && bgFileIdInput.value) {
                        selectedBackgroundImageId = bgFileIdInput.value;
                    } else {
                        selectedBackgroundImageId = null;
                    }
                    selectedBackgroundImagePath = null;
                    loadBackgroundImages();
                });

                const bgSearchInput = document.getElementById('bg_image_search_input');
                const bgTypeSelect = document.getElementById('bg_image_type_select');
                const bgCategorySelect = document.getElementById('bg_image_category_select');
                const bgUploadInput = document.getElementById('bg_image_upload_input');
                
                if (bgSearchInput) {
                    bgSearchInput.addEventListener('input', debounce(loadBackgroundImages, 300));
                }
                if (bgTypeSelect) {
                    bgTypeSelect.addEventListener('change', loadBackgroundImages);
                }
                if (bgCategorySelect) {
                    bgCategorySelect.addEventListener('change', loadBackgroundImages);
                }
                if (bgUploadInput) {
                    bgUploadInput.addEventListener('change', function(e) {
                        const file = e.target.files[0];
                        if (file) {
                            uploadBackgroundImage(file);
                        }
                    });
                }
            }
        });

        function openFileSelectorModal() {
            const modalElement = document.getElementById('kt_modal_file_selector');
            if (modalElement) {
                if (typeof bootstrap !== 'undefined' && bootstrap.Modal) {
                    const modal = new bootstrap.Modal(modalElement);
                    modal.show();
                } else if (typeof $ !== 'undefined' && $.fn.modal) {
                    $(modalElement).modal('show');
                }
            }
        }

        function loadFiles() {
            const searchInput = document.getElementById('file_search_input');
            const typeSelect = document.getElementById('file_type_select');
            const categorySelect = document.getElementById('file_category_select');
            const container = document.getElementById('files_list_container');

            if (!searchInput || !typeSelect || !categorySelect || !container) return;

            const search = searchInput.value || '';
            const type = typeSelect.value || 'all';
            const category = categorySelect.value || 'all';

            // Loading göster
            container.innerHTML = '<div class="col-12 text-center py-10"><div class="spinner-border text-primary" role="status"><span class="visually-hidden">{{ __('common.loading') }}</span></div></div>';

            // Media Library'den tüm dosyaları yükle (File modelindeki dosyalar)
            const params = new URLSearchParams();
            if (search) params.append('search', search);
            if (type !== 'all') params.append('type', type);
            if (category !== 'all') params.append('category', category);

            fetch('{{ route("media-library.index") }}?' + params.toString(), {
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success !== false) {
                    // Media Library'den gelen dosyaları formatla - PDF dosyalarını filtrele
                    const dbFiles = (data.files || []).filter(file => {
                        if (!file.id) return false;
                        return file.type === 'document' || file.mime_type === 'application/pdf' || 
                               (file.original_name && file.original_name.toLowerCase().endsWith('.pdf'));
                    }).map(file => ({
                        id: file.id,
                        name: file.name,
                        original_name: file.original_name,
                        path: file.path,
                        type: file.type,
                        mime_type: file.mime_type,
                        size: file.size
                    }));
                    
                    // File modelinde olmayan dosyalar (settings klasöründeki) - PDF olanları filtrele
                    mediaFiles = (data.files || []).filter(file => {
                        if (file.id !== null) return false;
                        return file.type === 'document' || file.mime_type === 'application/pdf' || 
                               (file.name && file.name.toLowerCase().endsWith('.pdf'));
                    });
                    
                    // Tek seferde render et
                    renderFilesList(dbFiles, mediaFiles);
                } else {
                    renderFilesList([], []);
                }
            })
            .catch(error => {
                console.error('Error loading media files:', error);
                renderFilesList([], []);
            });
        }

        function renderFilesList(dbFiles, mediaFiles = []) {
            const container = document.getElementById('files_list_container');
            if (!container) return;

            let html = '';

            // Veritabanındaki dosyalar
            if (dbFiles && dbFiles.length > 0) {
                dbFiles.forEach(file => {
                    const isSelected = selectedFileId == file.id;
                    
                    html += `
                        <div class="col-xl-3 col-lg-4 col-md-6 col-sm-6 col-12">
                            <div class="card card-flush file-item h-100" data-file-id="${file.id}" data-file-name="${file.name}" data-file-selected="${isSelected}" ${isSelected ? 'style="border: 2px solid #0d6efd; border-radius: 8px;"' : ''}>
                                <div class="card-body">
                                    <div class="card-preview" style="width: 100% !important; height: 180px !important; min-width: 0 !important; min-height: 180px !important; max-width: 100% !important; max-height: 180px !important; display: flex !important; align-items: center !important; justify-content: center !important;">
                                        <i class="ki-duotone ki-file text-gray-400" style="font-size: 48px !important; width: 48px !important; height: 48px !important;"><span class="path1"></span><span class="path2"></span></i>
                                    </div>
                                    <div class="card-title-section">
                                        <h6 class="text-gray-800 fw-bold" title="${file.name}">
                                            ${truncateString(file.name, 25)}
                                        </h6>
                                        <div class="card-meta">${truncateString(file.original_name, 30)}</div>
                                    </div>
                                    <div class="card-actions file-action-buttons-${file.id}">
                                        ${isSelected ? `
                                            <div class="d-flex gap-2">
                                                <button type="button" class="btn btn-sm btn-success w-100" disabled>
                                                    <i class="ki-solid ki-check fs-6"></i> {{ __('common.selected') }}
                                                </button>
                                                <button type="button" class="btn btn-sm btn-light-danger" onclick="event.stopPropagation(); clearFileSelectionUI('${file.id}', 'db');">
                                                    <i class="ki-solid ki-cross fs-6"></i>
                                                </button>
                                            </div>
                                        ` : `
                                            <button type="button" class="btn btn-sm btn-primary w-100 file-select-btn" data-file-id="${file.id}" data-file-name="${file.name}">
                                                {{ __('common.select') }}
                                            </button>
                                        `}
                                    </div>
                                </div>
                            </div>
                        </div>
                    `;
                });
            }

            // Media library dosyaları
            if (mediaFiles && mediaFiles.length > 0) {
                mediaFiles.forEach(file => {
                    // Eğer bu dosya zaten DB dosyaları arasında varsa, media library versiyonunu gösterme
                    const existingDbFile = dbFiles.find(dbFile => dbFile.path === file.path);
                    if (existingDbFile) {
                        return;
                    }
                    
                    const fileKey = file.path.replace(/[^a-zA-Z0-9]/g, '_');
                    const isSelected = selectedMediaPath == file.path;
                    
                    html += `
                        <div class="col-xl-3 col-lg-4 col-md-6 col-sm-6 col-12">
                            <div class="card card-flush media-file-item h-100" data-file-path="${file.path}" data-file-url="${file.url}" data-file-selected="${isSelected}" ${isSelected ? 'style="border: 2px solid #0d6efd; border-radius: 8px;"' : ''}>
                                <div class="card-body">
                                    <div class="card-preview" style="width: 100% !important; height: 180px !important; min-width: 0 !important; min-height: 180px !important; max-width: 100% !important; max-height: 180px !important; display: flex !important; align-items: center !important; justify-content: center !important;">
                                        <i class="ki-duotone ki-file text-gray-400" style="font-size: 48px !important; width: 48px !important; height: 48px !important;"><span class="path1"></span><span class="path2"></span></i>
                                    </div>
                                    <div class="card-title-section">
                                        <h6 class="text-gray-800 fw-bold" title="${file.name}">
                                            ${truncateString(file.name, 25)}
                                        </h6>
                                        <div class="card-meta">Media Library</div>
                                    </div>
                                    <div class="card-actions media-action-buttons-${fileKey}">
                                        ${isSelected ? `
                                            <div class="d-flex gap-2">
                                                <button type="button" class="btn btn-sm btn-success w-100" disabled>
                                                    <i class="ki-solid ki-check fs-6"></i> {{ __('common.selected') }}
                                                </button>
                                                <button type="button" class="btn btn-sm btn-light-danger" onclick="event.stopPropagation(); clearMediaFileSelectionUI('${fileKey}');">
                                                    <i class="ki-solid ki-cross fs-6"></i>
                                                </button>
                                            </div>
                                        ` : `
                                            <button type="button" class="btn btn-sm btn-primary w-100 media-select-btn" data-file-path="${file.path}" data-file-url="${file.url}">
                                                {{ __('common.select') }}
                                            </button>
                                        `}
                                    </div>
                                </div>
                            </div>
                        </div>
                    `;
                });
            }

            if (!html) {
                html = '<div class="col-12 text-center py-10"><p class="text-gray-500">PDF dosyası bulunamadı.</p></div>';
            }

            container.innerHTML = html;

            // Render sonrası inline style'ları zorla uygula
            setTimeout(() => {
                const previews = container.querySelectorAll('.card-preview');
                previews.forEach(preview => {
                    preview.style.width = '100%';
                    preview.style.height = '180px';
                    preview.style.minWidth = '0';
                    preview.style.minHeight = '180px';
                    preview.style.maxWidth = '100%';
                    preview.style.maxHeight = '180px';
                    preview.style.display = 'flex';
                    preview.style.alignItems = 'center';
                    preview.style.justifyContent = 'center';
                });
            }, 100);

            // Event listener'ları ekle
            document.querySelectorAll('.file-select-btn').forEach(btn => {
                btn.addEventListener('click', function(e) {
                    e.stopPropagation();
                    const fileId = this.getAttribute('data-file-id');
                    const fileName = this.getAttribute('data-file-name');
                    const card = this.closest('.file-item');
                    
                    // Önceki seçimleri temizle
                    if (selectedFileId && selectedFileId != fileId) {
                        const prevCard = document.querySelector(`.file-item[data-file-id="${selectedFileId}"]`);
                        if (prevCard) {
                            updateFileSelectionUI(prevCard, selectedFileId, false);
                        }
                    }
                    if (selectedMediaPath) {
                        const prevFileKey = selectedMediaPath.replace(/[^a-zA-Z0-9]/g, '_');
                        const prevCard = document.querySelector(`.media-file-item[data-file-path="${selectedMediaPath}"]`);
                        if (prevCard) {
                            updateMediaFileSelectionUI(prevCard, prevFileKey, false);
                        }
                    }
                    
                    // Seçildi durumunu göster
                    updateFileSelectionUI(card, fileId, true);
                    selectFile(fileId, fileName, 'db');
                });
            });

            document.querySelectorAll('.media-select-btn').forEach(btn => {
                btn.addEventListener('click', function(e) {
                    e.stopPropagation();
                    const filePath = this.getAttribute('data-file-path');
                    const fileUrl = this.getAttribute('data-file-url');
                    const fileName = fileUrl.split('/').pop();
                    const card = this.closest('.media-file-item');
                    const fileKey = filePath.replace(/[^a-zA-Z0-9]/g, '_');
                    
                    // Önceki seçimleri temizle
                    if (selectedMediaPath && selectedMediaPath != filePath) {
                        const prevFileKey = selectedMediaPath.replace(/[^a-zA-Z0-9]/g, '_');
                        const prevCard = document.querySelector(`.media-file-item[data-file-path="${selectedMediaPath}"]`);
                        if (prevCard) {
                            updateMediaFileSelectionUI(prevCard, prevFileKey, false);
                        }
                    }
                    if (selectedFileId) {
                        const prevCard = document.querySelector(`.file-item[data-file-id="${selectedFileId}"]`);
                        if (prevCard) {
                            updateFileSelectionUI(prevCard, selectedFileId, false);
                        }
                    }
                    
                    // Media library dosyasını seçili olarak işaretle
                    if (card) {
                        updateMediaFileSelectionUI(card, fileKey, true);
                    }
                    selectedMediaPath = filePath;
                    selectedFileId = null;
                    createFileFromPath(filePath, fileName);
                });
            });
        }

        function truncateString(str, maxLength) {
            if (!str) return '';
            return str.length > maxLength ? str.substring(0, maxLength) + '...' : str;
        }

        function selectFile(fileId, fileName, source) {
            selectedFileId = fileId;
            selectedMediaPath = null;
            
            // Input'a değeri yaz
            const fileIdInput = document.getElementById('file_id_input');
            if (fileIdInput) {
                fileIdInput.value = fileId;
            }
            
            // Preview'ı güncelle
            const previewDiv = document.getElementById('file_id_preview');
            if (previewDiv) {
                previewDiv.innerHTML = `
                    <div class="alert alert-success d-flex align-items-center justify-content-between">
                        <span><i class="ki-solid ki-check fs-6 me-2"></i>${fileName}</span>
                        <button type="button" class="btn btn-sm btn-light-danger" onclick="clearFileSelection()">
                            <i class="ki-solid ki-cross fs-6"></i>
                        </button>
                    </div>
                `;
            }
            
            // PDF input'unu devre dışı bırak
            const pdfInput = document.querySelector('input[name="pdf_file"]');
            if (pdfInput) {
                pdfInput.required = false;
                pdfInput.disabled = true;
            }
            
            // Modal'ı kapat
            const modalElement = document.getElementById('kt_modal_file_selector');
            if (modalElement) {
                if (typeof bootstrap !== 'undefined' && bootstrap.Modal) {
                    const modal = bootstrap.Modal.getInstance(modalElement);
                    if (modal) modal.hide();
                } else if (typeof $ !== 'undefined' && $.fn.modal) {
                    $(modalElement).modal('hide');
                }
            }
        }

        function updateFileSelectionUI(card, fileId, selected) {
            if (!card) return;
            
            const buttonsContainer = card.querySelector(`.file-action-buttons-${fileId}`);
            if (!buttonsContainer) return;
            
            if (selected) {
                card.setAttribute('data-file-selected', 'true');
                card.style.border = '2px solid #0d6efd';
                card.style.borderRadius = '8px';
                buttonsContainer.innerHTML = `
                    <div class="d-flex gap-2">
                        <button type="button" class="btn btn-sm btn-success w-100" disabled>
                            <i class="ki-solid ki-check fs-6"></i> {{ __('common.selected') }}
                        </button>
                        <button type="button" class="btn btn-sm btn-light-danger" onclick="event.stopPropagation(); clearFileSelectionUI('${fileId}', 'db');">
                            <i class="ki-solid ki-cross fs-6"></i>
                        </button>
                    </div>
                `;
            } else {
                card.setAttribute('data-file-selected', 'false');
                card.style.border = '';
                card.style.borderRadius = '';
                buttonsContainer.innerHTML = `
                    <button type="button" class="btn btn-sm btn-primary w-100 file-select-btn" data-file-id="${fileId}" data-file-name="${card.getAttribute('data-file-name')}">
                        {{ __('common.select') }}
                    </button>
                `;
                // Event listener'ı tekrar ekle
                buttonsContainer.querySelector('.file-select-btn').addEventListener('click', function(e) {
                    e.stopPropagation();
                    selectFile(fileId, card.getAttribute('data-file-name'), 'db');
                });
            }
        }

        function updateMediaFileSelectionUI(card, fileKey, selected) {
            if (!card) return;
            
            const buttonsContainer = card.querySelector(`.media-action-buttons-${fileKey}`);
            if (!buttonsContainer) return;
            
            if (selected) {
                card.setAttribute('data-file-selected', 'true');
                card.style.border = '2px solid #0d6efd';
                card.style.borderRadius = '8px';
                buttonsContainer.innerHTML = `
                    <div class="d-flex gap-2">
                        <button type="button" class="btn btn-sm btn-success w-100" disabled>
                            <i class="ki-solid ki-check fs-6"></i> {{ __('common.selected') }}
                        </button>
                        <button type="button" class="btn btn-sm btn-light-danger" onclick="event.stopPropagation(); clearMediaFileSelectionUI('${fileKey}');">
                            <i class="ki-solid ki-cross fs-6"></i>
                        </button>
                    </div>
                `;
            } else {
                card.setAttribute('data-file-selected', 'false');
                card.style.border = '';
                card.style.borderRadius = '';
                const filePath = card.getAttribute('data-file-path');
                const fileUrl = card.getAttribute('data-file-url');
                buttonsContainer.innerHTML = `
                    <button type="button" class="btn btn-sm btn-primary w-100 media-select-btn" data-file-path="${filePath}" data-file-url="${fileUrl}">
                        {{ __('common.select') }}
                    </button>
                `;
                // Event listener'ı tekrar ekle
                buttonsContainer.querySelector('.media-select-btn').addEventListener('click', function(e) {
                    e.stopPropagation();
                    const fileName = fileUrl.split('/').pop();
                    createFileFromPath(filePath, fileName);
                });
            }
        }

        function clearFileSelectionUI(fileId, source) {
            selectedFileId = null;
            selectedMediaPath = null;
            const fileIdInput = document.getElementById('file_id_input');
            const fileIdPreview = document.getElementById('file_id_preview');
            if (fileIdInput) fileIdInput.value = '';
            if (fileIdPreview) fileIdPreview.innerHTML = '';
            
            // PDF input'unu aktif et
            const pdfInput = document.querySelector('input[name="pdf_file"]');
            if (pdfInput) {
                pdfInput.required = true;
                pdfInput.disabled = false;
            }
            
            // Dosya listesini yeniden yükle
            loadFiles();
        }

        function clearMediaFileSelectionUI(fileKey) {
            selectedMediaPath = null;
            selectedFileId = null;
            const fileIdInput = document.getElementById('file_id_input');
            const fileIdPreview = document.getElementById('file_id_preview');
            if (fileIdInput) fileIdInput.value = '';
            if (fileIdPreview) fileIdPreview.innerHTML = '';
            
            // PDF input'unu aktif et
            const pdfInput = document.querySelector('input[name="pdf_file"]');
            if (pdfInput) {
                pdfInput.required = true;
                pdfInput.disabled = false;
            }
            
            // Dosya listesini yeniden yükle
            loadFiles();
        }

        function createFileFromPath(path, fileName) {
            selectedMediaPath = path;
            selectedFileId = null;

            fetch('{{ route("file-management.create-from-path") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    path: path,
                    name: fileName
                })
            })
            .then(response => {
                if (!response.ok) {
                    return response.json().then(err => {
                        throw new Error(err.message || 'Dosya oluşturulamadı.');
                    });
                }
                return response.json();
            })
            .then(data => {
                if (data.success && data.id) {
                    // Yeni dosyayı files array'ine ekle
                    files.push({
                        id: data.id,
                        name: data.file.name,
                        original_name: data.file.original_name
                    });
                    selectedFileId = data.id;
                    selectedMediaPath = null;
                    selectFile(data.id, data.file.name || fileName, 'media');
                } else {
                    throw new Error(data.message || 'Dosya oluşturulamadı.');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Dosya oluşturulurken bir hata oluştu: ' + error.message);
                selectedMediaPath = null;
                selectedFileId = null;
                loadFiles();
            });
        }

        function uploadFile(file) {
            // PDF kontrolü
            if (file.type !== 'application/pdf' && !file.name.toLowerCase().endsWith('.pdf')) {
                alert('Sadece PDF dosyaları yüklenebilir.');
                document.getElementById('file_upload_input').value = '';
                return;
            }

            // Loading göstergesi göster
            const uploadContainer = document.querySelector('#kt_modal_file_selector .modal-body');
            const loadingHtml = `
                <div id="upload_loading" class="position-fixed top-0 start-0 w-100 h-100 d-flex align-items-center justify-content-center" style="background: rgba(0,0,0,0.5); z-index: 9999;">
                    <div class="bg-white rounded p-5 text-center">
                        <div class="spinner-border text-primary mb-3" role="status" style="width: 3rem; height: 3rem;">
                            <span class="visually-hidden">Yükleniyor...</span>
                        </div>
                        <div class="fw-bold">{{ __('common.loading') }}</div>
                        <div class="text-muted mt-2">${file.name}</div>
                    </div>
                </div>
            `;
            if (uploadContainer) {
                uploadContainer.insertAdjacentHTML('beforeend', loadingHtml);
            }

            const formData = new FormData();
            formData.append('file', file);
            formData.append('folder', 'files');
            
            const uploadCategorySelect = document.getElementById('upload_category_select');
            if (uploadCategorySelect && uploadCategorySelect.value) {
                formData.append('category_id', uploadCategorySelect.value);
            } else {
                const categorySelect = document.getElementById('file_category_select');
                if (categorySelect && categorySelect.value && categorySelect.value !== 'all') {
                    formData.append('category_id', categorySelect.value);
                }
            }

            const csrfToken = document.querySelector('meta[name="csrf-token"]');
            if (!csrfToken) {
                alert('CSRF token bulunamadı. Sayfayı yenileyin.');
                const loadingEl = document.getElementById('upload_loading');
                if (loadingEl) loadingEl.remove();
                return;
            }

            // Dosya boyutu kontrolü (config'den al)
            const maxFileSize = {{ config('files.max_file_size') * 1024 }}; // KB'den byte'a çevir
            if (file.size > maxFileSize) {
                const maxSizeMB = (maxFileSize / (1024 * 1024)).toFixed(2);
                const fileSizeMB = (file.size / (1024 * 1024)).toFixed(2);
                alert(`Dosya boyutu çok büyük! Maksimum: ${maxSizeMB} MB, Seçilen dosya: ${fileSizeMB} MB`);
                const loadingEl = document.getElementById('upload_loading');
                if (loadingEl) loadingEl.remove();
                return;
            }

            const uploadUrl = '{{ route("media-library.store") }}';
            console.log('Upload URL:', uploadUrl);
            console.log('File size:', file.size);
            console.log('File type:', file.type);
            console.log('CSRF Token:', csrfToken.getAttribute('content') ? 'Present' : 'Missing');
            console.log('Max file size (bytes):', {{ config('files.max_file_size') * 1024 }});

            // Timeout kaldırıldı - büyük dosyalar için yeterli süre verilsin
            fetch(uploadUrl, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': csrfToken.getAttribute('content'),
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                credentials: 'same-origin'
            })
            .then(async response => {
                console.log('Response status:', response.status);
                console.log('Response ok:', response.ok);
                console.log('Response headers:', Object.fromEntries(response.headers.entries()));
                
                // Response'u önce text olarak al
                const responseText = await response.text();
                console.log('Response text (first 500 chars):', responseText.substring(0, 500));
                
                if (!response.ok) {
                    let errorMessage = 'Dosya yüklenirken bir hata oluştu.';
                    try {
                        const errorData = JSON.parse(responseText);
                        console.error('Error data:', errorData);
                        if (errorData.message) {
                            errorMessage = errorData.message;
                        } else if (errorData.errors) {
                            const firstError = Object.values(errorData.errors)[0];
                            errorMessage = Array.isArray(firstError) ? firstError[0] : firstError;
                        }
                    } catch (e) {
                        console.error('Error parsing error response:', e);
                        errorMessage = 'Sunucu hatası: ' + response.status + ' ' + response.statusText;
                    }
                    throw new Error(errorMessage);
                }
                
                // Response'u JSON'a çevir
                let responseData;
                try {
                    responseData = JSON.parse(responseText);
                    console.log('Parsed response data:', responseData);
                } catch (parseError) {
                    console.error('JSON parse error:', parseError);
                    console.error('Response text:', responseText);
                    throw new Error('Sunucudan geçersiz yanıt alındı: ' + parseError.message);
                }
                
                return responseData;
            })
            .then(data => {
                console.log('Upload success data:', data);
                
                // Loading'i kaldır
                const loadingEl = document.getElementById('upload_loading');
                if (loadingEl) loadingEl.remove();

                if (data && data.success) {
                    console.log('Upload successful, reloading files...');
                    
                    // Upload input'u temizle
                    const uploadInput = document.getElementById('file_upload_input');
                    if (uploadInput) {
                        uploadInput.value = '';
                    }
                    
                    // Dosyaları yeniden yükle
                    if (typeof loadFiles === 'function') {
                        loadFiles();
                    } else {
                        console.error('loadFiles function not found');
                    }
                    
                    // Başarı mesajı
                    if (typeof Swal !== 'undefined') {
                        Swal.fire({
                            icon: 'success',
                            title: '{{ __('common.success') }}',
                            text: '{{ __('common.file_uploaded') }}',
                            timer: 2000,
                            showConfirmButton: false
                        }).then(() => {
                            console.log('Success message closed');
                        });
                    } else {
                        alert('{{ __('common.file_uploaded') }}');
                    }
                    
                    console.log('Upload process completed');
                } else {
                    console.error('Upload failed:', data);
                    throw new Error(data?.message || 'Dosya yüklenemedi.');
                }
            })
            .catch(error => {
                // Loading'i kaldır
                const loadingEl = document.getElementById('upload_loading');
                if (loadingEl) loadingEl.remove();

                console.error('Upload error:', error);
                console.error('Error name:', error.name);
                console.error('Error message:', error.message);
                console.error('Error stack:', error.stack);
                
                let errorMessage = 'Dosya yüklenirken bir hata oluştu.';
                
                if (error.name === 'AbortError') {
                    errorMessage = 'İstek zaman aşımına uğradı. Dosya çok büyük olabilir. Lütfen daha küçük bir dosya deneyin.';
                } else if (error.message) {
                    errorMessage = error.message;
                } else if (error.name === 'TypeError') {
                    if (error.message && error.message.includes('fetch')) {
                        errorMessage = 'Sunucuya bağlanılamadı. Lütfen:\n1. İnternet bağlantınızı kontrol edin\n2. Sayfayı yenileyin\n3. Tarayıcı konsolunu kontrol edin (F12)';
                    } else {
                        errorMessage = 'Beklenmeyen bir hata oluştu. Lütfen sayfayı yenileyin.';
                    }
                } else if (error.name === 'NetworkError' || error.message.includes('Failed to fetch')) {
                    errorMessage = 'Ağ hatası oluştu. Lütfen:\n1. İnternet bağlantınızı kontrol edin\n2. Sayfayı yenileyin\n3. Dosya boyutunun çok büyük olmadığından emin olun\n4. Tarayıcı konsolundaki hata mesajlarını kontrol edin';
                }
                
                if (typeof Swal !== 'undefined') {
                    Swal.fire({
                        icon: 'error',
                        title: 'Hata',
                        text: errorMessage,
                        confirmButtonText: 'Tamam',
                        footer: '<small>Detaylar için tarayıcı konsolunu kontrol edin (F12)</small>',
                        width: '600px'
                    });
                } else {
                    alert(errorMessage);
                }
                
                document.getElementById('file_upload_input').value = '';
            });
        }

        function clearFileSelection() {
            selectedFileId = null;
            selectedMediaPath = null;
            
            // Input'u temizle
            const fileIdInput = document.getElementById('file_id_input');
            if (fileIdInput) {
                fileIdInput.value = '';
            }
            
            // Preview'ı temizle
            const previewDiv = document.getElementById('file_id_preview');
            if (previewDiv) {
                previewDiv.innerHTML = '';
            }
            
            // PDF input'unu aktif et
            const pdfInput = document.querySelector('input[name="pdf_file"]');
            if (pdfInput) {
                pdfInput.required = true;
                pdfInput.disabled = false;
            }
            
            // Modal'daki seçimi temizle
            if (selectedFileId) {
                const card = document.querySelector(`.file-item[data-file-id="${selectedFileId}"]`);
                if (card) {
                    card.style.border = '';
                    const actions = card.querySelector('.card-actions');
                    if (actions) {
                        const fileName = card.getAttribute('data-file-name');
                        actions.innerHTML = `<button type="button" class="btn btn-sm btn-primary w-100 file-select-btn" data-file-id="${selectedFileId}" data-file-name="${fileName}">Seç</button>`;
                    }
                }
            }
        }

        // Arkaplan görseli modal fonksiyonları
        function openBackgroundImageSelectorModal() {
            const modalElement = document.getElementById('kt_modal_background_image_selector');
            if (modalElement) {
                if (typeof bootstrap !== 'undefined' && bootstrap.Modal) {
                    const modal = new bootstrap.Modal(modalElement);
                    modal.show();
                } else if (typeof $ !== 'undefined' && $.fn.modal) {
                    $(modalElement).modal('show');
                }
            }
        }

        function loadBackgroundImages() {
            const searchInput = document.getElementById('bg_image_search_input');
            const typeSelect = document.getElementById('bg_image_type_select');
            const categorySelect = document.getElementById('bg_image_category_select');
            const container = document.getElementById('bg_image_list_container');

            if (!searchInput || !typeSelect || !categorySelect || !container) return;

            const search = searchInput.value || '';
            const type = typeSelect.value || 'all';
            const category = categorySelect.value || 'all';

            container.innerHTML = '<div class="col-12 text-center py-10"><div class="spinner-border text-primary" role="status"><span class="visually-hidden">{{ __('common.loading') }}</span></div></div>';

            const params = new URLSearchParams();
            if (search) params.append('search', search);
            if (type !== 'all') params.append('type', type);
            if (category !== 'all') params.append('category', category);

            fetch('{{ route("media-library.index") }}?' + params.toString(), {
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success !== false) {
                    const dbFiles = (data.files || []).filter(file => {
                        if (!file.id) return false;
                        return file.type === 'image';
                    }).map(file => ({
                        id: file.id,
                        name: file.name,
                        original_name: file.original_name,
                        path: file.path,
                        type: file.type,
                        mime_type: file.mime_type,
                        size: file.size,
                        url: file.url
                    }));
                    
                    backgroundImageFiles = (data.files || []).filter(file => {
                        if (file.id !== null) return false;
                        return file.type === 'image';
                    });
                    
                    renderBackgroundImageList(dbFiles, backgroundImageFiles);
                } else {
                    renderBackgroundImageList([], []);
                }
            })
            .catch(error => {
                console.error('Error loading background images:', error);
                renderBackgroundImageList([], []);
            });
        }

        function renderBackgroundImageList(dbFiles, mediaFiles = []) {
            const container = document.getElementById('bg_image_list_container');
            if (!container) return;

            let html = '';

            if (dbFiles && dbFiles.length > 0) {
                dbFiles.forEach(file => {
                    const isSelected = selectedBackgroundImageId == file.id;
                    
                    html += `
                        <div class="col-xl-3 col-lg-4 col-md-6 col-sm-6 col-12">
                            <div class="card card-flush bg-image-item h-100" data-file-id="${file.id}" data-file-name="${file.name}" data-file-url="${file.url}" ${isSelected ? 'style="border: 2px solid #0d6efd; border-radius: 8px;"' : ''}>
                                <div class="card-body">
                                    <div class="card-preview" style="width: 100% !important; height: 180px !important; display: flex !important; align-items: center !important; justify-content: center !important;">
                                        <img src="${file.url}" alt="${file.name}" style="width: 100% !important; height: 180px !important; object-fit: contain !important;">
                                    </div>
                                    <div class="card-title-section">
                                        <h6 class="text-gray-800 fw-bold" title="${file.name}">
                                            ${truncateString(file.name, 25)}
                                        </h6>
                                    </div>
                                    <div class="card-actions">
                                        ${isSelected ? `
                                            <div class="d-flex gap-2">
                                                <button type="button" class="btn btn-sm btn-success w-100" disabled>
                                                    <i class="ki-solid ki-check fs-6"></i> {{ __('common.selected') }}
                                                </button>
                                                <button type="button" class="btn btn-sm btn-light-danger" onclick="clearBackgroundImageSelection()">
                                                    <i class="ki-solid ki-cross fs-6"></i>
                                                </button>
                                            </div>
                                        ` : `
                                            <button type="button" class="btn btn-sm btn-primary w-100 bg-image-select-btn" data-file-id="${file.id}" data-file-name="${file.name}" data-file-url="${file.url}">
                                                {{ __('common.select') }}
                                            </button>
                                        `}
                                    </div>
                                </div>
                            </div>
                        </div>
                    `;
                });
            }

            if (mediaFiles && mediaFiles.length > 0) {
                mediaFiles.forEach(file => {
                    const existingDbFile = dbFiles.find(dbFile => dbFile.path === file.path);
                    if (existingDbFile) return;
                    
                    const fileKey = file.path.replace(/[^a-zA-Z0-9]/g, '_');
                    const isSelected = selectedBackgroundImagePath == file.path;
                    
                    html += `
                        <div class="col-xl-3 col-lg-4 col-md-6 col-sm-6 col-12">
                            <div class="card card-flush bg-media-item h-100" data-file-path="${file.path}" data-file-url="${file.url}" ${isSelected ? 'style="border: 2px solid #0d6efd; border-radius: 8px;"' : ''}>
                                <div class="card-body">
                                    <div class="card-preview" style="width: 100% !important; height: 180px !important; display: flex !important; align-items: center !important; justify-content: center !important;">
                                        <img src="${file.url}" alt="${file.name}" style="width: 100% !important; height: 180px !important; object-fit: contain !important;">
                                    </div>
                                    <div class="card-title-section">
                                        <h6 class="text-gray-800 fw-bold" title="${file.name}">
                                            ${truncateString(file.name, 25)}
                                        </h6>
                                        <div class="card-meta">Media Library</div>
                                    </div>
                                    <div class="card-actions">
                                        ${isSelected ? `
                                            <div class="d-flex gap-2">
                                                <button type="button" class="btn btn-sm btn-success w-100" disabled>
                                                    <i class="ki-solid ki-check fs-6"></i> {{ __('common.selected') }}
                                                </button>
                                                <button type="button" class="btn btn-sm btn-light-danger" onclick="clearBackgroundImageSelection()">
                                                    <i class="ki-solid ki-cross fs-6"></i>
                                                </button>
                                            </div>
                                        ` : `
                                            <button type="button" class="btn btn-sm btn-primary w-100 bg-media-select-btn" data-file-path="${file.path}" data-file-url="${file.url}">
                                                {{ __('common.select') }}
                                            </button>
                                        `}
                                    </div>
                                </div>
                            </div>
                        </div>
                    `;
                });
            }

            if (!html) {
                html = '<div class="col-12 text-center py-10"><p class="text-gray-500">Görsel bulunamadı.</p></div>';
            }

            container.innerHTML = html;

            document.querySelectorAll('.bg-image-select-btn').forEach(btn => {
                btn.addEventListener('click', function(e) {
                    e.stopPropagation();
                    const fileId = this.getAttribute('data-file-id');
                    const fileName = this.getAttribute('data-file-name');
                    const fileUrl = this.getAttribute('data-file-url');
                    
                    if (selectedBackgroundImageId && selectedBackgroundImageId != fileId) {
                        const prevCard = document.querySelector(`.bg-image-item[data-file-id="${selectedBackgroundImageId}"]`);
                        if (prevCard) prevCard.style.border = '';
                    }
                    if (selectedBackgroundImagePath) {
                        const prevCard = document.querySelector(`.bg-media-item[data-file-path="${selectedBackgroundImagePath}"]`);
                        if (prevCard) prevCard.style.border = '';
                    }
                    
                    const card = this.closest('.bg-image-item');
                    card.style.border = '2px solid #0d6efd';
                    card.style.borderRadius = '8px';
                    
                    selectBackgroundImage(fileId, fileName, fileUrl);
                });
            });

            document.querySelectorAll('.bg-media-select-btn').forEach(btn => {
                btn.addEventListener('click', function(e) {
                    e.stopPropagation();
                    const filePath = this.getAttribute('data-file-path');
                    const fileUrl = this.getAttribute('data-file-url');
                    const fileName = fileUrl.split('/').pop();
                    
                    if (selectedBackgroundImageId) {
                        const prevCard = document.querySelector(`.bg-image-item[data-file-id="${selectedBackgroundImageId}"]`);
                        if (prevCard) prevCard.style.border = '';
                    }
                    if (selectedBackgroundImagePath && selectedBackgroundImagePath != filePath) {
                        const prevCard = document.querySelector(`.bg-media-item[data-file-path="${selectedBackgroundImagePath}"]`);
                        if (prevCard) prevCard.style.border = '';
                    }
                    
                    const card = this.closest('.bg-media-item');
                    card.style.border = '2px solid #0d6efd';
                    card.style.borderRadius = '8px';
                    
                    selectedBackgroundImagePath = filePath;
                    selectedBackgroundImageId = null;
                    createBackgroundImageFromPath(filePath, fileName, fileUrl);
                });
            });
        }

        function selectBackgroundImage(fileId, fileName, fileUrl) {
            selectedBackgroundImageId = fileId;
            selectedBackgroundImagePath = null;
            
            const bgFileIdInput = document.getElementById('background_image_file_id_input');
            if (bgFileIdInput) bgFileIdInput.value = fileId;
            
            const bgTypeInput = document.getElementById('background_type_input');
            if (bgTypeInput) bgTypeInput.value = 'image';
            
            const previewDiv = document.getElementById('background_image_preview');
            if (previewDiv) {
                previewDiv.style.backdropFilter = 'blur(10px)';
                previewDiv.innerHTML = `
                    <div class="alert alert-success d-flex align-items-center justify-content-between" style="backdrop-filter: blur(10px);">
                        <span>
                            <img src="${fileUrl}" alt="Preview" class="img-thumbnail me-2" style="max-width: 50px; max-height: 50px;">
                            ${fileName}
                        </span>
                        <button type="button" class="btn btn-sm btn-light-danger" onclick="clearBackgroundImageSelection()">
                            <i class="ki-solid ki-cross fs-6"></i>
                        </button>
                    </div>
                `;
            }
            
            // Renk seçimini gizle, görsel seçimini göster
            const colorSection = document.getElementById('background_color_section');
            const imageSection = document.getElementById('background_image_section');
            if (colorSection) colorSection.style.display = 'none';
            if (imageSection) imageSection.style.display = 'block';
            
            const modalElement = document.getElementById('kt_modal_background_image_selector');
            if (modalElement) {
                if (typeof bootstrap !== 'undefined' && bootstrap.Modal) {
                    const modal = bootstrap.Modal.getInstance(modalElement);
                    if (modal) modal.hide();
                } else if (typeof $ !== 'undefined' && $.fn.modal) {
                    $(modalElement).modal('hide');
                }
            }
        }

        function createBackgroundImageFromPath(path, fileName, fileUrl) {
            selectedBackgroundImagePath = path;
            selectedBackgroundImageId = null;

            fetch('{{ route("file-management.create-from-path") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    path: path,
                    name: fileName
                })
            })
            .then(response => {
                if (!response.ok) {
                    return response.json().then(err => {
                        throw new Error(err.message || 'Dosya oluşturulamadı.');
                    });
                }
                return response.json();
            })
            .then(data => {
                if (data.success && data.id) {
                    files.push({
                        id: data.id,
                        name: data.file.name,
                        original_name: data.file.original_name,
                        url: fileUrl
                    });
                    selectedBackgroundImageId = data.id;
                    selectedBackgroundImagePath = null;
                    selectBackgroundImage(data.id, data.file.name || fileName, fileUrl);
                } else {
                    throw new Error(data.message || 'Dosya oluşturulamadı.');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Dosya oluşturulurken bir hata oluştu: ' + error.message);
                selectedBackgroundImagePath = null;
                selectedBackgroundImageId = null;
                loadBackgroundImages();
            });
        }

        function clearBackgroundImageSelection() {
            selectedBackgroundImageId = null;
            selectedBackgroundImagePath = null;
            
            const bgFileIdInput = document.getElementById('background_image_file_id_input');
            if (bgFileIdInput) bgFileIdInput.value = '';
            
            const bgTypeInput = document.getElementById('background_type_input');
            if (bgTypeInput) bgTypeInput.value = 'color';
            
            const previewDiv = document.getElementById('background_image_preview');
            if (previewDiv) previewDiv.innerHTML = '';
            
            // Renk seçimini göster, görsel seçimini gizle
            const colorSection = document.getElementById('background_color_section');
            const imageSection = document.getElementById('background_image_section');
            if (colorSection) colorSection.style.display = 'block';
            if (imageSection) imageSection.style.display = 'block';
            
            // Modal'daki seçimi temizle
            if (selectedBackgroundImageId) {
                const card = document.querySelector(`.bg-image-item[data-file-id="${selectedBackgroundImageId}"]`);
                if (card) card.style.border = '';
            }
            if (selectedBackgroundImagePath) {
                const card = document.querySelector(`.bg-media-item[data-file-path="${selectedBackgroundImagePath}"]`);
                if (card) card.style.border = '';
            }
        }

        function uploadBackgroundImage(file) {
            // Görsel kontrolü
            if (!file.type.startsWith('image/')) {
                alert('Sadece görsel dosyaları yüklenebilir.');
                document.getElementById('bg_image_upload_input').value = '';
                return;
            }

            // Loading göstergesi göster
            const uploadContainer = document.querySelector('#kt_modal_background_image_selector .modal-body');
            const loadingHtml = `
                <div id="bg_upload_loading" class="position-fixed top-0 start-0 w-100 h-100 d-flex align-items-center justify-content-center" style="background: rgba(0,0,0,0.5); z-index: 9999;">
                    <div class="bg-white rounded p-5 text-center">
                        <div class="spinner-border text-primary mb-3" role="status" style="width: 3rem; height: 3rem;">
                            <span class="visually-hidden">Yükleniyor...</span>
                        </div>
                        <div class="fw-bold">{{ __('common.loading') }}</div>
                        <div class="text-muted mt-2">${file.name}</div>
                    </div>
                </div>
            `;
            if (uploadContainer) {
                uploadContainer.insertAdjacentHTML('beforeend', loadingHtml);
            }

            const formData = new FormData();
            formData.append('file', file);
            formData.append('folder', 'files');
            
            const uploadCategorySelect = document.getElementById('bg_upload_category_select');
            if (uploadCategorySelect && uploadCategorySelect.value) {
                formData.append('category_id', uploadCategorySelect.value);
            } else {
                const categorySelect = document.getElementById('bg_image_category_select');
                if (categorySelect && categorySelect.value && categorySelect.value !== 'all') {
                    formData.append('category_id', categorySelect.value);
                }
            }

            const csrfToken = document.querySelector('meta[name="csrf-token"]');
            if (!csrfToken) {
                alert('CSRF token bulunamadı. Sayfayı yenileyin.');
                const loadingEl = document.getElementById('bg_upload_loading');
                if (loadingEl) loadingEl.remove();
                return;
            }

            const uploadUrl = '{{ route("media-library.store") }}';
            console.log('Background Image Upload URL:', uploadUrl);
            console.log('File size:', file.size);
            console.log('File type:', file.type);

            fetch(uploadUrl, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': csrfToken.getAttribute('content'),
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                credentials: 'same-origin'
            })
            .then(async response => {
                console.log('Response status:', response.status);
                console.log('Response ok:', response.ok);
                
                if (!response.ok) {
                    let errorMessage = 'Dosya yüklenirken bir hata oluştu.';
                    try {
                        const errorData = await response.json();
                        console.error('Error data:', errorData);
                        if (errorData.message) {
                            errorMessage = errorData.message;
                        } else if (errorData.errors) {
                            const firstError = Object.values(errorData.errors)[0];
                            errorMessage = Array.isArray(firstError) ? firstError[0] : firstError;
                        }
                    } catch (e) {
                        console.error('Error parsing response:', e);
                        errorMessage = 'Sunucu hatası: ' + response.status + ' ' + response.statusText;
                    }
                    throw new Error(errorMessage);
                }
                return response.json();
            })
            .then(data => {
                // Loading'i kaldır
                const loadingEl = document.getElementById('bg_upload_loading');
                if (loadingEl) loadingEl.remove();

                if (data.success) {
                    loadBackgroundImages();
                    document.getElementById('bg_image_upload_input').value = '';
                    if (typeof Swal !== 'undefined') {
                        Swal.fire({
                            icon: 'success',
                            title: '{{ __('common.success') }}',
                            text: '{{ __('common.file_uploaded') }}',
                            timer: 2000,
                            showConfirmButton: false
                        });
                    }
                } else {
                    throw new Error(data.message || 'Dosya yüklenemedi.');
                }
            })
            .catch(error => {
                // Loading'i kaldır
                const loadingEl = document.getElementById('bg_upload_loading');
                if (loadingEl) loadingEl.remove();

                console.error('Upload error:', error);
                console.error('Error name:', error.name);
                console.error('Error message:', error.message);
                console.error('Error stack:', error.stack);
                
                let errorMessage = 'Dosya yüklenirken bir hata oluştu.';
                
                if (error.message) {
                    errorMessage = error.message;
                } else if (error.name === 'TypeError') {
                    if (error.message && error.message.includes('fetch')) {
                        errorMessage = 'Sunucuya bağlanılamadı. Lütfen:\n1. İnternet bağlantınızı kontrol edin\n2. Sayfayı yenileyin\n3. Tarayıcı konsolunu kontrol edin (F12)';
                    } else {
                        errorMessage = 'Beklenmeyen bir hata oluştu. Lütfen sayfayı yenileyin.';
                    }
                } else if (error.name === 'NetworkError' || error.message.includes('Failed to fetch')) {
                    errorMessage = 'Ağ hatası oluştu. Lütfen:\n1. İnternet bağlantınızı kontrol edin\n2. Sayfayı yenileyin\n3. Dosya boyutunun çok büyük olmadığından emin olun';
                }
                
                if (typeof Swal !== 'undefined') {
                    Swal.fire({
                        icon: 'error',
                        title: 'Hata',
                        text: errorMessage,
                        confirmButtonText: 'Tamam',
                        footer: '<small>Detaylar için tarayıcı konsolunu kontrol edin (F12)</small>'
                    });
                } else {
                    alert(errorMessage);
                }
                
                document.getElementById('bg_image_upload_input').value = '';
            });
        }


        // Renk inputlarını senkronize et
        document.querySelector('input[name="background_color"]').addEventListener('input', function() {
            document.querySelector('input[name="background_color_text"]').value = this.value;
        });

        document.querySelector('input[name="background_color_text"]').addEventListener('input', function() {
            if (/^#([A-Fa-f0-9]{6}|[A-Fa-f0-9]{3})$/.test(this.value)) {
                document.querySelector('input[name="background_color"]').value = this.value;
            }
        });

        // PDF input değiştiğinde dosya seçimini temizle
        document.querySelector('input[name="pdf_file"]')?.addEventListener('change', function() {
            if (this.files.length > 0) {
                clearFileSelection();
            }
        });
    </script>
    @endpush

</x-default-layout>
