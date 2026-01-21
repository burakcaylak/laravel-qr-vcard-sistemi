<?php

use App\Http\Controllers\Apps\PermissionManagementController;
use App\Http\Controllers\Apps\RoleManagementController;
use App\Http\Controllers\Apps\UserManagementController;
use App\Http\Controllers\Auth\SocialiteController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\FileManagementController;
use App\Http\Controllers\QrCodeController;
use App\Http\Controllers\QrAccessController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\MediaLibraryController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\IndexController;
use App\Http\Controllers\ActivityLogController;
use App\Http\Controllers\VCardController;
use App\Http\Controllers\VCardTemplateController;
use App\Http\Controllers\BrochureController;
use App\Http\Controllers\ShortLinkController;
use App\Http\Controllers\AnalyticsController;
use App\Http\Controllers\ApiTokenController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Public index page
Route::get('/', [IndexController::class, 'index'])->name('index');

Route::middleware(['auth'])->group(function () {

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Analytics & Reporting (Rate limiting: 60 requests per minute)
    Route::name('analytics.')->prefix('analytics')->middleware(['throttle:analytics'])->group(function () {
        Route::get('/', [AnalyticsController::class, 'index'])->name('index');
        Route::get('/short-link/{id}', [AnalyticsController::class, 'shortLink'])->name('short-link');
        Route::get('/qr-code/{id}', [AnalyticsController::class, 'qrCode'])->name('qr-code');
        Route::get('/brochure/{id}', [AnalyticsController::class, 'brochure'])->name('brochure');
        Route::get('/v-card/{id}', [AnalyticsController::class, 'vCard'])->name('v-card');
        Route::get('/export', [AnalyticsController::class, 'export'])->name('export');
    });

    Route::name('user-management.')->group(function () {
        Route::resource('/user-management/users', UserManagementController::class);
    });

    // Dosya yönetimi
    // File Management route'ları Media Library'ye yönlendiriliyor
    Route::get('file-management', function() {
        return redirect()->route('media-library.index');
    })->name('file-management.index');
    
    Route::get('file-management/create', function() {
        return redirect()->route('media-library.index');
    })->name('file-management.create');
    
    Route::get('file-management/{file}', function($file) {
        if (is_numeric($file)) {
            return redirect()->route('media-library.show', $file);
        }
        return redirect()->route('media-library.index');
    })->name('file-management.show');
    
    Route::get('file-management/{file}/edit', function($file) {
        if (is_numeric($file)) {
            return redirect()->route('media-library.edit', $file);
        }
        return redirect()->route('media-library.index');
    })->name('file-management.edit');
    
    Route::get('file-management/{file}/download', [FileManagementController::class, 'download'])->name('file-management.download');
    Route::post('file-management/create-from-path', [FileManagementController::class, 'createFromPath'])->name('file-management.create-from-path');
    Route::post('file-management/bulk-action', [FileManagementController::class, 'bulkAction'])->name('file-management.bulk-action');
    
    // QR kod yönetimi (Rate limiting: 120 requests per minute)
    Route::name('qr-code.')->prefix('qr-code')->middleware(['throttle:web'])->group(function () {
        Route::get('/', [QrCodeController::class, 'index'])->name('index');
        Route::get('/create', [QrCodeController::class, 'create'])->name('create');
        Route::post('/', [QrCodeController::class, 'store'])->name('store');
        Route::get('/{qrCode}', [QrCodeController::class, 'show'])->name('show');
        Route::get('/{qrCode}/edit', [QrCodeController::class, 'edit'])->name('edit');
        Route::put('/{qrCode}', [QrCodeController::class, 'update'])->name('update');
        Route::get('/{qrCode}/download', [QrCodeController::class, 'download'])->name('download');
        Route::delete('/{qrCode}', [QrCodeController::class, 'destroy'])->name('destroy');
        Route::post('/bulk-action', [QrCodeController::class, 'bulkAction'])->name('bulk-action');
    });

    // Ortam Kütüphanesi (Rate limiting: 60 requests per minute)
    Route::middleware(['throttle:60,1'])->group(function () {
        Route::get('/media-library', [MediaLibraryController::class, 'index'])->name('media-library.index');
        Route::post('/media-library/check-file', [MediaLibraryController::class, 'checkFile'])->name('media-library.check-file');
        Route::post('/media-library', [MediaLibraryController::class, 'store'])->name('media-library.store');
    });
    Route::get('/media-library/{id}', [MediaLibraryController::class, 'show'])->name('media-library.show');
    Route::get('/media-library/{id}/edit', [MediaLibraryController::class, 'edit'])->name('media-library.edit');
    Route::match(['get', 'options'], '/media-library/{id}/download', [MediaLibraryController::class, 'download'])->name('media-library.download');
    Route::put('/media-library/{id}', [MediaLibraryController::class, 'update'])->name('media-library.update');
    Route::delete('/media-library/{path}', [MediaLibraryController::class, 'destroy'])->name('media-library.destroy')->where('path', '.*');

    // Ayarlar
    Route::get('/settings', [SettingsController::class, 'index'])->name('settings.index');
    Route::put('/settings', [SettingsController::class, 'update'])->name('settings.update');

    // API Token Yönetimi
    Route::name('api-tokens.')->prefix('api-tokens')->group(function () {
        Route::get('/', [ApiTokenController::class, 'index'])->name('index');
        Route::post('/', [ApiTokenController::class, 'store'])->name('store');
        Route::delete('/{id}', [ApiTokenController::class, 'destroy'])->name('destroy');
        Route::delete('/', [ApiTokenController::class, 'destroyAll'])->name('destroy-all');
    });

    // Kategori Yönetimi
    Route::resource('categories', CategoryController::class);

    // vCard Yönetimi (Rate limiting: 120 requests per minute)
    Route::name('v-card.')->prefix('v-card')->middleware(['throttle:web'])->group(function () {
        Route::get('/', [VCardController::class, 'index'])->name('index');
        Route::get('/create', [VCardController::class, 'create'])->name('create');
        Route::post('/', [VCardController::class, 'store'])->name('store');
        Route::get('/{vCard}', [VCardController::class, 'show'])->name('show');
        Route::get('/{vCard}/edit', [VCardController::class, 'edit'])->name('edit');
        Route::put('/{vCard}', [VCardController::class, 'update'])->name('update');
        Route::delete('/{vCard}', [VCardController::class, 'destroy'])->name('destroy');
        Route::get('/{vCard}/download', [VCardController::class, 'download'])->name('download');
        Route::post('/bulk-action', [VCardController::class, 'bulkAction'])->name('bulk-action');
    });

    // vCard Şablon Yönetimi (Rate limiting: 120 requests per minute)
    Route::name('v-card-template.')->prefix('v-card-template')->middleware(['throttle:web'])->group(function () {
        Route::get('/', [VCardTemplateController::class, 'index'])->name('index');
        Route::get('/create', [VCardTemplateController::class, 'create'])->name('create');
        Route::post('/', [VCardTemplateController::class, 'store'])->name('store');
        Route::get('/{vCardTemplate}', [VCardTemplateController::class, 'show'])->name('show');
        Route::get('/{vCardTemplate}/edit', [VCardTemplateController::class, 'edit'])->name('edit');
        Route::put('/{vCardTemplate}', [VCardTemplateController::class, 'update'])->name('update');
        Route::delete('/{vCardTemplate}', [VCardTemplateController::class, 'destroy'])->name('destroy');
    });

    // Kitapçık Yönetimi (Rate limiting: 120 requests per minute)
    Route::name('brochure.')->prefix('brochure')->middleware(['throttle:web'])->group(function () {
        Route::get('/', [BrochureController::class, 'index'])->name('index');
        Route::get('/create', [BrochureController::class, 'create'])->name('create');
        Route::post('/', [BrochureController::class, 'store'])->name('store');
        Route::get('/{brochure}', [BrochureController::class, 'show'])->name('show')->where('brochure', '[0-9]+');
        Route::get('/{brochure}/edit', [BrochureController::class, 'edit'])->name('edit')->where('brochure', '[0-9]+');
        Route::put('/{brochure}', [BrochureController::class, 'update'])->name('update')->where('brochure', '[0-9]+');
        Route::delete('/{brochure}', [BrochureController::class, 'destroy'])->name('destroy')->where('brochure', '[0-9]+');
        Route::get('/{brochure}/download', [BrochureController::class, 'download'])->name('download')->where('brochure', '[0-9]+');
        Route::post('/bulk-action', [BrochureController::class, 'bulkAction'])->name('bulk-action');
    });

    // Activity Logs
    Route::get('/activity-logs', [ActivityLogController::class, 'index'])->name('activity-logs.index');

    // Link Kısaltma Yönetimi (Rate limiting: 120 requests per minute)
    Route::name('short-link.')->prefix('short-link')->middleware(['throttle:web'])->group(function () {
        Route::get('/', [ShortLinkController::class, 'index'])->name('index');
        Route::get('/create', [ShortLinkController::class, 'create'])->name('create');
        Route::post('/', [ShortLinkController::class, 'store'])->name('store');
        Route::get('/{shortLink}', [ShortLinkController::class, 'show'])->name('show');
        Route::get('/{shortLink}/edit', [ShortLinkController::class, 'edit'])->name('edit');
        Route::put('/{shortLink}', [ShortLinkController::class, 'update'])->name('update');
        Route::delete('/{shortLink}', [ShortLinkController::class, 'destroy'])->name('destroy');
        Route::get('/{shortLink}/qr/download', [ShortLinkController::class, 'downloadQr'])->name('qr.download');
        Route::post('/bulk-action', [ShortLinkController::class, 'bulkAction'])->name('bulk-action');
        Route::get('/export/csv', [ShortLinkController::class, 'export'])->name('export');
    });

});

