<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class FileApiController extends Controller
{
    public function index(Request $request)
    {
        $query = File::where('user_id', $request->user()->id);

        // Filtreleme
        if ($request->has('type')) {
            $query->where('type', $request->type);
        }

        if ($request->has('is_active')) {
            $query->where('is_active', $request->boolean('is_active'));
        }

        if ($request->has('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        $files = $query->latest()
            ->paginate($request->get('per_page', 15));

        return response()->json([
            'success' => true,
            'data' => $files,
        ]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'file' => 'required|file|max:' . config('files.max_file_size'),
            'name' => 'nullable|string|max:255',
            'description' => 'nullable|string|max:1000',
            'category_id' => 'nullable|exists:categories,id',
            'is_public' => 'nullable|boolean',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors(),
            ], 422);
        }

        $uploadedFile = $request->file('file');
        $originalName = $uploadedFile->getClientOriginalName();
        $extension = $uploadedFile->getClientOriginalExtension();
        
        // Güvenli dosya adı oluştur
        $safeName = preg_replace('/[^a-zA-Z0-9._-]/', '_', pathinfo($originalName, PATHINFO_FILENAME));
        $safeName = preg_replace('/_+/', '_', $safeName);
        $safeName = trim($safeName, '_');
        
        if (empty($safeName)) {
            $safeName = 'file_' . time();
        }

        $fileName = $safeName . '.' . $extension;
        $folder = 'files';
        $targetPath = $folder . '/' . $fileName;

        // Aynı isimde dosya varsa, sayı ekle
        $baseName = pathinfo($fileName, PATHINFO_FILENAME);
        $counter = 1;
        while (Storage::disk('public')->exists($targetPath)) {
            $fileName = $baseName . '_' . $counter . '.' . $extension;
            $targetPath = $folder . '/' . $fileName;
            $counter++;
        }

        // Dosyayı kaydet
        $path = $uploadedFile->storeAs($folder, $fileName, 'public');

        // Name alanını belirle
        $name = $request->input('name') ?: pathinfo($originalName, PATHINFO_FILENAME);

        $file = File::create([
            'user_id' => $request->user()->id,
            'name' => trim($name),
            'original_name' => $originalName,
            'path' => $path,
            'type' => $this->getFileType($extension),
            'mime_type' => $uploadedFile->getMimeType(),
            'size' => $uploadedFile->getSize(),
            'description' => $request->input('description'),
            'category_id' => $request->input('category_id'),
            'is_public' => $request->boolean('is_public', false),
        ]);

        return response()->json([
            'success' => true,
            'data' => $file,
            'message' => 'File uploaded successfully',
        ], 201);
    }

    public function show(Request $request, $id)
    {
        $file = File::where('id', $id)
            ->where('user_id', $request->user()->id)
            ->first();

        if (!$file) {
            return response()->json([
                'success' => false,
                'message' => 'File not found',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $file,
        ]);
    }

    public function update(Request $request, $id)
    {
        $file = File::where('id', $id)
            ->where('user_id', $request->user()->id)
            ->first();

        if (!$file) {
            return response()->json([
                'success' => false,
                'message' => 'File not found',
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'category_id' => 'nullable|exists:categories,id',
            'is_public' => 'nullable|boolean',
            'is_active' => 'nullable|boolean',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors(),
            ], 422);
        }

        $data = $validator->validated();
        $file->update($data);

        return response()->json([
            'success' => true,
            'data' => $file->fresh(),
            'message' => 'File updated successfully',
        ]);
    }

    public function destroy(Request $request, $id)
    {
        $file = File::where('id', $id)
            ->where('user_id', $request->user()->id)
            ->first();

        if (!$file) {
            return response()->json([
                'success' => false,
                'message' => 'File not found',
            ], 404);
        }

        // Dosyayı storage'dan sil
        if (Storage::disk('public')->exists($file->path)) {
            Storage::disk('public')->delete($file->path);
        }

        // Thumbnail varsa sil
        $thumbnailPath = str_replace(
            pathinfo($file->path, PATHINFO_DIRNAME),
            pathinfo($file->path, PATHINFO_DIRNAME) . '/thumbnails',
            $file->path
        );
        
        if (Storage::disk('public')->exists($thumbnailPath)) {
            Storage::disk('public')->delete($thumbnailPath);
        }

        $file->delete();

        return response()->json([
            'success' => true,
            'message' => 'File deleted successfully',
        ]);
    }

    public function download(Request $request, $id)
    {
        $file = File::where('id', $id)
            ->where(function($query) use ($request) {
                $query->where('user_id', $request->user()->id)
                      ->orWhere('is_public', true);
            })
            ->first();

        if (!$file) {
            return response()->json([
                'success' => false,
                'message' => 'File not found',
            ], 404);
        }

        if (!Storage::disk('public')->exists($file->path)) {
            return response()->json([
                'success' => false,
                'message' => 'File not found on storage',
            ], 404);
        }

        $file->increment('download_count');

        $downloadUrl = Storage::disk('public')->url($file->path);

        return response()->json([
            'success' => true,
            'data' => [
                'download_url' => $downloadUrl,
                'file' => $file,
            ],
        ]);
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
