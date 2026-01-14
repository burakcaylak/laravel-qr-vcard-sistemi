<x-default-layout>

    @section('title')
        {{ __('common.qr_code_management') }}
    @endsection

    @section('breadcrumbs')
        {{ Breadcrumbs::render('qr-code.index') }}
    @endsection

    <div class="card">
        <!--begin::Card header-->
        <div class="card-header border-0 pt-6">
            <!--begin::Card title-->
            <div class="card-title">
                <!--begin::Search-->
                <div class="d-flex align-items-center position-relative my-1">
                    {!! getIcon('magnifier', 'fs-3 position-absolute ms-5') !!}
                    <input type="text" data-kt-qr-table-filter="search" class="form-control form-control-solid w-250px ps-13" placeholder="{{ __('common.search') }}..." id="mySearchInput"/>
                </div>
                <!--end::Search-->
            </div>
            <!--begin::Card title-->

            <!--begin::Card toolbar-->
            <div class="card-toolbar">
                <!--begin::Toolbar-->
                <div class="d-flex justify-content-end" data-kt-qr-table-toolbar="base">
                    <!--begin::Add QR-->
                    <a href="{{ route('qr-code.create') }}" class="btn btn-primary">
                        <i class="ki-solid ki-scan-barcode fs-2"></i>
                        {{ __('common.create_qr_code') }}
                    </a>
                    <!--end::Add QR-->
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
            document.getElementById('mySearchInput').addEventListener('keyup', function () {
                window.LaravelDataTables['qr-codes-table'].search(this.value).draw();
            });
            document.addEventListener('livewire:init', function () {
                Livewire.on('success', function () {
                    window.LaravelDataTables['qr-codes-table'].ajax.reload();
                });
                Livewire.on('qr_code_created', function (qrCodeId) {
                    window.LaravelDataTables['qr-codes-table'].ajax.reload();
                });
            });
        </script>
    @endpush

</x-default-layout>

