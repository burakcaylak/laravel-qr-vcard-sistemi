<x-default-layout>

    @section('title')
        Dosya Düzenle
    @endsection

    @section('breadcrumbs')
        {{ Breadcrumbs::render('file-management.edit', $file) }}
    @endsection

    <div class="card">
        <div class="card-header">
            <div class="card-title">
                <h3 class="fw-bold m-0">Dosya Düzenle</h3>
            </div>
        </div>
        <div class="card-body">
            <form action="{{ route('file-management.update', $file) }}" method="POST">
                @csrf
                @method('PUT')
                
                <div class="mb-5">
                    <label class="form-label">Dosya Adı</label>
                    <input type="text" name="name" class="form-control" value="{{ old('name', $file->name) }}" required>
                </div>

                <div class="mb-5">
                    <label class="form-label">Kategori</label>
                    <input type="text" name="category" class="form-control" value="{{ old('category', $file->category) }}">
                </div>

                <div class="mb-5">
                    <label class="form-label">Açıklama</label>
                    <textarea name="description" class="form-control" rows="3">{{ old('description', $file->description) }}</textarea>
                </div>

                <div class="mb-5">
                    <label class="form-check form-switch form-check-custom form-check-solid">
                        <input class="form-check-input" type="checkbox" name="is_public" value="1" {{ old('is_public', $file->is_public) ? 'checked' : '' }}>
                        <span class="form-check-label">Herkese Açık</span>
                    </label>
                </div>

                <div class="d-flex justify-content-end">
                    <a href="{{ route('file-management.show', $file) }}" class="btn btn-light me-3">İptal</a>
                    <button type="submit" class="btn btn-primary">Kaydet</button>
                </div>
            </form>
        </div>
    </div>

</x-default-layout>


