<x-default-layout>

    @section('title')
        Dosya Düzenle
    @endsection

    @section('breadcrumbs')
        <div class="d-flex align-items-center flex-wrap me-3 mb-5 mb-lg-0">
            <a href="{{ route('media-library.index') }}" class="text-gray-500 fw-semibold fs-7">Ortam Kütüphanesi</a>
            <span class="text-gray-500 fw-semibold fs-7 mx-2">/</span>
            <a href="{{ route('media-library.show', $file) }}" class="text-gray-500 fw-semibold fs-7">{{ $file->name }}</a>
            <span class="text-gray-500 fw-semibold fs-7 mx-2">/</span>
            <span class="text-gray-800 fw-semibold fs-7">Düzenle</span>
        </div>
    @endsection

    <div class="card">
        <div class="card-header">
            <div class="card-title">
                <h3 class="fw-bold m-0">Dosya Düzenle</h3>
            </div>
        </div>
        <div class="card-body">
            <form action="{{ route('media-library.update', $file) }}" method="POST">
                @csrf
                @method('PUT')
                
                <div class="mb-5">
                    <label class="form-label required">Dosya Adı</label>
                    <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $file->name) }}" required>
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-5">
                    <label class="form-label">Kategori</label>
                    <select name="category_id" class="form-select @error('category_id') is-invalid @enderror">
                        <option value="">Kategori Seçin</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ old('category_id', $file->category_id) == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('category_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-5">
                    <label class="form-label">Açıklama</label>
                    <textarea name="description" class="form-control @error('description') is-invalid @enderror" rows="3">{{ old('description', $file->description) }}</textarea>
                    @error('description')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-5">
                    <label class="form-check form-switch form-check-custom form-check-solid">
                        <input class="form-check-input" type="checkbox" name="is_public" value="1" {{ old('is_public', $file->is_public) ? 'checked' : '' }}>
                        <span class="form-check-label">Herkese Açık</span>
                    </label>
                </div>

                <div class="d-flex justify-content-end">
                    <a href="{{ route('media-library.show', $file) }}" class="btn btn-light me-3">İptal</a>
                    <button type="submit" class="btn btn-primary">Kaydet</button>
                </div>
            </form>
        </div>
    </div>

</x-default-layout>
