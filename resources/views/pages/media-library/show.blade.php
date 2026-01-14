<x-default-layout>

    @section('title')
        Dosya Detayları
    @endsection

    @section('breadcrumbs')
        <div class="d-flex align-items-center flex-wrap me-3 mb-5 mb-lg-0">
            <a href="{{ route('media-library.index') }}" class="text-gray-500 fw-semibold fs-7">Ortam Kütüphanesi</a>
            <span class="text-gray-500 fw-semibold fs-7 mx-2">/</span>
            <span class="text-gray-800 fw-semibold fs-7">{{ $file->name }}</span>
        </div>
    @endsection

    <div class="card">
        <div class="card-header">
            <div class="card-title">
                <h3 class="fw-bold m-0">{{ $file->name }}</h3>
            </div>
            <div class="card-toolbar">
                <a href="{{ route('media-library.edit', $file) }}" class="btn btn-light-primary me-3">
                    {!! getIcon('pencil', 'fs-2', '', 'i') !!}
                    Düzenle
                </a>
                <a href="{{ $file->url }}" target="_blank" class="btn btn-primary">
                    {!! getIcon('download', 'fs-2', '', 'i') !!}
                    İndir
                </a>
            </div>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <table class="table table-borderless">
                        <tr>
                            <th>Orijinal Ad:</th>
                            <td>{{ $file->original_name }}</td>
                        </tr>
                        <tr>
                            <th>Tip:</th>
                            <td>
                                @php
                                    $types = [
                                        'image' => 'Resim',
                                        'document' => 'Belge',
                                        'video' => 'Video',
                                        'audio' => 'Ses',
                                        'other' => 'Diğer',
                                    ];
                                    $typeText = $types[$file->type] ?? ucfirst($file->type);
                                @endphp
                                <span class="badge badge-light-primary">{{ $typeText }}</span>
                            </td>
                        </tr>
                        <tr>
                            <th>Boyut:</th>
                            <td>{{ $file->size_human }}</td>
                        </tr>
                        <tr>
                            <th>Kategori:</th>
                            <td>
                                @if($file->category)
                                    <span class="badge badge-light-info">{{ $file->category->name }}</span>
                                @else
                                    <span class="text-gray-500">-</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th>Yükleyen:</th>
                            <td>{{ $file->user->name }}</td>
                        </tr>
                        <tr>
                            <th>Yüklenme Tarihi:</th>
                            <td>{{ $file->created_at->format('d M Y, H:i') }}</td>
                        </tr>
                        <tr>
                            <th>İndirme Sayısı:</th>
                            <td>{{ $file->download_count }}</td>
                        </tr>
                        <tr>
                            <th>Durum:</th>
                            <td>
                                @if($file->is_public)
                                    <span class="badge badge-light-success">Herkese Açık</span>
                                @else
                                    <span class="badge badge-light-secondary">Özel</span>
                                @endif
                            </td>
                        </tr>
                    </table>
                </div>
                <div class="col-md-6">
                    @if($file->type === 'image')
                        <img src="{{ $file->url }}" alt="{{ $file->name }}" class="img-fluid rounded">
                    @else
                        <div class="d-flex align-items-center justify-content-center bg-light rounded" style="height: 300px;">
                            {!! getIcon('file', 'fs-1') !!}
                        </div>
                    @endif
                </div>
            </div>
            @if($file->description)
                <div class="mt-5">
                    <h4>Açıklama</h4>
                    <p>{{ $file->description }}</p>
                </div>
            @endif
            @if($file->qrCodes->count() > 0)
                <div class="mt-5">
                    <h4>İlişkili QR Kodlar ({{ $file->qrCodes->count() }})</h4>
                    <div class="table-responsive">
                        <table class="table table-row-dashed">
                            <thead>
                                <tr>
                                    <th>QR Kod Adı</th>
                                    <th>Kategori</th>
                                    <th>Taranma</th>
                                    <th>Durum</th>
                                    <th>İşlemler</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($file->qrCodes as $qrCode)
                                    <tr>
                                        <td>{{ $qrCode->name }}</td>
                                        <td>{{ $qrCode->category ?? '-' }}</td>
                                        <td>{{ $qrCode->scan_count }}</td>
                                        <td>
                                            @if($qrCode->is_active)
                                                <span class="badge badge-light-success">Aktif</span>
                                            @else
                                                <span class="badge badge-light-secondary">Pasif</span>
                                            @endif
                                        </td>
                                        <td>
                                            <a href="{{ route('qr-code.show', $qrCode) }}" class="btn btn-sm btn-light">Görüntüle</a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @endif
        </div>
    </div>

</x-default-layout>
