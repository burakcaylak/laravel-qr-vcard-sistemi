<?php

namespace App\Http\Controllers;

use App\Models\ShortLink;
use App\Models\ShortLinkClick;
use App\Models\QrCode;
use App\Models\Brochure;
use App\Models\VCard;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AnalyticsController extends Controller
{
    /**
     * Genel Analytics Dashboard
     */
    public function index(Request $request)
    {
        $dateFrom = $request->input('date_from', Carbon::now()->subDays(30)->format('Y-m-d'));
        $dateTo = $request->input('date_to', Carbon::now()->format('Y-m-d'));
        $module = $request->input('module', 'all');

        // Genel istatistikler
        $stats = [
            'total_clicks' => ShortLinkClick::whereBetween('created_at', [$dateFrom, $dateTo . ' 23:59:59'])->count(),
            'total_qr_scans' => QrCode::whereBetween('created_at', [$dateFrom, $dateTo . ' 23:59:59'])->sum('scan_count'),
            'total_brochure_views' => Brochure::whereBetween('created_at', [$dateFrom, $dateTo . ' 23:59:59'])->sum('view_count'),
            'total_vcard_scans' => VCard::whereBetween('created_at', [$dateFrom, $dateTo . ' 23:59:59'])->sum('scan_count'),
        ];

        // Zaman bazlı grafik verileri
        $timeSeries = $this->getTimeSeriesData($dateFrom, $dateTo);

        // Coğrafi dağılım
        $geographicData = $this->getGeographicData($dateFrom, $dateTo);

        // Referrer analizi
        $referrerData = $this->getReferrerData($dateFrom, $dateTo);

        // Cihaz/Browser istatistikleri
        $deviceData = $this->getDeviceData($dateFrom, $dateTo);

        // En çok tıklanan linkler
        $topLinks = ShortLink::with('user')
            ->whereHas('clicks', function($query) use ($dateFrom, $dateTo) {
                $query->whereBetween('created_at', [$dateFrom, $dateTo . ' 23:59:59']);
            })
            ->withCount(['clicks' => function($query) use ($dateFrom, $dateTo) {
                $query->whereBetween('created_at', [$dateFrom, $dateTo . ' 23:59:59']);
            }])
            ->orderBy('clicks_count', 'desc')
            ->limit(10)
            ->get();

        // En çok taranan QR kodlar
        $topQrCodes = QrCode::with('user')
            ->whereBetween('created_at', [$dateFrom, $dateTo . ' 23:59:59'])
            ->orderBy('scan_count', 'desc')
            ->limit(10)
            ->get();

        return view('pages.analytics.index', compact(
            'stats',
            'timeSeries',
            'geographicData',
            'referrerData',
            'deviceData',
            'topLinks',
            'topQrCodes',
            'dateFrom',
            'dateTo',
            'module'
        ));
    }

    /**
     * ShortLink detaylı analizi
     */
    public function shortLink($id, Request $request)
    {
        $shortLink = ShortLink::with(['user', 'category'])->findOrFail($id);
        
        $dateFrom = $request->input('date_from', Carbon::now()->subDays(30)->format('Y-m-d'));
        $dateTo = $request->input('date_to', Carbon::now()->format('Y-m-d'));

        // Tıklama geçmişi
        $clicks = ShortLinkClick::where('short_link_id', $id)
            ->whereBetween('created_at', [$dateFrom, $dateTo . ' 23:59:59'])
            ->orderBy('created_at', 'desc')
            ->paginate(50);

        // Zaman bazlı grafik
        $timeSeries = ShortLinkClick::selectRaw('DATE(created_at) as date, COUNT(*) as count')
            ->where('short_link_id', $id)
            ->whereBetween('created_at', [$dateFrom, $dateTo . ' 23:59:59'])
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // Coğrafi dağılım
        $geographicData = ShortLinkClick::selectRaw('country, city, COUNT(*) as count')
            ->where('short_link_id', $id)
            ->whereBetween('created_at', [$dateFrom, $dateTo . ' 23:59:59'])
            ->whereNotNull('country')
            ->groupBy('country', 'city')
            ->orderBy('count', 'desc')
            ->limit(20)
            ->get();

        // Referrer analizi
        $referrerData = ShortLinkClick::selectRaw('referer, COUNT(*) as count')
            ->where('short_link_id', $id)
            ->whereBetween('created_at', [$dateFrom, $dateTo . ' 23:59:59'])
            ->whereNotNull('referer')
            ->groupBy('referer')
            ->orderBy('count', 'desc')
            ->limit(20)
            ->get();

        // Cihaz/Browser istatistikleri
        $deviceData = [
            'browsers' => ShortLinkClick::selectRaw('browser, COUNT(*) as count')
                ->where('short_link_id', $id)
                ->whereBetween('created_at', [$dateFrom, $dateTo . ' 23:59:59'])
                ->whereNotNull('browser')
                ->groupBy('browser')
                ->orderBy('count', 'desc')
                ->get(),
            'platforms' => ShortLinkClick::selectRaw('platform, COUNT(*) as count')
                ->where('short_link_id', $id)
                ->whereBetween('created_at', [$dateFrom, $dateTo . ' 23:59:59'])
                ->whereNotNull('platform')
                ->groupBy('platform')
                ->orderBy('count', 'desc')
                ->get(),
            'devices' => ShortLinkClick::selectRaw('device_type, COUNT(*) as count')
                ->where('short_link_id', $id)
                ->whereBetween('created_at', [$dateFrom, $dateTo . ' 23:59:59'])
                ->whereNotNull('device_type')
                ->groupBy('device_type')
                ->orderBy('count', 'desc')
                ->get(),
        ];

        return view('pages.analytics.short-link', compact(
            'shortLink',
            'clicks',
            'timeSeries',
            'geographicData',
            'referrerData',
            'deviceData',
            'dateFrom',
            'dateTo'
        ));
    }

    /**
     * QR Code analizi
     */
    public function qrCode($id, Request $request)
    {
        $qrCode = QrCode::with(['user', 'category'])->findOrFail($id);
        
        $dateFrom = $request->input('date_from', Carbon::now()->subDays(30)->format('Y-m-d'));
        $dateTo = $request->input('date_to', Carbon::now()->format('Y-m-d'));

        // Temel istatistikler
        $stats = [
            'total_scans' => $qrCode->scan_count,
            'total_downloads' => $qrCode->download_count,
            'created_at' => $qrCode->created_at,
        ];

        return view('pages.analytics.qr-code', compact('qrCode', 'stats', 'dateFrom', 'dateTo'));
    }

    /**
     * Brochure analizi
     */
    public function brochure($id, Request $request)
    {
        $brochure = Brochure::with(['user', 'category'])->findOrFail($id);
        
        $dateFrom = $request->input('date_from', Carbon::now()->subDays(30)->format('Y-m-d'));
        $dateTo = $request->input('date_to', Carbon::now()->format('Y-m-d'));

        // Temel istatistikler
        $stats = [
            'total_views' => $brochure->view_count,
            'total_downloads' => $brochure->download_count,
            'created_at' => $brochure->created_at,
        ];

        return view('pages.analytics.brochure', compact('brochure', 'stats', 'dateFrom', 'dateTo'));
    }

    /**
     * VCard analizi
     */
    public function vCard($id, Request $request)
    {
        $vCard = VCard::with(['user', 'category'])->findOrFail($id);
        
        $dateFrom = $request->input('date_from', Carbon::now()->subDays(30)->format('Y-m-d'));
        $dateTo = $request->input('date_to', Carbon::now()->format('Y-m-d'));

        // Temel istatistikler
        $stats = [
            'total_scans' => $vCard->scan_count,
            'created_at' => $vCard->created_at,
        ];

        return view('pages.analytics.v-card', compact('vCard', 'stats', 'dateFrom', 'dateTo'));
    }

    /**
     * Export - CSV
     */
    public function export(Request $request)
    {
        $type = $request->input('type', 'short-link');
        $id = $request->input('id');
        $dateFrom = $request->input('date_from', Carbon::now()->subDays(30)->format('Y-m-d'));
        $dateTo = $request->input('date_to', Carbon::now()->format('Y-m-d'));

        $filename = 'analytics_' . $type . '_' . date('Y-m-d') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function() use ($type, $id, $dateFrom, $dateTo) {
            $file = fopen('php://output', 'w');
            
            if ($type === 'short-link' && $id) {
                fputcsv($file, ['Date', 'IP Address', 'Country', 'City', 'Browser', 'Platform', 'Device', 'Referer']);
                
                ShortLinkClick::where('short_link_id', $id)
                    ->whereBetween('created_at', [$dateFrom, $dateTo . ' 23:59:59'])
                    ->orderBy('created_at', 'desc')
                    ->chunk(100, function($clicks) use ($file) {
                        foreach ($clicks as $click) {
                            fputcsv($file, [
                                $click->created_at->format('Y-m-d H:i:s'),
                                $click->ip_address,
                                $click->country ?? '-',
                                $click->city ?? '-',
                                $click->browser ?? '-',
                                $click->platform ?? '-',
                                $click->device_type ?? '-',
                                $click->referer ?? '-',
                            ]);
                        }
                    });
            }
            
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Zaman bazlı grafik verileri
     */
    private function getTimeSeriesData($dateFrom, $dateTo)
    {
        $days = Carbon::parse($dateFrom)->diffInDays(Carbon::parse($dateTo));
        $data = [];

        for ($i = 0; $i <= $days; $i++) {
            $date = Carbon::parse($dateFrom)->addDays($i)->format('Y-m-d');
            $data[] = [
                'date' => Carbon::parse($dateFrom)->addDays($i)->format('d M'),
                'clicks' => ShortLinkClick::whereDate('created_at', $date)->count(),
                'qr_scans' => QrCode::whereDate('created_at', $date)->sum('scan_count'),
                'brochure_views' => Brochure::whereDate('created_at', $date)->sum('view_count'),
                'vcard_scans' => VCard::whereDate('created_at', $date)->sum('scan_count'),
            ];
        }

        return $data;
    }

    /**
     * Coğrafi dağılım verileri
     */
    private function getGeographicData($dateFrom, $dateTo)
    {
        return ShortLinkClick::selectRaw('country, COUNT(*) as count')
            ->whereBetween('created_at', [$dateFrom, $dateTo . ' 23:59:59'])
            ->whereNotNull('country')
            ->groupBy('country')
            ->orderBy('count', 'desc')
            ->limit(20)
            ->get();
    }

    /**
     * Referrer verileri
     */
    private function getReferrerData($dateFrom, $dateTo)
    {
        return ShortLinkClick::selectRaw('referer, COUNT(*) as count')
            ->whereBetween('created_at', [$dateFrom, $dateTo . ' 23:59:59'])
            ->whereNotNull('referer')
            ->groupBy('referer')
            ->orderBy('count', 'desc')
            ->limit(20)
            ->get();
    }

    /**
     * Cihaz verileri
     */
    private function getDeviceData($dateFrom, $dateTo)
    {
        return [
            'browsers' => ShortLinkClick::selectRaw('browser, COUNT(*) as count')
                ->whereBetween('created_at', [$dateFrom, $dateTo . ' 23:59:59'])
                ->whereNotNull('browser')
                ->groupBy('browser')
                ->orderBy('count', 'desc')
                ->limit(10)
                ->get(),
            'platforms' => ShortLinkClick::selectRaw('platform, COUNT(*) as count')
                ->whereBetween('created_at', [$dateFrom, $dateTo . ' 23:59:59'])
                ->whereNotNull('platform')
                ->groupBy('platform')
                ->orderBy('count', 'desc')
                ->limit(10)
                ->get(),
            'devices' => ShortLinkClick::selectRaw('device_type, COUNT(*) as count')
                ->whereBetween('created_at', [$dateFrom, $dateTo . ' 23:59:59'])
                ->whereNotNull('device_type')
                ->groupBy('device_type')
                ->orderBy('count', 'desc')
                ->limit(10)
                ->get(),
        ];
    }
}
