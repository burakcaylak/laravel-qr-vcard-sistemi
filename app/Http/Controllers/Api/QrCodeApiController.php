<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\QrCode;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

class QrCodeApiController extends Controller
{
    public function index(Request $request)
    {
        $query = QrCode::where('user_id', $request->user()->id)
            ->with(['category', 'file']);

        // Filtreleme
        if ($request->has('qr_type')) {
            $query->where('qr_type', $request->qr_type);
        }

        if ($request->has('is_active')) {
            $query->where('is_active', $request->boolean('is_active'));
        }

        if ($request->has('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        $qrCodes = $query->latest()
            ->paginate($request->get('per_page', 15));

        return response()->json([
            'success' => true,
            'data' => $qrCodes,
        ]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'category_id' => 'nullable|exists:categories,id',
            'description' => 'nullable|string|max:1000',
            'qr_type' => 'required|in:file,url,multi_file,text,email,phone,wifi,vcard',
            'content' => 'required_if:qr_type,url,text,email,phone,wifi,vcard|string',
            'file_id' => 'required_if:qr_type,file|exists:files,id',
            'file_ids' => 'required_if:qr_type,multi_file|array|min:1',
            'file_ids.*' => 'exists:files,id',
            'button_names' => 'required_if:qr_type,multi_file|array|min:1',
            'button_names.*' => 'string|max:255',
            'page_title' => 'required_if:qr_type,multi_file|string|max:255',
            'size' => 'nullable|integer|min:100|max:1000',
            'format' => 'nullable|in:png,svg',
            'is_active' => 'nullable|boolean',
            'expires_at' => 'nullable|date|after_or_equal:today',
            'password' => 'nullable|string|min:4|max:255',
            'password_protected' => 'nullable|boolean',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors(),
            ], 422);
        }

        $data = $validator->validated();
        $data['user_id'] = $request->user()->id;

        // Password protection
        if (!empty($data['password']) && ($data['password_protected'] ?? false)) {
            $data['password'] = Hash::make($data['password']);
            $data['password_protected'] = true;
        } else {
            $data['password'] = null;
            $data['password_protected'] = false;
        }

        $qrCode = QrCode::create($data);

        return response()->json([
            'success' => true,
            'data' => $qrCode->load(['category', 'file']),
            'message' => 'QR code created successfully',
        ], 201);
    }

    public function show(Request $request, $id)
    {
        $qrCode = QrCode::where('id', $id)
            ->where('user_id', $request->user()->id)
            ->with(['category', 'file', 'files'])
            ->first();

        if (!$qrCode) {
            return response()->json([
                'success' => false,
                'message' => 'QR code not found',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $qrCode,
        ]);
    }

    public function update(Request $request, $id)
    {
        $qrCode = QrCode::where('id', $id)
            ->where('user_id', $request->user()->id)
            ->first();

        if (!$qrCode) {
            return response()->json([
                'success' => false,
                'message' => 'QR code not found',
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|required|string|max:255',
            'category_id' => 'nullable|exists:categories,id',
            'description' => 'nullable|string|max:1000',
            'content' => 'sometimes|required|string',
            'file_id' => 'nullable|exists:files,id',
            'size' => 'nullable|integer|min:100|max:1000',
            'format' => 'nullable|in:png,svg',
            'is_active' => 'nullable|boolean',
            'expires_at' => 'nullable|date|after_or_equal:today',
            'password' => 'nullable|string|min:4|max:255',
            'password_protected' => 'nullable|boolean',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors(),
            ], 422);
        }

        $data = $validator->validated();

        // Password protection
        if (isset($data['password_protected']) && $data['password_protected']) {
            if (!empty($data['password'])) {
                $data['password'] = Hash::make($data['password']);
            } else {
                unset($data['password']);
            }
            $data['password_protected'] = true;
        } elseif (isset($data['password_protected']) && !$data['password_protected']) {
            $data['password'] = null;
            $data['password_protected'] = false;
        }

        $qrCode->update($data);

        return response()->json([
            'success' => true,
            'data' => $qrCode->fresh()->load(['category', 'file']),
            'message' => 'QR code updated successfully',
        ]);
    }

    public function destroy(Request $request, $id)
    {
        $qrCode = QrCode::where('id', $id)
            ->where('user_id', $request->user()->id)
            ->first();

        if (!$qrCode) {
            return response()->json([
                'success' => false,
                'message' => 'QR code not found',
            ], 404);
        }

        // QR kod görselini sil
        if ($qrCode->file_path && Storage::disk('public')->exists($qrCode->file_path)) {
            Storage::disk('public')->delete($qrCode->file_path);
        }

        $qrCode->delete();

        return response()->json([
            'success' => true,
            'message' => 'QR code deleted successfully',
        ]);
    }

    public function stats(Request $request, $id)
    {
        $qrCode = QrCode::where('id', $id)
            ->where('user_id', $request->user()->id)
            ->first();

        if (!$qrCode) {
            return response()->json([
                'success' => false,
                'message' => 'QR code not found',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => [
                'total_scans' => $qrCode->scan_count,
                'total_downloads' => $qrCode->download_count,
                'is_active' => $qrCode->is_active,
                'created_at' => $qrCode->created_at,
                'expires_at' => $qrCode->expires_at,
            ],
        ]);
    }
}
