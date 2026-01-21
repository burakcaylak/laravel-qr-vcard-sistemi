<x-default-layout>

    @section('title')
        {{ __('common.file_management') }}
    @endsection

    @section('breadcrumbs')
        {{ Breadcrumbs::render('file-management.index') }}
    @endsection

    <div class="card">
        <!--begin::Card header-->
        <div class="card-header border-0 pt-6">
            <!--begin::Card title-->
            <div class="card-title">
                <!--begin::Search-->
                <div class="d-flex align-items-center position-relative my-1">
                    {!! getIcon('magnifier', 'fs-3 position-absolute ms-5') !!}
                    <input type="text" data-kt-file-table-filter="search" class="form-control form-control-solid w-250px ps-13" placeholder="{{ __('common.search') }}..." id="mySearchInput"/>
                </div>
                <!--end::Search-->
                <!--begin::Filters-->
                <div class="d-flex align-items-center gap-2 ms-5">
                    <select class="form-select form-select-sm w-150px" id="filter-status">
                        <option value="">{{ __('common.all_status') }}</option>
                        <option value="active">{{ __('common.active') }}</option>
                        <option value="inactive">{{ __('common.inactive') }}</option>
                    </select>
                    <select class="form-select form-select-sm w-150px" id="filter-category">
                        <option value="">{{ __('common.all_categories') }}</option>
                        @foreach(\App\Models\Category::where('is_active', true)->orderBy('name')->get() as $category)
                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                        @endforeach
                    </select>
                    <input type="date" class="form-control form-control-sm w-150px" id="filter-date-from" placeholder="{{ __('common.date_from') }}">
                    <input type="date" class="form-control form-control-sm w-150px" id="filter-date-to" placeholder="{{ __('common.date_to') }}">
                    <button type="button" class="btn btn-sm btn-light" id="filter-apply">
                        {{ __('common.filter') }}
                    </button>
                    <button type="button" class="btn btn-sm btn-light" id="filter-clear">
                        {{ __('common.clear') }}
                    </button>
                </div>
                <!--end::Filters-->
            </div>
            <!--begin::Card title-->

            <!--begin::Card toolbar-->
            <div class="card-toolbar">
                <!--begin::Toolbar-->
                <div class="d-flex justify-content-end gap-2" data-kt-file-table-toolbar="base">
                    <!--begin::Bulk Actions-->
                    <div class="d-none d-flex align-items-center gap-2" id="bulk-actions-container">
                        <select class="form-select form-select-sm w-150px" id="bulk-action-select">
                            <option value="">{{ __('common.select_action') }}</option>
                            <option value="activate">{{ __('common.activate') }}</option>
                            <option value="deactivate">{{ __('common.deactivate') }}</option>
                            <option value="delete">{{ __('common.delete') }}</option>
                        </select>
                        <button type="button" class="btn btn-sm btn-primary" id="bulk-action-btn">
                            {{ __('common.apply') }}
                        </button>
                    </div>
                    <!--end::Bulk Actions-->
                    <!--begin::Add file-->
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#kt_modal_file_upload">
                        {!! getIcon('plus', 'fs-2', '', 'i') !!}
                        {{ __('common.upload') }}
                    </button>
                    <!--end::Add file-->
                </div>
                <!--end::Toolbar-->

                <!--begin::Modal-->
                <!-- Modal Livewire component'inin dışına taşındı -->
                <!--end::Modal-->
            </div>
            <!--end::Card toolbar-->
        </div>
        <!--end::Card header-->

        <!--begin::Card body-->
        <div class="card-body py-4">
            <!--begin::Table-->
            <div class="table-responsive">
                {{ $dataTable->table() }}
            </div>
            <!--end::Table-->
        </div>
        <!--end::Card body-->
    </div>

    <!--begin::Modal - Livewire component dışına taşındı-->
    <div wire:ignore>
        <livewire:file.file-upload-modal></livewire:file.file-upload-modal>
    </div>
    <!--end::Modal-->

    @push('scripts')
        {{ $dataTable->scripts() }}
        <script>
            document.getElementById('mySearchInput').addEventListener('keyup', function () {
                window.LaravelDataTables['files-table'].search(this.value).draw();
            });
            
            // Livewire yüklendikten sonra çalışacak fonksiyon
            function initLivewireListeners() {
                if (typeof Livewire === 'undefined') {
                    // Livewire henüz yüklenmemiş, tekrar dene
                    setTimeout(initLivewireListeners, 100);
                    return;
                }
                
                Livewire.on('file-uploaded-success', function () {
                    // Başarılı yükleme sonrası modal'ı kapatmaya izin ver
                    allowModalClose = true;
                    
                    // Modal'ı kapat
                    var modalElement = document.getElementById('kt_modal_file_upload');
                    if (modalElement) {
                        var modal = bootstrap.Modal.getInstance(modalElement);
                        if (modal) {
                            modal.hide();
                        } else {
                            var bsModal = new bootstrap.Modal(modalElement);
                            bsModal.hide();
                        }
                    }
                    
                    // Tabloyu yenile
                    if (window.LaravelDataTables && window.LaravelDataTables['files-table']) {
                        window.LaravelDataTables['files-table'].ajax.reload();
                    }
                    
                    // Başarı mesajını göster
                    Swal.fire({
                        text: window.__('common.file_uploaded'),
                        icon: "success",
                        buttonsStyling: false,
                        confirmButtonText: window.__('common.ok'),
                        customClass: {
                            confirmButton: "btn btn-primary"
                        }
                    });
                });
                
                Livewire.on('file_uploaded', function (fileId) {
                    if (window.LaravelDataTables && window.LaravelDataTables['files-table']) {
                        window.LaravelDataTables['files-table'].ajax.reload();
                    }
                });
            }
            
            // Livewire init event'ini dinle
            document.addEventListener('livewire:init', function () {
                initLivewireListeners();
            });
            
            // Eğer Livewire zaten yüklenmişse
            if (typeof Livewire !== 'undefined') {
                initLivewireListeners();
            }
            
            // Modal'ın kapanmasını kontrol et - dosya seçildiğinde kapanmasını engelle
            var allowModalClose = false;
            var isFileUploading = false;
            var modalKeepOpenInterval = null;
            
            // Modal açıldığında allowModalClose'u false yap
            $(document).on('shown.bs.modal', '#kt_modal_file_upload', function () {
                allowModalClose = false;
                isFileUploading = false;
                console.log('Modal açıldı, allowModalClose: false');
                
                // Dosya adı gösterimini temizle
                $('#selected_file_name_display').hide();
            });
            
            // Modal kapatıldığında Livewire event'i gönder ve allowModalClose'u sıfırla
            $(document).on('hidden.bs.modal', '#kt_modal_file_upload', function () {
                allowModalClose = false;
                isFileUploading = false;
                
                // Interval'ı temizle
                if (modalKeepOpenInterval) {
                    clearInterval(modalKeepOpenInterval);
                    modalKeepOpenInterval = null;
                }
                
                if (typeof Livewire !== 'undefined') {
                    Livewire.dispatch('show_file_upload_modal');
                }
                console.log('Modal kapatıldı');
            });
            
            // Modal kapanma event'ini dinle - EN ÖNEMLİ KISIM
            $(document).on('hide.bs.modal', '#kt_modal_file_upload', function (e) {
                console.log('Modal kapanma denemesi - allowModalClose:', allowModalClose, 'isFileUploading:', isFileUploading);
                
                // Eğer dosya yükleme işlemi devam ediyorsa veya izin verilmemişse engelle
                if (!allowModalClose || isFileUploading) {
                    console.log('Modal kapanması engellendi!');
                    e.preventDefault();
                    e.stopPropagation();
                    e.stopImmediatePropagation();
                    
                    // Modal'ı zorla açık tut
                    setTimeout(function() {
                        var modalElement = document.getElementById('kt_modal_file_upload');
                        if (modalElement) {
                            var modal = bootstrap.Modal.getInstance(modalElement);
                            if (!modal || !modal._isShown) {
                                console.log('Modal zorla açılıyor...');
                                var bsModal = new bootstrap.Modal(modalElement);
                                bsModal.show();
                            }
                        }
                    }, 10);
                    
                    return false;
                }
            });
            
            // İptal butonuna tıklandığında modal'ı kapatmaya izin ver
            $(document).on('click', '#kt_modal_file_upload [data-bs-dismiss="modal"]', function() {
                console.log('İptal butonuna tıklandı');
                allowModalClose = true;
            });
            
            // Dosya seçildiğinde modal'ın kapanmasını engelle
            $(document).on('change', '#file_input_upload', function() {
                var fileName = this.files[0]?.name;
                console.log('Dosya seçildi:', fileName);
                allowModalClose = false;
                isFileUploading = true; // Livewire upload başlayacak
            });
            
            // Livewire dosya yükleme event'lerini dinle
            $(document).on('livewire-upload-start', function(e) {
                console.log('Livewire upload başladı');
                isFileUploading = true;
                allowModalClose = false;
            });
            
            $(document).on('livewire-upload-finish', function(e) {
                console.log('Livewire upload bitti');
                isFileUploading = false;
                allowModalClose = false; // Hala kapatmaya izin verme
                
                // Modal'ın açık olduğundan emin ol - agresif kontrol
                var checkCount = 0;
                var checkInterval = setInterval(function() {
                    checkCount++;
                    var modalElement = document.getElementById('kt_modal_file_upload');
                    if (modalElement) {
                        var modal = bootstrap.Modal.getInstance(modalElement);
                        if (!modal || !modal._isShown) {
                            console.log('Upload bitti, modal kapalı, yeniden açılıyor... (deneme ' + checkCount + ')');
                            var bsModal = new bootstrap.Modal(modalElement);
                            bsModal.show();
                        } else {
                            if (checkCount >= 5) {
                                console.log('Modal açık, kontrol tamamlandı');
                                clearInterval(checkInterval);
                            }
                        }
                    }
                    
                    // 30 denemeden sonra dur
                    if (checkCount >= 30) {
                        clearInterval(checkInterval);
                    }
                }, 50);
            });
            
            $(document).on('livewire-upload-error', function(e) {
                console.log('Livewire upload hatası');
                isFileUploading = false;
                allowModalClose = false;
            });
            
            // MutationObserver ile modal'ın DOM'dan kaldırılmasını izle
            var modalObserver = null;
            
            function startModalObserver() {
                var modalElement = document.getElementById('kt_modal_file_upload');
                if (!modalElement) return;
                
                if (modalObserver) {
                    modalObserver.disconnect();
                }
                
                modalObserver = new MutationObserver(function(mutations) {
                    if (!allowModalClose) {
                        var modalElement = document.getElementById('kt_modal_file_upload');
                        if (modalElement) {
                            var modal = bootstrap.Modal.getInstance(modalElement);
                            if (!modal || !modal._isShown) {
                                console.log('MutationObserver: Modal kapalı tespit edildi, zorla açılıyor...');
                                // Modal'ı zorla aç
                                $(modalElement).addClass('show').css('display', 'block');
                                $(modalElement).attr('aria-hidden', 'false');
                                $('body').addClass('modal-open');
                                $('.modal-backdrop').remove();
                                $('<div class="modal-backdrop fade show"></div>').appendTo('body');
                                
                                var bsModal = new bootstrap.Modal(modalElement, {
                                    backdrop: 'static',
                                    keyboard: false
                                });
                                bsModal.show();
                            }
                        }
                    }
                });
                
                modalObserver.observe(modalElement, {
                    attributes: true,
                    attributeFilter: ['class', 'style', 'aria-hidden'],
                    childList: false,
                    subtree: false
                });
            }
            
            // Modal açıldığında observer'ı başlat
            $(document).on('shown.bs.modal', '#kt_modal_file_upload', function () {
                startModalObserver();
            });
            
            // Livewire component güncellendiğinde modal'ı zorla açık tut
            document.addEventListener('livewire:update', function() {
                if (!allowModalClose) {
                    console.log('Livewire update - modal kontrol ediliyor...');
                    setTimeout(function() {
                        var modalElement = document.getElementById('kt_modal_file_upload');
                        if (modalElement) {
                            var modal = bootstrap.Modal.getInstance(modalElement);
                            if (!modal || !modal._isShown) {
                                console.log('Livewire update sonrası modal kapalı, zorla açılıyor...');
                                $(modalElement).addClass('show').css('display', 'block');
                                $(modalElement).attr('aria-hidden', 'false');
                                $('body').addClass('modal-open');
                                $('.modal-backdrop').remove();
                                $('<div class="modal-backdrop fade show"></div>').appendTo('body');
                                
                                var bsModal = new bootstrap.Modal(modalElement, {
                                    backdrop: 'static',
                                    keyboard: false
                                });
                                bsModal.show();
                                
                                // Observer'ı yeniden başlat
                                startModalObserver();
                            }
                        }
                    }, 10);
                }
            });
            
            // Livewire component render edildiğinde de kontrol et
            document.addEventListener('livewire:load', function() {
                console.log('Livewire load - modal kontrol ediliyor...');
            });
            
            // Sürekli kontrol mekanizması - her 100ms'de bir kontrol et
            var continuousCheckInterval = null;
            $(document).on('shown.bs.modal', '#kt_modal_file_upload', function () {
                if (continuousCheckInterval) {
                    clearInterval(continuousCheckInterval);
                }
                continuousCheckInterval = setInterval(function() {
                    if (!allowModalClose) {
                        var modalElement = document.getElementById('kt_modal_file_upload');
                        if (modalElement) {
                            var modal = bootstrap.Modal.getInstance(modalElement);
                            if (!modal || !modal._isShown) {
                                console.log('Sürekli kontrol: Modal kapalı, zorla açılıyor...');
                                $(modalElement).addClass('show').css('display', 'block');
                                $(modalElement).attr('aria-hidden', 'false');
                                $('body').addClass('modal-open');
                                $('.modal-backdrop').remove();
                                $('<div class="modal-backdrop fade show"></div>').appendTo('body');
                                
                                var bsModal = new bootstrap.Modal(modalElement, {
                                    backdrop: 'static',
                                    keyboard: false
                                });
                                bsModal.show();
                            }
                        }
                    } else {
                        clearInterval(continuousCheckInterval);
                        continuousCheckInterval = null;
                    }
                }, 100);
            });
            
            $(document).on('hidden.bs.modal', '#kt_modal_file_upload', function () {
                if (continuousCheckInterval) {
                    clearInterval(continuousCheckInterval);
                    continuousCheckInterval = null;
                }
                if (modalObserver) {
                    modalObserver.disconnect();
                    modalObserver = null;
                }
            });
            
            // Livewire event'lerini dinle
            function initLivewireModalHandler() {
                if (typeof Livewire === 'undefined') {
                    setTimeout(initLivewireModalHandler, 100);
                    return;
                }
                
                // Modal kapanmasını engelle
                Livewire.on('prevent-modal-close', function() {
                    console.log('prevent-modal-close event alındı');
                    allowModalClose = false;
                    isFileUploading = true;
                });
                
                // Modal kapanmasına izin ver
                Livewire.on('allow-modal-close', function() {
                    console.log('allow-modal-close event alındı');
                    allowModalClose = true;
                    isFileUploading = false;
                });
                
                // Hata durumunda modal'ı açık tut
                Livewire.on('livewire:error', function() {
                    console.log('Livewire hata event alındı');
                    allowModalClose = false;
                    isFileUploading = false;
                    var modalElement = document.getElementById('kt_modal_file_upload');
                    if (modalElement) {
                        var modal = bootstrap.Modal.getInstance(modalElement);
                        if (!modal || !modal._isShown) {
                            var bsModal = new bootstrap.Modal(modalElement);
                            bsModal.show();
                        }
                    }
                });
            }
            
            initLivewireModalHandler();

            // Toplu işlemler
            document.addEventListener('DOMContentLoaded', function() {
                const bulkActionsContainer = document.getElementById('bulk-actions-container');
                const bulkActionSelect = document.getElementById('bulk-action-select');
                const bulkActionBtn = document.getElementById('bulk-action-btn');
                const table = window.LaravelDataTables['files-table'];

                // Checkbox durumunu güncelle
                function updateCheckboxState() {
                    const checkboxes = document.querySelectorAll('input[type="checkbox"][data-file-id]');
                    const selectAll = document.getElementById('select-all');
                    const checkedCount = Array.from(checkboxes).filter(cb => cb.checked).length;
                    const totalCount = checkboxes.length;
                    
                    // Bulk actions container'ı göster/gizle
                    if (checkedCount > 0) {
                        bulkActionsContainer.classList.remove('d-none');
                    } else {
                        bulkActionsContainer.classList.add('d-none');
                    }
                    
                    // Select all checkbox durumunu güncelle
                    if (selectAll && totalCount > 0) {
                        if (checkedCount === 0) {
                            selectAll.checked = false;
                            selectAll.indeterminate = false;
                        } else if (checkedCount === totalCount) {
                            selectAll.checked = true;
                            selectAll.indeterminate = false;
                        } else {
                            selectAll.checked = false;
                            selectAll.indeterminate = true;
                        }
                    }
                }

                // Checkbox değişikliklerini dinle
                table.on('draw', function() {
                    updateCheckboxState();
                    
                    // Individual checkbox'lara event listener ekle
                    const checkboxes = document.querySelectorAll('input[type="checkbox"][data-file-id]');
                    checkboxes.forEach(function(checkbox) {
                        checkbox.addEventListener('change', updateCheckboxState);
                    });
                });
                
                // İlk yüklemede de çalıştır
                updateCheckboxState();

                // Toplu işlem butonu
                bulkActionBtn.addEventListener('click', function() {
                    const action = bulkActionSelect.value;
                    if (!action) {
                        Swal.fire({
                            icon: 'warning',
                            title: '{{ __('common.select_action') }}',
                            text: '{{ __('common.please_select_action') }}'
                        });
                        return;
                    }

                    const checkedBoxes = document.querySelectorAll('input[type="checkbox"][data-file-id]:checked');
                    const ids = Array.from(checkedBoxes).map(cb => cb.getAttribute('data-file-id'));

                    if (ids.length === 0) {
                        Swal.fire({
                            icon: 'warning',
                            title: '{{ __('common.no_selection') }}',
                            text: '{{ __('common.please_select_items') }}'
                        });
                        return;
                    }

                    Swal.fire({
                        title: '{{ __('common.are_you_sure') }}',
                        text: '{{ __('common.bulk_action_confirm') }}',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonText: '{{ __('common.apply') }}',
                        cancelButtonText: '{{ __('common.cancel') }}'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            fetch('{{ route('file-management.bulk-action') }}', {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                },
                                body: JSON.stringify({
                                    action: action,
                                    ids: ids
                                })
                            })
                            .then(response => response.json())
                            .then(data => {
                                if (data.success) {
                                    Swal.fire({
                                        icon: 'success',
                                        title: '{{ __('common.success') }}',
                                        text: data.message || '{{ __('common.bulk_action_success') }}',
                                        timer: 2000
                                    });
                                    table.ajax.reload();
                                } else {
                                    Swal.fire({
                                        icon: 'error',
                                        title: '{{ __('common.error') }}',
                                        text: data.message || '{{ __('common.bulk_action_error') }}'
                                    });
                                }
                            })
                            .catch(error => {
                                Swal.fire({
                                    icon: 'error',
                                    title: '{{ __('common.error') }}',
                                    text: '{{ __('common.bulk_action_error') }}'
                                });
                            });
                        }
                    });
                });
            });
        </script>
    @endpush

</x-default-layout>


