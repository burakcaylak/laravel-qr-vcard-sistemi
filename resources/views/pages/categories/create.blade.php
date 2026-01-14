<x-default-layout>

    @section('title')
        {{ __('common.create_category') }}
    @endsection

    @section('breadcrumbs')
        {{ Breadcrumbs::render('categories.create') }}
    @endsection

    <div class="card">
        <div class="card-header">
            <div class="card-title">
                <h3 class="fw-bold m-0">{{ __('common.create_category_title') }}</h3>
            </div>
        </div>
        <div class="card-body">
            <form action="{{ route('categories.store') }}" method="POST">
                @csrf
                
                <div class="mb-5">
                    <label class="form-label required">{{ __('common.category_name') }}</label>
                    <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" required>
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-5">
                    <label class="form-label">{{ __('common.description') }}</label>
                    <textarea name="description" class="form-control" rows="3">{{ old('description') }}</textarea>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-5">
                        <label class="form-label">{{ __('common.category_color') }}</label>
                        <input type="color" name="color" class="form-control form-control-color" value="{{ old('color', '#667eea') }}">
                        <small class="form-text text-muted">{{ __('common.select_color') }}</small>
                    </div>
                    <div class="col-md-6 mb-5">
                        <label class="form-label">{{ __('common.sort_order') }}</label>
                        <input type="number" name="sort_order" class="form-control" value="{{ old('sort_order', 0) }}" min="0">
                    </div>
                </div>

                <div class="mb-5">
                    <label class="form-check form-switch form-check-custom form-check-solid">
                        <input class="form-check-input" type="checkbox" name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }}>
                        <span class="form-check-label">{{ __('common.active') }}</span>
                    </label>
                </div>

                <div class="d-flex justify-content-end">
                    <a href="{{ route('categories.index') }}" class="btn btn-light me-3">{{ __('common.cancel') }}</a>
                    <button type="submit" class="btn btn-primary">{{ __('common.create') }}</button>
                </div>
            </form>
        </div>
    </div>

</x-default-layout>
