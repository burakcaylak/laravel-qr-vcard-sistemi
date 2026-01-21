<x-default-layout>

    @section('title')
        {{ __('common.dashboard') }}
    @endsection

    @section('breadcrumbs')
        {{ Breadcrumbs::render('dashboard') }}
    @endsection

    <!--begin::Row - İstatistikler-->
    <div class="row g-5 g-xl-8 mb-5 mb-xl-10">
        <!--begin::Col-->
        <div class="col-md-6 col-lg-3">
            <div class="card card-flush h-xl-100">
                <div class="card-body d-flex flex-column justify-content-between">
                    <div class="d-flex flex-column">
                        <div class="d-flex align-items-center mb-2">
                            <span class="svg-icon svg-icon-primary svg-icon-2hx">
                                {!! getIcon('folder', 'fs-1') !!}
                            </span>
                            <div class="ms-3">
                                <div class="fs-4 fw-bold text-gray-800">{{ $stats['total_files'] }}</div>
                                <div class="fs-6 fw-semibold text-gray-500">{{ __('common.total_files') }}</div>
                            </div>
                        </div>
                    </div>
                    <a href="{{ route('file-management.index') }}" class="btn btn-sm btn-light-primary">{{ __('common.view') }}</a>
                </div>
            </div>
        </div>
        <!--end::Col-->
        <!--begin::Col-->
        <div class="col-md-6 col-lg-3">
            <div class="card card-flush h-xl-100">
                <div class="card-body d-flex flex-column justify-content-between">
                    <div class="d-flex flex-column">
                        <div class="d-flex align-items-center mb-2">
                            <span class="svg-icon svg-icon-info svg-icon-2hx">
                                {!! getIcon('profile-user', 'fs-1') !!}
                            </span>
                            <div class="ms-3">
                                <div class="fs-4 fw-bold text-gray-800">{{ $stats['total_users'] }}</div>
                                <div class="fs-6 fw-semibold text-gray-500">{{ __('common.total_users') }}</div>
                            </div>
                        </div>
                    </div>
                    <a href="{{ route('user-management.users.index') }}" class="btn btn-sm btn-light-info">{{ __('common.view') }}</a>
                </div>
            </div>
        </div>
        <!--end::Col-->
        <!--begin::Col-->
        <div class="col-md-6 col-lg-3">
            <div class="card card-flush h-xl-100">
                <div class="card-body d-flex flex-column justify-content-between">
                    <div class="d-flex flex-column">
                        <div class="d-flex align-items-center mb-2">
                            <span class="svg-icon svg-icon-success svg-icon-2hx">
                                <i class="ki-solid ki-scan-barcode fs-1"></i>
                            </span>
                            <div class="ms-3">
                                <div class="fs-4 fw-bold text-gray-800">{{ $stats['total_qr_code_scans'] }}</div>
                                <div class="fs-6 fw-semibold text-gray-500">{{ __('common.total_qr_code_scans') }}</div>
                            </div>
                        </div>
                    </div>
                    <a href="{{ route('qr-code.index') }}" class="btn btn-sm btn-light-success">{{ __('common.view') }}</a>
                </div>
            </div>
        </div>
        <!--end::Col-->
        <!--begin::Col-->
        <div class="col-md-6 col-lg-3">
            <div class="card card-flush h-xl-100">
                <div class="card-body d-flex flex-column justify-content-between">
                    <div class="d-flex flex-column">
                        <div class="d-flex align-items-center mb-2">
                            <span class="svg-icon svg-icon-primary svg-icon-2hx">
                                <i class="ki-solid ki-address-book fs-1"></i>
                            </span>
                            <div class="ms-3">
                                <div class="fs-4 fw-bold text-gray-800">{{ $stats['total_vcard_scans'] }}</div>
                                <div class="fs-6 fw-semibold text-gray-500">{{ __('common.total_vcard_scans') }}</div>
                            </div>
                        </div>
                    </div>
                    <a href="{{ route('v-card.index') }}" class="btn btn-sm btn-light-primary">{{ __('common.view') }}</a>
                </div>
            </div>
        </div>
        <!--end::Col-->
    </div>
    <!--end::Row - İstatistikler-->

    <!--begin::Row - Ek İstatistikler-->
    <div class="row g-5 g-xl-8 mb-5 mb-xl-10">
        <!--begin::Col-->
        <div class="col-md-6 col-lg-3">
            <div class="card card-flush h-xl-100">
                <div class="card-body d-flex flex-column justify-content-between">
                    <div class="d-flex flex-column">
                        <div class="d-flex align-items-center mb-2">
                            <span class="svg-icon svg-icon-danger svg-icon-2hx">
                                {!! getIcon('disconnect', 'fs-1') !!}
                            </span>
                            <div class="ms-3">
                                <div class="fs-4 fw-bold text-gray-800">{{ $stats['total_short_links'] }}</div>
                                <div class="fs-6 fw-semibold text-gray-500">{{ __('common.total_short_links') }}</div>
                            </div>
                        </div>
                    </div>
                    <a href="{{ route('short-link.index') }}" class="btn btn-sm btn-light-danger">{{ __('common.view') }}</a>
                </div>
            </div>
        </div>
        <!--end::Col-->
        <!--begin::Col-->
        <div class="col-md-6 col-lg-3">
            <div class="card card-flush h-xl-100">
                <div class="card-body d-flex flex-column justify-content-between">
                    <div class="d-flex flex-column">
                        <div class="d-flex align-items-center mb-2">
                            <span class="svg-icon svg-icon-warning svg-icon-2hx">
                                {!! getIcon('mouse', 'fs-1') !!}
                            </span>
                            <div class="ms-3">
                                <div class="fs-4 fw-bold text-gray-800">{{ $stats['total_short_link_clicks'] }}</div>
                                <div class="fs-6 fw-semibold text-gray-500">{{ __('common.total_clicks') }}</div>
                            </div>
                        </div>
                    </div>
                    <a href="{{ route('short-link.index') }}" class="btn btn-sm btn-light-warning">{{ __('common.view') }}</a>
                </div>
            </div>
        </div>
        <!--end::Col-->
        <!--begin::Col-->
        <div class="col-md-6 col-lg-3">
            <div class="card card-flush h-xl-100">
                <div class="card-body d-flex flex-column justify-content-between">
                    <div class="d-flex flex-column">
                        <div class="d-flex align-items-center mb-2">
                            <span class="svg-icon svg-icon-info svg-icon-2hx">
                                {!! getIcon('document', 'fs-1') !!}
                            </span>
                            <div class="ms-3">
                                <div class="fs-4 fw-bold text-gray-800">{{ $stats['total_brochures'] }}</div>
                                <div class="fs-6 fw-semibold text-gray-500">{{ __('common.total_brochures') }}</div>
                            </div>
                        </div>
                    </div>
                    <a href="{{ route('brochure.index') }}" class="btn btn-sm btn-light-info">{{ __('common.view') }}</a>
                </div>
            </div>
        </div>
        <!--end::Col-->
        <!--begin::Col-->
        <div class="col-md-6 col-lg-3">
            <div class="card card-flush h-xl-100">
                <div class="card-body d-flex flex-column justify-content-between">
                    <div class="d-flex flex-column">
                        <div class="d-flex align-items-center mb-2">
                            <span class="svg-icon svg-icon-success svg-icon-2hx">
                                {!! getIcon('eye', 'fs-1') !!}
                            </span>
                            <div class="ms-3">
                                <div class="fs-4 fw-bold text-gray-800">{{ $stats['total_brochure_views'] }}</div>
                                <div class="fs-6 fw-semibold text-gray-500">{{ __('common.total_brochure_views') }}</div>
                            </div>
                        </div>
                    </div>
                    <a href="{{ route('brochure.index') }}" class="btn btn-sm btn-light-success">{{ __('common.view') }}</a>
                </div>
            </div>
        </div>
        <!--end::Col-->
    </div>
    <!--end::Row - Ek İstatistikler-->

    <!--begin::Row - Grafikler-->
    <div class="row g-5 g-xl-8 mb-5 mb-xl-10">
        <!--begin::Col - Son 7 Günlük Aktivite-->
        <div class="col-xl-12">
            <div class="card">
                <div class="card-header border-0 pt-6">
                    <div class="card-title">
                        <h3 class="fw-bold m-0">{{ __('common.last_7_days_activity') }}</h3>
                    </div>
                </div>
                <div class="card-body pt-0">
                    <div id="kt_charts_widget_activity" style="height: 350px;"></div>
                </div>
            </div>
        </div>
        <!--end::Col-->
    </div>
    <!--end::Row - Grafikler-->

    <!--begin::Row - En Çok Tıklananlar-->
    <div class="row g-5 g-xl-8 mb-5 mb-xl-10">
        <!--begin::Col - En Çok Tıklanan Linkler-->
        <div class="col-xl-6">
            <div class="card">
                <div class="card-header border-0 pt-6">
                    <div class="card-title">
                        <h3 class="fw-bold m-0">{{ __('common.top_clicked_links') }}</h3>
                    </div>
                    <div class="card-toolbar">
                        <a href="{{ route('short-link.index') }}" class="btn btn-sm btn-light">{{ __('common.view_all') }}</a>
                    </div>
                </div>
                <div class="card-body pt-0">
                    @if($topLinks->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-row-dashed table-row-gray-300 align-middle gs-0 gy-4">
                                <thead>
                                    <tr class="fw-bold text-muted">
                                        <th>{{ __('common.short_code') }}</th>
                                        <th>{{ __('common.title') }}</th>
                                        <th>{{ __('common.click_count') }}</th>
                                        <th class="text-end">{{ __('common.operation') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($topLinks as $link)
                                        <tr>
                                            <td>
                                                <a href="{{ $link->short_url }}" target="_blank" class="text-primary fw-bold">{{ $link->short_code }}</a>
                                            </td>
                                            <td>{{ Str::limit($link->title ?? '-', 30) }}</td>
                                            <td><span class="badge badge-light-info">{{ $link->click_count }}</span></td>
                                            <td class="text-end">
                                                <a href="{{ route('short-link.show', $link) }}" class="btn btn-sm btn-light-primary">{{ __('common.view') }}</a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-10">
                            <p class="text-gray-500">{{ __('common.no_data') }}</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
        <!--end::Col-->
        <!--begin::Col - En Çok Taranan QR Kodlar-->
        <div class="col-xl-6">
            <div class="card">
                <div class="card-header border-0 pt-6">
                    <div class="card-title">
                        <h3 class="fw-bold m-0">{{ __('common.top_scanned_qr_codes') }}</h3>
                    </div>
                    <div class="card-toolbar">
                        <a href="{{ route('qr-code.index') }}" class="btn btn-sm btn-light">{{ __('common.view_all') }}</a>
                    </div>
                </div>
                <div class="card-body pt-0">
                    @if($topQrCodes->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-row-dashed table-row-gray-300 align-middle gs-0 gy-4">
                                <thead>
                                    <tr class="fw-bold text-muted">
                                        <th>{{ __('common.qr_code_name') }}</th>
                                        <th>{{ __('common.type') }}</th>
                                        <th>{{ __('common.scan_count') }}</th>
                                        <th class="text-end">{{ __('common.operation') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($topQrCodes as $qrCode)
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <i class="ki-solid ki-scan-barcode fs-3 text-success me-2"></i>
                                                    <span class="text-gray-800 fw-bold">{{ Str::limit($qrCode->name, 30) }}</span>
                                                </div>
                                            </td>
                                            <td>
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
                                                @endphp
                                                <span class="badge badge-light-primary">{{ $types[$qrCode->qr_type] ?? $qrCode->qr_type }}</span>
                                            </td>
                                            <td><span class="badge badge-light-success">{{ $qrCode->scan_count }}</span></td>
                                            <td class="text-end">
                                                <a href="{{ route('qr-code.show', $qrCode) }}" class="btn btn-sm btn-light-success">{{ __('common.view') }}</a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-10">
                            <p class="text-gray-500">{{ __('common.no_data') }}</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
        <!--end::Col-->
    </div>
    <!--end::Row - En Çok Tıklananlar-->

    <!--begin::Row - Kısayollar-->
    <div class="row g-5 g-xl-8 mb-5 mb-xl-10">
        <div class="col-12">
            <div class="card">
                <div class="card-header border-0 pt-6">
                    <div class="card-title">
                        <h3 class="fw-bold m-0">{{ __('common.shortcuts') }}</h3>
                    </div>
                </div>
                <div class="card-body pt-0">
                    <div class="row g-4">
                        @foreach($shortcuts as $shortcut)
                            <div class="col-xl-2 col-lg-3 col-md-4 col-sm-6">
                                <a href="{{ $shortcut['route'] }}" class="card card-hover border border-dashed border-gray-300 border-hover-primary h-100 text-decoration-none">
                                    <div class="card-body d-flex flex-column align-items-center justify-content-center p-5">
                                        <span class="svg-icon svg-icon-{{ $shortcut['color'] }} svg-icon-3x mb-4">
                                            @if($shortcut['icon'] === 'scan-barcode')
                                                <i class="ki-solid ki-scan-barcode fs-1"></i>
                                            @elseif($shortcut['icon'] === 'abstract-26')
                                                <i class="ki-duotone ki-abstract-26 fs-1"><span class="path1"></span><span class="path2"></span></i>
                                            @elseif($shortcut['icon'] === 'category')
                                                <i class="ki-duotone ki-category fs-1"><span class="path1"></span><span class="path2"></span></i>
                                            @elseif($shortcut['icon'] === 'address-book')
                                                <i class="ki-solid ki-address-book fs-1"></i>
                                            @else
                                                {!! getIcon($shortcut['icon'], 'fs-1') !!}
                                            @endif
                                        </span>
                                        <div class="fw-bold text-gray-800 text-center">{{ $shortcut['title'] }}</div>
                                    </div>
                                </a>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--end::Row - Kısayollar-->

    <!--begin::Row - Son Eklenenler-->
    <div class="row g-5 g-xl-8">
        <!--begin::Col - Son Dosyalar-->
        <div class="col-xl-6">
            <div class="card">
                <div class="card-header border-0 pt-6">
                    <div class="card-title">
                        <h3 class="fw-bold m-0">{{ __('common.recent_files') }}</h3>
                    </div>
                    <div class="card-toolbar">
                        <a href="{{ route('file-management.index') }}" class="btn btn-sm btn-light">{{ __('common.view_all') }}</a>
                    </div>
                </div>
                <div class="card-body pt-0">
                    @if($recentFiles->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-row-dashed table-row-gray-300 align-middle gs-0 gy-4">
                                <thead>
                                    <tr class="fw-bold text-muted">
                                        <th>{{ __('common.file_name') }}</th>
                                        <th>{{ __('common.uploaded_by') }}</th>
                                        <th>{{ __('common.date') }}</th>
                                        <th class="text-end">{{ __('common.operation') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($recentFiles as $file)
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    {!! getIcon('file', 'fs-3 text-primary me-2') !!}
                                                    <span class="text-gray-800 fw-bold">{{ Str::limit($file->name, 30) }}</span>
                                                </div>
                                            </td>
                                            <td>{{ $file->user->name ?? '-' }}</td>
                                            <td>{{ $file->created_at->format('d.m.Y H:i') }}</td>
                                            <td class="text-end">
                                                <a href="{{ route('file-management.show', $file) }}" class="btn btn-sm btn-light-primary">{{ __('common.view') }}</a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-10">
                            <p class="text-gray-500">{{ __('common.no_files_uploaded') }}</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
        <!--end::Col - Son Dosyalar-->

        <!--begin::Col - Son QR Kodlar-->
        <div class="col-xl-6">
            <div class="card">
                <div class="card-header border-0 pt-6">
                    <div class="card-title">
                        <h3 class="fw-bold m-0">{{ __('common.recent_qr_codes') }}</h3>
                    </div>
                    <div class="card-toolbar">
                        <a href="{{ route('qr-code.index') }}" class="btn btn-sm btn-light">{{ __('common.view_all') }}</a>
                    </div>
                </div>
                <div class="card-body pt-0">
                    @if($recentQrCodes->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-row-dashed table-row-gray-300 align-middle gs-0 gy-4">
                                <thead>
                                    <tr class="fw-bold text-muted">
                                        <th>{{ __('common.qr_code_name') }}</th>
                                        <th>{{ __('common.created') }}</th>
                                        <th>{{ __('common.date') }}</th>
                                        <th class="text-end">{{ __('common.operation') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($recentQrCodes as $qrCode)
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <i class="ki-solid ki-scan-barcode fs-3 text-success me-2"></i>
                                                    <span class="text-gray-800 fw-bold">{{ Str::limit($qrCode->name, 30) }}</span>
                                                </div>
                                            </td>
                                            <td>{{ $qrCode->user->name ?? '-' }}</td>
                                            <td>{{ $qrCode->created_at->format('d.m.Y H:i') }}</td>
                                            <td class="text-end">
                                                <a href="{{ route('qr-code.show', $qrCode) }}" class="btn btn-sm btn-light-success">{{ __('common.view') }}</a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-10">
                            <p class="text-gray-500">{{ __('common.no_qr_codes_created') }}</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
        <!--end::Col - Son QR Kodlar-->
    </div>
    <!--end::Row - Son Eklenenler-->

    <!--begin::Row - Son Kullanıcılar-->
    <div class="row g-5 g-xl-8 mt-5">
        <div class="col-12">
            <div class="card">
                <div class="card-header border-0 pt-6">
                    <div class="card-title">
                        <h3 class="fw-bold m-0">{{ __('common.recent_users') }}</h3>
                    </div>
                    <div class="card-toolbar">
                        <a href="{{ route('user-management.users.index') }}" class="btn btn-sm btn-light">{{ __('common.view_all') }}</a>
                    </div>
                </div>
                <div class="card-body pt-0">
                    @if($recentUsers->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-row-dashed table-row-gray-300 align-middle gs-0 gy-4">
                                <thead>
                                    <tr class="fw-bold text-muted">
                                        <th>{{ __('common.user') }}</th>
                                        <th>{{ __('common.email') }}</th>
                                        <th>{{ __('common.registration_date') }}</th>
                                        <th class="text-end">{{ __('common.operation') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($recentUsers as $user)
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="symbol symbol-40px me-3">
                                                        <div class="symbol-label fs-3 {{ app(\App\Actions\GetThemeType::class)->handle('bg-light-? text-?', $user->name) }}">
                                                            {{ substr($user->name, 0, 1) }}
                                                        </div>
                                                    </div>
                                                    <span class="text-gray-800 fw-bold">{{ $user->name }}</span>
                                                </div>
                                            </td>
                                            <td>{{ $user->email }}</td>
                                            <td>{{ $user->created_at->format('d.m.Y H:i') }}</td>
                                            <td class="text-end">
                                                <a href="{{ route('user-management.users.show', $user) }}" class="btn btn-sm btn-light-primary">{{ __('common.view') }}</a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-10">
                            <p class="text-gray-500">{{ __('common.no_users_added') }}</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    <!--end::Row - Son Kullanıcılar-->

    @push('scripts')
    <script>
        "use strict";
        var KTChartsWidgetActivity = function() {
            var chart = {
                self: null,
                rendered: false
            };

            var initChart = function() {
                var element = document.getElementById("kt_charts_widget_activity");
                if (!element) {
                    return;
                }

                var height = parseInt(KTUtil.css(element, 'height'));
                var labelColor = KTUtil.getCssVariableValue('--bs-gray-500');
                var borderColor = KTUtil.getCssVariableValue('--bs-gray-200');
                var baseColor = KTUtil.getCssVariableValue('--bs-primary');
                var secondaryColor = KTUtil.getCssVariableValue('--bs-success');
                var tertiaryColor = KTUtil.getCssVariableValue('--bs-info');

                var options = {
                    series: [{
                        name: '{{ __('common.clicks') }}',
                        data: [{{ implode(',', array_column($last7Days, 'clicks')) }}]
                    }, {
                        name: '{{ __('common.qr_scans') }}',
                        data: [{{ implode(',', array_column($last7Days, 'qr_scans')) }}]
                    }, {
                        name: '{{ __('common.vcard_scans') }}',
                        data: [{{ implode(',', array_column($last7Days, 'vcard_scans')) }}]
                    }],
                    chart: {
                        fontFamily: 'inherit',
                        type: 'bar',
                        height: height,
                        toolbar: {
                            show: false
                        }
                    },
                    plotOptions: {
                        bar: {
                            horizontal: false,
                            columnWidth: ['30%'],
                            borderRadius: 4
                        }
                    },
                    legend: {
                        show: false
                    },
                    dataLabels: {
                        enabled: false
                    },
                    stroke: {
                        show: true,
                        width: 2,
                        colors: ['transparent']
                    },
                    xaxis: {
                        categories: [@foreach($last7Days as $day)'{{ $day['date'] }}'@if(!$loop->last),@endif @endforeach],
                        axisBorder: {
                            show: false,
                        },
                        axisTicks: {
                            show: false
                        },
                        labels: {
                            style: {
                                colors: labelColor,
                                fontSize: '12px'
                            }
                        }
                    },
                    yaxis: {
                        labels: {
                            style: {
                                colors: labelColor,
                                fontSize: '12px'
                            }
                        }
                    },
                    fill: {
                        opacity: 1
                    },
                    states: {
                        normal: {
                            filter: {
                                type: 'none',
                                value: 0
                            }
                        },
                        hover: {
                            filter: {
                                type: 'none',
                                value: 0
                            }
                        },
                        active: {
                            allowMultipleDataPointsSelection: false,
                            filter: {
                                type: 'none',
                                value: 0
                            }
                        }
                    },
                    tooltip: {
                        style: {
                            fontSize: '12px'
                        },
                        y: {
                            formatter: function(val) {
                                return val + " {{ __('common.times') }}";
                            }
                        }
                    },
                    colors: [baseColor, secondaryColor, tertiaryColor],
                    grid: {
                        borderColor: borderColor,
                        strokeDashArray: 4,
                        yaxis: {
                            lines: {
                                show: true
                            }
                        }
                    }
                };

                chart.self = new ApexCharts(element, options);
                chart.self.render().then(function() {
                    chart.rendered = true;
                });
            };

            return {
                init: function() {
                    initChart();
                }
            };
        }();

        KTUtil.onDOMContentLoaded(function() {
            KTChartsWidgetActivity.init();
        });
    </script>
    @endpush

</x-default-layout>
