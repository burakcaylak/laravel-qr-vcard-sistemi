<?php

namespace App\Http\Controllers;

use App\Http\Requests\VCardTemplateRequest;
use App\Models\VCardTemplate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class VCardTemplateController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(VCardTemplate::class);
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $templates = VCardTemplate::with('user')
            ->latest()
            ->get();
            
        return view('pages.v-card-template.index', compact('templates'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = \App\Models\Category::where('is_active', true)
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get();

        return view('pages.v-card-template.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(VCardTemplateRequest $request)
    {
        $data = $request->validated();
        $data['user_id'] = auth()->id();

        // Logo yükleme - önce media library'den seçilen path'i kontrol et
        if ($request->has('logo_path') && !empty($request->input('logo_path'))) {
            $data['logo_path'] = $request->input('logo_path');
        } elseif ($request->hasFile('logo')) {
            $logo = $request->file('logo');
            $logoName = time() . '_' . $logo->getClientOriginalName();
            $logoPath = $logo->storeAs('v-card-templates/logos', $logoName, 'public');
            $data['logo_path'] = $logoPath;
        }

        // Arkaplan görseli yükleme - önce media library'den seçilen path'i kontrol et
        if ($request->has('background_path') && !empty($request->input('background_path'))) {
            $data['background_path'] = $request->input('background_path');
        } elseif ($request->hasFile('background')) {
            $background = $request->file('background');
            $backgroundName = time() . '_' . $background->getClientOriginalName();
            $backgroundPath = $background->storeAs('v-card-templates/backgrounds', $backgroundName, 'public');
            $data['background_path'] = $backgroundPath;
        }

        // is_active checkbox işaretli değilse form'dan gönderilmez
        if (!$request->has('is_active')) {
            $data['is_active'] = false;
        } else {
            $data['is_active'] = (bool) $request->input('is_active');
        }

        $template = VCardTemplate::create($data);

        return redirect()->route('v-card-template.index')
            ->with('success', __('common.template_created'));
    }

    /**
     * Display the specified resource.
     */
    public function show(VCardTemplate $vCardTemplate)
    {
        $vCardTemplate->load('user', 'vCards');
        return view('pages.v-card-template.show', compact('vCardTemplate'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(VCardTemplate $vCardTemplate)
    {
        $categories = \App\Models\Category::where('is_active', true)
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get();

        return view('pages.v-card-template.edit', compact('vCardTemplate', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(VCardTemplateRequest $request, VCardTemplate $vCardTemplate)
    {
        $data = $request->validated();

        // Logo yükleme - önce media library'den seçilen path'i kontrol et
        if ($request->has('logo_path') && !empty($request->input('logo_path'))) {
            // Eski logoyu sil (sadece v-card-templates klasöründeyse)
            if ($vCardTemplate->logo_path && Storage::disk('public')->exists($vCardTemplate->logo_path) && strpos($vCardTemplate->logo_path, 'v-card-templates/') === 0) {
                Storage::disk('public')->delete($vCardTemplate->logo_path);
            }
            $data['logo_path'] = $request->input('logo_path');
        } elseif ($request->hasFile('logo')) {
            // Eski logoyu sil (sadece v-card-templates klasöründeyse)
            if ($vCardTemplate->logo_path && Storage::disk('public')->exists($vCardTemplate->logo_path) && strpos($vCardTemplate->logo_path, 'v-card-templates/') === 0) {
                Storage::disk('public')->delete($vCardTemplate->logo_path);
            }
            
            $logo = $request->file('logo');
            $logoName = time() . '_' . $logo->getClientOriginalName();
            $logoPath = $logo->storeAs('v-card-templates/logos', $logoName, 'public');
            $data['logo_path'] = $logoPath;
        }

        // Arkaplan görseli yükleme - önce media library'den seçilen path'i kontrol et
        if ($request->has('background_path') && !empty($request->input('background_path'))) {
            // Eski arkaplan görselini sil (sadece v-card-templates klasöründeyse)
            if ($vCardTemplate->background_path && Storage::disk('public')->exists($vCardTemplate->background_path) && strpos($vCardTemplate->background_path, 'v-card-templates/') === 0) {
                Storage::disk('public')->delete($vCardTemplate->background_path);
            }
            $data['background_path'] = $request->input('background_path');
        } elseif ($request->hasFile('background')) {
            // Eski arkaplan görselini sil (sadece v-card-templates klasöründeyse)
            if ($vCardTemplate->background_path && Storage::disk('public')->exists($vCardTemplate->background_path) && strpos($vCardTemplate->background_path, 'v-card-templates/') === 0) {
                Storage::disk('public')->delete($vCardTemplate->background_path);
            }
            
            $background = $request->file('background');
            $backgroundName = time() . '_' . $background->getClientOriginalName();
            $backgroundPath = $background->storeAs('v-card-templates/backgrounds', $backgroundName, 'public');
            $data['background_path'] = $backgroundPath;
        }

        // is_active checkbox işaretli değilse form'dan gönderilmez
        if (!$request->has('is_active')) {
            $data['is_active'] = false;
        } else {
            $data['is_active'] = (bool) $request->input('is_active');
        }

        $vCardTemplate->update($data);

        return redirect()->route('v-card-template.index')
            ->with('success', __('common.template_updated'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(VCardTemplate $vCardTemplate)
    {
        // Logo'yu sil
        if ($vCardTemplate->logo_path && Storage::disk('public')->exists($vCardTemplate->logo_path)) {
            Storage::disk('public')->delete($vCardTemplate->logo_path);
        }

        // Arkaplan görselini sil
        if ($vCardTemplate->background_path && Storage::disk('public')->exists($vCardTemplate->background_path)) {
            Storage::disk('public')->delete($vCardTemplate->background_path);
        }

        $vCardTemplate->delete();

        return redirect()->route('v-card-template.index')
            ->with('success', __('common.template_deleted'));
    }
}
