<!--begin:: QR Info -->
<div class="d-flex align-items-center">
    @if($qrCode->file_path)
        <img src="{{ asset('storage/' . $qrCode->file_path) }}" alt="QR Kod" class="w-50px h-50px me-3" style="object-fit: contain;">
    @else
        <span class="svg-icon svg-icon-success svg-icon-2 me-3">
            <i class="ki-solid ki-scan-barcode fs-2"></i>
        </span>
    @endif
    <div class="d-flex flex-column">
        <a href="{{ route('qr-code.show', $qrCode) }}" class="text-gray-800 text-hover-primary fw-bold">
            {{ $qrCode->name }}
        </a>
    </div>
</div>
<!--end::QR Info-->

