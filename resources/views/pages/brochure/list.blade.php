<x-default-layout>

    @section('title')
        {{ __('common.brochure_management') }}
    @endsection

    @section('breadcrumbs')
        {{ Breadcrumbs::render('brochure.index') }}
    @endsection

    <div class="card">
        <!--begin::Card header-->
        <div class="card-header border-0 pt-6">
            <!--begin::Card title-->
            <div class="card-title">
                <!--begin::Search-->
                <div class="d-flex align-items-center position-relative my-1">
                    {!! getIcon('magnifier', 'fs-3 position-absolute ms-5') !!}
                    <input type="text" id="brochure_search" class="form-control form-control-solid w-250px ps-13" placeholder="{{ __('common.search') }}..."/>
                </div>
                <!--end::Search-->
            </div>
            <!--end::Card title-->

            <!--begin::Card toolbar-->
            <div class="card-toolbar">
                <!--begin::Toolbar-->
                <div class="d-flex justify-content-end">
                    <!--begin::Add Brochure-->
                    <a href="{{ route('brochure.create') }}" class="btn btn-primary">
                        <i class="ki-solid ki-document fs-2"></i>
                        {{ __('common.create_brochure') }}
                    </a>
                    <!--end::Add Brochure-->
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
                <table class="table table-row-dashed table-row-gray-300 align-middle gs-0 gy-4">
                    <thead>
                        <tr class="fw-bold text-muted">
                            <th class="min-w-80px">{{ __('common.qr_code') }}</th>
                            <th class="min-w-150px">{{ __('common.name') }}</th>
                            <th class="min-w-150px">{{ __('common.category') }}</th>
                            <th class="min-w-100px">{{ __('common.background') }}</th>
                            <th class="min-w-100px">{{ __('common.status') }}</th>
                            <th class="min-w-100px">{{ __('common.view_count') }}</th>
                            <th class="min-w-100px text-end">{{ __('common.actions') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($brochures as $brochure)
                            <tr>
                                <td>
                                    @if($brochure->qr_code_path && \Illuminate\Support\Facades\Storage::disk('public')->exists($brochure->qr_code_path))
                                        <a href="{{ route('brochure.download', $brochure) }}" class="d-inline-block" title="{{ __('common.download_qr_code') }}" download>
                                            <img src="{{ asset('storage/' . $brochure->qr_code_path) }}" alt="QR Code" class="w-50px h-50px" style="cursor: pointer; object-fit: contain;">
                                        </a>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td>
                                    <span class="text-dark fw-bold text-hover-primary d-block fs-6">
                                        {{ $brochure->name }}
                                    </span>
                                    @if($brochure->description)
                                        <span class="text-muted fw-semibold text-muted d-block fs-7 mt-1">
                                            {{ Str::limit($brochure->description, 50) }}
                                        </span>
                                    @endif
                                </td>
                                <td>
                                    <span class="text-muted fw-semibold text-muted d-block fs-7">
                                        {{ $brochure->category->name ?? '-' }}
                                    </span>
                                </td>
                                <td>
                                    @if($brochure->background_type === 'image' && $brochure->background_image_path)
                                        <span class="badge badge-light-info">{{ __('common.image') }}</span>
                                    @else
                                        <div class="d-flex align-items-center">
                                            <span class="badge badge-light-primary me-2">{{ __('common.color') }}</span>
                                            <div style="width: 20px; height: 20px; background-color: {{ $brochure->background_color ?? '#ffffff' }}; border: 1px solid #ddd; border-radius: 3px;"></div>
                                        </div>
                                    @endif
                                </td>
                                <td>
                                    @if($brochure->is_expired)
                                        <span class="badge badge-light-danger">{{ __('common.expired') }}</span>
                                    @elseif($brochure->is_active)
                                        <span class="badge badge-light-success">{{ __('common.active') }}</span>
                                    @else
                                        <span class="badge badge-light-secondary">{{ __('common.inactive') }}</span>
                                    @endif
                                </td>
                                <td>
                                    <span class="text-muted fw-semibold text-muted d-block fs-7">
                                        {{ $brochure->view_count }}
                                    </span>
                                </td>
                                <td class="text-end">
                                    <a href="{{ route('brochure.show', $brochure) }}" class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm me-1">
                                        {!! getIcon('eye', 'fs-2') !!}
                                    </a>
                                    <a href="{{ route('brochure.edit', $brochure) }}" class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm me-1">
                                        {!! getIcon('pencil', 'fs-2') !!}
                                    </a>
                                    <form action="{{ route('brochure.destroy', $brochure) }}" method="POST" class="d-inline" onsubmit="return confirm('{{ __('common.delete_brochure_confirm') }}');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-icon btn-bg-light btn-active-color-danger btn-sm">
                                            {!! getIcon('trash', 'fs-2') !!}
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-10">
                                    <span class="text-muted">{{ __('common.no_data') }}</span>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <!--end::Table-->
            
            @if($brochures->hasPages())
                <div class="d-flex justify-content-between align-items-center mt-5">
                    <div class="text-muted">
                        {{ __('common.showing') }} {{ $brochures->firstItem() }} {{ __('common.to') }} {{ $brochures->lastItem() }} {{ __('common.of') }} {{ $brochures->total() }} {{ __('common.results') }}
                    </div>
                    <div>
                        {{ $brochures->links() }}
                    </div>
                </div>
            @endif
        </div>
        <!--end::Card body-->
    </div>

    @push('scripts')
    <script>
        document.getElementById('brochure_search').addEventListener('keyup', function () {
            const searchTerm = this.value.toLowerCase();
            const rows = document.querySelectorAll('tbody tr');
            
            rows.forEach(row => {
                const text = row.textContent.toLowerCase();
                row.style.display = text.includes(searchTerm) ? '' : 'none';
            });
        });
    </script>
    @endpush

</x-default-layout>
