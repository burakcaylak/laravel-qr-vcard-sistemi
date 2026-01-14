<?php

namespace App\Livewire\QrCode;

use App\Models\QrCode;
use Livewire\Component;

class QrCodePreview extends Component
{
    public $qrCodeId;

    public function mount($qrCodeId = null)
    {
        $this->qrCodeId = $qrCodeId;
    }

    public function render()
    {
        $qrCode = null;
        
        if ($this->qrCodeId) {
            $qrCode = QrCode::where('id', $this->qrCodeId)
                ->where('user_id', auth()->id())
                ->first();
        }

        return view('livewire.qr-code.qr-code-preview', compact('qrCode'));
    }

    public function download()
    {
        if (!$this->qrCodeId) {
            return;
        }

        $qrCode = QrCode::where('id', $this->qrCodeId)
            ->where('user_id', auth()->id())
            ->first();

        if ($qrCode) {
            return redirect()->route('qr-code.download', $qrCode);
        }
    }
}
