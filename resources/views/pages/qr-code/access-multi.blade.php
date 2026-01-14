<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $qrCode->page_title ?? 'Dosyalar' }}</title>
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
        <h1 class="text-center mb-5">{{ $qrCode->page_title ?? 'Dosyalar' }}</h1>
        @if($files->count() > 0)
            <div class="d-grid gap-3">
                @foreach($files as $file)
                    <a href="{{ route('qr.access.file', ['token' => $qrCode->token, 'fileId' => $file->id]) }}" class="btn btn-primary btn-lg file-button">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-download" viewBox="0 0 16 16" style="margin-right: 8px;">
                            <path d="M.5 9.9a.5.5 0 0 1 .5.5v2.5a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1v-2.5a.5.5 0 0 1 1 0v2.5a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2v-2.5a.5.5 0 0 1 .5-.5z"/>
                            <path d="M7.646 11.854a.5.5 0 0 0 .708 0l3-3a.5.5 0 0 0-.708-.708L8.5 10.293V1.5a.5.5 0 0 0-1 0v8.793L5.354 8.146a.5.5 0 1 0-.708.708l3 3z"/>
                        </svg>
                        {{ $file->pivot->button_name ?? $file->name }}
                    </a>
                @endforeach
            </div>
        @else
            <div class="alert alert-warning text-center">
                <p class="mb-0">Henüz dosya eklenmemiş.</p>
            </div>
        @endif
    </div>
</body>
</html>

