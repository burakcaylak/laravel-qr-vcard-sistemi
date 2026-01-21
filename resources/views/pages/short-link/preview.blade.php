<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="noindex, nofollow">
    <title>{{ $shortLink->title ?? __('common.short_link') }} - {{ __('common.preview') }}</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        .preview-container {
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
            max-width: 600px;
            width: 100%;
            padding: 40px;
            text-align: center;
        }
        .preview-icon {
            font-size: 64px;
            color: #667eea;
            margin-bottom: 20px;
        }
        h1 {
            font-size: 28px;
            font-weight: 700;
            color: #1a202c;
            margin-bottom: 10px;
        }
        .url-preview {
            background: #f7fafc;
            border: 2px dashed #cbd5e0;
            border-radius: 10px;
            padding: 15px;
            margin: 20px 0;
            word-break: break-all;
            color: #4a5568;
            font-size: 14px;
        }
        .btn-group {
            display: flex;
            gap: 10px;
            justify-content: center;
            flex-wrap: wrap;
            margin-top: 30px;
        }
        .btn {
            padding: 12px 30px;
            border-radius: 8px;
            font-weight: 600;
            text-decoration: none;
            display: inline-block;
            transition: all 0.3s;
            border: none;
            cursor: pointer;
            font-size: 16px;
        }
        .btn-primary {
            background: #667eea;
            color: white;
        }
        .btn-primary:hover {
            background: #5568d3;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
        }
        .btn-secondary {
            background: #e2e8f0;
            color: #4a5568;
        }
        .btn-secondary:hover {
            background: #cbd5e0;
        }
        .social-share {
            margin-top: 30px;
            padding-top: 30px;
            border-top: 1px solid #e2e8f0;
        }
        .social-share h3 {
            font-size: 16px;
            color: #718096;
            margin-bottom: 15px;
        }
        .social-buttons {
            display: flex;
            gap: 10px;
            justify-content: center;
            flex-wrap: wrap;
        }
        .social-btn {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            text-decoration: none;
            color: white;
            font-size: 18px;
            transition: transform 0.3s;
        }
        .social-btn:hover {
            transform: scale(1.1);
        }
        .whatsapp { background: #25D366; }
        .twitter { background: #1DA1F2; }
        .facebook { background: #1877F2; }
        .linkedin { background: #0077B5; }
    </style>
</head>
<body>
    <div class="preview-container">
        <div class="preview-icon">
            <i class="ki-solid ki-link"></i>
        </div>
        <h1>{{ $shortLink->title ?? __('common.short_link') }}</h1>
        @if($shortLink->description)
        <p style="color: #718096; margin-bottom: 20px;">{{ $shortLink->description }}</p>
        @endif
        <div class="url-preview">
            {{ $shortLink->original_url }}
        </div>
        <div class="btn-group">
            <a href="{{ $shortLink->original_url }}" class="btn btn-primary" target="_blank">
                {{ __('common.continue') }}
            </a>
            <a href="{{ route('short-link.redirect', $shortLink->short_code) }}" class="btn btn-secondary">
                {{ __('common.go_back') }}
            </a>
        </div>
        <div class="social-share">
            <h3>{{ __('common.share') }}</h3>
            <div class="social-buttons">
                <a href="https://wa.me/?text={{ urlencode($shortLink->short_url . ' - ' . ($shortLink->title ?? '')) }}" 
                   class="social-btn whatsapp" target="_blank" title="WhatsApp">
                    <i class="fab fa-whatsapp"></i>
                </a>
                <a href="https://twitter.com/intent/tweet?url={{ urlencode($shortLink->short_url) }}&text={{ urlencode($shortLink->title ?? '') }}" 
                   class="social-btn twitter" target="_blank" title="Twitter">
                    <i class="fab fa-twitter"></i>
                </a>
                <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode($shortLink->short_url) }}" 
                   class="social-btn facebook" target="_blank" title="Facebook">
                    <i class="fab fa-facebook-f"></i>
                </a>
                <a href="https://www.linkedin.com/sharing/share-offsite/?url={{ urlencode($shortLink->short_url) }}" 
                   class="social-btn linkedin" target="_blank" title="LinkedIn">
                    <i class="fab fa-linkedin-in"></i>
                </a>
            </div>
        </div>
    </div>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</body>
</html>
