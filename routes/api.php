<?php

use App\Actions\SamplePermissionApi;
use App\Actions\SampleRoleApi;
use App\Actions\SampleUserApi;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::prefix('v1')->group(function () {

    Route::get('/users', function (Request $request) {
        return app(SampleUserApi::class)->datatableList($request);
    });

    Route::post('/users-list', function (Request $request) {
        return app(SampleUserApi::class)->datatableList($request);
    });

    Route::post('/users', function (Request $request) {
        return app(SampleUserApi::class)->create($request);
    });

    Route::get('/users/{id}', function ($id) {
        return app(SampleUserApi::class)->get($id);
    });

    Route::put('/users/{id}', function ($id, Request $request) {
        return app(SampleUserApi::class)->update($id, $request);
    });

    Route::delete('/users/{id}', function ($id) {
        return app(SampleUserApi::class)->delete($id);
    });


    Route::get('/roles', function (Request $request) {
        return app(SampleRoleApi::class)->datatableList($request);
    });

    Route::post('/roles-list', function (Request $request) {
        return app(SampleRoleApi::class)->datatableList($request);
    });

    Route::post('/roles', function (Request $request) {
        return app(SampleRoleApi::class)->create($request);
    });

    Route::get('/roles/{id}', function ($id) {
        return app(SampleRoleApi::class)->get($id);
    });

    Route::put('/roles/{id}', function ($id, Request $request) {
        return app(SampleRoleApi::class)->update($id, $request);
    });

    Route::delete('/roles/{id}', function ($id) {
        return app(SampleRoleApi::class)->delete($id);
    });

    Route::post('/roles/{id}/users', function (Request $request, $id) {
        $request->merge(['id' => $id]);
        return app(SampleRoleApi::class)->usersDatatableList($request);
    });

    Route::delete('/roles/{id}/users/{user_id}', function ($id, $user_id) {
        return app(SampleRoleApi::class)->deleteUser($id, $user_id);
    });



    Route::get('/permissions', function (Request $request) {
        return app(SamplePermissionApi::class)->datatableList($request);
    });

    Route::post('/permissions-list', function (Request $request) {
        return app(SamplePermissionApi::class)->datatableList($request);
    });

    Route::post('/permissions', function (Request $request) {
        return app(SamplePermissionApi::class)->create($request);
    });

    Route::get('/permissions/{id}', function ($id) {
        return app(SamplePermissionApi::class)->get($id);
    });

    Route::put('/permissions/{id}', function ($id, Request $request) {
        return app(SamplePermissionApi::class)->update($id, $request);
    });

    Route::delete('/permissions/{id}', function ($id) {
        return app(SamplePermissionApi::class)->delete($id);
    });

    // Short Link API
    Route::middleware('auth:sanctum')->prefix('short-links')->group(function () {
        Route::get('/', [\App\Http\Controllers\Api\ShortLinkApiController::class, 'index']);
        Route::post('/', [\App\Http\Controllers\Api\ShortLinkApiController::class, 'store']);
        Route::get('/{id}', [\App\Http\Controllers\Api\ShortLinkApiController::class, 'show']);
        Route::put('/{id}', [\App\Http\Controllers\Api\ShortLinkApiController::class, 'update']);
        Route::delete('/{id}', [\App\Http\Controllers\Api\ShortLinkApiController::class, 'destroy']);
        Route::get('/{id}/stats', [\App\Http\Controllers\Api\ShortLinkApiController::class, 'stats']);
    });

    // QR Code API
    Route::middleware('auth:sanctum')->prefix('qr-codes')->group(function () {
        Route::get('/', [\App\Http\Controllers\Api\QrCodeApiController::class, 'index']);
        Route::post('/', [\App\Http\Controllers\Api\QrCodeApiController::class, 'store']);
        Route::get('/{id}', [\App\Http\Controllers\Api\QrCodeApiController::class, 'show']);
        Route::put('/{id}', [\App\Http\Controllers\Api\QrCodeApiController::class, 'update']);
        Route::delete('/{id}', [\App\Http\Controllers\Api\QrCodeApiController::class, 'destroy']);
        Route::get('/{id}/stats', [\App\Http\Controllers\Api\QrCodeApiController::class, 'stats']);
    });

    // Brochure API
    Route::middleware('auth:sanctum')->prefix('brochures')->group(function () {
        Route::get('/', [\App\Http\Controllers\Api\BrochureApiController::class, 'index']);
        Route::post('/', [\App\Http\Controllers\Api\BrochureApiController::class, 'store']);
        Route::get('/{id}', [\App\Http\Controllers\Api\BrochureApiController::class, 'show']);
        Route::put('/{id}', [\App\Http\Controllers\Api\BrochureApiController::class, 'update']);
        Route::delete('/{id}', [\App\Http\Controllers\Api\BrochureApiController::class, 'destroy']);
        Route::get('/{id}/stats', [\App\Http\Controllers\Api\BrochureApiController::class, 'stats']);
    });

    // VCard API
    Route::middleware('auth:sanctum')->prefix('vcards')->group(function () {
        Route::get('/', [\App\Http\Controllers\Api\VCardApiController::class, 'index']);
        Route::post('/', [\App\Http\Controllers\Api\VCardApiController::class, 'store']);
        Route::get('/{id}', [\App\Http\Controllers\Api\VCardApiController::class, 'show']);
        Route::put('/{id}', [\App\Http\Controllers\Api\VCardApiController::class, 'update']);
        Route::delete('/{id}', [\App\Http\Controllers\Api\VCardApiController::class, 'destroy']);
        Route::get('/{id}/stats', [\App\Http\Controllers\Api\VCardApiController::class, 'stats']);
    });

    // File API
    Route::middleware('auth:sanctum')->prefix('files')->group(function () {
        Route::get('/', [\App\Http\Controllers\Api\FileApiController::class, 'index']);
        Route::post('/', [\App\Http\Controllers\Api\FileApiController::class, 'store']);
        Route::get('/{id}', [\App\Http\Controllers\Api\FileApiController::class, 'show']);
        Route::put('/{id}', [\App\Http\Controllers\Api\FileApiController::class, 'update']);
        Route::delete('/{id}', [\App\Http\Controllers\Api\FileApiController::class, 'destroy']);
        Route::get('/{id}/download', [\App\Http\Controllers\Api\FileApiController::class, 'download']);
    });
});
