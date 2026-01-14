<?php

return [
    /*
    |--------------------------------------------------------------------------
    | File Upload Configuration
    |--------------------------------------------------------------------------
    |
    | Bu dosya, uygulama genelinde kullanılan dosya yükleme ayarlarını içerir.
    |
    */

    'max_file_size' => env('MAX_FILE_SIZE', 51200), // 50MB in KB

    'allowed_mime_types' => [
        'image' => [
            'image/jpeg',
            'image/jpg',
            'image/png',
            'image/gif',
            'image/svg+xml',
            'image/webp',
        ],
        'document' => [
            'application/pdf',
            'application/msword',
            'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
            'application/vnd.ms-excel',
            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'application/vnd.ms-powerpoint',
            'application/vnd.openxmlformats-officedocument.presentationml.presentation',
            'text/plain',
        ],
        'video' => [
            'video/mp4',
            'video/avi',
            'video/quicktime',
            'video/x-msvideo',
            'video/x-flv',
        ],
        'audio' => [
            'audio/mpeg',
            'audio/wav',
            'audio/ogg',
            'audio/mp4',
        ],
    ],

    'allowed_extensions' => [
        'image' => ['jpg', 'jpeg', 'png', 'gif', 'svg', 'webp'],
        'document' => ['pdf', 'doc', 'docx', 'xls', 'xlsx', 'ppt', 'pptx', 'txt'],
        'video' => ['mp4', 'avi', 'mov', 'wmv', 'flv'],
        'audio' => ['mp3', 'wav', 'ogg', 'm4a'],
    ],
    
    /*
    |--------------------------------------------------------------------------
    | ClamAV Configuration
    |--------------------------------------------------------------------------
    |
    | ClamAV virus scanner path. Set to null to disable virus scanning.
    |
    */
    'clamav_path' => env('CLAMAV_PATH', '/usr/bin/clamscan'),
    'enable_virus_scanning' => env('ENABLE_VIRUS_SCANNING', false),
];
