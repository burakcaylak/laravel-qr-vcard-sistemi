<?php

namespace App\Http\Controllers;

use App\Models\File;
use App\Models\QrCode;
use App\Models\User;
use App\Models\Settings;
use App\Models\VCard;
use App\Models\ShortLink;
use App\Models\ShortLinkClick;
use App\Models\Brochure;
use App\Helpers\CacheHelper;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        // İstatistikler (Cache'den al)
        $stats = CacheHelper::getDashboardStats();

        // Son 30 günlük tıklama/tarama istatistikleri
        $clickStats = ShortLinkClick::selectRaw('DATE(created_at) as date, COUNT(*) as count')
            ->where('created_at', '>=', Carbon::now()->subDays(30))
            ->groupBy('date')
            ->orderBy('date')
            ->get()
            ->pluck('count', 'date')
            ->toArray();

        // Son 7 günlük istatistikler
        $last7Days = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i)->format('Y-m-d');
            $last7Days[] = [
                'date' => Carbon::now()->subDays($i)->format('d M'),
                'clicks' => ShortLinkClick::whereDate('created_at', $date)->count(),
                'qr_scans' => QrCode::whereDate('created_at', $date)->sum('scan_count'),
                'vcard_scans' => VCard::whereDate('created_at', $date)->sum('scan_count'),
            ];
        }

        // En çok tıklanan linkler
        $topLinks = ShortLink::orderBy('click_count', 'desc')
            ->limit(5)
            ->get();

        // En çok taranan QR kodlar
        $topQrCodes = QrCode::orderBy('scan_count', 'desc')
            ->limit(5)
            ->get();

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

        return view('pages.dashboards.index', compact('stats', 'recentFiles', 'recentQrCodes', 'recentUsers', 'shortcuts', 'last7Days', 'topLinks', 'topQrCodes'));
    }
}
