<div class="modal fade" id="kt_modal_file_upload" tabindex="-1" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false" wire:ignore.self>
    <div class="modal-dialog modal-dialog-centered mw-650px">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="fw-bold">Dosya Yükle</h2>
                <div class="btn btn-icon btn-sm btn-active-icon-primary" data-bs-dismiss="modal">
                    {!! getIcon('cross', 'fs-1') !!}
                </div>
            </div>
            <form wire:submit.prevent="submit" enctype="multipart/form-data">
                <div class="modal-body scroll-y mx-5 mx-xl-15 my-7">
                    @if(session('error'))
                        <div class="alert alert-danger">
                            {{ session('error') }}
                        </div>
                    @endif
                    @if($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    
                    <div class="mb-5">
                        <label for="file_input_upload" class="form-label required">Dosya</label>
                        <input type="file" wire:model="file" name="file" id="file_input_upload" class="form-control @error('file') is-invalid @enderror" required accept="*/*">
                        @error('file')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        @if($file)
                            <div class="form-text">Seçilen dosya: {{ $file->getClientOriginalName() }}</div>
                        @endif
                    </div>

                    <div class="mb-5">
                        <label for="file_name_input" class="form-label">Dosya Adı</label>
                        <input type="text" wire:model="name" name="name" id="file_name_input" class="form-control" placeholder="Boş bırakılırsa orijinal ad kullanılır">
                    </div>

                    <div class="mb-5">
                        <label for="file_category_input" class="form-label">Kategori</label>
                        <input type="text" wire:model="category" name="category" id="file_category_input" class="form-control">
                    </div>

                    <div class="mb-5">
                        <label for="file_description_input" class="form-label">Açıklama</label>
                        <textarea wire:model="description" name="description" id="file_description_input" class="form-control" rows="3"></textarea>
                    </div>

                    <div class="mb-5">
                        <label class="form-check form-switch form-check-custom form-check-solid">
                            <input class="form-check-input" type="checkbox" wire:model="is_public" name="is_public" id="file_is_public_input" value="1">
                            <span class="form-check-label" for="file_is_public_input">Herkese Açık</span>
                        </label>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">İptal</button>
                    <button type="submit" class="btn btn-primary">
                        <span wire:loading.remove wire:target="submit">Yükle</span>
                        <span wire:loading wire:target="submit">Yükleniyor...</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
