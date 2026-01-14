<?php

namespace App\Livewire\QrCode;

use App\Models\File;
use Livewire\Component;

class QrCodeGeneratorModal extends Component
{
    public $name;
    public $category;
    public $requested_by;
    public $request_date;
    public $description;
    public $qr_type = 'file';
    public $file_id;
    public $content;
    public $size = 300;
    public $format = 'png';
    public $is_active = true;
    public $expires_at;

    protected $rules = [
        'name' => 'required|string|max:255',
        'category' => 'nullable|string|max:100',
        'requested_by' => 'nullable|string|max:255',
        'request_date' => 'nullable|date',
        'description' => 'nullable|string|max:1000',
        'qr_type' => 'required|in:file,url,text,email,phone,wifi,vcard',
        'file_id' => 'required_if:qr_type,file|exists:files,id',
        'content' => 'required_if:qr_type,url,text,email,phone,wifi,vcard|string',
        'size' => 'nullable|integer|min:100|max:1000',
        'format' => 'nullable|in:png,svg',
        'is_active' => 'boolean',
        'expires_at' => 'nullable|date|after:today',
    ];

    protected $listeners = [
        'show_qr_code_generator_modal' => 'showModal',
    ];

    public function render()
    {
        $files = File::where('user_id', auth()->id())
            ->latest()
            ->get();

        return view('livewire.qr-code.qr-code-generator-modal', compact('files'));
    }

    public function showModal()
    {
        $this->reset();
        $this->resetValidation();
        $this->qr_type = 'file';
        $this->size = 300;
        $this->format = 'png';
        $this->is_active = true;
    }

    public function updatedQrType()
    {
        if ($this->qr_type !== 'file') {
            $this->file_id = null;
        }
    }

    public function submit()
    {
        $this->validate();

        $data = [
            'user_id' => auth()->id(),
            'name' => $this->name,
            'category' => $this->category,
            'requested_by' => $this->requested_by,
            'request_date' => $this->request_date,
            'description' => $this->description,
            'qr_type' => $this->qr_type,
            'size' => $this->size,
            'format' => $this->format,
            'is_active' => $this->is_active,
            'expires_at' => $this->expires_at,
        ];

        if ($this->qr_type === 'file') {
            $data['file_id'] = $this->file_id;
        } else {
            $data['content'] = $this->content;
        }

        $qrCode = \App\Models\QrCode::create($data);
        
        // Model'de created event'inde content ayarlanıyor, tekrar kontrol et
        if ($qrCode->qr_type === 'file' && empty($qrCode->content)) {
            $qrCode->content = route('qr.access', $qrCode->token);
            $qrCode->saveQuietly();
        }
        
        // QR kod görselini oluştur
        $controller = new \App\Http\Controllers\QrCodeController();
        $controller->generateQrImage($qrCode);

        $this->reset();
        $this->dispatch('success', 'QR kod başarıyla oluşturuldu.');
        $this->dispatch('qr_code_created', $qrCode->id);
        $this->dispatch('closeModal', '#kt_modal_qr_code_generator');
    }
}
