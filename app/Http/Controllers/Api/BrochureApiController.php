<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Brochure;
use App\Models\File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

class BrochureApiController extends Controller
{
    public function index(Request $request)
    {
        $query = Brochure::where('user_id', $request->user()->id)
            ->with(['category', 'file']);

        // Filtreleme
        if ($request->has('is_active')) {
            $query->where('is_active', $request->boolean('is_active'));
        }

        if ($request->has('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        $brochures = $query->latest()
            ->paginate($request->get('per_page', 15));

        return response()->json([
            'success' => true,
            'data' => $brochures,
        ]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'category_id' => 'nullable|exists:categories,id',
            'file_id' => 'nullable|exists:files,id',
            'background_type' => 'nullable|in:image,color',
            'background_color' => 'nullable|string|regex:/^#([A-Fa-f0-9]{6}|[A-Fa-f0-9]{3})$/',
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

        // file_id kontrolü
        if (empty($data['file_id'])) {
            return response()->json([
                'success' => false,
                'message' => 'file_id is required',
            ], 422);
        }

        // Dosyanın PDF olduğunu kontrol et
        $file = File::find($data['file_id']);
        if (!$file || ($file->type !== 'document' && $file->mime_type !== 'application/pdf')) {
            return response()->json([
                'success' => false,
                'message' => 'File must be a PDF',
            ], 422);
        }

        // Background color default
        if (empty($data['background_color'])) {
            $data['background_color'] = '#ffffff';
        }

        // Password protection
        if (!empty($data['password']) && ($data['password_protected'] ?? false)) {
            $data['password'] = Hash::make($data['password']);
            $data['password_protected'] = true;
        } else {
            $data['password'] = null;
            $data['password_protected'] = false;
        }

        $brochure = Brochure::create($data);

        return response()->json([
            'success' => true,
            'data' => $brochure->load(['category', 'file']),
            'message' => 'Brochure created successfully',
        ], 201);
    }

    public function show(Request $request, $id)
    {
        $brochure = Brochure::where('id', $id)
            ->where('user_id', $request->user()->id)
            ->with(['category', 'file'])
            ->first();

        if (!$brochure) {
            return response()->json([
                'success' => false,
                'message' => 'Brochure not found',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $brochure,
        ]);
    }

    public function update(Request $request, $id)
    {
        $brochure = Brochure::where('id', $id)
            ->where('user_id', $request->user()->id)
            ->first();

        if (!$brochure) {
            return response()->json([
                'success' => false,
                'message' => 'Brochure not found',
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'category_id' => 'nullable|exists:categories,id',
            'file_id' => 'nullable|exists:files,id',
            'background_type' => 'nullable|in:image,color',
            'background_color' => 'nullable|string|regex:/^#([A-Fa-f0-9]{6}|[A-Fa-f0-9]{3})$/',
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

        $brochure->update($data);

        return response()->json([
            'success' => true,
            'data' => $brochure->fresh()->load(['category', 'file']),
            'message' => 'Brochure updated successfully',
        ]);
    }

    public function destroy(Request $request, $id)
    {
        $brochure = Brochure::where('id', $id)
            ->where('user_id', $request->user()->id)
            ->first();

        if (!$brochure) {
            return response()->json([
                'success' => false,
                'message' => 'Brochure not found',
            ], 404);
        }

        // QR kod görselini sil
        if ($brochure->qr_code_path && Storage::disk('public')->exists($brochure->qr_code_path)) {
            Storage::disk('public')->delete($brochure->qr_code_path);
        }

        $brochure->delete();

        return response()->json([
            'success' => true,
            'message' => 'Brochure deleted successfully',
        ]);
    }

    public function stats(Request $request, $id)
    {
        $brochure = Brochure::where('id', $id)
            ->where('user_id', $request->user()->id)
            ->first();

        if (!$brochure) {
            return response()->json([
                'success' => false,
                'message' => 'Brochure not found',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => [
                'total_views' => $brochure->view_count,
                'total_downloads' => $brochure->download_count,
                'is_active' => $brochure->is_active,
                'created_at' => $brochure->created_at,
                'expires_at' => $brochure->expires_at,
            ],
        ]);
    }
}
