<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>QR Kod Erişimi</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: unset;
            background-color: #0033A1;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        .access-card {
            background-color: rgba(255, 255, 255, 1);
            border-style: solid;
            border-width: 1px;
            border-color: rgba(0, 0, 0, 1);
            border-radius: 0;
            box-shadow: 0 10px 40px rgba(0,0,0,0.2);
            padding: 40px;
            max-width: 800px;
            width: 100%;
        }
        .access-card h1 {
            color: #000;
        }
        .file-button {
            transition: all 0.3s ease;
            background-color: #0033A1 !important;
            border-color: #0033A1 !important;
            border-radius: 0 !important;
        }
        .file-button:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.2);
            background-color: #002d8a !important;
            border-color: #002d8a !important;
        }
    </style>
</head>
<body>
    <div class="access-card">
        @if($qrCode->qr_type === 'url')
            {{-- URL tipi için özel görünüm: İçerik (düz metin) ve Görüntüle butonu --}}
            @if(isset($content) && $content)
                <div class="mb-4">
                    <p class="text-muted mb-0">{{ $content }}</p>
                </div>
            @endif
            
            @php
                // Eğer dosya varsa dosyayı aç, yoksa URL'yi aç
                if (isset($file) && $file) {
                    $linkUrl = route('qr.access.file', ['token' => $qrCode->token, 'fileId' => $file->id]);
                    $isFile = true;
                } else {
                    // Buton için URL formatla
                    $linkUrl = isset($url) ? $url : (isset($content) ? $content : '#');
                    // Eğer http:// veya https:// yoksa ekle
                    if ($linkUrl !== '#' && !preg_match('/^https?:\/\//i', $linkUrl)) {
                        $linkUrl = 'http://' . $linkUrl;
                    }
                    $isFile = false;
                }
            @endphp
            <div class="d-grid gap-3">
                <a href="{{ $linkUrl }}" {{ $isFile ? '' : 'target="_blank" rel="noopener noreferrer"' }} class="btn btn-primary btn-lg file-button">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-box-arrow-up-right" viewBox="0 0 16 16" style="margin-right: 8px;">
                        <path fill-rule="evenodd" d="M8.636 3.5a.5.5 0 0 0-.5-.5H1.5A1.5 1.5 0 0 0 0 4.5v10A1.5 1.5 0 0 0 1.5 16h10a1.5 1.5 0 0 0 1.5-1.5V7.864a.5.5 0 0 0-1 0V14.5a.5.5 0 0 1-.5.5h-10a.5.5 0 0 1-.5-.5v-10a.5.5 0 0 1 .5-.5h6.636a.5.5 0 0 0 .5-.5z"/>
                        <path fill-rule="evenodd" d="M16 .5a.5.5 0 0 0-.5-.5h-5a.5.5 0 0 0 0 1h3.793L6.146 9.146a.5.5 0 1 0 .708.708L15 1.707V5.5a.5.5 0 0 0 1 0v-5z"/>
                    </svg>
                    Görüntüle
                </a>
            </div>
        @elseif(count($files) > 0)
            <h2 class="mb-4 text-center">Dosyalar</h2>
            <p class="text-muted text-center mb-4">Aşağıdaki dosyalardan birini seçerek indirebilirsiniz.</p>
            <div class="d-grid gap-2">
                @foreach($files as $file)
                    <a href="{{ route('qr.access.file', ['token' => $qrCode->token, 'fileId' => $file->id]) }}" class="btn btn-primary btn-lg">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-download" viewBox="0 0 16 16" style="margin-right: 8px;">
                            <path d="M.5 9.9a.5.5 0 0 1 .5.5v2.5a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1v-2.5a.5.5 0 0 1 1 0v2.5a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2v-2.5a.5.5 0 0 1 .5-.5z"/>
                            <path d="M7.646 11.854a.5.5 0 0 0 .708 0l3-3a.5.5 0 0 0-.708-.708L8.5 10.293V1.5a.5.5 0 0 0-1 0v8.793L5.354 8.146a.5.5 0 1 0-.708.708l3 3z"/>
                        </svg>
                        {{ $file->name }} ({{ $file->original_name }})
                    </a>
                @endforeach
            </div>
        @elseif($qrCode->file)
            <div class="text-center">
                <h2 class="mb-4">Dosya İndiriliyor...</h2>
                <p class="text-muted mb-4">Eğer dosya otomatik olarak indirilmediyse, aşağıdaki butona tıklayın.</p>
                <a href="{{ route('qr.access.file', ['token' => $qrCode->token, 'fileId' => $qrCode->file->id]) }}" class="btn btn-primary btn-lg">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-download" viewBox="0 0 16 16" style="margin-right: 8px;">
                        <path d="M.5 9.9a.5.5 0 0 1 .5.5v2.5a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1v-2.5a.5.5 0 0 1 1 0v2.5a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2v-2.5a.5.5 0 0 1 .5-.5z"/>
                        <path d="M7.646 11.854a.5.5 0 0 0 .708 0l3-3a.5.5 0 0 0-.708-.708L8.5 10.293V1.5a.5.5 0 0 0-1 0v8.793L5.354 8.146a.5.5 0 1 0-.708.708l3 3z"/>
                    </svg>
                    Dosyayı İndir
                </a>
            </div>
            <script>
                // Otomatik indirme
                setTimeout(function() {
                    window.location.href = '{{ route("qr.access.file", ["token" => $qrCode->token, "fileId" => $qrCode->file->id]) }}';
                }, 1000);
            </script>
        @else
            <div class="text-center">
                @if(isset($error))
                    <div class="alert alert-danger mb-4">
                        <p class="mb-0"><strong>Hata:</strong> {{ $error }}</p>
                        @if(isset($content) && $content)
                            <p class="mb-0 mt-2"><small>Girilen değer: {{ $content }}</small></p>
                        @endif
                    </div>
                @else
                    {{-- Diğer tipler için eski görünüm --}}
                    <h2 class="mb-4">QR Kod İçeriği</h2>
                    <div class="alert alert-info">
                        <p class="mb-0">{{ isset($content) ? $content : '' }}</p>
                    </div>
                @endif
            </div>
        @endif
    </div>
</body>
</html>


