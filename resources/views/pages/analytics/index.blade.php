<x-default-layout>

    @section('title')
        {{ __('common.analytics_dashboard') }}
    @endsection

    @section('breadcrumbs')
        {{ Breadcrumbs::render('analytics.index') }}
    @endsection

    <!--begin::Card - Filtreler-->
    <div class="card mb-5">
        <div class="card-body">
            <form method="GET" action="{{ route('analytics.index') }}" id="analytics-filter-form">
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
                        <a href="{{ route('analytics.index') }}" class="btn btn-light">
                            {{ __('common.clear') }}
                        </a>
                    </div>
                    <div class="col-md-3 d-flex align-items-end justify-content-end">
                        <a href="{{ route('analytics.export', ['type' => 'all', 'date_from' => $dateFrom, 'date_to' => $dateTo]) }}" class="btn btn-success">
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
        <div class="col-md-6 col-lg-3">
            <div class="card card-flush h-xl-100">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-2">
                        <span class="svg-icon svg-icon-primary svg-icon-2hx">
                            {!! getIcon('mouse', 'fs-1') !!}
                        </span>
                        <div class="ms-3">
                            <div class="fs-4 fw-bold text-gray-800">{{ number_format($stats['total_clicks']) }}</div>
                            <div class="fs-6 fw-semibold text-gray-500">{{ __('common.total_clicks') }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-lg-3">
            <div class="card card-flush h-xl-100">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-2">
                        <span class="svg-icon svg-icon-success svg-icon-2hx">
                            {!! getIcon('scan-barcode', 'fs-1') !!}
                        </span>
                        <div class="ms-3">
                            <div class="fs-4 fw-bold text-gray-800">{{ number_format($stats['total_qr_scans']) }}</div>
                            <div class="fs-6 fw-semibold text-gray-500">{{ __('common.total_scans') }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-lg-3">
            <div class="card card-flush h-xl-100">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-2">
                        <span class="svg-icon svg-icon-info svg-icon-2hx">
                            {!! getIcon('eye', 'fs-1') !!}
                        </span>
                        <div class="ms-3">
                            <div class="fs-4 fw-bold text-gray-800">{{ number_format($stats['total_brochure_views']) }}</div>
                            <div class="fs-6 fw-semibold text-gray-500">{{ __('common.total_views') }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-lg-3">
            <div class="card card-flush h-xl-100">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-2">
                        <span class="svg-icon svg-icon-warning svg-icon-2hx">
                            {!! getIcon('address-book', 'fs-1') !!}
                        </span>
                        <div class="ms-3">
                            <div class="fs-4 fw-bold text-gray-800">{{ number_format($stats['total_vcard_scans']) }}</div>
                            <div class="fs-6 fw-semibold text-gray-500">{{ __('common.vcard_scans') }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--end::Row - İstatistikler-->

    <!--begin::Row - Zaman Serisi Grafiği-->
    <div class="row g-5 g-xl-8 mb-5 mb-xl-10">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-header border-0 pt-6">
                    <div class="card-title">
                        <h3 class="fw-bold m-0">{{ __('common.time_series') }}</h3>
                    </div>
                </div>
                <div class="card-body pt-0">
                    <div id="kt_charts_time_series" style="height: 400px;"></div>
                </div>
            </div>
        </div>
    </div>
    <!--end::Row - Zaman Serisi Grafiği-->

    <!--begin::Row - Coğrafi Dağılım ve Referrer-->
    <div class="row g-5 g-xl-8 mb-5 mb-xl-10">
        <div class="col-xl-6">
            <div class="card">
                <div class="card-header border-0 pt-6">
                    <div class="card-title">
                        <h3 class="fw-bold m-0">{{ __('common.geographic_distribution') }}</h3>
                    </div>
                </div>
                <div class="card-body pt-0">
                    @if($geographicData->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-row-dashed table-row-gray-300 align-middle gs-0 gy-4">
                                <thead>
                                    <tr class="fw-bold text-muted">
                                        <th>{{ __('common.country') }}</th>
                                        <th class="text-end">{{ __('common.total_clicks') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($geographicData as $geo)
                                        <tr>
                                            <td>{{ $geo->country ?? __('common.unknown') }}</td>
                                            <td class="text-end fw-bold">{{ number_format($geo->count) }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-10">
                            <p class="text-muted">{{ __('common.no_data_available') }}</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
        <div class="col-xl-6">
            <div class="card">
                <div class="card-header border-0 pt-6">
                    <div class="card-title">
                        <h3 class="fw-bold m-0">{{ __('common.referrer_analysis') }}</h3>
                    </div>
                </div>
                <div class="card-body pt-0">
                    @if($referrerData->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-row-dashed table-row-gray-300 align-middle gs-0 gy-4">
                                <thead>
                                    <tr class="fw-bold text-muted">
                                        <th>{{ __('common.referrer') }}</th>
                                        <th class="text-end">{{ __('common.total_clicks') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($referrerData as $ref)
                                        <tr>
                                            <td>
                                                <a href="{{ $ref->referer }}" target="_blank" class="text-primary">
                                                    {{ \Illuminate\Support\Str::limit($ref->referer, 50) }}
                                                </a>
                                            </td>
                                            <td class="text-end fw-bold">{{ number_format($ref->count) }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-10">
                            <p class="text-muted">{{ __('common.no_data_available') }}</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    <!--end::Row - Coğrafi Dağılım ve Referrer-->

    <!--begin::Row - Cihaz İstatistikleri-->
    <div class="row g-5 g-xl-8 mb-5 mb-xl-10">
        <div class="col-xl-4">
            <div class="card">
                <div class="card-header border-0 pt-6">
                    <div class="card-title">
                        <h3 class="fw-bold m-0">{{ __('common.browser_statistics') }}</h3>
                    </div>
                </div>
                <div class="card-body pt-0">
                    @if($deviceData['browsers']->count() > 0)
                        <div id="kt_charts_browsers" style="height: 300px;"></div>
                    @else
                        <div class="text-center py-10">
                            <p class="text-muted">{{ __('common.no_data_available') }}</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
        <div class="col-xl-4">
            <div class="card">
                <div class="card-header border-0 pt-6">
                    <div class="card-title">
                        <h3 class="fw-bold m-0">{{ __('common.platform_statistics') }}</h3>
                    </div>
                </div>
                <div class="card-body pt-0">
                    @if($deviceData['platforms']->count() > 0)
                        <div id="kt_charts_platforms" style="height: 300px;"></div>
                    @else
                        <div class="text-center py-10">
                            <p class="text-muted">{{ __('common.no_data_available') }}</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
        <div class="col-xl-4">
            <div class="card">
                <div class="card-header border-0 pt-6">
                    <div class="card-title">
                        <h3 class="fw-bold m-0">{{ __('common.device_statistics') }}</h3>
                    </div>
                </div>
                <div class="card-body pt-0">
                    @if($deviceData['devices']->count() > 0)
                        <div id="kt_charts_devices" style="height: 300px;"></div>
                    @else
                        <div class="text-center py-10">
                            <p class="text-muted">{{ __('common.no_data_available') }}</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    <!--end::Row - Cihaz İstatistikleri-->

    <!--begin::Row - En İyi Performans Gösterenler-->
    <div class="row g-5 g-xl-8">
        <div class="col-xl-6">
            <div class="card">
                <div class="card-header border-0 pt-6">
                    <div class="card-title">
                        <h3 class="fw-bold m-0">{{ __('common.top_clicked_links') }}</h3>
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
                                        <th class="text-end">{{ __('common.click_count') }}</th>
                                        <th class="text-end">{{ __('common.actions') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($topLinks as $link)
                                        <tr>
                                            <td>
                                                <a href="{{ $link->short_url }}" target="_blank" class="text-primary fw-bold">{{ $link->short_code }}</a>
                                            </td>
                                            <td>{{ $link->title ?? '-' }}</td>
                                            <td class="text-end fw-bold">{{ number_format($link->clicks_count) }}</td>
                                            <td class="text-end">
                                                <a href="{{ route('analytics.short-link', $link->id) }}" class="btn btn-sm btn-light-primary">
                                                    {{ __('common.detailed_analytics') }}
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-10">
                            <p class="text-muted">{{ __('common.no_data_available') }}</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
        <div class="col-xl-6">
            <div class="card">
                <div class="card-header border-0 pt-6">
                    <div class="card-title">
                        <h3 class="fw-bold m-0">{{ __('common.top_scanned_qr_codes') }}</h3>
                    </div>
                </div>
                <div class="card-body pt-0">
                    @if($topQrCodes->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-row-dashed table-row-gray-300 align-middle gs-0 gy-4">
                                <thead>
                                    <tr class="fw-bold text-muted">
                                        <th>{{ __('common.name') }}</th>
                                        <th class="text-end">{{ __('common.scan_count') }}</th>
                                        <th class="text-end">{{ __('common.actions') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($topQrCodes as $qr)
                                        <tr>
                                            <td>{{ $qr->name }}</td>
                                            <td class="text-end fw-bold">{{ number_format($qr->scan_count) }}</td>
                                            <td class="text-end">
                                                <a href="{{ route('analytics.qr-code', $qr->id) }}" class="btn btn-sm btn-light-primary">
                                                    {{ __('common.detailed_analytics') }}
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-10">
                            <p class="text-muted">{{ __('common.no_data_available') }}</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    <!--end::Row - En İyi Performans Gösterenler-->

    @push('scripts')
    <script>
        "use strict";
        
        // Zaman Serisi Grafiği
        var KTChartsTimeSeries = function() {
            var chart = {
                self: null,
                rendered: false
            };

            var initChart = function() {
                var element = document.getElementById("kt_charts_time_series");
                if (!element) {
                    return;
                }

                var height = parseInt(KTUtil.css(element, 'height'));
                var labelColor = KTUtil.getCssVariableValue('--bs-gray-500');
                var borderColor = KTUtil.getCssVariableValue('--bs-gray-200');
                var baseColor = KTUtil.getCssVariableValue('--bs-primary');
                var secondaryColor = KTUtil.getCssVariableValue('--bs-success');
                var tertiaryColor = KTUtil.getCssVariableValue('--bs-info');
                var warningColor = KTUtil.getCssVariableValue('--bs-warning');

                var timeSeriesData = @json($timeSeries);

                var options = {
                    series: [{
                        name: '{{ __('common.clicks') }}',
                        data: timeSeriesData.map(item => item.clicks)
                    }, {
                        name: '{{ __('common.qr_scans') }}',
                        data: timeSeriesData.map(item => item.qr_scans)
                    }, {
                        name: '{{ __('common.total_views') }}',
                        data: timeSeriesData.map(item => item.brochure_views)
                    }, {
                        name: '{{ __('common.vcard_scans') }}',
                        data: timeSeriesData.map(item => item.vcard_scans)
                    }],
                    chart: {
                        fontFamily: 'inherit',
                        type: 'line',
                        height: height,
                        toolbar: {
                            show: false
                        }
                    },
                    stroke: {
                        curve: 'smooth',
                        width: 3
                    },
                    xaxis: {
                        categories: timeSeriesData.map(item => item.date),
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
                    colors: [baseColor, secondaryColor, tertiaryColor, warningColor],
                    grid: {
                        borderColor: borderColor,
                        strokeDashArray: 4,
                        yaxis: {
                            lines: {
                                show: true
                            }
                        }
                    },
                    legend: {
                        show: true,
                        position: 'top'
                    },
                    tooltip: {
                        style: {
                            fontSize: '12px'
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

        // Browser Grafiği
        var KTChartsBrowsers = function() {
            var initChart = function() {
                var element = document.getElementById("kt_charts_browsers");
                if (!element) {
                    return;
                }

                var browsers = @json($deviceData['browsers']);

                var options = {
                    series: browsers.map(item => item.count),
                    chart: {
                        type: 'pie',
                        height: 300
                    },
                    labels: browsers.map(item => item.browser),
                    legend: {
                        position: 'bottom'
                    }
                };

                new ApexCharts(element, options).render();
            };

            return {
                init: function() {
                    initChart();
                }
            };
        }();

        // Platform Grafiği
        var KTChartsPlatforms = function() {
            var initChart = function() {
                var element = document.getElementById("kt_charts_platforms");
                if (!element) {
                    return;
                }

                var platforms = @json($deviceData['platforms']);

                var options = {
                    series: platforms.map(item => item.count),
                    chart: {
                        type: 'pie',
                        height: 300
                    },
                    labels: platforms.map(item => item.platform),
                    legend: {
                        position: 'bottom'
                    }
                };

                new ApexCharts(element, options).render();
            };

            return {
                init: function() {
                    initChart();
                }
            };
        }();

        // Device Grafiği
        var KTChartsDevices = function() {
            var initChart = function() {
                var element = document.getElementById("kt_charts_devices");
                if (!element) {
                    return;
                }

                var devices = @json($deviceData['devices']);

                var options = {
                    series: devices.map(item => item.count),
                    chart: {
                        type: 'pie',
                        height: 300
                    },
                    labels: devices.map(item => item.device_type),
                    legend: {
                        position: 'bottom'
                    }
                };

                new ApexCharts(element, options).render();
            };

            return {
                init: function() {
                    initChart();
                }
            };
        }();

        KTUtil.onDOMContentLoaded(function() {
            KTChartsTimeSeries.init();
            @if($deviceData['browsers']->count() > 0)
            KTChartsBrowsers.init();
            @endif
            @if($deviceData['platforms']->count() > 0)
            KTChartsPlatforms.init();
            @endif
            @if($deviceData['devices']->count() > 0)
            KTChartsDevices.init();
            @endif
        });
    </script>
    @endpush

</x-default-layout>
