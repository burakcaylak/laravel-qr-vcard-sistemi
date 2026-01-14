<?php

namespace App\Http\Controllers;

use App\Models\File;
use App\Models\QrCode;
use App\Models\User;
use App\Models\Settings;
use App\Models\VCard;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // İstatistikler
        $stats = [
            'total_files' => File::count(),
            'total_users' => User::count(),
            'total_qr_code_scans' => QrCode::sum('scan_count'),
            'total_vcard_scans' => VCard::sum('scan_count'),
        ];

        // Son eklenen dosyalar
        $recentFiles = File::with('user')
            ->latest()
            ->limit(5)
            ->get();

        // Son eklenen QR kodlar
        $recentQrCodes = QrCode::with(['user', 'file'])
            ->latest()
            ->limit(5)
            ->get();

        // Son eklenen kullanıcılar
        $recentUsers = User::latest()
            ->limit(5)
            ->get();

        // Kısayollar
        $shortcuts = [
            [
                'title' => __('common.create_qr_code'),
                'icon' => 'scan-barcode',
                'route' => route('qr-code.create'),
                'color' => 'success',
            ],
            [
                'title' => __('common.qr_code_list'),
                'icon' => 'abstract-26',
                'route' => route('qr-code.index'),
                'color' => 'primary',
            ],
            [
                'title' => __('common.v_card_list'),
                'icon' => 'address-book',
                'route' => route('v-card.index'),
                'color' => 'primary',
            ],
            [
                'title' => __('common.media_library'),
                'icon' => 'picture',
                'route' => route('media-library.index'),
                'color' => 'info',
            ],
            [
                'title' => __('common.category_management'),
                'icon' => 'category',
                'route' => route('categories.index'),
                'color' => 'warning',
            ],
            [
                'title' => __('common.settings'),
                'icon' => 'setting-2',
                'route' => route('settings.index'),
                'color' => 'danger',
            ],
        ];

        return view('pages.dashboards.index', compact('stats', 'recentFiles', 'recentQrCodes', 'recentUsers', 'shortcuts'));
    }
}
