<x-default-layout>

    @section('title')
        {{ __('common.create_qr_code') }}
    @endsection

    @section('breadcrumbs')
        {{ Breadcrumbs::render('qr-code.create') }}
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
        /* Önizleme alanı için güçlü kurallar - Universal selector */
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
        #files_list_container .card-preview,
        #files_list_container .card .card-preview,
        #files_list_container .card-body .card-preview {
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
            margin: 0 auto 0.75rem auto !important;
            overflow: hidden !important;
            flex-shrink: 0 !important;
            flex-grow: 0 !important;
        }
        #files_list_container .card-preview img,
        #files_list_container .card .card-preview img,
        #files_list_container .card-body .card-preview img {
            width: 180px !important;
            height: 180px !important;
            min-width: 180px !important;
            min-height: 180px !important;
            max-width: 180px !important;
            max-height: 180px !important;
            object-fit: contain !important;
            display: block !important;
        }
        #files_list_container .card-preview i,
        #files_list_container .card-preview .ki-duotone,
        #files_list_container .card-preview .ki-solid,
        #files_list_container .card .card-preview i,
        #files_list_container .card .card-preview .ki-duotone,
        #files_list_container .card .card-preview .ki-solid,
        #files_list_container .card-body .card-preview i,
        #files_list_container .card-body .card-preview .ki-duotone,
        #files_list_container .card-body .card-preview .ki-solid {
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
        #files_list_container .card-preview .ki-duotone span,
        #files_list_container .card-preview .ki-solid span {
            display: block !important;
        }
        #files_list_container .card-title-section {
            flex: 1 !important;
            margin-bottom: 0.75rem !important;
        }
        #files_list_container .card-title-section h6 {
            font-size: 0.875rem !important;
            font-weight: 600 !important;
            margin-bottom: 0.25rem !important;
            line-height: 1.3 !important;
            display: -webkit-box !important;
            -webkit-line-clamp: 2 !important;
            -webkit-box-orient: vertical !important;
            overflow: hidden !important;
        }
        #files_list_container .card-meta {
            font-size: 0.75rem !important;
            color: #6c757d !important;
            margin-bottom: 0.25rem !important;
        }
        #files_list_container .card-actions {
            margin-top: auto !important;
        }
    </style>
    @endpush

    <div class="card">
        <div class="card-header">
            <div class="card-title">
                <span class="svg-icon svg-icon-success svg-icon-2 me-2">
                    <i class="ki-solid ki-scan-barcode fs-2"></i>
                </span>
                <h3 class="fw-bold m-0 d-inline">{{ __('common.create_qr_code') }}</h3>
            </div>
        </div>
        <div class="card-body">
            <form action="{{ route('qr-code.store') }}" method="POST" id="qr_code_form">
                @csrf
                
                <div class="mb-5">
                    <label class="form-label required">{{ __('common.qr_code_name') }}</label>
                    <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" required>
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="row">
                    <div class="col-md-6 mb-5">
                        <label class="form-label">{{ __('common.category') }}</label>
                        <select name="category_id" id="category_id" class="form-select" data-control="select2" data-placeholder="{{ __('common.select_category') }}">
                            <option value="">{{ __('common.select_category') }}</option>
                            @foreach($categories ?? [] as $category)
                                <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6 mb-5">
                        <label class="form-label">{{ __('common.requested_by') }}</label>
                        <input type="text" name="requested_by" class="form-control" value="{{ old('requested_by') }}">
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-5">
                        <label class="form-label">{{ __('common.request_date') }}</label>
                        <input type="date" name="request_date" class="form-control" value="{{ old('request_date', date('Y-m-d')) }}">
                    </div>
                    <div class="col-md-6 mb-5">
                        <label class="form-label">{{ __('common.qr_code_type') }}</label>
                        <select name="qr_type" id="qr_type" class="form-select @error('qr_type') is-invalid @enderror" required>
                            <option value="file" {{ old('qr_type', 'file') == 'file' ? 'selected' : '' }}>{{ __('common.type_file') }}</option>
                            <option value="url" {{ old('qr_type') == 'url' ? 'selected' : '' }}>{{ __('common.type_url') }}</option>
                            <option value="multi_file" {{ old('qr_type') == 'multi_file' ? 'selected' : '' }}>{{ __('common.type_multi_file') }}</option>
                        </select>
                        <div id="qr_type_description" class="form-text mt-2"></div>
                        @error('qr_type')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="mb-5" id="file_id_field" style="display: {{ in_array(old('qr_type', 'file'), ['file', 'url']) ? 'block' : 'none' }};">
                    <label class="form-label {{ old('qr_type', 'file') == 'file' ? 'required' : '' }}">{{ __('common.select_file') }}</label>
                    <input type="hidden" name="file_id" id="file_id_input" value="{{ old('file_id') }}">
                    <div id="file_id_preview" class="mb-3">
                        @if(old('file_id'))
                            @php
                                $selectedFile = $files->firstWhere('id', old('file_id'));
                            @endphp
                            @if($selectedFile)
                                <div class="alert alert-info d-flex align-items-center justify-content-between">
                                    <span>{{ $selectedFile->name }} ({{ $selectedFile->original_name }})</span>
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
                    @error('file_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-5" id="multi_file_field" style="display: {{ old('qr_type') == 'multi_file' ? 'block' : 'none' }};">
                    <label class="form-label required">{{ __('common.page_title') }}</label>
                    <input type="text" name="page_title" id="page_title" class="form-control" value="{{ old('page_title') }}" placeholder="{{ __('common.example_catalog') }}">
                    <small class="form-text text-muted">{{ __('common.multi_file_page_title_desc') }}</small>
                    
                    <div class="mt-5" id="file_buttons_container">
                        <label class="form-label required">{{ __('common.files_and_buttons') }}</label>
                        <div id="file_buttons_list">
                            <!-- Dinamik olarak eklenecek -->
                        </div>
                        <button type="button" class="btn btn-sm btn-light-primary mt-3" id="add_file_button">
                            <i class="ki-solid ki-plus fs-6"></i> {{ __('common.add_file') }}
                        </button>
                    </div>
                </div>

                <div class="mb-5" id="content_field" style="display: {{ old('qr_type', 'file') == 'url' ? 'block' : 'none' }};">
                    <label class="form-label required">{{ __('common.content') }}</label>
                    <textarea name="content" class="form-control @error('content') is-invalid @enderror" rows="3" {{ old('qr_type', 'file') == 'url' ? 'required' : '' }}>{{ old('content') }}</textarea>
                    @error('content')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="row">
                    <div class="col-md-6 mb-5">
                        <label class="form-label">{{ __('common.size') }} (px)</label>
                        <input type="number" name="size" class="form-control" value="{{ old('size', 300) }}" min="100" max="1000">
                    </div>
                    <div class="col-md-6 mb-5">
                        <label class="form-label">{{ __('common.format') }}</label>
                        <select name="format" class="form-select">
                            <option value="svg" {{ old('format', 'svg') == 'svg' ? 'selected' : '' }}>SVG</option>
                            <option value="png" {{ old('format') == 'png' ? 'selected' : '' }}>PNG</option>
                        </select>
                        <small class="form-text text-muted">{{ __('common.png_format_info') }}</small>
                    </div>
                </div>

                <div class="mb-5">
                    <label class="form-label">{{ __('common.description') }}</label>
                    <textarea name="description" class="form-control" rows="3">{{ old('description') }}</textarea>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-5">
                        <label class="form-check form-switch form-check-custom form-check-solid">
                            <input class="form-check-input" type="checkbox" name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }}>
                            <span class="form-check-label">{{ __('common.active') }}</span>
                        </label>
                    </div>
                    <div class="col-md-6 mb-5">
                        <label class="form-label">{{ __('common.expires_at') }}</label>
                        <input type="date" name="expires_at" class="form-control" value="{{ old('expires_at') }}">
                    </div>
                </div>

                <div class="d-flex justify-content-end">
                    <a href="{{ route('qr-code.index') }}" class="btn btn-light me-3">{{ __('common.cancel') }}</a>
                    <button type="submit" class="btn btn-primary">{{ __('common.create') }}</button>
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
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">{{ __('common.upload_from_computer') }}</label>
                                <input type="file" id="file_upload_input" class="form-control" accept="*/*">
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
            let files = @json($files); // let yapıldı, yeni dosyalar eklendiğinde güncellenebilir
            let fileButtonCounter = 0;
            let currentFileField = null;
            let mediaFiles = [];
            let selectedFileId = null; // Seçili dosya ID'sini takip et (tek dosya için)
            let selectedMediaPath = null; // Seçili media library dosya path'ini takip et (tek dosya için)
            let selectedMultiFileIds = {}; // Çoklu dosya seçimlerini takip et {buttonId: fileId}

            // QR Kod Tipi açıklamaları
            const qrTypeDescriptions = {
                'file': 'Tek bir dosya için QR kod oluşturur. QR kod taranınca seçilen dosya indirilebilir.',
                'url': 'Herhangi bir URL için QR kod oluşturur. QR kod taranınca belirtilen URL\'ye yönlendirir.',
                'multi_file': 'Birden fazla dosya için QR kod oluşturur. QR kod taranınca bir sayfa açılır ve kullanıcı istediği dosyayı seçerek indirebilir.'
            };

            // QR Kod Tipi değiştiğinde açıklama göster
            document.getElementById('qr_type').addEventListener('change', function() {
                const descriptionDiv = document.getElementById('qr_type_description');
                const selectedType = this.value;
                if (qrTypeDescriptions[selectedType]) {
                    descriptionDiv.textContent = qrTypeDescriptions[selectedType];
                    descriptionDiv.style.display = 'block';
                } else {
                    descriptionDiv.style.display = 'none';
                }
            });

            // İlk yüklemede açıklama göster
            const qrTypeSelect = document.getElementById('qr_type');
            if (qrTypeSelect) {
                const descriptionDiv = document.getElementById('qr_type_description');
                const selectedType = qrTypeSelect.value;
                if (qrTypeDescriptions[selectedType]) {
                    descriptionDiv.textContent = qrTypeDescriptions[selectedType];
                    descriptionDiv.style.display = 'block';
                }
            }

            function updateFields() {
                const qrType = document.getElementById('qr_type').value;
                const fileField = document.getElementById('file_id_field');
                const multiFileField = document.getElementById('multi_file_field');
                const contentField = document.getElementById('content_field');
                const fileIdSelect = document.getElementById('file_id_select');
                const contentTextarea = contentField.querySelector('textarea');
                
                if (qrType === 'file') {
                    fileField.style.display = 'block';
                    multiFileField.style.display = 'none';
                    contentField.style.display = 'none';
                    if (fileIdSelect) {
                        fileIdSelect.style.display = 'block';
                        fileIdSelect.setAttribute('required', 'required');
                    }
                    if (contentTextarea) {
                        contentTextarea.removeAttribute('required');
                    }
                } else if (qrType === 'multi_file') {
                    fileField.style.display = 'none';
                    multiFileField.style.display = 'block';
                    contentField.style.display = 'none';
                    if (fileIdSelect) {
                        fileIdSelect.style.display = 'none';
                        fileIdSelect.removeAttribute('required');
                    }
                    if (contentTextarea) {
                        contentTextarea.removeAttribute('required');
                    }
                    if (document.getElementById('file_buttons_list').children.length === 0) {
                        addFileButton();
                    }
                } else {
                    fileField.style.display = 'block';
                    multiFileField.style.display = 'none';
                    contentField.style.display = 'block';
                    if (fileIdSelect) {
                        fileIdSelect.style.display = 'block';
                        fileIdSelect.removeAttribute('required');
                    }
                    if (contentTextarea) {
                        contentTextarea.setAttribute('required', 'required');
                    }
                }
            }
            
            document.getElementById('qr_type').addEventListener('change', updateFields);
            
            // İlk yüklemede alanları güncelle
            updateFields();

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
                            ` + window.__('common.select_file') + `
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
                });

                // Dosya seç butonuna event listener ekle
                div.querySelector('.file-select-multi-btn').addEventListener('click', function() {
                    const buttonId = this.getAttribute('data-button-id');
                    currentFileField = 'multi_file_' + buttonId;
                    openFileSelectorModal();
                });
            }

            document.getElementById('add_file_button').addEventListener('click', addFileButton);

            // İlk yüklemede eğer multi_file seçiliyse
            if (document.getElementById('qr_type').value === 'multi_file') {
                addFileButton();
            }
            
            // Form submit öncesi kontrol
            const form = document.getElementById('qr_code_form');
            if (form) {
                form.addEventListener('submit', function(e) {
                    const qrType = document.getElementById('qr_type').value;
                    if (qrType === 'multi_file') {
                        const fileInputs = document.querySelectorAll('input[name="file_ids[]"]');
                        const buttonInputs = document.querySelectorAll('input[name="button_names[]"]');
                        
                        // Boş dosya seçimlerini kaldır
                        const itemsToRemove = [];
                        fileInputs.forEach((input) => {
                            if (!input.value || input.value === '') {
                                itemsToRemove.push(input.closest('.file-button-item'));
                            }
                        });
                        itemsToRemove.forEach(item => item?.remove());
                        
                        // Son kontrol: En az bir dosya olmalı
                        const remainingInputs = document.querySelectorAll('input[name="file_ids[]"]');
                        if (remainingInputs.length === 0) {
                            e.preventDefault();
                            alert(window.__('common.at_least_one_file'));
                            return false;
                        }
                        
                        // Tüm file_ids ve button_names değerlerini kontrol et
                        const fileIds = Array.from(remainingInputs).map(i => i.value).filter(v => v);
                        const buttonNames = Array.from(document.querySelectorAll('input[name="button_names[]"]'))
                            .map(i => i.value)
                            .filter((v, idx) => fileIds[idx]); // Sadece seçili dosyalar için
                        
                        // Her dosya için button name olmalı
                        if (fileIds.length !== buttonNames.length) {
                            e.preventDefault();
                            alert('Her dosya için buton adı gereklidir.');
                            return false;
                        }
                        
                        // Debug için console
                        console.log('Submitting form with:', {
                            file_ids: fileIds,
                            button_names: buttonNames,
                            file_ids_count: fileIds.length,
                            button_names_count: buttonNames.length
                        });
                    }
                });
            }

            // Dosya seçimi modalı
            document.addEventListener('DOMContentLoaded', function() {
                // Dosya seç butonuna tıklandığında
                document.querySelectorAll('[data-media-select="file_id"]').forEach(function(button) {
                    button.addEventListener('click', function(e) {
                        e.preventDefault();
                        currentFileField = 'file_id';
                        openFileSelectorModal();
                    });
                });

                // Modal açıldığında dosyaları yükle
                const modalElement = document.getElementById('kt_modal_file_selector');
                if (modalElement) {
                    modalElement.addEventListener('show.bs.modal', function() {
                        // Seçili dosya ID'sini kontrol et
                        if (currentFileField && currentFileField.startsWith('multi_file_')) {
                            // Çoklu dosya seçimi
                            const buttonId = currentFileField.replace('multi_file_', '');
                            const fileInput = document.querySelector(`.file-button-item[data-file-button-id="${buttonId}"] .file-id-input`);
                            if (fileInput && fileInput.value) {
                                selectedMultiFileIds[buttonId] = fileInput.value;
                            }
                            selectedFileId = null;
                            selectedMediaPath = null;
                        } else {
                            // Tek dosya seçimi
                            const fileIdInput = document.getElementById('file_id_input');
                            if (fileIdInput && fileIdInput.value) {
                                selectedFileId = fileIdInput.value;
                            } else {
                                selectedFileId = null;
                            }
                            selectedMediaPath = null;
                        }
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
                container.innerHTML = '<div class="col-12 text-center py-10"><div class="spinner-border text-primary" role="status"><span class="visually-hidden">Yükleniyor...</span></div></div>';

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
                        // Media Library'den gelen dosyaları formatla
                        const dbFiles = (data.files || []).filter(file => file.id !== null).map(file => ({
                            id: file.id,
                            name: file.name,
                            original_name: file.original_name,
                            path: file.path,
                            type: file.type,
                            mime_type: file.mime_type,
                            size: file.size
                        }));
                        
                        // File modelinde olmayan dosyalar (settings klasöründeki)
                        mediaFiles = (data.files || []).filter(file => file.id === null);
                        
                        // Tek seferde render et
                        renderFilesList(dbFiles, mediaFiles);
                    } else {
                        // Hata durumunda boş liste göster
                        renderFilesList([], []);
                    }
                })
                .catch(error => {
                    console.error('Error loading media files:', error);
                    // Hata durumunda boş liste göster
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
                        // Tek dosya seçimi için kontrol
                        const isSelected = selectedFileId == file.id;
                        // Çoklu dosya seçimi için kontrol
                        const isMultiSelected = currentFileField && currentFileField.startsWith('multi_file_') 
                            ? selectedMultiFileIds[currentFileField.replace('multi_file_', '')] == file.id
                            : false;
                        const isAnySelected = isSelected || isMultiSelected;
                        
                        html += `
                            <div class="col-xl-3 col-lg-4 col-md-6 col-sm-6 col-12">
                                <div class="card card-flush file-item h-100" data-file-id="${file.id}" data-file-name="${file.name}" data-file-selected="${isAnySelected}" ${isAnySelected ? 'style="border: 2px solid #0d6efd; border-radius: 8px;"' : ''}>
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
                                        ${isAnySelected ? `
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
                        // Eğer bu dosya zaten DB dosyaları arasında varsa, media library versiyonunu gösterme (çünkü DB versiyonu zaten gösteriliyor)
                        const existingDbFile = dbFiles.find(dbFile => dbFile.path === file.path);
                        if (existingDbFile) {
                            // DB'de bu dosya var, media library versiyonunu atla
                            return; // Bu dosyayı atla
                        }
                        
                        const isImage = file.type === 'image';
                        const fileKey = file.path.replace(/[^a-zA-Z0-9]/g, '_');
                        // Tek dosya seçimi için kontrol
                        const isSelected = selectedMediaPath == file.path;
                        // Çoklu dosya seçimi için kontrol (media library dosyası seçildiyse)
                        const isMultiSelected = false; // Media library dosyaları çoklu seçimde kullanılmıyor (önce File modelinde oluşturuluyor)
                        const isAnySelected = isSelected || isMultiSelected;
                        
                        html += `
                            <div class="col-xl-3 col-lg-4 col-md-6 col-sm-6 col-12">
                                <div class="card card-flush media-file-item h-100" data-file-path="${file.path}" data-file-url="${file.url}" data-file-selected="${isAnySelected}" ${isAnySelected ? 'style="border: 2px solid #0d6efd; border-radius: 8px;"' : ''}>
                                    <div class="card-body">
                                    <div class="card-preview" style="width: 100% !important; height: 180px !important; min-width: 0 !important; min-height: 180px !important; max-width: 100% !important; max-height: 180px !important; display: flex !important; align-items: center !important; justify-content: center !important;">
                                        ${isImage ? `
                                            <img src="${file.url}" alt="${file.name}" style="width: 100% !important; height: 180px !important; min-width: 0 !important; min-height: 180px !important; max-width: 100% !important; max-height: 180px !important; object-fit: contain !important; display: block !important;">
                                        ` : `
                                            ${getFileIcon(file.type)}
                                        `}
                                    </div>
                                    <div class="card-title-section">
                                        <h6 class="text-gray-800 fw-bold" title="${file.name}">
                                            ${truncateString(file.name, 25)}
                                        </h6>
                                        <div class="card-meta">Media Library</div>
                                    </div>
                                    <div class="card-actions media-action-buttons-${fileKey}">
                                        ${isAnySelected ? `
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
                    html = '<div class="col-12 text-center py-10"><p class="text-gray-500">Henüz dosya bulunamadı.</p></div>';
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
                        
                        const img = preview.querySelector('img');
                        if (img) {
                            img.style.width = '100%';
                            img.style.height = '180px';
                            img.style.minWidth = '0';
                            img.style.minHeight = '180px';
                            img.style.maxWidth = '100%';
                            img.style.maxHeight = '180px';
                            img.style.objectFit = 'contain';
                            img.style.display = 'block';
                        }
                        
                        const icons = preview.querySelectorAll('i, .ki-duotone, .ki-solid');
                        icons.forEach(icon => {
                            icon.style.fontSize = '48px';
                            icon.style.width = '48px';
                            icon.style.height = '48px';
                            icon.style.minWidth = '48px';
                            icon.style.minHeight = '48px';
                            icon.style.maxWidth = '48px';
                            icon.style.maxHeight = '48px';
                        });
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
                        if (currentFileField && currentFileField.startsWith('multi_file_')) {
                            const buttonId = currentFileField.replace('multi_file_', '');
                            // Çoklu dosya seçimi - sadece bu buton için seçimi temizle
                            const prevSelectedId = selectedMultiFileIds[buttonId];
                            if (prevSelectedId && prevSelectedId != fileId) {
                                const prevCard = document.querySelector(`.file-item[data-file-id="${prevSelectedId}"]`);
                                if (prevCard) {
                                    updateFileSelectionUI(prevCard, prevSelectedId, false);
                                }
                            }
                        } else {
                            // Tek dosya seçimi - önceki seçimi temizle
                            if (selectedFileId && selectedFileId != fileId) {
                                const prevCard = document.querySelector(`.file-item[data-file-id="${selectedFileId}"]`);
                                if (prevCard) {
                                    updateFileSelectionUI(prevCard, selectedFileId, false);
                                }
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
                        if (currentFileField && currentFileField.startsWith('multi_file_')) {
                            const buttonId = currentFileField.replace('multi_file_', '');
                            // Çoklu dosya seçimi - önceki media seçimini temizle
                            if (selectedMediaPath && selectedMediaPath != filePath) {
                                const prevFileKey = selectedMediaPath.replace(/[^a-zA-Z0-9]/g, '_');
                                const prevCard = document.querySelector(`.media-file-item[data-file-path="${selectedMediaPath}"]`);
                                if (prevCard) {
                                    updateMediaFileSelectionUI(prevCard, prevFileKey, false);
                                }
                            }
                        } else {
                            // Tek dosya seçimi - önceki seçimleri temizle
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
                        }
                        
                        // Media library dosyasını seçili olarak işaretle
                        if (card) {
                            updateMediaFileSelectionUI(card, fileKey, true);
                        }
                        selectedMediaPath = filePath;
                        selectedFileId = null; // DB dosya seçimini temizle
                        createFileFromPath(filePath, fileName);
                    });
                });
            }

            function selectFile(fileId, fileName, source) {
                if (!currentFileField) return;

                // Çoklu dosya seçimi mi kontrol et
                if (currentFileField.startsWith('multi_file_')) {
                    const buttonId = currentFileField.replace('multi_file_', '');
                    const fileInput = document.querySelector(`.file-button-item[data-file-button-id="${buttonId}"] .file-id-input`);
                    const previewDiv = document.querySelector(`.file-button-item[data-file-button-id="${buttonId}"] .file-preview-${buttonId}`);
                    const selectBtn = document.querySelector(`.file-button-item[data-file-button-id="${buttonId}"] .file-select-multi-btn`);
                    
                    // Önceki seçimi temizle (eğer varsa)
                    const prevSelectedId = selectedMultiFileIds[buttonId];
                    if (prevSelectedId && prevSelectedId != fileId) {
                        const prevCard = document.querySelector(`.file-item[data-file-id="${prevSelectedId}"]`);
                        if (prevCard) {
                            updateFileSelectionUI(prevCard, prevSelectedId, false);
                        }
                    }
                    
                    // Seçili dosyayı kaydet
                    selectedMultiFileIds[buttonId] = fileId;
                    
                    if (fileInput) {
                        fileInput.value = fileId;
                    }
                    if (previewDiv) {
                        previewDiv.innerHTML = `
                            <div class="alert alert-success d-flex align-items-center justify-content-between">
                                <span><i class="ki-solid ki-check fs-6 me-2"></i>${fileName}</span>
                                <button type="button" class="btn btn-sm btn-light-danger" onclick="clearMultiFileSelection('${buttonId}')">
                                    <i class="ki-solid ki-cross fs-6"></i>
                                </button>
                            </div>
                        `;
                    }
                    if (selectBtn) {
                        selectBtn.innerHTML = `
                            <i class="ki-solid ki-check fs-5 me-2"></i>` + window.__('common.selected') + `
                        `;
                        selectBtn.classList.remove('btn-primary');
                        selectBtn.classList.add('btn-success');
                    }
                } else {
                    // Tek dosya seçimi - önceki seçimi temizle
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
                    
                    selectedFileId = fileId;
                    selectedMediaPath = null; // Media library seçimini temizle
                    const fileIdInput = document.getElementById('file_id_input');
                    const previewDiv = document.getElementById('file_id_preview');
                    if (fileIdInput) {
                        fileIdInput.value = fileId;
                    }
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
                }

                // Modal'ı kapat
                const modalElement = document.getElementById('kt_modal_file_selector');
                if (modalElement) {
                    const modal = bootstrap.Modal.getInstance(modalElement);
                    if (modal) modal.hide();
                }
            }

            function clearMultiFileSelection(buttonId) {
                const fileInput = document.querySelector(`.file-button-item[data-file-button-id="${buttonId}"] .file-id-input`);
                const previewDiv = document.querySelector(`.file-button-item[data-file-button-id="${buttonId}"] .file-preview-${buttonId}`);
                const selectBtn = document.querySelector(`.file-button-item[data-file-button-id="${buttonId}"] .file-select-multi-btn`);
                
                // Seçili dosyayı temizle
                delete selectedMultiFileIds[buttonId];
                
                if (fileInput) fileInput.value = '';
                if (previewDiv) previewDiv.innerHTML = '';
                if (selectBtn) {
                    selectBtn.innerHTML = `
                        <i class="ki-duotone ki-folder fs-5"><span class="path1"></span><span class="path2"></span></i>
                        ` + window.__('common.select_file') + `
                    `;
                    selectBtn.classList.remove('btn-success');
                    selectBtn.classList.add('btn-primary');
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
                                <i class="ki-solid ki-check fs-6"></i> ` + window.__('common.selected') + `
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
                            Seç
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
                                <i class="ki-solid ki-check fs-6"></i> ` + window.__('common.selected') + `
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
                            Seç
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
                if (source === 'db') {
                    // Çoklu dosya seçimi mi kontrol et
                    if (currentFileField && currentFileField.startsWith('multi_file_')) {
                        const buttonId = currentFileField.replace('multi_file_', '');
                        delete selectedMultiFileIds[buttonId];
                        const fileInput = document.querySelector(`.file-button-item[data-file-button-id="${buttonId}"] .file-id-input`);
                        const previewDiv = document.querySelector(`.file-button-item[data-file-button-id="${buttonId}"] .file-preview-${buttonId}`);
                        const selectBtn = document.querySelector(`.file-button-item[data-file-button-id="${buttonId}"] .file-select-multi-btn`);
                        if (fileInput) fileInput.value = '';
                        if (previewDiv) previewDiv.innerHTML = '';
                        if (selectBtn) {
                            selectBtn.innerHTML = `
                                <i class="ki-duotone ki-folder fs-5"><span class="path1"></span><span class="path2"></span></i>
                                ` + window.__('common.select_file') + `
                            `;
                            selectBtn.classList.remove('btn-success');
                            selectBtn.classList.add('btn-primary');
                        }
                    } else {
                        selectedFileId = null;
                        selectedMediaPath = null;
                        const fileIdInput = document.getElementById('file_id_input');
                        const fileIdPreview = document.getElementById('file_id_preview');
                        if (fileIdInput) fileIdInput.value = '';
                        if (fileIdPreview) fileIdPreview.innerHTML = '';
                    }
                    // Dosya listesini yeniden yükle
                    loadFiles();
                }
            }

            function clearMediaFileSelectionUI(fileKey) {
                // Çoklu dosya seçimi mi kontrol et
                if (currentFileField && currentFileField.startsWith('multi_file_')) {
                    const buttonId = currentFileField.replace('multi_file_', '');
                    delete selectedMultiFileIds[buttonId];
                    const fileInput = document.querySelector(`.file-button-item[data-file-button-id="${buttonId}"] .file-id-input`);
                    const previewDiv = document.querySelector(`.file-button-item[data-file-button-id="${buttonId}"] .file-preview-${buttonId}`);
                    const selectBtn = document.querySelector(`.file-button-item[data-file-button-id="${buttonId}"] .file-select-multi-btn`);
                    if (fileInput) fileInput.value = '';
                    if (previewDiv) previewDiv.innerHTML = '';
                    if (selectBtn) {
                        selectBtn.innerHTML = `
                            <i class="ki-duotone ki-folder fs-5"><span class="path1"></span><span class="path2"></span></i>
                            Dosya Seç
                        `;
                        selectBtn.classList.remove('btn-success');
                        selectBtn.classList.add('btn-primary');
                    }
                } else {
                    selectedMediaPath = null;
                    selectedFileId = null;
                    const fileIdInput = document.getElementById('file_id_input');
                    const fileIdPreview = document.getElementById('file_id_preview');
                    if (fileIdInput) fileIdInput.value = '';
                    if (fileIdPreview) fileIdPreview.innerHTML = '';
                }
                // Dosya listesini yeniden yükle
                loadFiles();
            }

            function createFileFromPath(path, fileName) {
                // Loading durumu için media path'i işaretle
                if (!(currentFileField && currentFileField.startsWith('multi_file_'))) {
                    selectedMediaPath = path;
                    selectedFileId = null;
                }

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
                        // Dosya oluşturuldu, seç
                        if (currentFileField && currentFileField.startsWith('multi_file_')) {
                            // Çoklu dosya seçimi için selectFile çağrılacak
                        } else {
                            selectedFileId = data.id;
                            selectedMediaPath = null; // Media library seçimini temizle
                        }
                        selectFile(data.id, data.file.name || fileName, 'media');
                    } else {
                        throw new Error(data.message || 'Dosya oluşturulamadı.');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    Swal.fire({
                        icon: 'error',
                        title: getTranslation('common.error'),
                        text: error.message || 'Dosya oluşturulurken bir hata oluştu.'
                    });
                    // Seçimi temizle
                    if (!(currentFileField && currentFileField.startsWith('multi_file_'))) {
                        selectedMediaPath = null;
                        selectedFileId = null;
                    }
                    // Dosya listesini yeniden yükle
                    loadFiles();
                });
            }

            function clearFileSelection() {
                const fileId = document.getElementById('file_id_input').value;
                selectedFileId = null;
                selectedMediaPath = null;
                document.getElementById('file_id_input').value = '';
                document.getElementById('file_id_preview').innerHTML = '';
            }

            function uploadFile(file) {
                const formData = new FormData();
                formData.append('file', file);
                formData.append('folder', 'files');
                
                // Yükleme için kategori seçimi (upload_category_select)
                const uploadCategorySelect = document.getElementById('upload_category_select');
                if (uploadCategorySelect && uploadCategorySelect.value) {
                    formData.append('category_id', uploadCategorySelect.value);
                } else {
                    // Eğer yükleme kategorisi seçilmemişse, filtre kategorisini kullan
                    const categorySelect = document.getElementById('file_category_select');
                    if (categorySelect && categorySelect.value && categorySelect.value !== 'all') {
                        formData.append('category_id', categorySelect.value);
                    }
                }

                fetch('{{ route("media-library.store") }}', {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Accept': 'application/json'
                    }
                })
                .then(response => {
                    if (!response.ok) {
                        return response.json().then(err => {
                            throw new Error(err.message || 'Dosya yüklenirken bir hata oluştu.');
                        });
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.success) {
                        // Dosyaları yeniden yükle
                        loadFiles();
                        // Upload input'u temizle
                        document.getElementById('file_upload_input').value = '';
                    } else {
                        throw new Error(data.message || 'Dosya yüklenemedi.');
                    }
                })
                .catch(error => {
                    console.error('Upload error:', error);
                    alert('Dosya yüklenirken bir hata oluştu: ' + error.message);
                });
            }

            function getFileIcon(type) {
                const icons = {
                    'video': '<i class="ki-duotone ki-video text-gray-400"><span class="path1"></span><span class="path2"></span></i>',
                    'audio': '<i class="ki-duotone ki-music text-gray-400"><span class="path1"></span><span class="path2"></span></i>',
                    'document': '<i class="ki-duotone ki-document text-gray-400"><span class="path1"></span><span class="path2"></span></i>',
                    'other': '<i class="ki-duotone ki-file text-gray-400"><span class="path1"></span><span class="path2"></span></i>'
                };
                return icons[type] || icons['other'];
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

            // Çeviri fonksiyonunu global scope'a taşı
            window.getTranslation = function(key) {
                if (typeof window.__ === 'function') {
                    const translated = window.__(key);
                    return translated !== key ? translated : key;
                }
                // Fallback çeviriler
                const fallbacks = {
                    'common.add_new_category': '{{ __('common.add_new_category') }}',
                    'common.new_category_name_prompt': '{{ __('common.new_category_name_prompt') }}',
                    'common.category_create_error_message': '{{ __('common.category_create_error_message') }}'
                };
                return fallbacks[key] || key;
            };
            
            // Kısa yol için
            function getTranslation(key) {
                return window.getTranslation(key);
            }

            // Kategori için yeni ekleme özelliği
            document.addEventListener('DOMContentLoaded', function() {
                // Ana form kategori seçimi
                const categorySelect = document.getElementById('category_id');
                if (categorySelect && typeof $ !== 'undefined') {
                    // Select2 ile kategori seçimi
                    $(categorySelect).select2({
                        placeholder: '{{ __('common.select_category') }}',
                        allowClear: true,
                        language: {
                            noResults: function() {
                                return '<div class="p-2"><button type="button" class="btn btn-sm btn-primary w-100" onclick="event.stopPropagation(); createNewCategory();">+ ' + getTranslation('common.add_new_category') + '</button></div>';
                            }
                        },
                        escapeMarkup: function(markup) {
                            return markup;
                        },
                        dropdownCssClass: 'select2-dropdown-no-overflow'
                    });

                    // Select2 açıldığında "yeni ekle" seçeneğini ekle
                    $(categorySelect).on('select2:open', function() {
                        setTimeout(function() {
                            const results = $('.select2-results__options');
                            if (results.find('.add-category-option').length === 0 && results.find('.select2-results__message').length === 0) {
                                results.prepend('<li class="select2-results__option add-category-option" style="cursor: pointer; padding: 8px;"><button type="button" class="btn btn-sm btn-primary w-100" onclick="event.stopPropagation(); createNewCategory();">+ ' + getTranslation('common.add_new_category') + '</button></li>');
                            }
                        }, 100);
                    });

                    // Select2 kapandığında "yeni ekle" butonunu temizle
                    $(categorySelect).on('select2:close', function() {
                        $('.add-category-option').remove();
                    });
                }

                // Upload kategori seçimi için Select2 başlatma
                const uploadCategorySelect = document.getElementById('upload_category_select');
                if (uploadCategorySelect && typeof $ !== 'undefined') {
                    $(uploadCategorySelect).select2({
                        placeholder: '{{ __('common.select_category') }}',
                        allowClear: true,
                        language: {
                            noResults: function() {
                                return '<div class="p-2"><button type="button" class="btn btn-sm btn-primary w-100" onclick="event.stopPropagation(); createNewCategoryForUpload();">+ ' + getTranslation('common.add_new_category') + '</button></div>';
                            }
                        },
                        escapeMarkup: function(markup) {
                            return markup;
                        },
                        dropdownCssClass: 'select2-dropdown-no-overflow'
                    });

                    // Select2 açıldığında "yeni ekle" seçeneğini ekle
                    $(uploadCategorySelect).on('select2:open', function() {
                        setTimeout(function() {
                            const dropdown = $('.select2-dropdown');
                            const options = dropdown.find('.select2-results__options');
                            if (options.find('.add-category-option-upload').length === 0 && options.find('.select2-results__message').length === 0) {
                                options.prepend('<li class="select2-results__option add-category-option-upload" style="cursor: pointer; padding: 8px;"><button type="button" class="btn btn-sm btn-primary w-100" onclick="event.stopPropagation(); createNewCategoryForUpload();">+ ' + getTranslation('common.add_new_category') + '</button></li>');
                            }
                        }, 100);
                    });

                    // Select2 kapandığında "yeni ekle" butonunu temizle
                    $(uploadCategorySelect).on('select2:close', function() {
                        $('.add-category-option-upload').remove();
                    });
                }
            });

            function createNewCategory() {
                // Select2'yi kapat
                if (typeof $ !== 'undefined') {
                    $('#category_id').select2('close');
                }
                
                const categoryName = prompt(getTranslation('common.new_category_name_prompt'));
                if (categoryName && categoryName.trim()) {
                    createCategoryAndAddToSelects(categoryName.trim(), 'category_id');
                }
            }

            function createNewCategoryForUpload() {
                // Select2'yi kapat
                if (typeof $ !== 'undefined') {
                    $('#upload_category_select').select2('close');
                }
                
                const categoryName = prompt(getTranslation('common.new_category_name_prompt'));
                if (categoryName && categoryName.trim()) {
                    createCategoryAndAddToSelects(categoryName.trim(), 'upload_category_select');
                }
            }

            function createCategoryAndAddToSelects(categoryName, targetSelectId) {
                // FormData kullan (Laravel form request için)
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
                            throw new Error(err.message || getTranslation('common.category_create_error_message'));
                        });
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.success || data.id) {
                        const categoryId = data.id || data.category?.id;
                        const categoryNameValue = data.category?.name || categoryName;
                        
                        // Tüm kategori select'lerine ekle
                        const selectIds = ['category_id', 'upload_category_select', 'file_category_select'];
                        selectIds.forEach(selectId => {
                            const select = document.getElementById(selectId);
                            if (select) {
                                // Eğer kategori zaten varsa ekleme
                                const existingOption = Array.from(select.options).find(opt => opt.value == categoryId);
                                if (!existingOption) {
                                    const option = document.createElement('option');
                                    option.value = categoryId;
                                    option.textContent = categoryNameValue;
                                    select.appendChild(option);
                                    
                                    // Select2 varsa güncelle
                                    if (typeof $ !== 'undefined' && $(select).hasClass('select2-hidden-accessible')) {
                                        $(select).trigger('change');
                                    }
                                }
                            }
                        });
                        
                        // Hedef select'i seç ve güncelle
                        const targetSelect = document.getElementById(targetSelectId);
                        if (targetSelect) {
                            targetSelect.value = categoryId;
                            if (typeof $ !== 'undefined') {
                                $(targetSelect).trigger('change');
                            }
                        }
                    } else {
                        throw new Error(getTranslation('common.category_create_error_message'));
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert(getTranslation('common.category_create_error_message') + ': ' + error.message);
                });
            }
        </script>
    @endpush

</x-default-layout>


