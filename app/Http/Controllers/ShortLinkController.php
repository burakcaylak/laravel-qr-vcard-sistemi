<?php

namespace App\Http\Controllers;

use App\DataTables\ShortLinksDataTable;
use App\Http\Requests\ShortLinkRequest;
use App\Models\ShortLink;
use App\Models\ShortLinkClick;
use App\Models\Category;
use App\Helpers\ActivityLogHelper;
use App\Helpers\UserAgentHelper;
use App\Helpers\CacheHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class ShortLinkController extends Controller
{
    public function index(ShortLinksDataTable $dataTable)
    {
        return $dataTable->render('pages.short-link.list');
    }

    public function create()
    {
        $categories = CacheHelper::getActiveCategories();

        return view('pages.short-link.create', compact('categories'));
    }

    public function store(ShortLinkRequest $request)
    {
        $data = $request->validated();
        $data['user_id'] = auth()->id();

        if (!$request->has('is_active')) {
            $data['is_active'] = false;
        } else {
            $data['is_active'] = (bool) $request->input('is_active');
        }

        // Password protection kontrolü
        // Checkbox gönderilmediğinde false olarak kabul et (Laravel checkbox göndermezse)
        $passwordProtected = $request->has('password_protected') && $request->input('password_protected');
        
        if ($passwordProtected) {
            $data['password_protected'] = true;
            // Şifre varsa hash'le
            if (!empty($data['password'])) {
                $data['password'] = Hash::make($data['password']);
            } else {
                // Şifre korumalı ama şifre girilmemiş
                return back()->withErrors(['password' => __('common.password_required_when_protected')]);
            }
        } else {
            // Password protection kapalı
            $data['password_protected'] = false;
            $data['password'] = null;
        }

        // QR kod ayarları
        $data['qr_code_size'] = $request->input('qr_code_size', 300);
        $data['qr_code_format'] = $request->input('qr_code_format', 'png');

        if (empty($data['short_code'])) {
            $data['short_code'] = ShortLink::generateUniqueCode();
        }

        $shortLink = ShortLink::create($data);

        Log::info('Short link created', [
            'user_id' => auth()->id(),
            'short_link_id' => $shortLink->id,
            'short_code' => $shortLink->short_code,
            'ip' => request()->ip(),
        ]);

        ActivityLogHelper::logShortLink('created', $shortLink);

        return redirect()->route('short-link.show', $shortLink)
            ->with('success', __('common.short_link_created'));
    }

    public function show(ShortLink $shortLink)
    {
        $this->authorize('view', $shortLink);
        
        // QR kod görseli yoksa oluştur
        if (!$shortLink->qr_code_path || !Storage::disk('public')->exists($shortLink->qr_code_path)) {
            try {
                $shortLink->generateQrCode();
                $shortLink->refresh();
            } catch (\Exception $e) {
                \Log::error('QR code image generation failed in show method', [
                    'short_link_id' => $shortLink->id,
                    'error' => $e->getMessage()
                ]);
            }
        }
        
        $shortLink->load(['clicks' => function($query) {
            $query->latest()->limit(100);
        }, 'history' => function($query) {
            $query->latest()->limit(50);
        }]);
        
        return view('pages.short-link.show', compact('shortLink'));
    }

    public function edit(ShortLink $shortLink)
    {
        $this->authorize('update', $shortLink);
        
        $categories = CacheHelper::getActiveCategories();

        return view('pages.short-link.edit', compact('shortLink', 'categories'));
    }

    public function update(ShortLinkRequest $request, ShortLink $shortLink)
    {
        $this->authorize('update', $shortLink);

        $data = $request->validated();

        if (!$request->has('is_active')) {
            $data['is_active'] = false;
        } else {
            $data['is_active'] = (bool) $request->input('is_active');
        }

        // Password protection kontrolü
        // Checkbox gönderilmediğinde false olarak kabul et (Laravel checkbox göndermezse)
        $passwordProtected = $request->has('password_protected') && $request->input('password_protected');
        
        if ($passwordProtected) {
            $data['password_protected'] = true;
            // Şifre varsa hash'le
            if (!empty($data['password'])) {
                $data['password'] = Hash::make($data['password']);
            } elseif (empty($shortLink->password)) {
                // Şifre korumalı ama şifre girilmemiş ve mevcut şifre yoksa hata
                return back()->withErrors(['password' => __('common.password_required_when_protected')]);
            } else {
                // Şifre değiştirilmemiş, mevcut şifreyi koru
                unset($data['password']);
            }
        } else {
            // Password protection kapalı
            $data['password_protected'] = false;
            // Şifre koruması kapatılıyorsa şifreyi ve session'ı temizle
            if ($shortLink->password_protected) {
                $data['password'] = null;
                // Session'ı temizle
                $sessionKey = 'short_link_' . $shortLink->short_code . '_verified';
                session()->forget($sessionKey);
            } else {
                // Şifre koruması zaten kapalıydı, şifre alanını değiştirme
                unset($data['password']);
            }
        }

        // QR kod ayarları
        if ($request->has('qr_code_size')) {
            $data['qr_code_size'] = $request->input('qr_code_size');
        }
        if ($request->has('qr_code_format')) {
            $data['qr_code_format'] = $request->input('qr_code_format');
        }

        $shortLink->update($data);
        
        // Model'i yeniden yükle
        $shortLink->refresh();

        // QR kod yeniden oluştur
        if ($request->has('regenerate_qr')) {
            $shortLink->generateQrCode();
        }

        Log::info('Short link updated', [
            'user_id' => auth()->id(),
            'short_link_id' => $shortLink->id,
            'ip' => request()->ip(),
        ]);

        ActivityLogHelper::logShortLink('updated', $shortLink);

        return redirect()->route('short-link.show', $shortLink)
            ->with('success', __('common.short_link_updated'));
    }

    public function destroy(ShortLink $shortLink)
    {
        $this->authorize('delete', $shortLink);

        $shortLink->delete();

        Log::info('Short link deleted', [
            'user_id' => auth()->id(),
            'short_link_id' => $shortLink->id,
            'ip' => request()->ip(),
        ]);

        ActivityLogHelper::logShortLink('deleted', $shortLink);

        return redirect()->route('short-link.index')
            ->with('success', __('common.short_link_deleted'));
    }

    public function redirect($shortCode)
    {
        $shortLink = ShortLink::where('short_code', $shortCode)
            ->where('is_active', true)
            ->first();

        if (!$shortLink) {
            abort(404, __('common.short_link_not_found'));
        }

        // Model'i veritabanından yeniden yükle (cache sorununu önlemek için)
        $shortLink->refresh();

        if ($shortLink->is_expired) {
            abort(410, __('common.short_link_expired'));
    }

        // Şifre kontrolü
        if ($shortLink->password_protected) {
            $sessionKey = 'short_link_' . $shortCode . '_verified';
            if (!session()->has($sessionKey)) {
                return redirect()->route('short-link.password', $shortCode);
            }
        }

        // Tıklama kaydı oluştur
        $this->recordClick($shortLink);

        return redirect($shortLink->original_url);
    }

    public function preview($shortCode)
    {
        $shortLink = ShortLink::where('short_code', $shortCode)
            ->where('is_active', true)
            ->first();

        if (!$shortLink) {
            abort(404, __('common.short_link_not_found'));
        }

        if ($shortLink->is_expired) {
            abort(410, __('common.short_link_expired'));
        }

        return view('pages.short-link.preview', compact('shortLink'));
    }

    public function password($shortCode)
    {
        $shortLink = ShortLink::where('short_code', $shortCode)
            ->where('is_active', true)
            ->first();

        if (!$shortLink) {
            abort(404, __('common.short_link_not_found'));
        }

        if (!$shortLink->password_protected) {
            return redirect()->route('short-link.redirect', $shortCode);
        }

        return view('pages.short-link.password', compact('shortLink'));
    }

    public function verifyPassword(Request $request, $shortCode)
    {
        $request->validate([
            'password' => 'required|string',
        ]);

        $shortLink = ShortLink::where('short_code', $shortCode)
            ->where('is_active', true)
            ->first();

        if (!$shortLink) {
            abort(404, __('common.short_link_not_found'));
        }

        if ($shortLink->verifyPassword($request->password)) {
            // Şifre doğru, session'a kaydet
            session(['short_link_' . $shortCode . '_verified' => true]);
            // Redirect metoduna yönlendir (orada tıklama kaydı yapılacak ve session temizlenecek)
            return redirect()->route('short-link.redirect', $shortCode);
        }

        return back()->withErrors(['password' => __('common.invalid_password')]);
    }

    public function downloadQr(ShortLink $shortLink)
    {
        $this->authorize('view', $shortLink);

        if (!$shortLink->qr_code_path || !Storage::disk('public')->exists($shortLink->qr_code_path)) {
            $shortLink->generateQrCode();
        }

        $filePath = Storage::disk('public')->path($shortLink->qr_code_path);
        $fileName = 'qr-code-' . $shortLink->short_code . '.' . $shortLink->qr_code_format;

        return response()->download($filePath, $fileName);
    }

    public function bulkAction(Request $request)
    {
        $request->validate([
            'action' => 'required|in:delete,activate,deactivate',
            'ids' => 'required|array',
            'ids.*' => 'exists:short_links,id',
        ]);

        $ids = $request->ids;
        $action = $request->action;

        DB::beginTransaction();
        try {
            switch ($action) {
                case 'delete':
                    ShortLink::whereIn('id', $ids)
                        ->where('user_id', auth()->id())
                        ->delete();
                    break;
                case 'activate':
                    ShortLink::whereIn('id', $ids)
                        ->where('user_id', auth()->id())
                        ->update(['is_active' => true]);
                    break;
                case 'deactivate':
                    ShortLink::whereIn('id', $ids)
                        ->where('user_id', auth()->id())
                        ->update(['is_active' => false]);
                    break;
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => __('common.bulk_action_success'),
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => __('common.bulk_action_error'),
            ], 500);
        }
    }

    public function export(Request $request)
    {
        $shortLinks = ShortLink::where('user_id', auth()->id())
            ->with('category')
            ->get();

        $filename = 'short-links-' . date('Y-m-d-His') . '.csv';
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function() use ($shortLinks) {
            $file = fopen('php://output', 'w');
            
            // BOM for Excel UTF-8 support
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));
            
            // Headers
            fputcsv($file, [
                __('common.short_code'),
                __('common.original_url'),
                __('common.title'),
                __('common.category'),
                __('common.click_count'),
                __('common.status'),
                __('common.created_at'),
            ]);

            // Data
            foreach ($shortLinks as $link) {
                fputcsv($file, [
                    $link->short_code,
                    $link->original_url,
                    $link->title ?? '',
                    $link->category ? $link->category->name : '',
                    $link->click_count,
                    $link->is_active ? __('common.active') : __('common.inactive'),
                    $link->created_at->format('Y-m-d H:i:s'),
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    private function recordClick(ShortLink $shortLink)
    {
        $shortLink->incrementClickCount();

        $userAgent = request()->userAgent();
        $parsed = UserAgentHelper::parse($userAgent);

        ShortLinkClick::create([
            'short_link_id' => $shortLink->id,
            'ip_address' => request()->ip(),
            'user_agent' => $userAgent,
            'referer' => request()->header('referer'),
            'browser' => $parsed['browser'],
            'platform' => $parsed['platform'],
            'device_type' => $parsed['device_type'],
        ]);

        Log::info('Short link accessed', [
            'short_link_id' => $shortLink->id,
            'short_code' => $shortLink->short_code,
            'ip' => request()->ip(),
            'user_agent' => $userAgent,
        ]);
    }
}
