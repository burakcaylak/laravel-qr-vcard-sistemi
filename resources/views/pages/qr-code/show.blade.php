<x-default-layout>

    @section('title')
        QR Kod Detayları
    @endsection

    @section('breadcrumbs')
        {{ Breadcrumbs::render('qr-code.show', $qrCode) }}
    @endsection

    <div class="card">
        <div class="card-header">
            <div class="card-title">
                <span class="svg-icon svg-icon-success svg-icon-2 me-2">
                    <i class="ki-solid ki-scan-barcode fs-2"></i>
                </span>
                <h3 class="fw-bold m-0 d-inline">{{ $qrCode->name }}</h3>
            </div>
            <div class="card-toolbar">
                <a href="{{ route('qr-code.download', $qrCode) }}" class="btn btn-primary me-3">
                    {!! getIcon('download', 'fs-2', '', 'i') !!}
                    QR Kod İndir
                </a>
                <a href="{{ route('qr-code.edit', $qrCode) }}" class="btn btn-light">
                    {!! getIcon('pencil', 'fs-2', '', 'i') !!}
                    Düzenle
                </a>
            </div>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <div class="text-center mb-10">
                        @if($qrCode->file_path)
                            <img src="{{ asset('storage/' . $qrCode->file_path) }}" alt="QR Kod" class="img-fluid" style="max-width: 300px;">
                        @endif
                    </div>
                    <div class="text-center">
                        <p class="text-muted">Token: <code>{{ $qrCode->token }}</code></p>
                        <p class="text-muted">URL: <a href="{{ $qrCode->qr_url }}" target="_blank">{{ $qrCode->qr_url }}</a></p>
                    </div>
                </div>
                <div class="col-md-6">
                    <table class="table table-borderless">
                        <tr>
                            <th>Kategori:</th>
                            <td>{{ $qrCode->category ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th>Talep Eden Kişi:</th>
                            <td>{{ $qrCode->requested_by ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th>Talep Tarihi:</th>
                            <td>{{ $qrCode->request_date ? $qrCode->request_date->translatedFormat('d F Y') : '-' }}</td>
                        </tr>
                        <tr>
                            <th>Tip:</th>
                            <td>
                                @php
                                    $types = [
                                        'file' => ['badge' => 'badge-light-primary', 'text' => 'Dosya'],
                                        'url' => ['badge' => 'badge-light-info', 'text' => 'URL'],
                                        'multi_file' => ['badge' => 'badge-light-success', 'text' => 'Çoklu Dosya'],
                                        'text' => ['badge' => 'badge-light-warning', 'text' => 'Metin'],
                                        'email' => ['badge' => 'badge-light-success', 'text' => 'E-posta'],
                                        'phone' => ['badge' => 'badge-light-danger', 'text' => 'Telefon'],
                                        'wifi' => ['badge' => 'badge-light-dark', 'text' => 'WiFi'],
                                        'vcard' => ['badge' => 'badge-light-secondary', 'text' => 'vCard'],
                                    ];
                                    $typeData = $types[$qrCode->qr_type] ?? ['badge' => 'badge-light-secondary', 'text' => strtoupper($qrCode->qr_type)];
                                @endphp
                                <span class="badge {{ $typeData['badge'] }}">{{ $typeData['text'] }}</span>
                            </td>
                        </tr>
                        @if($qrCode->file)
                            <tr>
                                <th>İlişkili Dosya:</th>
                                <td><a href="{{ route('file-management.show', $qrCode->file) }}">{{ $qrCode->file->name }}</a></td>
                            </tr>
                        @endif
                        <tr>
                            <th>Boyut:</th>
                            <td>{{ $qrCode->size }}px</td>
                        </tr>
                        <tr>
                            <th>Format:</th>
                            <td>{{ strtoupper($qrCode->format) }}</td>
                        </tr>
                        <tr>
                            <th>Taranma Sayısı:</th>
                            <td>{{ $qrCode->scan_count }}</td>
                        </tr>
                        <tr>
                            <th>İndirme Sayısı:</th>
                            <td><span class="badge badge-light-info">{{ $qrCode->download_count }}</span></td>
                        </tr>
                        <tr>
                            <th>Durum:</th>
                            <td>
                                @if($qrCode->is_expired)
                                    <span class="badge badge-light-danger">Süresi Dolmuş</span>
                                @elseif($qrCode->is_active)
                                    <span class="badge badge-light-success">Aktif</span>
                                @else
                                    <span class="badge badge-light-secondary">Pasif</span>
                                @endif
                            </td>
                        </tr>
                        @if($qrCode->expires_at)
                            <tr>
                                <th>Son Kullanma:</th>
                                <td>{{ $qrCode->expires_at->translatedFormat('d F Y, H:i') }}</td>
                            </tr>
                        @endif
                        <tr>
                            <th>Oluşturulma:</th>
                            <td>{{ $qrCode->created_at->translatedFormat('d F Y, H:i') }}</td>
                        </tr>
                    </table>
                </div>
            </div>
            @if($qrCode->description)
                <div class="mt-5">
                    <h4>Açıklama</h4>
                    <p>{{ $qrCode->description }}</p>
                </div>
            @endif
        </div>
    </div>

    <!--begin::Aktivite Kayıtları-->
    @if($activityLogs && $activityLogs->count() > 0)
        <div class="card mt-5">
            <div class="card-header">
                <div class="card-title">
                    <span class="svg-icon svg-icon-primary svg-icon-2 me-2">
                        <i class="ki-solid ki-chart-line-up fs-2"></i>
                    </span>
                    <h3 class="fw-bold m-0">Aktivite Kayıtları</h3>
                </div>
            </div>
            <div class="card-body">
                <div class="timeline">
                    @foreach($activityLogs as $log)
                        <div class="timeline-item d-flex position-relative mb-5">
                            <div class="timeline-line w-40px"></div>
                            <div class="timeline-icon me-4">
                                @php
                                    $iconClass = 'ki-solid ki-check-circle';
                                    $iconColor = 'text-success';
                                    if ($log->event === 'created') {
                                        $iconClass = 'ki-solid ki-plus-circle';
                                        $iconColor = 'text-primary';
                                    } elseif ($log->event === 'updated') {
                                        $iconClass = 'ki-solid ki-pencil';
                                        $iconColor = 'text-info';
                                    } elseif ($log->event === 'deleted') {
                                        $iconClass = 'ki-solid ki-trash';
                                        $iconColor = 'text-danger';
                                    } elseif ($log->event === 'downloaded') {
                                        $iconClass = 'ki-solid ki-download';
                                        $iconColor = 'text-warning';
                                    }
                                @endphp
                                <div class="timeline-badge">
                                    <span class="{{ $iconColor }}">
                                        <i class="{{ $iconClass }} fs-2"></i>
                                    </span>
                                </div>
                            </div>
                            <div class="timeline-content d-flex flex-wrap pb-2">
                                <div class="flex-grow-1">
                                    <div class="fw-bold text-gray-800 mb-1">{{ $log->description }}</div>
                                    <div class="d-flex align-items-center">
                                        <div class="symbol symbol-25px me-3">
                                            <div class="symbol-label fs-3 bg-light-info text-info">
                                                <i class="ki-solid ki-user fs-2"></i>
                                            </div>
                                        </div>
                                        <div class="flex-grow-1">
                                            <div class="text-gray-600 fw-semibold">
                                                {{ $log->user_name ?? 'Sistem' }}
                                                @if($log->user_email)
                                                    <span class="text-muted">({{ $log->user_email }})</span>
                                                @endif
                                            </div>
                                            <div class="text-muted fs-7">
                                                {{ \Carbon\Carbon::parse($log->created_at)->diffForHumans() }}
                                                <span class="mx-1">•</span>
                                                {{ \Carbon\Carbon::parse($log->created_at)->translatedFormat('d F Y, H:i') }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    @endif
    <!--end::Aktivite Kayıtları-->

</x-default-layout>


