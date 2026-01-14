<x-default-layout>

    @section('title')
        {{ __('common.v_card_management') }}
    @endsection

    @section('breadcrumbs')
        {{ Breadcrumbs::render('v-card.index') }}
    @endsection

    <div class="card">
        <!--begin::Card header-->
        <div class="card-header border-0 pt-6">
            <!--begin::Card title-->
            <div class="card-title">
                <!--begin::Search-->
                <div class="d-flex align-items-center position-relative my-1">
                    {!! getIcon('magnifier', 'fs-3 position-absolute ms-5') !!}
                    <input type="text" id="vcard_search" class="form-control form-control-solid w-250px ps-13" placeholder="{{ __('common.search') }}..."/>
                </div>
                <!--end::Search-->
            </div>
            <!--end::Card title-->

            <!--begin::Card toolbar-->
            <div class="card-toolbar">
                <!--begin::Toolbar-->
                <div class="d-flex justify-content-end">
                    <!--begin::Add vCard-->
                    <a href="{{ route('v-card.create') }}" class="btn btn-primary">
                        <i class="ki-solid ki-profile-user fs-2"></i>
                        {{ __('common.create_v_card') }}
                    </a>
                    <!--end::Add vCard-->
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
                            <th class="min-w-150px">{{ __('common.company') }}</th>
                            <th class="min-w-100px">{{ __('common.email') }}</th>
                            <th class="min-w-100px">{{ __('common.phone') }}</th>
                            <th class="min-w-100px">{{ __('common.status') }}</th>
                            <th class="min-w-100px">{{ __('common.scan_count') }}</th>
                            <th class="min-w-100px text-end">{{ __('common.actions') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($vCards as $vCard)
                            <tr>
                                <td>
                                    @if($vCard->file_path && \Illuminate\Support\Facades\Storage::disk('public')->exists($vCard->file_path))
                                        <a href="{{ route('v-card.download', $vCard) }}" class="d-inline-block" title="{{ __('common.download_qr_code') }}" download>
                                            <img src="{{ asset('storage/' . $vCard->file_path) }}" alt="QR Code" class="w-50px h-50px" style="cursor: pointer; object-fit: contain;">
                                        </a>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td>
                                    <span class="text-dark fw-bold text-hover-primary d-block fs-6">
                                        {{ $vCard->getLocalizedField('name') ?? '-' }}
                                    </span>
                                </td>
                                <td>
                                    <span class="text-muted fw-semibold text-muted d-block fs-7">
                                        {{ $vCard->getLocalizedField('company') ?? '-' }}
                                    </span>
                                </td>
                                <td>
                                    <span class="text-muted fw-semibold text-muted d-block fs-7">
                                        {{ $vCard->email ?? $vCard->getLocalizedField('email') ?? '-' }}
                                    </span>
                                </td>
                                <td>
                                    <span class="text-muted fw-semibold text-muted d-block fs-7">
                                        {{ $vCard->phone ?? $vCard->getLocalizedField('phone') ?? '-' }}
                                    </span>
                                </td>
                                <td>
                                    @if($vCard->is_expired)
                                        <span class="badge badge-light-danger">{{ __('common.expired') }}</span>
                                    @elseif($vCard->is_active)
                                        <span class="badge badge-light-success">{{ __('common.active') }}</span>
                                    @else
                                        <span class="badge badge-light-secondary">{{ __('common.inactive') }}</span>
                                    @endif
                                </td>
                                <td>
                                    <span class="text-muted fw-semibold text-muted d-block fs-7">
                                        {{ $vCard->scan_count }}
                                    </span>
                                </td>
                                <td class="text-end">
                                    <a href="{{ route('v-card.show', $vCard) }}" class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm me-1">
                                        {!! getIcon('eye', 'fs-2') !!}
                                    </a>
                                    <a href="{{ route('v-card.edit', $vCard) }}" class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm me-1">
                                        {!! getIcon('pencil', 'fs-2') !!}
                                    </a>
                                    <form action="{{ route('v-card.destroy', $vCard) }}" method="POST" class="d-inline" onsubmit="return confirm('{{ __('common.delete_v_card_confirm') }}');">
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
                                <td colspan="8" class="text-center py-10">
                                    <span class="text-muted">{{ __('common.no_data') }}</span>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <!--end::Table-->
            
            @if($vCards->hasPages())
                <div class="d-flex justify-content-between align-items-center mt-5">
                    <div class="text-muted">
                        {{ __('common.showing') }} {{ $vCards->firstItem() }} {{ __('common.to') }} {{ $vCards->lastItem() }} {{ __('common.of') }} {{ $vCards->total() }} {{ __('common.results') }}
                    </div>
                    <div>
                        {{ $vCards->links() }}
                    </div>
                </div>
            @endif
        </div>
        <!--end::Card body-->
    </div>

    @push('scripts')
    <script>
        document.getElementById('vcard_search').addEventListener('keyup', function () {
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
