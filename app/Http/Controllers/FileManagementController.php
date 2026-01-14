<?php

namespace App\Http\Controllers;

use App\DataTables\FilesDataTable;
use App\Http\Requests\FileRequest;
use App\Models\File;
use App\Helpers\ActivityLogHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class FileManagementController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(FilesDataTable $dataTable)
    {
        return $dataTable->render('pages.file-management.list');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return redirect()->route('file-management.index');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(FileRequest $request)
    {
        if (!$request->hasFile('file')) {
            return back()->withErrors(['file' => 'Dosya yüklenemedi.'])->withInput();
        }

        $uploadedFile = $request->file('file');
        $originalName = $uploadedFile->getClientOriginalName();
        $extension = $uploadedFile->getClientOriginalExtension();
        $fileName = Str::random(40) . '.' . $extension;
        $path = 'files/' . $fileName;

        $uploadedFile->storeAs('public', $path);

        $file = File::create([
            'user_id' => auth()->id(),
            'name' => $request->input('name', pathinfo($originalName, PATHINFO_FILENAME)),
            'original_name' => $originalName,
            'path' => $path,
            'type' => $this->getFileType($extension),
            'mime_type' => $uploadedFile->getMimeType(),
            'size' => $uploadedFile->getSize(),
            'description' => $request->input('description'),
            'category' => $request->input('category'),
            'is_public' => $request->boolean('is_public', false),
        ]);

        ActivityLogHelper::logFile('created', $file);

        return redirect()->route('file-management.show', $file)
            ->with('success', __('common.file_uploaded'));
    }

    /**
     * Display the specified resource.
     */
    public function show(File $file)
    {
        $this->authorize('view', $file);

        $file->load(['user', 'qrCodes']);

        return view('pages.file-management.show', compact('file'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(File $file)
    {
        $this->authorize('update', $file);

        return view('pages.file-management.edit', compact('file'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(FileRequest $request, File $file)
    {
        $this->authorize('update', $file);

        $file->update($request->validated());

        ActivityLogHelper::logFile('updated', $file);

        return redirect()->route('file-management.show', $file)
            ->with('success', __('common.file_updated'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(File $file)
    {
        $this->authorize('delete', $file);

        ActivityLogHelper::logFile('deleted', $file);

        if (Storage::disk('public')->exists($file->path)) {
            Storage::disk('public')->delete($file->path);
        }

        $file->delete();

        return redirect()->route('file-management.index')
            ->with('success', __('common.file_deleted'));
    }

    /**
     * Create File from media library path
     */
    public function createFromPath(Request $request)
    {
        $request->validate([
            'path' => 'required|string',
            'name' => 'nullable|string|max:255',
        ]);

        $path = $request->input('path');
        
        // Path'in storage'da var olduğunu kontrol et
        if (!Storage::disk('public')->exists($path)) {
            return response()->json([
                'success' => false,
                'message' => 'Dosya bulunamadı.',
            ], 404);
        }

        $fileName = basename($path);
        $extension = pathinfo($fileName, PATHINFO_EXTENSION);
        
        // Name alanını belirle - her zaman bir değer olmalı
        $name = $request->input('name');
        if (empty($name) || trim($name) === '') {
            // Name boşsa, dosya adından uzantısız adı al
            $name = pathinfo($fileName, PATHINFO_FILENAME);
            // Eğer pathinfo boş dönerse, dosya adını kullan
            if (empty($name) || trim($name) === '') {
                $name = $fileName;
            }
        }
        
        // Aynı path'te dosya var mı kontrol et
        $existingFile = File::where('path', $path)->first();
        if ($existingFile) {
            return response()->json([
                'success' => true,
                'id' => $existingFile->id,
                'file' => $existingFile,
                'message' => 'Dosya zaten mevcut.',
            ]);
        }

        $file = File::create([
            'user_id' => auth()->id(),
            'name' => trim($name), // Trim ile boşlukları temizle
            'original_name' => $fileName,
            'path' => $path,
            'type' => $this->getFileType($extension),
            'mime_type' => Storage::disk('public')->mimeType($path),
            'size' => Storage::disk('public')->size($path),
            'is_public' => false,
        ]);

        ActivityLogHelper::logFile('created', $file);

        return response()->json([
            'success' => true,
            'id' => $file->id,
            'file' => $file,
            'message' => 'Dosya başarıyla oluşturuldu.',
        ]);
    }

    /**
     * Download the specified file.
     */
    public function download(File $file)
    {
        $this->authorize('view', $file);

        if (Storage::disk('public')->exists($file->path)) {
            $file->increment('download_count');

            ActivityLogHelper::logFile('downloaded', $file);

            return Storage::disk('public')->download(
                $file->path,
                $file->original_name
            );
        }

        abort(404, 'Dosya bulunamadı.');
    }

    /**
     * Get file type based on extension.
     */
    protected function getFileType(string $extension): string
    {
        $imageTypes = ['jpg', 'jpeg', 'png', 'gif', 'svg', 'webp'];
        $documentTypes = ['pdf', 'doc', 'docx', 'xls', 'xlsx', 'ppt', 'pptx', 'txt'];
        $videoTypes = ['mp4', 'avi', 'mov', 'wmv', 'flv'];
        $audioTypes = ['mp3', 'wav', 'ogg', 'm4a'];

        $extension = strtolower($extension);

        if (in_array($extension, $imageTypes)) {
            return 'image';
        } elseif (in_array($extension, $documentTypes)) {
            return 'document';
        } elseif (in_array($extension, $videoTypes)) {
            return 'video';
        } elseif (in_array($extension, $audioTypes)) {
            return 'audio';
        }

        return 'other';
    }
}
