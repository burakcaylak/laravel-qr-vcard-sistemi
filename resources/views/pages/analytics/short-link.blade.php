<x-default-layout>

    @section('title')
        {{ __('common.detailed_analytics') }} - {{ $shortLink->title ?? $shortLink->short_code }}
    @endsection

    @section('breadcrumbs')
        {{ Breadcrumbs::render('analytics.index') }}
        {{ Breadcrumbs::render('analytics.short-link', $shortLink) }}
    @endsection

    <!--begin::Card - ShortLink Bilgileri-->
    <div class="card mb-5">
        <div class="card-header border-0 pt-6">
            <div class="card-title">
                <h3 class="fw-bold m-0">{{ $shortLink->title ?? $shortLink->short_code }}</h3>
            </div>
            <div class="card-toolbar">
                <a href="{{ route('short-link.show', $shortLink) }}" class="btn btn-sm btn-light">
                    {!! getIcon('arrow-left', 'fs-2') !!}
                    {{ __('common.back') }}
                </a>
            </div>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <p><strong>{{ __('common.short_code') }}:</strong> <a href="{{ $shortLink->short_url }}" target="_blank" class="text-primary">{{ $shortLink->short_code }}</a></p>
                    <p><strong>{{ __('common.original_url') }}:</strong> <a href="{{ $shortLink->original_url }}" target="_blank" class="text-break">{{ Str::limit($shortLink->original_url, 50) }}</a></p>
                    <p><strong>{{ __('common.created_at') }}:</strong> {{ $shortLink->created_at->translatedFormat('d F Y, H:i') }}</p>
                </div>
                <div class="col-md-6">
                    <p><strong>{{ __('common.click_count') }}:</strong> {{ number_format($shortLink->click_count) }}</p>
                    <p><strong>{{ __('common.status') }}:</strong> 
                        @if($shortLink->is_active)
                            <span class="badge badge-light-success">{{ __('common.active') }}</span>
                        @else
                            <span class="badge badge-light-secondary">{{ __('common.inactive') }}</span>
                        @endif
                    </p>
                    @if($shortLink->expires_at)
                        <p><strong>{{ __('common.expires_at') }}:</strong> {{ $shortLink->expires_at->translatedFormat('d F Y, H:i') }}</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
    <!--end::Card - ShortLink Bilgileri-->

    <!--begin::Card - Filtreler-->
    <div class="card mb-5">
        <div class="card-body">
            <form method="GET" action="{{ route('analytics.short-link', $shortLink->id) }}" id="analytics-filter-form">
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
                        <a href="{{ route('analytics.short-link', $shortLink->id) }}" class="btn btn-light">
                            {{ __('common.clear') }}
                        </a>
                    </div>
                    <div class="col-md-3 d-flex align-items-end justify-content-end">
                        <a href="{{ route('analytics.export', ['type' => 'short-link', 'id' => $shortLink->id, 'date_from' => $dateFrom, 'date_to' => $dateTo]) }}" class="btn btn-success">
                            {!! getIcon('file-down', 'fs-2') !!}
                            {{ __('common.export_csv') }}
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <!--end::Card - Filtreler-->

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
                                        <th>{{ __('common.city') }}</th>
                                        <th class="text-end">{{ __('common.total_clicks') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($geographicData as $geo)
                                        <tr>
                                            <td>{{ $geo->country ?? __('common.unknown') }}</td>
                                            <td>{{ $geo->city ?? '-' }}</td>
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

    <!--begin::Card - Tıklama Geçmişi-->
    <div class="card">
        <div class="card-header border-0 pt-6">
            <div class="card-title">
                <h3 class="fw-bold m-0">{{ __('common.click_history') }}</h3>
            </div>
        </div>
        <div class="card-body pt-0">
            @if($clicks->count() > 0)
                <div class="table-responsive">
                    <table class="table table-row-dashed table-row-gray-300 align-middle gs-0 gy-4">
                        <thead>
                            <tr class="fw-bold text-muted">
                                <th>{{ __('common.date_time') }}</th>
                                <th>{{ __('common.ip_address') }}</th>
                                <th>{{ __('common.country') }}</th>
                                <th>{{ __('common.city') }}</th>
                                <th>{{ __('common.browser') }}</th>
                                <th>{{ __('common.platform') }}</th>
                                <th>{{ __('common.device_type') }}</th>
                                <th>{{ __('common.referrer') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($clicks as $click)
                                <tr>
                                    <td>{{ $click->created_at->translatedFormat('d M Y, H:i') }}</td>
                                    <td>{{ $click->ip_address }}</td>
                                    <td>{{ $click->country ?? '-' }}</td>
                                    <td>{{ $click->city ?? '-' }}</td>
                                    <td>{{ $click->browser ?? '-' }}</td>
                                    <td>{{ $click->platform ?? '-' }}</td>
                                    <td>{{ $click->device_type ?? '-' }}</td>
                                    <td>
                                        @if($click->referer)
                                            <a href="{{ $click->referer }}" target="_blank" class="text-primary">
                                                {{ \Illuminate\Support\Str::limit($click->referer, 30) }}
                                            </a>
                                        @else
                                            -
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="mt-5">
                    {{ $clicks->links() }}
                </div>
            @else
                <div class="text-center py-10">
                    <p class="text-muted">{{ __('common.no_data_available') }}</p>
                </div>
            @endif
        </div>
    </div>
    <!--end::Card - Tıklama Geçmişi-->

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

                var timeSeriesData = @json($timeSeries);

                var options = {
                    series: [{
                        name: '{{ __('common.clicks') }}',
                        data: timeSeriesData.map(item => item.count)
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
                    colors: [baseColor],
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
                        show: false
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
