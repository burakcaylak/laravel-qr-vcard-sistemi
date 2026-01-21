<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'WM Dosya&QR Yönetimi') }}</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <!-- Robots Meta Tags - Botların siteyi indexlemesini engelle -->
    <meta name="robots" content="noindex, nofollow, noarchive, nosnippet, noimageindex">
    <meta name="googlebot" content="noindex, nofollow, noarchive, nosnippet, noimageindex">
    <meta name="bingbot" content="noindex, nofollow, noarchive, nosnippet, noimageindex">
    <meta name="slurp" content="noindex, nofollow, noarchive, nosnippet, noimageindex">
    <meta name="duckduckbot" content="noindex, nofollow, noarchive, nosnippet, noimageindex">
    
    {!! includeFavicon() !!}
    
    <!--begin::Fonts-->
    {!! includeFonts() !!}
    <!--end::Fonts-->
    
    <!--begin::Global Stylesheets Bundle-->
    @foreach(getGlobalAssets('css') as $path)
        {!! sprintf('<link rel="stylesheet" href="%s">', asset($path)) !!}
    @endforeach
    <!--end::Global Stylesheets Bundle-->
    
    <style>
        body {
            margin: 0;
            padding: 0;
            min-height: 100vh;
            position: relative;
            overflow: hidden;
        }
        
        .index-background {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-image: url('{{ getLoginImage() }}');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            z-index: 1;
        }
        
        .index-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 51, 161, 0.6);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            z-index: 2;
        }
        
        .index-container {
            position: relative;
            z-index: 3;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            text-align: center;
            padding: 2rem;
        }
        
        .index-logo {
            max-width: 300px;
            width: 100%;
            height: auto;
            filter: drop-shadow(0 4px 6px rgba(0, 0, 0, 0.1));
            cursor: pointer;
            transition: transform 0.3s ease, filter 0.3s ease;
        }
        
        .index-logo:hover {
            transform: scale(1.05);
            filter: drop-shadow(0 6px 12px rgba(0, 0, 0, 0.2));
        }
        
        .index-logo-link {
            display: inline-block;
            text-decoration: none;
        }
        
        @media (max-width: 768px) {
            .index-logo {
                max-width: 200px;
            }
        }
    </style>
</head>
<body>
    <div class="index-background"></div>
    <div class="index-overlay"></div>
    <div class="index-container">
        <a href="{{ route('login') }}" class="index-logo-link">
            <img src="{{ getLogo('light') }}" alt="Logo" class="index-logo">
        </a>
    </div>
</body>
</html>

