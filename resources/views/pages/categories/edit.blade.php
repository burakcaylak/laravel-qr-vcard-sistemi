<x-default-layout>

    @section('title')
        Kategori Düzenle
    @endsection

    @section('breadcrumbs')
        {{ Breadcrumbs::render('categories.edit', $category) }}
    @endsection

    <div class="card">
        <div class="card-header">
            <div class="card-title">
                <h3 class="fw-bold m-0">Kategori Düzenle</h3>
            </div>
        </div>
        <div class="card-body">
            <form action="{{ route('categories.update', $category) }}" method="POST">
                @csrf
                @method('PUT')
                
                <div class="mb-5">
                    <label class="form-label required">Kategori Adı</label>
                    <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $category->name) }}" required>
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-5">
                    <label class="form-label">Açıklama</label>
                    <textarea name="description" class="form-control" rows="3">{{ old('description', $category->description) }}</textarea>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-5">
                        <label class="form-label">Renk (Hex)</label>
                        <input type="color" name="color" class="form-control form-control-color" value="{{ old('color', $category->color ?? '#667eea') }}">
                        <small class="form-text text-muted">Kategori için renk seçin</small>
                    </div>
                    <div class="col-md-6 mb-5">
                        <label class="form-label">Sıralama</label>
                        <input type="number" name="sort_order" class="form-control" value="{{ old('sort_order', $category->sort_order) }}" min="0">
                    </div>
                </div>

                <div class="mb-5">
                    <label class="form-check form-switch form-check-custom form-check-solid">
                        <input class="form-check-input" type="checkbox" name="is_active" value="1" {{ old('is_active', $category->is_active) ? 'checked' : '' }}>
                        <span class="form-check-label">Aktif</span>
                    </label>
                </div>

                <div class="d-flex justify-content-end">
                    <a href="{{ route('categories.index') }}" class="btn btn-light me-3">İptal</a>
                    <button type="submit" class="btn btn-primary">Güncelle</button>
                </div>
            </form>
        </div>
    </div>

</x-default-layout>
