<x-default-layout>

    @section('title')
        {{ __('common.detailed_analytics') }} - {{ $vCard->getLocalizedField('name') ?? __('common.v_card') }}
    @endsection

    @section('breadcrumbs')
        {{ Breadcrumbs::render('analytics.index') }}
        {{ Breadcrumbs::render('analytics.v-card', $vCard) }}
    @endsection

    <!--begin::Card - VCard Bilgileri-->
    <div class="card mb-5">
        <div class="card-header border-0 pt-6">
            <div class="card-title">
                <h3 class="fw-bold m-0">{{ $vCard->getLocalizedField('name') ?? __('common.v_card') }}</h3>
            </div>
            <div class="card-toolbar">
                <a href="{{ route('v-card.show', $vCard) }}" class="btn btn-sm btn-light">
                    {!! getIcon('arrow-left', 'fs-2') !!}
                    {{ __('common.back') }}
                </a>
            </div>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <p><strong>{{ __('common.created_at') }}:</strong> {{ $vCard->created_at->translatedFormat('d F Y, H:i') }}</p>
                    <p><strong>{{ __('common.status') }}:</strong> 
                        @if($vCard->is_active)
                            <span class="badge badge-light-success">{{ __('common.active') }}</span>
                        @else
                            <span class="badge badge-light-secondary">{{ __('common.inactive') }}</span>
                        @endif
                    </p>
                </div>
                <div class="col-md-6">
                    <p><strong>{{ __('common.total_scans') }}:</strong> {{ number_format($stats['total_scans']) }}</p>
                    @if($vCard->expires_at)
                        <p><strong>{{ __('common.expires_at') }}:</strong> {{ $vCard->expires_at->translatedFormat('d F Y, H:i') }}</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
    <!--end::Card - VCard Bilgileri-->

    <!--begin::Card - Filtreler-->
    <div class="card mb-5">
        <div class="card-body">
            <form method="GET" action="{{ route('analytics.v-card', $vCard->id) }}" id="analytics-filter-form">
                <div class="row g-3">
                    <div class="col-md-3">
                        <label class="form-label">{{ __('common.from_date') }}</label>
                        <input type="date" name="date_from" class="form-control" value="{{ $dateFrom }}">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">{{ __('common.to_date') }}</label>
                        <input type="date" name="date_to" class="form-control" value="{{ $dateTo }}">
                    </div>
                    <div class="col-md-3 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary me-2">
                            {{ __('common.apply_filter') }}
                        </button>
                        <a href="{{ route('analytics.v-card', $vCard->id) }}" class="btn btn-light">
                            {{ __('common.clear') }}
                        </a>
                    </div>
                    <div class="col-md-3 d-flex align-items-end justify-content-end">
                        <a href="{{ route('analytics.export', ['type' => 'v-card', 'id' => $vCard->id, 'date_from' => $dateFrom, 'date_to' => $dateTo]) }}" class="btn btn-success">
                            {!! getIcon('file-down', 'fs-2') !!}
                            {{ __('common.export_csv') }}
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <!--end::Card - Filtreler-->

    <!--begin::Row - İstatistikler-->
    <div class="row g-5 g-xl-8 mb-5 mb-xl-10">
        <div class="col-md-6">
            <div class="card card-flush h-xl-100">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-2">
                        <span class="svg-icon svg-icon-success svg-icon-2hx">
                            {!! getIcon('scan-barcode', 'fs-1') !!}
                        </span>
                        <div class="ms-3">
                            <div class="fs-4 fw-bold text-gray-800">{{ number_format($stats['total_scans']) }}</div>
                            <div class="fs-6 fw-semibold text-gray-500">{{ __('common.total_scans') }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card card-flush h-xl-100">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-2">
                        <span class="svg-icon svg-icon-primary svg-icon-2hx">
                            {!! getIcon('calendar', 'fs-1') !!}
                        </span>
                        <div class="ms-3">
                            <div class="fs-4 fw-bold text-gray-800">{{ $stats['created_at']->translatedFormat('d M Y') }}</div>
                            <div class="fs-6 fw-semibold text-gray-500">{{ __('common.created_at') }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--end::Row - İstatistikler-->

    <!--begin::Card - Bilgi Notu-->
    <div class="card">
        <div class="card-body">
            <div class="alert alert-info d-flex align-items-center p-5">
                <span class="svg-icon svg-icon-2hx svg-icon-info me-4">
                    {!! getIcon('information', 'fs-1') !!}
                </span>
                <div class="d-flex flex-column">
                    <h4 class="mb-1 text-dark">{{ __('common.information') }}</h4>
                    <span>{{ __('common.vcard_analytics_note') }}</span>
                </div>
            </div>
        </div>
    </div>
    <!--end::Card - Bilgi Notu-->

</x-default-layout>
