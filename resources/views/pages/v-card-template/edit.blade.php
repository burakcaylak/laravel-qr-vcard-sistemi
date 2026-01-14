<x-default-layout>

    @section('title')
        {{ __('common.edit_vcard_template') }}
    @endsection

    @section('breadcrumbs')
        {{ Breadcrumbs::render('v-card-template.edit', $vCardTemplate) }}
    @endsection

    <div class="card">
        <div class="card-header">
            <div class="card-title">
                <h3 class="fw-bold m-0">{{ __('common.edit_vcard_template') }}</h3>
            </div>
        </div>
        <div class="card-body">
            <form action="{{ route('v-card-template.update', $vCardTemplate) }}" method="POST" id="template_form" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                
                <div class="mb-5">
                    <label class="form-label required">{{ __('common.template_name') }}</label>
                    <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $vCardTemplate->name) }}" required>
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Logo -->
                <div class="mb-5">
                    <label class="form-label">{{ __('common.template_logo') }}</label>
                    <div class="mb-3">
                        <div id="logo_preview" class="mb-3">
                            @if($vCardTemplate->logo_path)
                                <img src="{{ asset('storage/' . $vCardTemplate->logo_path) }}" alt="Logo" class="img-thumbnail" style="max-height: 100px;">
                            @endif
                        </div>
                    </div>
                    <input type="hidden" name="logo_path" id="logo_path" value="{{ $vCardTemplate->logo_path }}">
                    <input type="file" name="logo" id="logo_file" class="form-control form-control-solid d-none" accept="image/*">
                    <div class="d-flex gap-2">
                        <button type="button" class="btn btn-primary btn-sm" data-media-select="logo">
                            <i class="ki-duotone ki-folder fs-5"><span class="path1"></span><span class="path2"></span></i>
                            {{ __('common.select_file') }}
                        </button>
                        <button type="button" class="btn btn-light btn-sm" id="upload_logo_btn">
                            <i class="ki-duotone ki-file-up fs-5"><span class="path1"></span><span class="path2"></span></i>
                            {{ __('common.upload_from_computer') }}
                        </button>
                    </div>
                    <div class="form-text mt-2">{{ __('common.file_format_info') }}</div>
                </div>

                <!-- Renk -->
                <div class="mb-5">
                    <label class="form-label">{{ __('common.template_color') }}</label>
                    <input type="color" name="color" class="form-control form-control-color" value="{{ old('color', $vCardTemplate->color ?? '#0033A1') }}">
                    <small class="form-text text-muted">{{ __('common.select_color') }}</small>
                </div>

                <!-- Arkaplan Görseli -->
                <div class="mb-5">
                    <label class="form-label">{{ __('common.template_background') }}</label>
                    <div class="mb-3">
                        <div id="background_preview" class="mb-3">
                            @if($vCardTemplate->background_path)
                                <img src="{{ asset('storage/' . $vCardTemplate->background_path) }}" alt="Background" class="img-thumbnail" style="max-height: 200px;">
                            @endif
                        </div>
                    </div>
                    <input type="hidden" name="background_path" id="background_path" value="{{ $vCardTemplate->background_path }}">
                    <input type="file" name="background" id="background_file" class="form-control form-control-solid d-none" accept="image/*">
                    <div class="d-flex gap-2">
                        <button type="button" class="btn btn-primary btn-sm" data-media-select="background">
                            <i class="ki-duotone ki-folder fs-5"><span class="path1"></span><span class="path2"></span></i>
                            {{ __('common.select_file') }}
                        </button>
                        <button type="button" class="btn btn-light btn-sm" id="upload_background_btn">
                            <i class="ki-duotone ki-file-up fs-5"><span class="path1"></span><span class="path2"></span></i>
                            {{ __('common.upload_from_computer') }}
                        </button>
                    </div>
                    <div class="form-text mt-2">{{ __('common.max_file_size') }}</div>
                </div>

                <!-- Sosyal Medya Hesapları -->
                <div class="separator separator-dashed my-5"></div>
                <h4 class="mb-5">{{ __('common.template_social_media') }}</h4>

                <div class="mb-5">
                    <label class="form-label">{{ __('common.facebook_url') }}</label>
                    <input type="url" name="facebook_url" class="form-control" value="{{ old('facebook_url', $vCardTemplate->facebook_url) }}" placeholder="https://facebook.com/...">
                </div>

                <div class="mb-5">
                    <label class="form-label">{{ __('common.instagram_url') }}</label>
                    <input type="url" name="instagram_url" class="form-control" value="{{ old('instagram_url', $vCardTemplate->instagram_url) }}" placeholder="https://instagram.com/...">
                </div>

                <div class="mb-5">
                    <label class="form-label">{{ __('common.x_url') }}</label>
                    <input type="url" name="x_url" class="form-control" value="{{ old('x_url', $vCardTemplate->x_url) }}" placeholder="https://x.com/...">
                </div>

                <div class="mb-5">
                    <label class="form-label">{{ __('common.linkedin_url') }}</label>
                    <input type="url" name="linkedin_url" class="form-control" value="{{ old('linkedin_url', $vCardTemplate->linkedin_url) }}" placeholder="https://linkedin.com/...">
                </div>

                <div class="mb-5">
                    <label class="form-label">{{ __('common.youtube_url') }}</label>
                    <input type="url" name="youtube_url" class="form-control" value="{{ old('youtube_url', $vCardTemplate->youtube_url) }}" placeholder="https://youtube.com/...">
                </div>

                <div class="mb-5">
                    <label class="form-check form-switch form-check-custom form-check-solid">
                        <input class="form-check-input" type="checkbox" name="is_active" value="1" {{ old('is_active', $vCardTemplate->is_active) ? 'checked' : '' }}>
                        <span class="form-check-label">{{ __('common.active') }}</span>
                    </label>
                </div>

                <div class="d-flex justify-content-end">
                    <a href="{{ route('v-card-template.index') }}" class="btn btn-light me-3">{{ __('common.cancel') }}</a>
                    <button type="submit" class="btn btn-primary">{{ __('common.update') }}</button>
                </div>
            </form>
        </div>
    </div>

    <!--begin::Modal - File Selector-->
    <div class="modal fade" id="kt_modal_file_selector" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h2 class="fw-bold">{{ __('common.select_file') }}</h2>
                    <div class="btn btn-icon btn-sm btn-active-icon-primary" data-bs-dismiss="modal">
                        <i class="ki-solid ki-cross fs-1"></i>
                    </div>
                </div>
                <div class="modal-body mx-3 mx-xl-5 my-5">
                    <!--begin::Upload Section-->
                    <div class="row mb-5">
                        <div class="col-md-6">
                            <label class="form-label">{{ __('common.upload_from_computer') }}</label>
                            <input type="file" id="file_upload_input" class="form-control" accept="image/*">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">{{ __('common.category') }}</label>
                            <select id="upload_category_select" class="form-select" data-control="select2" data-placeholder="{{ __('common.select_category') }}">
                                <option value="">{{ __('common.select_category') }}</option>
                                @foreach($categories ?? [] as $category)
                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="form-text mb-5">{{ __('common.max_file_size') }}</div>
                    <!--end::Upload Section-->

                    <!--begin::Filters-->
                    <div class="row mb-5">
                        <div class="col-md-4">
                            <label class="form-label">{{ __('common.search') }}</label>
                            <input type="text" id="file_search_input" class="form-control" placeholder="{{ __('common.search') }}">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">{{ __('common.type') }}</label>
                            <select id="file_type_select" class="form-select">
                                <option value="all">{{ __('common.all_types') }}</option>
                                <option value="image">{{ __('common.image') }}</option>
                                <option value="document">{{ __('common.type_document') }}</option>
                                <option value="other">{{ __('common.type_other') }}</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">{{ __('common.category') }}</label>
                            <select id="file_category_select" class="form-select">
                                <option value="all">{{ __('common.all') }}</option>
                                @foreach($categories ?? [] as $category)
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

        // Categories for Select2
        @if(isset($categories))
        const categories = @json($categories);
        @else
        const categories = [];
        @endif

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

            // Upload butonları
            document.getElementById('upload_logo_btn')?.addEventListener('click', function() {
                document.getElementById('logo_file').click();
            });

            document.getElementById('upload_background_btn')?.addEventListener('click', function() {
                document.getElementById('background_file').click();
            });

            // File input değiştiğinde preview'ı güncelle
            ['logo', 'background'].forEach(field => {
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
                                    previewDiv.innerHTML = `<img src="${e.target.result}" alt="Yeni dosya" class="img-thumbnail" style="max-height: ${field === 'logo' ? '100px' : '200px'};">`;
                                };
                                reader.readAsDataURL(file);
                            }
                        }
                    });
                }
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
                        if (e.target.files && e.target.files[0]) {
                            uploadFile(e.target.files[0]);
                        }
                    });
                }
            }
        });

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
                    previewDiv.innerHTML = `<img src="${url}" alt="Seçilen dosya" class="img-thumbnail" style="max-height: ${currentMediaField === 'logo' ? '100px' : '200px'};">`;
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

        // Dosya yükleme fonksiyonu
        function uploadFile(file) {
            const formData = new FormData();
            formData.append('file', file);
            formData.append('_token', document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '');
            
            // Eğer modal içinde kategori seçilmişse, onu ekle
            const categorySelect = document.getElementById('upload_category_select');
            if (categorySelect && categorySelect.value && categorySelect.value !== '') {
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
    </script>
    @endpush

</x-default-layout>
