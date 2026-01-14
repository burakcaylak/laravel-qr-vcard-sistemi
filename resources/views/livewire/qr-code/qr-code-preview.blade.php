@if($qrCode)
    <div class="text-center">
        @if($qrCode->file_path)
            <img src="{{ asset('storage/' . $qrCode->file_path) }}" alt="QR Code" class="img-fluid mb-5" style="max-width: 300px;">
        @endif
        <div class="d-flex justify-content-center gap-3">
            <button wire:click="download" class="btn btn-primary">
                {!! getIcon('download', 'fs-2', '', 'i') !!}
                İndir
            </button>
        </div>
    </div>
@else
    <div class="text-center text-muted">
        <p>QR kod bulunamadı.</p>
    </div>
@endif
