<x-default-layout>

    @section('title')
        {{ __('common.short_link_list') }}
    @endsection

    @section('breadcrumbs')
        {{ Breadcrumbs::render('short-link.index') }}
    @endsection

    <div class="card">
        <!--begin::Card header-->
        <div class="card-header border-0 pt-6">
            <!--begin::Card title-->
            <div class="card-title">
                <!--begin::Search-->
                <div class="d-flex align-items-center position-relative my-1">
                    {!! getIcon('magnifier', 'fs-3 position-absolute ms-5') !!}
                    <input type="text" data-kt-short-link-table-filter="search" class="form-control form-control-solid w-250px ps-13" placeholder="{{ __('common.search') }}..." id="mySearchInput"/>
                </div>
                <!--end::Search-->
                <!--begin::Filters-->
                <div class="d-flex align-items-center gap-2 ms-5">
                    <select class="form-select form-select-sm w-150px" id="filter-status">
                        <option value="">{{ __('common.all_status') }}</option>
                        <option value="active">{{ __('common.active') }}</option>
                        <option value="inactive">{{ __('common.inactive') }}</option>
                        <option value="expired">{{ __('common.expired') }}</option>
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
                <div class="d-flex justify-content-end gap-2" data-kt-short-link-table-toolbar="base">
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
                    <!--begin::Export-->
                    <a href="{{ route('short-link.export') }}" class="btn btn-light">
                        {!! getIcon('file-down', 'fs-2') !!}
                        {{ __('common.export') }}
                    </a>
                    <!--end::Export-->
                    <!--begin::Add Short Link-->
                    <a href="{{ route('short-link.create') }}" class="btn btn-primary">
                        {!! getIcon('disconnect', 'fs-2') !!}
                        {{ __('common.create_short_link') }}
                    </a>
                    <!--end::Add Short Link-->
                </div>
                <!--end::Toolbar-->
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

    @push('scripts')
        {{ $dataTable->scripts() }}
        <script>
            const table = window.LaravelDataTables['short-links-table'];
            
            document.getElementById('mySearchInput').addEventListener('keyup', function () {
                table.search(this.value).draw();
            });

            // Filtreleme
            document.getElementById('filter-apply').addEventListener('click', function() {
                const status = document.getElementById('filter-status').value;
                const category = document.getElementById('filter-category').value;
                const dateFrom = document.getElementById('filter-date-from').value;
                const dateTo = document.getElementById('filter-date-to').value;
                
                table.ajax.url('{{ route('short-link.index') }}?filter_status=' + status + '&filter_category=' + category + '&filter_date_from=' + dateFrom + '&filter_date_to=' + dateTo).load();
            });

            document.getElementById('filter-clear').addEventListener('click', function() {
                document.getElementById('filter-status').value = '';
                document.getElementById('filter-category').value = '';
                document.getElementById('filter-date-from').value = '';
                document.getElementById('filter-date-to').value = '';
                table.ajax.url('{{ route('short-link.index') }}').load();
            });
            
            // İlk yüklemede menüyü başlat
            document.addEventListener('DOMContentLoaded', function() {
                if (typeof KTMenu !== 'undefined') {
                    KTMenu.createInstances();
                }

                // Toplu işlemler
                const bulkActionsContainer = document.getElementById('bulk-actions-container');
                const bulkActionSelect = document.getElementById('bulk-action-select');
                const bulkActionBtn = document.getElementById('bulk-action-btn');
                const table = window.LaravelDataTables['short-links-table'];

                // Checkbox durumunu güncelle
                function updateCheckboxState() {
                    const checkboxes = document.querySelectorAll('input[type="checkbox"][data-short-link-id]');
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
                    const checkboxes = document.querySelectorAll('input[type="checkbox"][data-short-link-id]');
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

                    const checkedBoxes = document.querySelectorAll('input[type="checkbox"][data-short-link-id]:checked');
                    const ids = Array.from(checkedBoxes).map(cb => cb.getAttribute('data-short-link-id'));

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
                            fetch('{{ route('short-link.bulk-action') }}', {
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
