<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ShortLink;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class ShortLinkApiController extends Controller
{
    public function index(Request $request)
    {
        $shortLinks = ShortLink::where('user_id', $request->user()->id)
            ->with('category')
            ->latest()
            ->paginate($request->get('per_page', 15));

        return response()->json([
            'success' => true,
            'data' => $shortLinks,
        ]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'original_url' => 'required|url|max:2048',
            'title' => 'nullable|string|max:255',
            'description' => 'nullable|string|max:1000',
            'category_id' => 'nullable|exists:categories,id',
            'short_code' => 'nullable|string|max:50|unique:short_links,short_code',
            'is_active' => 'nullable|boolean',
            'expires_at' => 'nullable|date|after_or_equal:today',
            'password' => 'nullable|string|min:4|max:255',
            'password_protected' => 'nullable|boolean',
            'qr_code_size' => 'nullable|integer|min:100|max:1000',
            'qr_code_format' => 'nullable|in:png,svg',
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

        $data['qr_code_size'] = $data['qr_code_size'] ?? 300;
        $data['qr_code_format'] = $data['qr_code_format'] ?? 'png';

        if (empty($data['short_code'])) {
            $data['short_code'] = ShortLink::generateUniqueCode();
        }

        $shortLink = ShortLink::create($data);

        return response()->json([
            'success' => true,
            'data' => $shortLink->load('category'),
            'message' => 'Short link created successfully',
        ], 201);
    }

    public function show(Request $request, $id)
    {
        $shortLink = ShortLink::where('id', $id)
            ->where('user_id', $request->user()->id)
            ->with(['category', 'clicks' => function($query) {
                $query->latest()->limit(100);
            }])
            ->first();

        if (!$shortLink) {
            return response()->json([
                'success' => false,
                'message' => 'Short link not found',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $shortLink,
        ]);
    }

    public function update(Request $request, $id)
    {
        $shortLink = ShortLink::where('id', $id)
            ->where('user_id', $request->user()->id)
            ->first();

        if (!$shortLink) {
            return response()->json([
                'success' => false,
                'message' => 'Short link not found',
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'original_url' => 'sometimes|required|url|max:2048',
            'title' => 'nullable|string|max:255',
            'description' => 'nullable|string|max:1000',
            'category_id' => 'nullable|exists:categories,id',
            'is_active' => 'nullable|boolean',
            'expires_at' => 'nullable|date|after_or_equal:today',
            'password' => 'nullable|string|min:4|max:255',
            'password_protected' => 'nullable|boolean',
            'qr_code_size' => 'nullable|integer|min:100|max:1000',
            'qr_code_format' => 'nullable|in:png,svg',
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

        $shortLink->update($data);

        return response()->json([
            'success' => true,
            'data' => $shortLink->fresh(),
            'message' => 'Short link updated successfully',
        ]);
    }

    public function destroy(Request $request, $id)
    {
        $shortLink = ShortLink::where('id', $id)
            ->where('user_id', $request->user()->id)
            ->first();

        if (!$shortLink) {
            return response()->json([
                'success' => false,
                'message' => 'Short link not found',
            ], 404);
        }

        $shortLink->delete();

        return response()->json([
            'success' => true,
            'message' => 'Short link deleted successfully',
        ]);
    }

    public function stats(Request $request, $id)
    {
        $shortLink = ShortLink::where('id', $id)
            ->where('user_id', $request->user()->id)
            ->first();

        if (!$shortLink) {
            return response()->json([
                'success' => false,
                'message' => 'Short link not found',
            ], 404);
        }

        $clicks = $shortLink->clicks()
            ->selectRaw('DATE(created_at) as date, COUNT(*) as count')
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        $browsers = $shortLink->clicks()
            ->selectRaw('browser, COUNT(*) as count')
            ->groupBy('browser')
            ->orderByDesc('count')
            ->get();

        $devices = $shortLink->clicks()
            ->selectRaw('device_type, COUNT(*) as count')
            ->groupBy('device_type')
            ->orderByDesc('count')
            ->get();

        return response()->json([
            'success' => true,
            'data' => [
                'total_clicks' => $shortLink->click_count,
                'clicks_by_date' => $clicks,
                'clicks_by_browser' => $browsers,
                'clicks_by_device' => $devices,
            ],
        ]);
    }
}
