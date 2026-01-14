<?php

namespace App\Livewire\File;

use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class FileUploadModal extends Component
{
    use WithFileUploads;

    public $name;
    public $description;
    public $category;
    public $is_public = false;

    public $file;

    protected $rules = [
        'file' => 'required|file|max:10240',
        'name' => 'nullable|string|max:255',
        'description' => 'nullable|string|max:1000',
        'category' => 'nullable|string|max:100',
        'is_public' => 'boolean',
    ];

    protected $listeners = [
        'show_file_upload_modal' => 'showModal',
    ];

    public function render()
    {
        return view('livewire.file.file-upload-modal');
    }

    public function showModal()
    {
        $this->reset();
        $this->resetValidation();
    }


    public function submit()
    {
        try {
            $this->validate();

            if (!$this->file) {
                $this->addError('file', 'Dosya seçilmedi.');
                $this->dispatch('allow-modal-close');
                return;
            }

            $uploadedFile = $this->file;
            $originalName = $uploadedFile->getClientOriginalName();
            $extension = $uploadedFile->getClientOriginalExtension();

            // Orijinal dosya adını kullan
            $path = 'files/' . $originalName;

            // Aynı isimde dosya var mı kontrol et
            if (Storage::disk('public')->exists($path)) {
                // Dosya varsa, benzersiz bir isim oluştur
                $nameWithoutExt = pathinfo($originalName, PATHINFO_FILENAME);
                $counter = 1;
                do {
                    $newName = $nameWithoutExt . '_' . $counter . '.' . $extension;
                    $path = 'files/' . $newName;
                    $counter++;
                } while (Storage::disk('public')->exists($path));
            }

            $uploadedFile->storeAs('public', $path);

            $file = \App\Models\File::create([
                'user_id' => auth()->id(),
                'name' => $this->name ?: pathinfo($originalName, PATHINFO_FILENAME),
                'original_name' => $originalName,
                'path' => $path,
                'type' => $this->getFileType($extension),
                'mime_type' => $uploadedFile->getMimeType(),
                'size' => $uploadedFile->getSize(),
                'description' => $this->description,
                'category' => $this->category,
                'is_public' => $this->is_public,
            ]);

            // Activity log
            \App\Helpers\ActivityLogHelper::logFile('created', $file);

            // Başarılı yükleme sonrası modalı kapat ve tabloyu yenile
            $this->reset();
            $this->resetValidation();

            // JavaScript event'ini dispatch et
            $this->dispatch('file-uploaded-success');
            $this->dispatch('file_uploaded', $file->id);
            $this->dispatch('allow-modal-close');
        } catch (\Illuminate\Validation\ValidationException $e) {
            // Validation hataları zaten gösteriliyor, modal açık kalsın
            session()->flash('error', 'Lütfen formu kontrol edin.');
            $this->dispatch('prevent-modal-close');
            throw $e;
        } catch (\Exception $e) {
            // Diğer hatalar için
            $this->addError('file', 'Dosya yüklenirken bir hata oluştu: ' . $e->getMessage());
            session()->flash('error', 'Dosya yüklenirken bir hata oluştu: ' . $e->getMessage());
            $this->dispatch('prevent-modal-close');
            \Log::error('File upload error: ' . $e->getMessage(), [
                'user_id' => auth()->id(),
                'file_name' => $this->file?->getClientOriginalName(),
                'trace' => $e->getTraceAsString()
            ]);
        }
    }

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
