<?php

namespace App\Http\Controllers;

use App\Http\Requests\SettingsRequest;
use App\Models\Settings;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;
use Illuminate\Support\Str;

class SettingsController extends Controller
{
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            // Sadece superadmin ayarlara erişebilir
            if (!auth()->user()->hasRole('superadmin')) {
                abort(403, __('common.unauthorized_action'));
            }
            return $next($request);
        });
    }

    public function index()
    {
        $settings = Settings::getSettings();
        $categories = \Illuminate\Support\Facades\Cache::remember('categories.active', 3600, function () {
            return \App\Models\Category::where('is_active', true)
                ->orderBy('sort_order')
                ->orderBy('name')
                ->get();
        });
        return view('pages.settings.index', compact('settings', 'categories'));
    }

    public function update(SettingsRequest $request)
    {
        $settings = Settings::getSettings();

        // Logo Light - Önce path kontrolü, sonra file upload
        if ($request->filled('logo_light_path')) {
            // Media library'den seçilmiş
            $settings->logo_light = $request->input('logo_light_path');
        } elseif ($request->hasFile('logo_light')) {
            // Yeni dosya yüklenmiş
            if ($settings->logo_light) {
                Storage::disk('public')->delete($settings->logo_light);
            }
            $logoLight = $request->file('logo_light');
            $logoLightPath = $this->processImage($logoLight, 'settings', 'logo-light');
            $settings->logo_light = $logoLightPath;
        }

        // Logo Dark - Önce path kontrolü, sonra file upload
        if ($request->filled('logo_dark_path')) {
            // Media library'den seçilmiş
            $settings->logo_dark = $request->input('logo_dark_path');
        } elseif ($request->hasFile('logo_dark')) {
            // Yeni dosya yüklenmiş
            if ($settings->logo_dark) {
                Storage::disk('public')->delete($settings->logo_dark);
            }
            $logoDark = $request->file('logo_dark');
            $logoDarkPath = $this->processImage($logoDark, 'settings', 'logo-dark');
            $settings->logo_dark = $logoDarkPath;
        }

        // Favicon - Önce path kontrolü, sonra file upload
        if ($request->filled('favicon_path')) {
            // Media library'den seçilmiş
            $settings->favicon = $request->input('favicon_path');
        } elseif ($request->hasFile('favicon')) {
            // Yeni dosya yüklenmiş
            if ($settings->favicon) {
                Storage::disk('public')->delete($settings->favicon);
            }
            $favicon = $request->file('favicon');
            $faviconPath = $this->processImage($favicon, 'settings', 'favicon');
            $settings->favicon = $faviconPath;
        }

        // Login Image - Önce path kontrolü, sonra file upload
        if ($request->filled('login_image_path')) {
            // Media library'den seçilmiş
            $settings->login_image = $request->input('login_image_path');
        } elseif ($request->hasFile('login_image')) {
            // Yeni dosya yüklenmiş
            if ($settings->login_image) {
                Storage::disk('public')->delete($settings->login_image);
            }
            $loginImage = $request->file('login_image');
            $loginImagePath = $this->processImage($loginImage, 'settings', 'login-image');
            $settings->login_image = $loginImagePath;
        }

        $settings->index_enabled = $request->has('index_enabled') && $request->input('index_enabled') == '1';
        $settings->language = $request->input('language', 'tr');
        $settings->footer_text = $request->input('footer_text');
        $settings->save();
        
        // Cache'i temizle
        Settings::clearCache();

        return redirect()->route('settings.index')->with('success', __('common.settings_updated'));
    }

    /**
     * Process image: convert to webp if not SVG, keep original name
     */
    private function processImage($file, $folder, $prefix)
    {
        $originalName = $file->getClientOriginalName();
        $extension = strtolower($file->getClientOriginalExtension());
        
        // SVG dosyalarını olduğu gibi kaydet
        if ($extension === 'svg') {
            $path = $file->storeAs($folder, $originalName, 'public');
            return $path;
        }
        
        // Diğer görselleri webp'ye çevir
        try {
            $manager = new ImageManager(new Driver());
            $image = $manager->read($file->getRealPath());
            
            // Orijinal ismin uzantısını webp ile değiştir
            $nameWithoutExtension = pathinfo($originalName, PATHINFO_FILENAME);
            $webpName = $nameWithoutExtension . '.webp';
            $webpPath = $folder . '/' . $webpName;
            $fullPath = storage_path('app/public/' . $webpPath);
            
            // Klasör yoksa oluştur
            $directory = dirname($fullPath);
            if (!is_dir($directory)) {
                mkdir($directory, 0755, true);
            }
            
            // Webp olarak kaydet (90 kalite)
            $image->toWebp(90)->save($fullPath);
            
            return $webpPath;
        } catch (\Exception $e) {
            // Hata durumunda orijinal dosyayı kaydet
            $path = $file->storeAs($folder, $originalName, 'public');
            return $path;
        }
    }
}