Route::get('/error', function () {
    abort(500);
});

Route::get('/auth/redirect/{provider}', [SocialiteController::class, 'redirect']);

// QR kod erişimi (herkese açık - Rate limiting: 100 requests per minute)
Route::middleware(['throttle:public-access'])->group(function () {
    Route::get('/qr/{token}', [QrAccessController::class, 'access'])->name('qr.access');
    Route::get('/qr/{token}/password', [QrAccessController::class, 'password'])->name('qr.password');
    Route::post('/qr/{token}/verify', [QrAccessController::class, 'verifyPassword'])->name('qr.verify');
    Route::get('/qr/{token}/file/{fileId}', [QrAccessController::class, 'downloadFile'])->name('qr.access.file');
});

// vCard erişimi (herkese açık - Rate limiting: 100 requests per minute)
Route::middleware(['throttle:public-access'])->group(function () {
    Route::get('/vcard/{token}', [VCardController::class, 'access'])->name('vcard.access');
});

// Kitapçık erişimi (herkese açık - flipbook görüntüleme - Rate limiting: 100 requests per minute)
Route::middleware(['throttle:public-access'])->group(function () {
    Route::get('/brochure/{token}', [BrochureController::class, 'access'])->name('brochure.access')->where('token', '[a-f0-9]{8}-[a-f0-9]{4}-[a-f0-9]{4}-[a-f0-9]{4}-[a-f0-9]{12}');
    Route::get('/brochure/{token}/password', [BrochureController::class, 'password'])->name('brochure.password')->where('token', '[a-f0-9]{8}-[a-f0-9]{4}-[a-f0-9]{4}-[a-f0-9]{4}-[a-f0-9]{12}');
    Route::post('/brochure/{token}/verify', [BrochureController::class, 'verifyPassword'])->name('brochure.verify')->where('token', '[a-f0-9]{8}-[a-f0-9]{4}-[a-f0-9]{4}-[a-f0-9]{4}-[a-f0-9]{12}');
    Route::match(['get', 'options'], '/brochure/{token}/pdf', [BrochureController::class, 'pdf'])->name('brochure.pdf')->where('token', '[a-f0-9]{8}-[a-f0-9]{4}-[a-f0-9]{4}-[a-f0-9]{4}-[a-f0-9]{12}');
});

// Link kısaltma erişimi (herkese açık - Rate limiting: 100 requests per minute)
Route::middleware(['throttle:public-access'])->group(function () {
    Route::get('/l/{shortCode}', [ShortLinkController::class, 'redirect'])->name('short-link.redirect');
    Route::get('/l/{shortCode}/preview', [ShortLinkController::class, 'preview'])->name('short-link.preview');
    Route::get('/l/{shortCode}/password', [ShortLinkController::class, 'password'])->name('short-link.password');
    Route::post('/l/{shortCode}/password', [ShortLinkController::class, 'verifyPassword'])->name('short-link.verify-password');
});

require __DIR__ . '/auth.php';
