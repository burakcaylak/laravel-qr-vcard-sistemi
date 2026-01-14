<x-default-layout>

    @section('title')
        {{ __('common.edit_qr_code') }}
    @endsection

    @section('breadcrumbs')
        {{ Breadcrumbs::render('qr-code.edit', $qrCode) }}
    @endsection

    @push('styles')
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
        /* Önizleme alanı için güçlü kurallar */
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
        #files_list_container .card-preview img,
        #files_list_container div.card-preview img,
        #files_list_container .card .card-preview img,
        #files_list_container .card-body .card-preview img,
        #files_list_container * .card-preview img {
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
        #files_list_container .card-preview i,
        #files_list_container .card-preview .ki-duotone,
        #files_list_container .card-preview .ki-solid,
        #files_list_container div.card-preview i,
        #files_list_container div.card-preview .ki-duotone,
        #files_list_container div.card-preview .ki-solid,
        #files_list_container * .card-preview i,
        #files_list_container * .card-preview .ki-duotone,
        #files_list_container * .card-preview .ki-solid {
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
        #files_list_container {
            width: 100% !important;
            margin: 0 !important;
            padding: 0 !important;
        }
        #files_list_container .card {
            display: flex !important;
            flex-direction: column !important;
            height: 100% !important;
            min-height: 280px !important;
            margin: 0 !important;
        }
        #files_list_container .card-body {
            display: flex !important;
            flex-direction: column !important;
            flex: 1 !important;
            padding: 1rem !important;
        }
        /* Modal taşmalarını önle */
        #kt_modal_file_selector .modal-body {
            overflow-x: hidden !important;
            max-height: 70vh !important;
        }
        #kt_modal_file_selector .modal-content {
            overflow: hidden !important;
        }
        #kt_modal_file_selector .row {
            margin-left: 0 !important;
            margin-right: 0 !important;
        }
        #kt_modal_file_selector .row > * {
            padding-left: 0.75rem !important;
            padding-right: 0.75rem !important;
        }
    </style>
    @endpush

    <div class="card">
        <div class="card-header">
            <div class="card-title">
                <span class="svg-icon svg-icon-success svg-icon-2 me-2">
                    <i class="ki-solid ki-scan-barcode fs-2"></i>
                </span>
                <h3 class="fw-bold m-0 d-inline">{{ __('common.edit_qr_code') }}</h3>
            </div>
        </div>
        <div class="card-body">
            <form action="{{ route('qr-code.update', $qrCode) }}" method="POST">
                @csrf
                @method('PUT')
                
                <div class="mb-5">
                    <label class="form-label required">{{ __('common.qr_code_name') }}</label>
                    <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $qrCode->name) }}" readonly>
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    <small class="form-text text-muted">{{ __('common.qr_code_name_readonly_hint') }}</small>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-5">
                        <label class="form-label">{{ __('common.category') }}</label>
                        <select name="category_id" class="form-select">
                            <option value="">{{ __('common.select_category') }}</option>
                            @foreach($categories ?? [] as $category)
                                <option value="{{ $category->id }}" {{ old('category_id', $qrCode->category_id) == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6 mb-5">
                        <label class="form-label">{{ __('common.requested_by') }}</label>
                        <input type="text" name="requested_by" class="form-control" value="{{ old('requested_by', $qrCode->requested_by) }}">
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-5">
                        <label class="form-label">{{ __('common.request_date') }}</label>
                        <input type="date" name="request_date" class="form-control" value="{{ old('request_date', $qrCode->request_date?->format('Y-m-d') ?? date('Y-m-d')) }}">
                    </div>
                    <div class="col-md-6 mb-5">
                        <label class="form-label">{{ __('common.qr_code_type') }}</label>
                        <select name="qr_type" class="form-select" disabled>
                            @php
                                $types = [
                                    'file' => __('common.type_file'),
                                    'url' => __('common.type_url'),
                                    'multi_file' => __('common.type_multi_file'),
                                    'text' => __('common.type_text'),
                                    'email' => __('common.type_email'),
                                    'phone' => __('common.type_phone'),
                                    'wifi' => __('common.type_wifi'),
                                    'vcard' => __('common.type_vcard'),
                                ];
                                $typeText = $types[$qrCode->qr_type] ?? strtoupper($qrCode->qr_type);
                            @endphp
                            <option value="{{ $qrCode->qr_type }}">{{ $typeText }}</option>
                        </select>
                        <input type="hidden" name="qr_type" value="{{ $qrCode->qr_type }}">
                    </div>
                </div>

                @if($qrCode->qr_type === 'file')
                    <div class="mb-5" id="file_id_field">
                        <label class="form-label">{{ __('common.file') }}</label>
                        <input type="hidden" name="file_id" id="file_id_input" value="{{ old('file_id', $qrCode->file_id) }}">
                        <div id="file_preview_container" class="mb-3">
                            @if($qrCode->file)
                                <div class="d-flex align-items-center gap-3 p-3 border rounded">
                                    <div class="flex-shrink-0">
                                        @if(str_starts_with($qrCode->file->mime_type ?? '', 'image/'))
                                            <img src="{{ storageUrl($qrCode->file->path) }}" alt="{{ $qrCode->file->name }}" style="width: 60px; height: 60px; object-fit: cover; border-radius: 4px;">
                                        @else
                                            <i class="ki-duotone ki-file fs-2x text-gray-500">
                                                <span class="path1"></span><span class="path2"></span>
                                            </i>
                                        @endif
                                    </div>
                                    <div class="flex-grow-1">
                                        <div class="fw-bold">{{ $qrCode->file->name }}</div>
                                        <div class="text-muted small">{{ $qrCode->file->original_name }}</div>
                                    </div>
                                    <button type="button" class="btn btn-sm btn-light-danger" onclick="clearFileSelection()">
                                        <i class="ki-solid ki-cross fs-6"></i>
                                    </button>
                                </div>
                            @endif
                        </div>
                        <button type="button" class="btn btn-primary btn-sm" onclick="openFileSelectorModal()">
                            <i class="ki-duotone ki-folder fs-5"><span class="path1"></span><span class="path2"></span></i>
                            {{ __('common.select_file') }}
                        </button>
                    </div>
                @elseif($qrCode->qr_type === 'multi_file')
                    <div class="mb-5">
                        <label class="form-label required">{{ __('common.page_title') }}</label>
                        <input type="text" name="page_title" class="form-control" value="{{ old('page_title', $qrCode->page_title) }}" required>
                        <small class="form-text text-muted">{{ __('common.page_title_hint') }}</small>
                    </div>
                    
                    <div class="mb-5" id="file_buttons_container">
                        <label class="form-label required">{{ __('common.files_and_button_names') }}</label>
                        <div id="file_buttons_list">
                            @foreach($qrCode->files as $index => $file)
                                <div class="row mb-3 file-button-item" data-file-button-id="{{ $index + 1 }}">
                                    <div class="col-md-5">
                                        <input type="hidden" name="file_ids[]" class="file-id-input" value="{{ $file->id }}">
                                        <div class="file-preview-{{ $index + 1 }} mb-2">
                                            <div class="d-flex align-items-center gap-2 p-2 border rounded">
                                                <div class="flex-shrink-0">
                                                    @if(str_starts_with($file->mime_type ?? '', 'image/'))
                                                        <img src="{{ storageUrl($file->path) }}" alt="{{ $file->name }}" style="width: 40px; height: 40px; object-fit: cover; border-radius: 4px;">
                                                    @else
                                                        <i class="ki-duotone ki-file fs-3 text-gray-500">
                                                            <span class="path1"></span><span class="path2"></span>
                                                        </i>
                                                    @endif
                                                </div>
                                                <div class="flex-grow-1">
                                                    <div class="fw-bold small">{{ $file->name }}</div>
                                                </div>
                                            </div>
                                        </div>
                                        <button type="button" class="btn btn-primary btn-sm w-100 file-select-multi-btn" data-button-id="{{ $index + 1 }}">
                                            <i class="ki-duotone ki-folder fs-5"><span class="path1"></span><span class="path2"></span></i>
                                            {{ __('common.select_file') }}
                                        </button>
                                    </div>
                                    <div class="col-md-6">
                                        <input type="text" name="button_names[]" class="form-control" placeholder="{{ __('common.button_name') }}" value="{{ $file->pivot->button_name ?? $file->name }}" required>
                                    </div>
                                    <div class="col-md-1">
                                        <button type="button" class="btn btn-sm btn-light-danger remove-file-button">
                                            <i class="ki-solid ki-cross fs-6"></i>
                                        </button>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        <button type="button" class="btn btn-sm btn-light-primary mt-3" id="add_file_button">
                            <i class="ki-solid ki-plus fs-6"></i> {{ __('common.add_file') }}
                        </button>
                    </div>
                @else
                    <div class="mb-5">
                        <label class="form-label">{{ __('common.content') }}</label>
                        <textarea name="content" class="form-control" rows="3">{{ old('content', $qrCode->content) }}</textarea>
                    </div>
                    <div class="mb-5" id="file_id_field">
                        <label class="form-label">{{ __('common.file') }}</label>
                        <input type="hidden" name="file_id" id="file_id_input" value="{{ old('file_id', $qrCode->file_id) }}">
                        <div id="file_preview_container" class="mb-3">
                            @if($qrCode->file)
                                <div class="d-flex align-items-center gap-3 p-3 border rounded">
                                    <div class="flex-shrink-0">
                                        @if(str_starts_with($qrCode->file->mime_type ?? '', 'image/'))
                                            <img src="{{ storageUrl($qrCode->file->path) }}" alt="{{ $qrCode->file->name }}" style="width: 60px; height: 60px; object-fit: cover; border-radius: 4px;">
                                        @else
                                            <i class="ki-duotone ki-file fs-2x text-gray-500">
                                                <span class="path1"></span><span class="path2"></span>
                                            </i>
                                        @endif
                                    </div>
                                    <div class="flex-grow-1">
                                        <div class="fw-bold">{{ $qrCode->file->name }}</div>
                                        <div class="text-muted small">{{ $qrCode->file->original_name }}</div>
                                    </div>
                                    <button type="button" class="btn btn-sm btn-light-danger" onclick="clearFileSelection()">
                                        <i class="ki-solid ki-cross fs-6"></i>
                                    </button>
                                </div>
                            @endif
                        </div>
                        <button type="button" class="btn btn-primary btn-sm" onclick="openFileSelectorModal()">
                            <i class="ki-duotone ki-folder fs-5"><span class="path1"></span><span class="path2"></span></i>
                            {{ __('common.select_file') }}
                        </button>
                    </div>
                @endif

                <div class="row">
                    <div class="col-md-6 mb-5">
                        <label class="form-label">{{ __('common.size') }} (px)</label>
                        <input type="number" name="size" class="form-control" value="{{ old('size', $qrCode->size) }}" min="100" max="1000">
                    </div>
                    <div class="col-md-6 mb-5">
                        <label class="form-label">{{ __('common.format') }}</label>
                        <select name="format" class="form-select">
                            <option value="svg" {{ old('format', $qrCode->format) == 'svg' ? 'selected' : '' }}>SVG</option>
                            <option value="png" {{ old('format', $qrCode->format) == 'png' ? 'selected' : '' }}>PNG</option>
                        </select>
                        <small class="form-text text-muted">{{ __('common.format_hint') }}</small>
                    </div>
                </div>

                <div class="mb-5">
                    <label class="form-label">{{ __('common.description') }}</label>
                    <textarea name="description" class="form-control" rows="3">{{ old('description', $qrCode->description) }}</textarea>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-5">
                        <label class="form-check form-switch form-check-custom form-check-solid">
                            <input class="form-check-input" type="checkbox" name="is_active" value="1" {{ old('is_active', $qrCode->is_active) ? 'checked' : '' }}>
                            <span class="form-check-label">{{ __('common.active') }}</span>
                        </label>
                    </div>
                    <div class="col-md-6 mb-5">
                        <label class="form-label">{{ __('common.expires_at') }}</label>
                        <input type="date" name="expires_at" class="form-control" value="{{ old('expires_at', $qrCode->expires_at?->format('Y-m-d')) }}">
                    </div>
                </div>

                <div class="d-flex justify-content-end">
                    <a href="{{ route('qr-code.show', $qrCode) }}" class="btn btn-light me-3">{{ __('common.cancel') }}</a>
                    <button type="submit" class="btn btn-primary">{{ __('common.save') }}</button>
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
                        {!! getIcon('cross', 'fs-1', '', 'i') !!}
                    </div>
                </div>
                <div class="modal-body scroll-y mx-3 mx-xl-5 my-5">
                    <!--begin::Upload Option-->
                    <div class="mb-5">
                        <label class="form-label">{{ __('common.upload_from_computer') }}</label>
                        <input type="file" id="file_upload_input" class="form-control" accept="*/*">
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
            let files = @json($files);
            let fileButtonCounter = {{ $qrCode->files->count() ?? 0 }};
            let currentFileField = null;
            let mediaFiles = [];
            let selectedFileId = {{ $qrCode->file_id ?? 'null' }};
            let selectedMediaPath = null;
            let selectedMultiFileIds = {};
            
            // Mevcut dosyaları selectedMultiFileIds'e ekle
            @foreach($qrCode->files as $index => $file)
                selectedMultiFileIds['{{ $index + 1 }}'] = {{ $file->id }};
            @endforeach

            function openFileSelectorModal() {
                currentFileField = 'single';
                const modalElement = document.getElementById('kt_modal_file_selector');
                if (modalElement) {
                    if (typeof bootstrap !== 'undefined' && bootstrap.Modal) {
                        const modal = new bootstrap.Modal(modalElement);
                        modal.show();
                    } else if (typeof $ !== 'undefined' && $.fn.modal) {
                        $(modalElement).modal('show');
                    }
                    loadFiles();
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
                        const dbFiles = (data.files || []).filter(file => file.id !== null).map(file => ({
                            id: file.id,
                            name: file.name,
                            original_name: file.original_name,
                            path: file.path,
                            type: file.type,
                            mime_type: file.mime_type,
                            size: file.size
                        }));
                        mediaFiles = (data.files || []).filter(file => file.id === null);
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

                if (dbFiles && dbFiles.length > 0) {
                    dbFiles.forEach(file => {
                        const isSelected = currentFileField === 'single' ? selectedFileId == file.id : 
                            (currentFileField && currentFileField.startsWith('multi_file_') ? selectedMultiFileIds[currentFileField.replace('multi_file_', '')] == file.id : false);
                        
                        html += `
                            <div class="col-xl-3 col-lg-4 col-md-6 col-sm-6 col-12">
                                <div class="card card-flush file-item h-100" data-file-id="${file.id}" ${isSelected ? 'style="border: 2px solid #0d6efd; border-radius: 8px;"' : ''}>
                                    <div class="card-body">
                                    <div class="card-preview" style="width: 100% !important; height: 180px !important; min-width: 0 !important; min-height: 180px !important; max-width: 100% !important; max-height: 180px !important; display: flex !important; align-items: center !important; justify-content: center !important;">
                                        ${file.mime_type && file.mime_type.startsWith('image/') ? 
                                            `<img src="{{ asset('storage') }}/${file.path}" alt="${file.name}" style="width: 100% !important; height: 180px !important; min-width: 0 !important; min-height: 180px !important; max-width: 100% !important; max-height: 180px !important; object-fit: contain !important; display: block !important; box-sizing: border-box !important;">` :
                                            `<i class="ki-duotone ki-file fs-1 text-gray-500" style="font-size: 48px !important; width: 48px !important; height: 48px !important;"><span class="path1"></span><span class="path2"></span></i>`
                                        }
                                    </div>
                                    <div class="mt-2">
                                        <div class="fw-bold text-truncate" title="${file.name}">${file.name}</div>
                                        <div class="text-muted small text-truncate" title="${file.original_name}">${file.original_name}</div>
                                    </div>
                                    <div class="mt-2">
                                        ${isSelected ? 
                                            `<button type="button" class="btn btn-sm btn-success w-100" disabled>{{ __('common.selected') }}</button>` :
                                            `<button type="button" class="btn btn-sm btn-primary w-100" onclick="selectFile(${file.id}, '${file.name}', '${file.original_name}', '${file.path}', '${file.mime_type || ''}')">{{ __('common.select') }}</button>`
                                        }
                                    </div>
                                    </div>
                                </div>
                            </div>
                        `;
                    });
                }

                if (mediaFiles && mediaFiles.length > 0) {
                    mediaFiles.forEach(file => {
                        const isSelected = currentFileField === 'single' ? selectedMediaPath === file.path : false;
                        
                        html += `
                            <div class="col-xl-3 col-lg-4 col-md-6 col-sm-6 col-12">
                                <div class="card card-flush file-item h-100" data-media-path="${file.path}" ${isSelected ? 'style="border: 2px solid #0d6efd; border-radius: 8px;"' : ''}>
                                    <div class="card-body">
                                    <div class="card-preview" style="width: 100% !important; height: 180px !important; min-width: 0 !important; min-height: 180px !important; max-width: 100% !important; max-height: 180px !important; display: flex !important; align-items: center !important; justify-content: center !important;">
                                        ${file.mime_type && file.mime_type.startsWith('image/') ? 
                                            `<img src="{{ asset('storage') }}/${file.path}" alt="${file.name}" style="width: 100% !important; height: 180px !important; min-width: 0 !important; min-height: 180px !important; max-width: 100% !important; max-height: 180px !important; object-fit: contain !important; display: block !important; box-sizing: border-box !important;">` :
                                            `<i class="ki-duotone ki-file fs-1 text-gray-500" style="font-size: 48px !important; width: 48px !important; height: 48px !important;"><span class="path1"></span><span class="path2"></span></i>`
                                        }
                                    </div>
                                    <div class="mt-2">
                                        <div class="fw-bold text-truncate" title="${file.name}">${file.name}</div>
                                        <div class="text-muted small text-truncate">Media Library</div>
                                    </div>
                                    <div class="mt-2">
                                        ${isSelected ? 
                                            `<button type="button" class="btn btn-sm btn-success w-100" disabled>{{ __('common.selected') }}</button>` :
                                            `<button type="button" class="btn btn-sm btn-primary w-100" onclick="selectMediaFile('${file.path}', '${file.name}')">{{ __('common.select') }}</button>`
                                        }
                                    </div>
                                    </div>
                                </div>
                            </div>
                        `;
                    });
                }

                if (!html) {
                    html = `<div class="col-12 text-center py-10"><p class="text-muted">{{ __('common.no_files_found') }}</p></div>`;
                }

                container.innerHTML = html;
            }

            function selectFile(fileId, fileName, originalName, filePath, mimeType) {
                if (currentFileField === 'single') {
                    selectedFileId = fileId;
                    selectedMediaPath = null;
                    document.getElementById('file_id_input').value = fileId;
                    updateFilePreview(fileId, fileName, originalName, filePath, mimeType);
                    const modal = bootstrap.Modal.getInstance(document.getElementById('kt_modal_file_selector'));
                    if (modal) modal.hide();
                } else if (currentFileField && currentFileField.startsWith('multi_file_')) {
                    const buttonId = currentFileField.replace('multi_file_', '');
                    selectedMultiFileIds[buttonId] = fileId;
                    updateMultiFilePreview(buttonId, fileId, fileName, originalName, filePath, mimeType);
                    const modal = bootstrap.Modal.getInstance(document.getElementById('kt_modal_file_selector'));
                    if (modal) modal.hide();
                }
                loadFiles();
            }

            function selectMediaFile(path, name) {
                if (currentFileField === 'single') {
                    selectedMediaPath = path;
                    selectedFileId = null;
                    createFileFromPath(path, name);
                }
            }

            function createFileFromPath(path, name) {
                fetch('{{ route("file-management.create-from-path") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: JSON.stringify({ path: path, name: name })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success && data.id) {
                        selectedFileId = data.id;
                        selectedMediaPath = null;
                        document.getElementById('file_id_input').value = data.id;
                        updateFilePreview(data.id, data.file.name, data.file.original_name, data.file.path, data.file.mime_type);
                        const modal = bootstrap.Modal.getInstance(document.getElementById('kt_modal_file_selector'));
                        if (modal) modal.hide();
                        loadFiles();
                    }
                })
                .catch(error => {
                    console.error('Error creating file from path:', error);
                });
            }

            function updateFilePreview(fileId, fileName, originalName, filePath, mimeType) {
                const container = document.getElementById('file_preview_container');
                const isImage = mimeType && mimeType.startsWith('image/');
                container.innerHTML = `
                    <div class="d-flex align-items-center gap-3 p-3 border rounded">
                        <div class="flex-shrink-0">
                            ${isImage ? 
                                `<img src="{{ asset('storage') }}/${filePath}" alt="${fileName}" style="width: 60px; height: 60px; object-fit: cover; border-radius: 4px;">` :
                                `<i class="ki-duotone ki-file fs-2x text-gray-500"><span class="path1"></span><span class="path2"></span></i>`
                            }
                        </div>
                        <div class="flex-grow-1">
                            <div class="fw-bold">${fileName}</div>
                            <div class="text-muted small">${originalName}</div>
                        </div>
                        <button type="button" class="btn btn-sm btn-light-danger" onclick="clearFileSelection()">
                            <i class="ki-solid ki-cross fs-6"></i>
                        </button>
                    </div>
                `;
            }

            function updateMultiFilePreview(buttonId, fileId, fileName, originalName, filePath, mimeType) {
                const previewDiv = document.querySelector(`.file-preview-${buttonId}`);
                const input = document.querySelector(`[data-file-button-id="${buttonId}"] .file-id-input`);
                if (input) input.value = fileId;
                const isImage = mimeType && mimeType.startsWith('image/');
                if (previewDiv) {
                    previewDiv.innerHTML = `
                        <div class="d-flex align-items-center gap-2 p-2 border rounded">
                            <div class="flex-shrink-0">
                                ${isImage ? 
                                    `<img src="{{ asset('storage') }}/${filePath}" alt="${fileName}" style="width: 40px; height: 40px; object-fit: cover; border-radius: 4px;">` :
                                    `<i class="ki-duotone ki-file fs-3 text-gray-500"><span class="path1"></span><span class="path2"></span></i>`
                                }
                            </div>
                            <div class="flex-grow-1">
                                <div class="fw-bold small">${fileName}</div>
                            </div>
                        </div>
                    `;
                }
            }

            function clearFileSelection() {
                selectedFileId = null;
                selectedMediaPath = null;
                document.getElementById('file_id_input').value = '';
                document.getElementById('file_preview_container').innerHTML = '';
            }

            function addFileButton() {
                fileButtonCounter++;
                const container = document.getElementById('file_buttons_list');
                const div = document.createElement('div');
                div.className = 'row mb-3 file-button-item';
                div.setAttribute('data-file-button-id', fileButtonCounter);
                div.innerHTML = `
                    <div class="col-md-5">
                        <input type="hidden" name="file_ids[]" class="file-id-input" value="">
                        <div class="file-preview-${fileButtonCounter} mb-2"></div>
                        <button type="button" class="btn btn-primary btn-sm w-100 file-select-multi-btn" data-button-id="${fileButtonCounter}">
                            <i class="ki-duotone ki-folder fs-5"><span class="path1"></span><span class="path2"></span></i>
                            {{ __('common.select_file') }}
                        </button>
                    </div>
                    <div class="col-md-6">
                        <input type="text" name="button_names[]" class="form-control" placeholder="{{ __('common.button_name') }}" required>
                    </div>
                    <div class="col-md-1">
                        <button type="button" class="btn btn-sm btn-light-danger remove-file-button">
                            <i class="ki-solid ki-cross fs-6"></i>
                        </button>
                    </div>
                `;
                container.appendChild(div);
                
                div.querySelector('.remove-file-button').addEventListener('click', function() {
                    div.remove();
                    delete selectedMultiFileIds[fileButtonCounter];
                });

                div.querySelector('.file-select-multi-btn').addEventListener('click', function() {
                    const buttonId = this.getAttribute('data-button-id');
                    currentFileField = 'multi_file_' + buttonId;
                    openFileSelectorModal();
                });
            }

            document.getElementById('add_file_button')?.addEventListener('click', addFileButton);

            document.querySelectorAll('.remove-file-button').forEach(button => {
                button.addEventListener('click', function() {
                    const item = this.closest('.file-button-item');
                    const buttonId = item.getAttribute('data-file-button-id');
                    if (buttonId) delete selectedMultiFileIds[buttonId];
                    item.remove();
                });
            });

            document.querySelectorAll('.file-select-multi-btn').forEach(button => {
                button.addEventListener('click', function() {
                    const buttonId = this.getAttribute('data-button-id');
                    currentFileField = 'multi_file_' + buttonId;
                    openFileSelectorModal();
                });
            });

            // Filtreler için event listener'lar
            document.getElementById('file_search_input')?.addEventListener('input', debounce(loadFiles, 300));
            document.getElementById('file_type_select')?.addEventListener('change', loadFiles);
            document.getElementById('file_category_select')?.addEventListener('change', loadFiles);

            // Dosya yükleme
            document.getElementById('file_upload_input')?.addEventListener('change', function(e) {
                const file = e.target.files[0];
                if (file) {
                    uploadFile(file);
                }
            });

            function uploadFile(file) {
                const formData = new FormData();
                formData.append('file', file);
                formData.append('_token', document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '');
                
                // Eğer modal içinde kategori seçilmişse, onu ekle
                const categorySelect = document.getElementById('file_category_select');
                if (categorySelect && categorySelect.value && categorySelect.value !== 'all') {
                    formData.append('category_id', categorySelect.value);
                }

                fetch('{{ route("media-library.store") }}', {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success && data.file) {
                        selectFile(data.file.id, data.file.name, data.file.original_name, data.file.path, data.file.mime_type);
                        document.getElementById('file_upload_input').value = '';
                    } else {
                        alert(data.message || '{{ __('common.error_occurred') }}');
                    }
                })
                .catch(error => {
                    console.error('Error uploading file:', error);
                    alert('{{ __('common.error_occurred') }}');
                });
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

            // Modal açıldığında dosyaları yükle
            document.getElementById('kt_modal_file_selector')?.addEventListener('shown.bs.modal', function() {
                loadFiles();
            });
        </script>
    @endpush

</x-default-layout>
