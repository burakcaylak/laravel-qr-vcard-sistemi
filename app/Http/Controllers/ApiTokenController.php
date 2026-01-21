<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Laravel\Sanctum\PersonalAccessToken;

class ApiTokenController extends Controller
{
    /**
     * Display API token management page
     */
    public function index()
    {
        $tokens = auth()->user()->tokens()->latest()->get();
        
        return view('pages.api-tokens.index', compact('tokens'));
    }

    /**
     * Create a new API token
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $token = auth()->user()->createToken($request->name);

        return back()->with([
            'success' => __('common.api_token_created'),
            'token' => $token->plainTextToken,
        ]);
    }

    /**
     * Delete an API token
     */
    public function destroy($id)
    {
        $token = auth()->user()->tokens()->find($id);

        if (!$token) {
            return back()->withErrors(['error' => __('common.api_token_not_found')]);
        }

        $token->delete();

        return back()->with('success', __('common.api_token_deleted'));
    }

    /**
     * Delete all API tokens
     */
    public function destroyAll()
    {
        auth()->user()->tokens()->delete();

        return back()->with('success', __('common.all_api_tokens_deleted'));
    }
}
