<div class="modal fade" id="kt_modal_qr_code_generator" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered mw-750px">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="fw-bold">QR Kod Oluştur</h2>
                <div class="btn btn-icon btn-sm btn-active-icon-primary" data-bs-dismiss="modal">
                    {!! getIcon('cross', 'fs-1') !!}
                </div>
            </div>
            <form wire:submit="submit">
                <div class="modal-body scroll-y mx-5 mx-xl-15 my-7">
                    <div class="mb-5">
                        <label class="form-label required">QR Kod Adı</label>
                        <input type="text" wire:model="name" class="form-control @error('name') is-invalid @enderror" required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-5">
                            <label class="form-label">Kategori</label>
                            <input type="text" wire:model="category" class="form-control">
                        </div>
                        <div class="col-md-6 mb-5">
                            <label class="form-label">Talep Eden Kişi</label>
                            <input type="text" wire:model="requested_by" class="form-control">
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-5">
                            <label class="form-label">Talep Tarihi</label>
                            <input type="date" wire:model="request_date" class="form-control">
                        </div>
                        <div class="col-md-6 mb-5">
                            <label class="form-label required">QR Kod Tipi</label>
                            <select wire:model="qr_type" wire:change="updatedQrType" class="form-select @error('qr_type') is-invalid @enderror" required>
                                <option value="file">Dosya</option>
                                <option value="url">URL</option>
                                <option value="text">Metin</option>
                                <option value="email">E-posta</option>
                                <option value="phone">Telefon</option>
                                <option value="wifi">WiFi</option>
                                <option value="vcard">vCard</option>
                            </select>
                            @error('qr_type')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    @if($qr_type === 'file')
                        <div class="mb-5">
                            <label class="form-label required">Dosya</label>
                            <select wire:model="file_id" class="form-select @error('file_id') is-invalid @enderror" required>
                                <option value="">Dosya Seçin</option>
                                @foreach($files as $file)
                                    <option value="{{ $file->id }}">{{ $file->name }} ({{ $file->original_name }})</option>
                                @endforeach
                            </select>
                            @error('file_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    @else
                        <div class="mb-5">
                            <label class="form-label required">İçerik</label>
                            <textarea wire:model="content" class="form-control @error('content') is-invalid @enderror" rows="3" required></textarea>
                            @error('content')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    @endif

                    <div class="row">
                        <div class="col-md-6 mb-5">
                            <label class="form-label">Boyut (px)</label>
                            <input type="number" wire:model="size" class="form-control" min="100" max="1000">
                        </div>
                        <div class="col-md-6 mb-5">
                            <label class="form-label">Format</label>
                            <select wire:model="format" class="form-select">
                                <option value="png">PNG</option>
                                <option value="svg">SVG</option>
                            </select>
                        </div>
                    </div>

                    <div class="mb-5">
                        <label class="form-label">Açıklama</label>
                        <textarea wire:model="description" class="form-control" rows="3"></textarea>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-5">
                            <label class="form-check form-switch form-check-custom form-check-solid">
                                <input class="form-check-input" type="checkbox" wire:model="is_active">
                                <span class="form-check-label">Aktif</span>
                            </label>
                        </div>
                        <div class="col-md-6 mb-5">
                            <label class="form-label">Son Kullanma Tarihi</label>
                            <input type="date" wire:model="expires_at" class="form-control">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">İptal</button>
                    <button type="submit" class="btn btn-primary">
                        <span wire:loading.remove wire:target="submit">Oluştur</span>
                        <span wire:loading wire:target="submit">Oluşturuluyor...</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
