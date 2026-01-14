<x-default-layout>

    @section('title')
        {{ __('common.users') }}
    @endsection

    @section('breadcrumbs')
        {{ Breadcrumbs::render('user-management.users.index') }}
    @endsection

    <div class="card">
        <!--begin::Card header-->
        <div class="card-header border-0 pt-6">
            <!--begin::Card title-->
            <div class="card-title">
                <!--begin::Search-->
                <div class="d-flex align-items-center position-relative my-1">
                    {!! getIcon('magnifier', 'fs-3 position-absolute ms-5') !!}
                    <input type="text" data-kt-user-table-filter="search" class="form-control form-control-solid w-250px ps-13" placeholder="{{ __('common.search') }} {{ __('common.users') }}..." id="mySearchInput"/>
                </div>
                <!--end::Search-->
            </div>
            <!--begin::Card title-->

            <!--begin::Card toolbar-->
            <div class="card-toolbar">
                <!--begin::Toolbar-->
                <div class="d-flex justify-content-end" data-kt-user-table-toolbar="base">
                    <!--begin::Add user-->
                    @if(!auth()->user()->hasRole('editor'))
                        <a href="{{ route('user-management.users.create') }}" class="btn btn-primary">
                            {!! getIcon('plus', 'fs-2', '', 'i') !!}
                            {{ __('common.create_user') }}
                        </a>
                    @endif
                    <!--end::Add user-->
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
                window.LaravelDataTables['users-table'].search(this.value).draw();
            });
            
            // Show success message if exists
            @if(session('success'))
                Swal.fire({
                    text: '{{ session('success') }}',
                    icon: 'success',
                    buttonsStyling: false,
                    confirmButtonText: window.__('common.ok'),
                    customClass: {
                        confirmButton: 'btn btn-primary'
                    }
                });
            @endif
            
            @if(session('error'))
                Swal.fire({
                    text: '{{ session('error') }}',
                    icon: 'error',
                    buttonsStyling: false,
                    confirmButtonText: window.__('common.ok'),
                    customClass: {
                        confirmButton: 'btn btn-danger'
                    }
                });
            @endif
        </script>
    @endpush

</x-default-layout>
