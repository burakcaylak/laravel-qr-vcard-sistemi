<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dosya Bulunamadı</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        .error-container {
            background: white;
            border-radius: 12px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.2);
            padding: 40px;
            max-width: 500px;
            width: 100%;
            text-align: center;
        }
        .error-icon {
            font-size: 64px;
            color: #ef4444;
            margin-bottom: 20px;
        }
        h1 {
            color: #1f2937;
            font-size: 24px;
            margin-bottom: 12px;
        }
        p {
            color: #6b7280;
            font-size: 16px;
            line-height: 1.6;
            margin-bottom: 24px;
        }
        .file-info {
            background: #f3f4f6;
            border-radius: 8px;
            padding: 16px;
            margin-bottom: 24px;
            text-align: left;
        }
        .file-info strong {
            color: #1f2937;
            display: block;
            margin-bottom: 8px;
        }
        .file-info span {
            color: #6b7280;
            font-size: 14px;
        }
        .btn {
            display: inline-block;
            padding: 12px 24px;
            background: #667eea;
            color: white;
            text-decoration: none;
            border-radius: 6px;
            font-weight: 500;
            transition: background 0.2s;
        }
        .btn:hover {
            background: #5568d3;
        }
    </style>
</head>
<body>
    <div class="error-container">
        <div class="error-icon">⚠️</div>
        <h1>Dosya Bulunamadı</h1>
        <p>{{ $error ?? 'İstenen dosya bulunamadı. Dosya silinmiş veya taşınmış olabilir.' }}</p>
        
        @if(isset($file))
        <div class="file-info">
            <strong>Dosya Bilgileri:</strong>
            <span>Dosya Adı: {{ $file->name }}</span><br>
            <span>Orijinal Ad: {{ $file->original_name }}</span><br>
            <span>Path: {{ $file->path }}</span>
        </div>
        @endif
        
        @if(isset($qrCode))
        <div class="file-info">
            <strong>QR Kod Bilgileri:</strong>
            <span>QR Kod Adı: {{ $qrCode->name }}</span><br>
            <span>QR Kod Tipi: {{ $qrCode->qr_type }}</span>
        </div>
        @endif
        
        <a href="javascript:history.back()" class="btn">Geri Dön</a>
    </div>
</body>
</html>
