<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\VCard;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

class VCardApiController extends Controller
{
    public function index(Request $request)
    {
        $query = VCard::where('user_id', $request->user()->id)
            ->with('category');

        // Filtreleme
        if ($request->has('is_active')) {
            $query->where('is_active', $request->boolean('is_active'));
        }

        if ($request->has('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        $vCards = $query->latest()
            ->paginate($request->get('per_page', 15));

        return response()->json([
            'success' => true,
            'data' => $vCards,
        ]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'template_id' => 'nullable|exists:v_card_templates,id',
            'category_id' => 'nullable|exists:categories,id',
            'name_tr' => 'nullable|string|max:255',
            'name_en' => 'nullable|string|max:255',
            'title_tr' => 'nullable|string|max:255',
            'title_en' => 'nullable|string|max:255',
            'phone_tr' => 'nullable|string|max:50',
            'phone_en' => 'nullable|string|max:50',
            'email_tr' => 'nullable|email|max:255',
            'email_en' => 'nullable|email|max:255',
            'company_tr' => 'nullable|string|max:255',
            'company_en' => 'nullable|string|max:255',
            'address_tr' => 'nullable|string|max:1000',
            'address_en' => 'nullable|string|max:1000',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:50',
            'mobile_phone' => 'nullable|string|max:50',
            'website' => 'nullable|url|max:255',
            'is_active' => 'nullable|boolean',
            'expires_at' => 'nullable|date|after_or_equal:today',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors(),
            ], 422);
        }

        $data = $validator->validated();
        $data['user_id'] = $request->user()->id;

        $vCard = VCard::create($data);

        return response()->json([
            'success' => true,
            'data' => $vCard->load('category'),
            'message' => 'VCard created successfully',
        ], 201);
    }

    public function show(Request $request, $id)
    {
        $vCard = VCard::where('id', $id)
            ->where('user_id', $request->user()->id)
            ->with('category')
            ->first();

        if (!$vCard) {
            return response()->json([
                'success' => false,
                'message' => 'VCard not found',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $vCard,
        ]);
    }

    public function update(Request $request, $id)
    {
        $vCard = VCard::where('id', $id)
            ->where('user_id', $request->user()->id)
            ->first();

        if (!$vCard) {
            return response()->json([
                'success' => false,
                'message' => 'VCard not found',
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'template_id' => 'nullable|exists:v_card_templates,id',
            'category_id' => 'nullable|exists:categories,id',
            'name_tr' => 'nullable|string|max:255',
            'name_en' => 'nullable|string|max:255',
            'title_tr' => 'nullable|string|max:255',
            'title_en' => 'nullable|string|max:255',
            'phone_tr' => 'nullable|string|max:50',
            'phone_en' => 'nullable|string|max:50',
            'email_tr' => 'nullable|email|max:255',
            'email_en' => 'nullable|email|max:255',
            'company_tr' => 'nullable|string|max:255',
            'company_en' => 'nullable|string|max:255',
            'address_tr' => 'nullable|string|max:1000',
            'address_en' => 'nullable|string|max:1000',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:50',
            'mobile_phone' => 'nullable|string|max:50',
            'website' => 'nullable|url|max:255',
            'is_active' => 'nullable|boolean',
            'expires_at' => 'nullable|date|after_or_equal:today',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors(),
            ], 422);
        }

        $data = $validator->validated();
        $vCard->update($data);

        return response()->json([
            'success' => true,
            'data' => $vCard->fresh()->load('category'),
            'message' => 'VCard updated successfully',
        ]);
    }

    public function destroy(Request $request, $id)
    {
        $vCard = VCard::where('id', $id)
            ->where('user_id', $request->user()->id)
            ->first();

        if (!$vCard) {
            return response()->json([
                'success' => false,
                'message' => 'VCard not found',
            ], 404);
        }

        // QR kod görselini sil
        if ($vCard->file_path && Storage::disk('public')->exists($vCard->file_path)) {
            Storage::disk('public')->delete($vCard->file_path);
        }

        $vCard->delete();

        return response()->json([
            'success' => true,
            'message' => 'VCard deleted successfully',
        ]);
    }

    public function stats(Request $request, $id)
    {
        $vCard = VCard::where('id', $id)
            ->where('user_id', $request->user()->id)
            ->first();

        if (!$vCard) {
            return response()->json([
                'success' => false,
                'message' => 'VCard not found',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => [
                'total_scans' => $vCard->scan_count,
                'is_active' => $vCard->is_active,
                'created_at' => $vCard->created_at,
                'expires_at' => $vCard->expires_at,
            ],
        ]);
    }
}
