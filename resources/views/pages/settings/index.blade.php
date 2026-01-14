<x-default-layout>

    @section('title')
        {{ __('common.settings_title') }}
    @endsection

    @section('breadcrumbs')
        {{ Breadcrumbs::render('settings') }}
    @endsection

    <!--begin::Content container-->
    <div id="kt_app_content_container" class="app-container container-xxl">
        <!--begin::Card-->
        <div class="card">
            <!--begin::Card header-->
            <div class="card-header border-0 pt-6">
                <div class="card-title">
                    <h3 class="fw-bold m-0">{{ __('common.settings_title') }}</h3>
                </div>
            </div>
            <!--end::Card header-->

            <!--begin::Card body-->
            <div class="card-body pt-0">
                    @if(session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif

                    <form action="{{ route('settings.update') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <!--begin::Logos Row-->
                        <div class="row mb-10">
                            <div class="col-md-6">
                                <label class="form-label fw-bold">{{ __('common.logo_light') }}</label>
                                <div class="mb-5">
                                    <div id="logo_light_preview" class="mb-3">
                                        @if($settings->logo_light)
                                            <img src="{{ storageUrl($settings->logo_light) }}" alt="Logo (Açık Mod)" class="img-thumbnail" style="max-height: 100px;">
                                        @endif
                                    </div>
                                </div>
                                <input type="hidden" name="logo_light_path" id="logo_light_path" value="{{ $settings->logo_light }}">
                                <input type="file" name="logo_light" id="logo_light_file" class="form-control form-control-solid d-none" accept="image/*">
                                <div class="d-flex gap-2">
                                    <button type="button" class="btn btn-primary btn-sm" data-media-select="logo_light">
                                        <i class="ki-duotone ki-folder fs-5"><span class="path1"></span><span class="path2"></span></i>
                                        {{ __('common.select_file') }}
                                    </button>
                                </div>
                                <div class="form-text mt-2">{{ __('common.file_format_info') }}</div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold">{{ __('common.logo_dark') }}</label>
                                <div class="mb-5">
                                    <div id="logo_dark_preview" class="mb-3">
                                        @if($settings->logo_dark)
                                            <img src="{{ storageUrl($settings->logo_dark) }}" alt="Logo (Koyu Mod)" class="img-thumbnail" style="max-height: 100px;">
                                        @endif
                                    </div>
                                </div>
                                <input type="hidden" name="logo_dark_path" id="logo_dark_path" value="{{ $settings->logo_dark }}">
                                <input type="file" name="logo_dark" id="logo_dark_file" class="form-control form-control-solid d-none" accept="image/*">
                                <div class="d-flex gap-2">
                                    <button type="button" class="btn btn-primary btn-sm" data-media-select="logo_dark">
                                        <i class="ki-duotone ki-folder fs-5"><span class="path1"></span><span class="path2"></span></i>
                                        {{ __('common.select_file') }}
                                    </button>
                                </div>
                                <div class="form-text mt-2">{{ __('common.file_format_info') }}</div>
                            </div>
                        </div>
                        <!--end::Logos Row-->

                        <!--begin::Favicon-->
                        <div class="mb-10">
                            <label class="form-label fw-bold">{{ __('common.favicon') }}</label>
                            <div class="mb-5">
                                <div id="favicon_preview" class="mb-3">
                                    @if($settings->favicon)
                                        <img src="{{ storageUrl($settings->favicon) }}" alt="Favicon" class="img-thumbnail" style="max-height: 50px;">
                                    @endif
                                </div>
                            </div>
                            <input type="hidden" name="favicon_path" id="favicon_path" value="{{ $settings->favicon }}">
                            <input type="file" name="favicon" id="favicon_file" class="form-control form-control-solid d-none" accept="image/*">
                            <div class="d-flex gap-2">
                                <button type="button" class="btn btn-primary btn-sm" data-media-select="favicon">
                                    <i class="ki-duotone ki-folder fs-5"><span class="path1"></span><span class="path2"></span></i>
                                    {{ __('common.select_file') }}
                                </button>
                            </div>
                            <div class="form-text mt-2">{{ __('common.favicon_format_info') }}</div>
                        </div>
                        <!--end::Favicon-->

                        <!--begin::Login Image-->
                        <div class="mb-10">
                            <label class="form-label fw-bold">{{ __('common.login_image') }}</label>
                            <div class="mb-5">
                                <div id="login_image_preview" class="mb-3">
                                    @if($settings->login_image)
                                        <img src="{{ storageUrl($settings->login_image) }}" alt="Giriş Ekranı Görseli" class="img-thumbnail" style="max-height: 200px;">
                                    @endif
                                </div>
                            </div>
                            <input type="hidden" name="login_image_path" id="login_image_path" value="{{ $settings->login_image }}">
                            <input type="file" name="login_image" id="login_image_file" class="form-control form-control-solid d-none" accept="image/*">
                            <div class="d-flex gap-2">
                                <button type="button" class="btn btn-primary btn-sm" data-media-select="login_image">
                                    <i class="ki-duotone ki-folder fs-5"><span class="path1"></span><span class="path2"></span></i>
                                    {{ __('common.select_file') }}
                                </button>
                            </div>
                            <div class="form-text mt-2">JPEG, PNG, JPG, GIF veya SVG formatında, maksimum 2MB</div>
                        </div>
                        <!--end::Login Image-->

                        <!--begin::Index Enabled-->
                        <div class="mb-10">
                            <label class="form-check form-switch form-check-custom form-check-solid">
                                <input class="form-check-input" type="checkbox" name="index_enabled" value="1" {{ $settings->index_enabled ? 'checked' : '' }}>
                                <span class="form-check-label fw-semibold">{{ __('common.index_enabled') }}</span>
                            </label>
                            <div class="form-text">{{ __('common.index_enabled_desc') }}</div>
                        </div>
                        <!--end::Index Enabled-->

                        <!--begin::Language-->
                        <div class="mb-10">
                            <label class="form-label fw-bold">{{ __('common.panel_language') }}</label>
                            <select name="language" class="form-select form-select-solid" data-control="select2" data-hide-search="true">
                                <option value="tr" {{ $settings->language === 'tr' ? 'selected' : '' }}>{{ __('common.turkish') }}</option>
                                <option value="en" {{ $settings->language === 'en' ? 'selected' : '' }}>{{ __('common.english') }}</option>
                            </select>
                        </div>
                        <!--end::Language-->

                        <!--begin::Footer Text-->
                        <div class="mb-10">
                            <label class="form-label fw-bold">{{ __('common.footer_text') }}</label>
                            <textarea name="footer_text" class="form-control form-control-solid" rows="2" placeholder="{{ __('common.example_footer', ['year' => date('Y')]) }}">{{ old('footer_text', $settings->footer_text) }}</textarea>
                            <div class="form-text">{{ __('common.footer_text_desc') }}</div>
                        </div>
                        <!--end::Footer Text-->

                        <!--begin::Actions-->
                        <div class="d-flex justify-content-end">
                            <button type="submit" class="btn btn-primary">
                                <span class="indicator-label">{{ __('common.save') }}</span>
                                <span class="indicator-progress">{{ __('common.loading') }}
                                    <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                                </span>
                            </button>
                        </div>
                        <!--end::Actions-->
                    </form>
            </div>
            <!--end::Card body-->
        </div>
        <!--end::Card-->
    </div>
    <!--end::Content container-->

    <!--begin::Modal - File Selector-->
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
                        <label class="form-label">{{ __('common.upload_from_computer') }}</label>
                        <input type="file" id="file_upload_input" class="form-control" accept="image/*">
                        <small class="form-text text-muted">{{ __('common.max_file_size') }}</small>
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
                                <option value="image">{{ __('common.type_image') }}</option>
                                <option value="video">{{ __('common.type_video') }}</option>
                                <option value="audio">{{ __('common.type_audio') }}</option>
                                <option value="document">{{ __('common.type_document') }}</option>
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
    <!--end::Modal - File Selector-->

    @push('scripts')
    <script>
        let currentMediaField = null;
        let selectedFileId = null;
        let selectedMediaPath = null;
        let mediaFiles = [];

        // Modal açma fonksiyonu
        function openFileSelectorModal(field) {
            currentMediaField = field;
            const modalElement = document.getElementById('kt_modal_file_selector');
            
            if (!modalElement) {
                console.error('Modal element not found');
                return;
            }

            // Bootstrap modal aç
            if (typeof bootstrap !== 'undefined' && bootstrap.Modal) {
                const modal = new bootstrap.Modal(modalElement);
                modal.show();
            } else if (typeof $ !== 'undefined' && $.fn.modal) {
                $(modalElement).modal('show');
            }
        }

        // Sayfa yüklendiğinde butonlara event listener ekle
        document.addEventListener('DOMContentLoaded', function() {
            // Tüm "Dosya Seç" butonlarına event listener ekle
            const mediaSelectButtons = document.querySelectorAll('[data-media-select]');
            
            mediaSelectButtons.forEach(function(button) {
                button.addEventListener('click', function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    
                    const field = this.getAttribute('data-media-select');
                    openFileSelectorModal(field);
                });
            });

            // Modal açıldığında dosyaları yükle
            const modalElement = document.getElementById('kt_modal_file_selector');
            if (modalElement) {
                modalElement.addEventListener('show.bs.modal', function(event) {
                    // Filtreleri sıfırla
                    const searchInput = document.getElementById('file_search_input');
                    const typeSelect = document.getElementById('file_type_select');
                    const categorySelect = document.getElementById('file_category_select');
                    
                    if (searchInput) searchInput.value = '';
                    if (typeSelect) typeSelect.value = 'all';
                    if (categorySelect) categorySelect.value = 'all';
                    
                    // Dosyaları yükle
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
        });

        // Dosyaları yükle
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
            container.innerHTML = '<div class="col-12 text-center py-10"><div class="spinner-border text-primary" role="status"><span class="visually-hidden">' + window.__('common.loading') + '</span></div></div>';

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
                    const dbFiles = (data.files || []).filter(file => file.id !== null).map(file => ({
                        id: file.id,
                        name: file.name,
                        original_name: file.original_name,
                        path: file.path,
                        type: file.type,
                        mime_type: file.mime_type,
                        size: file.size,
                        url: file.url || '{{ asset("storage") }}/' + file.path
                    }));
                    
                    mediaFiles = (data.files || []).filter(file => file.id === null);
                    
                    renderFilesList(dbFiles, mediaFiles);
                } else {
                    renderFilesList([], []);
                }
            })
            .catch(error => {
                console.error('Error loading files:', error);
                renderFilesList([], []);
            });
        }

        // Dosya listesini render et
        function renderFilesList(dbFiles, mediaFiles = []) {
            const container = document.getElementById('files_list_container');
            if (!container) return;

            let html = '';

            // Veritabanındaki dosyalar
            if (dbFiles && dbFiles.length > 0) {
                dbFiles.forEach(file => {
                    const isSelected = selectedFileId == file.id;
                    const isImage = file.type === 'image';
                    const fileSize = formatFileSize(file.size);
                    
                    html += `
                        <div class="col-xl-3 col-lg-4 col-md-6 col-sm-6 col-12">
                            <div class="card card-flush file-item h-100" data-file-id="${file.id}" data-file-name="${file.name}" data-file-selected="${isSelected}" ${isSelected ? 'style="border: 2px solid #0d6efd; border-radius: 8px;"' : ''}>
                                <div class="card-body">
                                    <div class="card-preview" style="width: 100% !important; height: 180px !important; min-width: 0 !important; min-height: 180px !important; max-width: 100% !important; max-height: 180px !important; display: flex !important; align-items: center !important; justify-content: center !important;">
                                        ${isImage ? `
                                            <img src="${file.url || '{{ asset("storage") }}/' + file.path}" alt="${file.name}" style="width: 100%; height: 180px; object-fit: contain;">
                                        ` : `
                                            <i class="ki-duotone ki-file fs-1" style="font-size: 48px !important; width: 48px !important; height: 48px !important; color: #6c757d !important;">
                                                <span class="path1"></span><span class="path2"></span>
                                            </i>
                                        `}
                                    </div>
                                    <div class="mt-3">
                                        <h6 class="text-gray-800 fw-bold mb-1" title="${file.name}">${truncateString(file.name, 20)}</h6>
                                        <div class="text-gray-500 fs-7">${fileSize}</div>
                                    </div>
                                    <div class="mt-2">
                                        ${isSelected ? `
                                            <div class="d-flex gap-2">
                                                <button type="button" class="btn btn-sm btn-success w-100" disabled>
                                                    <i class="ki-solid ki-check fs-6"></i> ` + window.__('common.selected') + `
                                                </button>
                                                <button type="button" class="btn btn-sm btn-light-danger" onclick="event.stopPropagation(); clearFileSelection('${file.id}');">
                                                    <i class="ki-solid ki-cross fs-6"></i>
                                                </button>
                                            </div>
                                        ` : `
                                            <button type="button" class="btn btn-sm btn-primary w-100 file-select-btn" data-file-id="${file.id}" data-file-name="${file.name}">
                                                ` + window.__('common.select') + `
                                            </button>
                                        `}
                                    </div>
                                </div>
                            </div>
                        </div>
                    `;
                });
            }

            // Media library dosyaları (File modelinde olmayan)
            if (mediaFiles && mediaFiles.length > 0) {
                mediaFiles.forEach(file => {
                    const isSelected = selectedMediaPath == file.path;
                    const isImage = file.type === 'image';
                    const fileSize = formatFileSize(file.size);
                    const fileKey = file.path.replace(/[^a-zA-Z0-9]/g, '_');
                    
                    html += `
                        <div class="col-xl-3 col-lg-4 col-md-6 col-sm-6 col-12">
                            <div class="card card-flush media-file-item h-100" data-file-path="${file.path}" data-file-url="${file.url}" data-file-selected="${isSelected}" ${isSelected ? 'style="border: 2px solid #0d6efd; border-radius: 8px;"' : ''}>
                                <div class="card-body">
                                    <div class="card-preview" style="width: 100% !important; height: 180px !important; min-width: 0 !important; min-height: 180px !important; max-width: 100% !important; max-height: 180px !important; display: flex !important; align-items: center !important; justify-content: center !important;">
                                        ${isImage ? `
                                            <img src="${file.url}" alt="${file.name}" style="width: 100%; height: 180px; object-fit: contain;">
                                        ` : `
                                            <i class="ki-duotone ki-file fs-1" style="font-size: 48px !important; width: 48px !important; height: 48px !important; color: #6c757d !important;">
                                                <span class="path1"></span><span class="path2"></span>
                                            </i>
                                        `}
                                    </div>
                                    <div class="mt-3">
                                        <h6 class="text-gray-800 fw-bold mb-1" title="${file.name}">${truncateString(file.name, 20)}</h6>
                                        <div class="text-gray-500 fs-7">${fileSize}</div>
                                    </div>
                                    <div class="mt-2">
                                        ${isSelected ? `
                                            <div class="d-flex gap-2">
                                                <button type="button" class="btn btn-sm btn-success w-100" disabled>
                                                    <i class="ki-solid ki-check fs-6"></i> ` + window.__('common.selected') + `
                                                </button>
                                                <button type="button" class="btn btn-sm btn-light-danger" onclick="event.stopPropagation(); clearMediaSelection('${file.path}');">
                                                    <i class="ki-solid ki-cross fs-6"></i>
                                                </button>
                                            </div>
                                        ` : `
                                            <button type="button" class="btn btn-sm btn-primary w-100 media-select-btn" data-file-path="${file.path}" data-file-url="${file.url}">
                                                ` + window.__('common.select') + `
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
                html = '<div class="col-12 text-center py-10"><p class="text-gray-500">' + window.__('common.no_files_found') + '</p></div>';
            }

            container.innerHTML = html;

            // Event listener'ları ekle
            document.querySelectorAll('.file-select-btn').forEach(btn => {
                btn.addEventListener('click', function(e) {
                    e.stopPropagation();
                    const fileId = this.getAttribute('data-file-id');
                    const fileName = this.getAttribute('data-file-name');
                    selectFile(fileId, fileName);
                });
            });

            document.querySelectorAll('.media-select-btn').forEach(btn => {
                btn.addEventListener('click', function(e) {
                    e.stopPropagation();
                    const filePath = this.getAttribute('data-file-path');
                    const fileUrl = this.getAttribute('data-file-url');
                    selectMediaFile(filePath, fileUrl);
                });
            });
        }

        // Dosya seçildiğinde (DB'den)
        function selectFile(fileId, fileName) {
            if (!currentMediaField) return;

            selectedFileId = fileId;
            selectedMediaPath = null;

            // Path input'u temizle (DB dosyası seçildi)
            const pathInput = document.getElementById(currentMediaField + '_path');
            if (pathInput) {
                pathInput.value = '';
            }

            // File input'u temizle
            const fileInput = document.getElementById(currentMediaField + '_file');
            if (fileInput) {
                fileInput.value = '';
            }

            // Preview'ı güncelle
            const previewDiv = document.getElementById(currentMediaField + '_preview');
            if (previewDiv) {
                previewDiv.innerHTML = `<div class="alert alert-info d-flex align-items-center justify-content-between">
                    <span>${fileName}</span>
                    <button type="button" class="btn btn-sm btn-light-danger" onclick="clearFileSelection('${fileId}')">
                        <i class="ki-solid ki-cross fs-6"></i>
                    </button>
                </div>`;
            }

            // Modal'ı kapat
            const modalElement = document.getElementById('kt_modal_file_selector');
            if (modalElement) {
                const modal = bootstrap.Modal.getInstance(modalElement);
                if (modal) {
                    modal.hide();
                }
            }
        }

        // Media library dosyası seçildiğinde
        function selectMediaFile(path, url) {
            if (!currentMediaField) return;

            selectedMediaPath = path;
            selectedFileId = null;

            // Hidden input'a path'i kaydet
            const pathInput = document.getElementById(currentMediaField + '_path');
            if (pathInput) {
                pathInput.value = path;
            }

            // File input'u temizle
            const fileInput = document.getElementById(currentMediaField + '_file');
            if (fileInput) {
                fileInput.value = '';
            }

            // Preview'ı güncelle
            const previewDiv = document.getElementById(currentMediaField + '_preview');
            if (previewDiv) {
                const isImage = url.match(/\.(jpg|jpeg|png|gif|svg|webp|ico)$/i);
                if (isImage) {
                    previewDiv.innerHTML = `<img src="${url}" alt="Seçilen dosya" class="img-thumbnail" style="max-height: ${currentMediaField === 'favicon' ? '50px' : currentMediaField === 'login_image' ? '200px' : '100px'};">`;
                } else {
                    previewDiv.innerHTML = `<div class="alert alert-info">Dosya seçildi: ${path.split('/').pop()}</div>`;
                }
            }

            // Modal'ı kapat
            const modalElement = document.getElementById('kt_modal_file_selector');
            if (modalElement) {
                const modal = bootstrap.Modal.getInstance(modalElement);
                if (modal) {
                    modal.hide();
                }
            }
        }

        // Dosya yükleme fonksiyonu
        function uploadFile(file) {
            const formData = new FormData();
            formData.append('file', file);
            formData.append('_token', document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '');
            
            // Eğer modal içinde kategori seçilmişse, onu ekle
            const categorySelect = document.getElementById('file_category_select');
            if (categorySelect && categorySelect.value && categorySelect.value !== 'all') {
                formData.append('category_id', categorySelect.value);
            }

            const container = document.getElementById('files_list_container');
            container.innerHTML = '<div class="col-12 text-center py-10"><div class="spinner-border text-primary" role="status"><span class="visually-hidden">' + window.__('common.loading') + '</span></div></div>';

            fetch('{{ route("media-library.store") }}', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '',
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success !== false && data.file) {
                    // Yeni dosyayı seç
                    if (data.file.id) {
                        selectFile(data.file.id, data.file.name);
                    } else {
                        selectMediaFile(data.file.path, data.file.url);
                    }
                } else {
                    alert(data.message || window.__('common.error_occurred'));
                    loadFiles();
                }
            })
            .catch(error => {
                console.error('Error uploading file:', error);
                alert(window.__('common.error_occurred'));
                loadFiles();
            });
        }

        // Seçimi temizle
        function clearFileSelection(fileId) {
            selectedFileId = null;
            const pathInput = document.getElementById(currentMediaField + '_path');
            const previewDiv = document.getElementById(currentMediaField + '_preview');
            if (pathInput) pathInput.value = '';
            if (previewDiv) previewDiv.innerHTML = '';
        }

        function clearMediaSelection(path) {
            selectedMediaPath = null;
            const pathInput = document.getElementById(currentMediaField + '_path');
            const previewDiv = document.getElementById(currentMediaField + '_preview');
            if (pathInput) pathInput.value = '';
            if (previewDiv) previewDiv.innerHTML = '';
        }

        // File input değiştiğinde preview'ı güncelle
        ['logo_light', 'logo_dark', 'favicon', 'login_image'].forEach(field => {
            const fileInput = document.getElementById(field + '_file');
            if (fileInput) {
                fileInput.addEventListener('change', function(e) {
                    const file = e.target.files[0];
                    if (file) {
                        // Path input'u temizle (yeni dosya yüklendi)
                        const pathInput = document.getElementById(field + '_path');
                        if (pathInput) {
                            pathInput.value = '';
                        }

                        // Seçimleri temizle
                        selectedFileId = null;
                        selectedMediaPath = null;

                        // Preview göster
                        const previewDiv = document.getElementById(field + '_preview');
                        if (previewDiv && file.type.startsWith('image/')) {
                            const reader = new FileReader();
                            reader.onload = function(e) {
                                previewDiv.innerHTML = `<img src="${e.target.result}" alt="Yeni dosya" class="img-thumbnail" style="max-height: ${field === 'favicon' ? '50px' : field === 'login_image' ? '200px' : '100px'};">`;
                            };
                            reader.readAsDataURL(file);
                        }
                    }
                });
            }
        });

        // Yardımcı fonksiyonlar
        function formatFileSize(bytes) {
            const units = ['B', 'KB', 'MB', 'GB', 'TB'];
            bytes = Math.max(bytes, 0);
            const pow = Math.floor((bytes ? Math.log(bytes) : 0) / Math.log(1024));
            const unit = units[Math.min(pow, units.length - 1)];
            bytes /= Math.pow(1024, pow);
            return Math.round(bytes * 100) / 100 + ' ' + unit;
        }

        function truncateString(str, maxLength) {
            return str.length > maxLength ? str.substring(0, maxLength) + '...' : str;
        }

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
    </script>
    @endpush
</x-default-layout>

