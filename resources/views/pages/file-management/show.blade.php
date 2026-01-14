<x-default-layout>

    @section('title')
        Dosya Detayları
    @endsection

    @section('breadcrumbs')
        {{ Breadcrumbs::render('file-management.show', $file) }}
    @endsection

    <div class="card">
        <div class="card-header">
            <div class="card-title">
                <h3 class="fw-bold m-0">{{ $file->name }}</h3>
            </div>
            <div class="card-toolbar">
                <a href="{{ route('file-management.download', $file) }}" class="btn btn-primary">
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
                            <td>{{ $file->category ?? '-' }}</td>
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
                    </table>
                </div>
                <div class="col-md-6">
                    @if($file->type === 'image')
                        <img src="{{ $file->url }}" alt="{{ $file->name }}" class="img-fluid rounded">
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


