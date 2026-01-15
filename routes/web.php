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
    
    // QR kod yönetimi (Rate limiting: 60 requests per minute)
    Route::name('qr-code.')->prefix('qr-code')->middleware(['throttle:60,1'])->group(function () {
        Route::get('/', [QrCodeController::class, 'index'])->name('index');
        Route::get('/create', [QrCodeController::class, 'create'])->name('create');
        Route::post('/', [QrCodeController::class, 'store'])->name('store');
        Route::get('/{qrCode}', [QrCodeController::class, 'show'])->name('show');
        Route::get('/{qrCode}/edit', [QrCodeController::class, 'edit'])->name('edit');
        Route::put('/{qrCode}', [QrCodeController::class, 'update'])->name('update');
        Route::get('/{qrCode}/download', [QrCodeController::class, 'download'])->name('download');
        Route::delete('/{qrCode}', [QrCodeController::class, 'destroy'])->name('destroy');
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

    // Kategori Yönetimi
    Route::resource('categories', CategoryController::class);

    // vCard Yönetimi (Rate limiting: 60 requests per minute)
    Route::name('v-card.')->prefix('v-card')->middleware(['throttle:60,1'])->group(function () {
        Route::get('/', [VCardController::class, 'index'])->name('index');
        Route::get('/create', [VCardController::class, 'create'])->name('create');
        Route::post('/', [VCardController::class, 'store'])->name('store');
        Route::get('/{vCard}', [VCardController::class, 'show'])->name('show');
        Route::get('/{vCard}/edit', [VCardController::class, 'edit'])->name('edit');
        Route::put('/{vCard}', [VCardController::class, 'update'])->name('update');
        Route::delete('/{vCard}', [VCardController::class, 'destroy'])->name('destroy');
        Route::get('/{vCard}/download', [VCardController::class, 'download'])->name('download');
    });

    // vCard Şablon Yönetimi (Rate limiting: 60 requests per minute)
    Route::name('v-card-template.')->prefix('v-card-template')->middleware(['throttle:60,1'])->group(function () {
        Route::get('/', [VCardTemplateController::class, 'index'])->name('index');
        Route::get('/create', [VCardTemplateController::class, 'create'])->name('create');
        Route::post('/', [VCardTemplateController::class, 'store'])->name('store');
        Route::get('/{vCardTemplate}', [VCardTemplateController::class, 'show'])->name('show');
        Route::get('/{vCardTemplate}/edit', [VCardTemplateController::class, 'edit'])->name('edit');
        Route::put('/{vCardTemplate}', [VCardTemplateController::class, 'update'])->name('update');
        Route::delete('/{vCardTemplate}', [VCardTemplateController::class, 'destroy'])->name('destroy');
    });

    // Kitapçık Yönetimi (Rate limiting: 60 requests per minute)
    Route::name('brochure.')->prefix('brochure')->middleware(['throttle:60,1'])->group(function () {
        Route::get('/', [BrochureController::class, 'index'])->name('index');
        Route::get('/create', [BrochureController::class, 'create'])->name('create');
        Route::post('/', [BrochureController::class, 'store'])->name('store');
        Route::get('/{brochure}', [BrochureController::class, 'show'])->name('show')->where('brochure', '[0-9]+');
        Route::get('/{brochure}/edit', [BrochureController::class, 'edit'])->name('edit')->where('brochure', '[0-9]+');
        Route::put('/{brochure}', [BrochureController::class, 'update'])->name('update')->where('brochure', '[0-9]+');
        Route::delete('/{brochure}', [BrochureController::class, 'destroy'])->name('destroy')->where('brochure', '[0-9]+');
        Route::get('/{brochure}/download', [BrochureController::class, 'download'])->name('download')->where('brochure', '[0-9]+');
    });

    // Activity Logs
    Route::get('/activity-logs', [ActivityLogController::class, 'index'])->name('activity-logs.index');

});

Route::get('/error', function () {
    abort(500);
});

Route::get('/auth/redirect/{provider}', [SocialiteController::class, 'redirect']);

// QR kod erişimi (herkese açık)
Route::get('/qr/{token}', [QrAccessController::class, 'access'])->name('qr.access');
Route::get('/qr/{token}/file/{fileId}', [QrAccessController::class, 'downloadFile'])->name('qr.access.file');

// vCard erişimi (herkese açık)
Route::get('/vcard/{token}', [VCardController::class, 'access'])->name('vcard.access');

// Kitapçık erişimi (herkese açık - flipbook görüntüleme) - ÖNCE TANIMLANMALI
Route::get('/brochure/{token}', [BrochureController::class, 'access'])->name('brochure.access')->where('token', '[a-f0-9]{8}-[a-f0-9]{4}-[a-f0-9]{4}-[a-f0-9]{4}-[a-f0-9]{12}');
Route::match(['get', 'options'], '/brochure/{token}/pdf', [BrochureController::class, 'pdf'])->name('brochure.pdf')->where('token', '[a-f0-9]{8}-[a-f0-9]{4}-[a-f0-9]{4}-[a-f0-9]{4}-[a-f0-9]{12}');

require __DIR__ . '/auth.php';
