<?php

namespace App\Helpers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class ActivityLogHelper
{
    /**
     * Log an activity
     */
    public static function log($description, $subject = null, $event = null, $properties = [])
    {
        if (!Schema::hasTable('activity_logs')) {
            return;
        }

        $userId = auth()->id();
        
        $data = [
            'log_name' => 'default',
            'description' => $description,
            'subject_type' => $subject ? get_class($subject) : null,
            'subject_id' => $subject ? $subject->id : null,
            'causer_type' => $userId ? \App\Models\User::class : null,
            'causer_id' => $userId,
            'properties' => json_encode(array_merge($properties, [
                'ip' => request()->ip(),
                'user_agent' => request()->userAgent(),
            ])),
            'event' => $event,
            'created_at' => now(),
            'updated_at' => now(),
        ];

        DB::table('activity_logs')->insert($data);
    }

    /**
     * Log file operations
     */
    public static function logFile($action, $file, $details = [])
    {
        $descriptions = [
            'created' => "Dosya oluşturuldu: {$file->name}",
            'updated' => "Dosya güncellendi: {$file->name}",
            'deleted' => "Dosya silindi: {$file->name}",
            'downloaded' => "Dosya indirildi: {$file->name}",
        ];

        self::log(
            $descriptions[$action] ?? "Dosya işlemi: {$file->name}",
            $file,
            $action,
            array_merge($details, [
                'file_name' => $file->name,
                'file_type' => $file->type,
                'file_size' => $file->size,
            ])
        );
    }

    /**
     * Log QR code operations
     */
    public static function logQrCode($action, $qrCode, $details = [])
    {
        $descriptions = [
            'created' => "QR kod oluşturuldu: {$qrCode->name}",
            'updated' => "QR kod güncellendi: {$qrCode->name}",
            'deleted' => "QR kod silindi: {$qrCode->name}",
            'downloaded' => "QR kod indirildi: {$qrCode->name}",
        ];

        self::log(
            $descriptions[$action] ?? "QR kod işlemi: {$qrCode->name}",
            $qrCode,
            $action,
            array_merge($details, [
                'qr_name' => $qrCode->name,
                'qr_type' => $qrCode->qr_type,
            ])
        );
    }

    /**
     * Log media library operations
     */
    public static function logMedia($action, $fileName, $folder = null, $details = [])
    {
        $descriptions = [
            'uploaded' => "Medya yüklendi: {$fileName}",
            'deleted' => "Medya silindi: {$fileName}",
        ];

        self::log(
            $descriptions[$action] ?? "Medya işlemi: {$fileName}",
            null,
            $action,
            array_merge($details, [
                'file_name' => $fileName,
                'folder' => $folder,
            ])
        );
    }

    /**
     * Log vCard operations
     */
    public static function logVCard($action, $vCard, $details = [])
    {
        $name = $vCard->getLocalizedField('name') ?? 'vCard #' . $vCard->id;
        
        $descriptions = [
            'created' => "vCard oluşturuldu: {$name}",
            'updated' => "vCard güncellendi: {$name}",
            'deleted' => "vCard silindi: {$name}",
            'downloaded' => "vCard indirildi: {$name}",
        ];

        self::log(
            $descriptions[$action] ?? "vCard işlemi: {$name}",
            $vCard,
            $action,
            array_merge($details, [
                'v_card_name' => $name,
                'v_card_token' => $vCard->token,
            ])
        );
    }

    /**
     * Log Brochure operations
     */
    public static function logBrochure($action, $brochure, $details = [])
    {
        $descriptions = [
            'created' => "Kitapçık oluşturuldu: {$brochure->name}",
            'updated' => "Kitapçık güncellendi: {$brochure->name}",
            'deleted' => "Kitapçık silindi: {$brochure->name}",
            'downloaded' => "Kitapçık QR kodu indirildi: {$brochure->name}",
        ];

        self::log(
            $descriptions[$action] ?? "Kitapçık işlemi: {$brochure->name}",
            $brochure,
            $action,
            array_merge($details, [
                'brochure_name' => $brochure->name,
                'brochure_token' => $brochure->token,
            ])
        );
    }

    /**
     * Log Short Link operations
     */
    public static function logShortLink($action, $shortLink, $details = [])
    {
        $name = $shortLink->title ?? $shortLink->short_code;
        
        $descriptions = [
            'created' => "Link kısaltıldı: {$name}",
            'updated' => "Link güncellendi: {$name}",
            'deleted' => "Link silindi: {$name}",
        ];

        self::log(
            $descriptions[$action] ?? "Link işlemi: {$name}",
            $shortLink,
            $action,
            array_merge($details, [
                'short_link_title' => $shortLink->title,
                'short_code' => $shortLink->short_code,
                'original_url' => $shortLink->original_url,
            ])
        );
    }
}

