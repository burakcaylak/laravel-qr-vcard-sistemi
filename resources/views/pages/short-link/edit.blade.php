<x-default-layout>

    @section('title')
        {{ __('common.edit') }} - {{ $shortLink->title ?? $shortLink->short_code }}
    @endsection

    @section('breadcrumbs')
        {{ Breadcrumbs::render('short-link.edit', $shortLink) }}
    @endsection

    <div class="card">
        <div class="card-header">
            <div class="card-title">
                <span class="svg-icon svg-icon-success svg-icon-2 me-2">
                    <i class="ki-solid ki-link fs-2"></i>
                </span>
                <h3 class="fw-bold m-0 d-inline">{{ __('common.edit') }}</h3>
            </div>
        </div>
        <div class="card-body">
            <form action="{{ route('short-link.update', $shortLink) }}" method="POST" id="short_link_form">
                @csrf
                @method('PUT')
                
                <div class="mb-5">
                    <label class="form-label required">{{ __('common.original_url') }}</label>
                    <input type="url" name="original_url" class="form-control @error('original_url') is-invalid @enderror" value="{{ old('original_url', $shortLink->original_url) }}" placeholder="https://example.com" required>
                    @error('original_url')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-5">
                    <label class="form-label">{{ __('common.title') }}</label>
                    <input type="text" name="title" class="form-control @error('title') is-invalid @enderror" value="{{ old('title', $shortLink->title) }}" placeholder="{{ __('common.title') }}">
                    @error('title')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-5">
                    <label class="form-label">{{ __('common.description') }}</label>
                    <textarea name="description" class="form-control @error('description') is-invalid @enderror" rows="3">{{ old('description', $shortLink->description) }}</textarea>
                    @error('description')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="row">
                    <div class="col-md-6 mb-5">
                        <label class="form-label">{{ __('common.category') }}</label>
                        <select name="category_id" id="category_id" class="form-select" data-control="select2" data-placeholder="{{ __('common.select_category') }}">
                            <option value="">{{ __('common.select_category') }}</option>
                            @foreach($categories ?? [] as $category)
                                <option value="{{ $category->id }}" {{ old('category_id', $shortLink->category_id) == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6 mb-5">
                        <label class="form-label">{{ __('common.short_code') }}</label>
                        <input type="text" name="short_code" class="form-control @error('short_code') is-invalid @enderror" value="{{ old('short_code', $shortLink->short_code) }}" readonly>
                        <small class="form-text text-muted">{{ __('common.short_code_cannot_change') }}</small>
                        @error('short_code')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <!-- Password Protection -->
                <div class="separator separator-dashed my-5"></div>
                <h4 class="fw-bold mb-5">{{ __('common.password_protection') }}</h4>
                <div class="mb-5">
                    <div class="form-check form-switch form-check-custom form-check-solid">
                        <input class="form-check-input" type="checkbox" name="password_protected" id="password_protected" value="1" {{ old('password_protected', $shortLink->password_protected) ? 'checked' : '' }}>
                        <label class="form-check-label" for="password_protected">
                            {{ __('common.password_protected') }}
                        </label>
                    </div>
                    <small class="form-text text-muted">{{ __('common.password_protected_link_hint') }}</small>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-5" id="password_field" style="display: {{ old('password_protected', $shortLink->password_protected) ? 'block' : 'none' }};">
                        <label class="form-label {{ old('password_protected', $shortLink->password_protected) ? 'required' : '' }}">{{ __('common.password') }}</label>
                        <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" placeholder="{{ __('common.password_leave_empty') }}">
                        <small class="form-text text-muted">{{ __('common.password_leave_empty') }}</small>
                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6 mb-5">
                        <label class="form-label">{{ __('common.expires_at') }}</label>
                        <input type="datetime-local" name="expires_at" class="form-control @error('expires_at') is-invalid @enderror" value="{{ old('expires_at', $shortLink->expires_at ? $shortLink->expires_at->format('Y-m-d\TH:i') : '') }}">
                        @error('expires_at')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <!-- End Password Protection -->

                <div class="separator separator-dashed my-5"></div>
                <h4 class="fw-bold mb-5">{{ __('common.qr_code_settings') }}</h4>

                <div class="row">
                    <div class="col-md-6 mb-5">
                        <label class="form-label">{{ __('common.qr_code_size') }}</label>
                        <input type="number" name="qr_code_size" class="form-control" value="{{ old('qr_code_size', $shortLink->qr_code_size ?? 300) }}" min="100" max="1000" step="50">
                        <small class="form-text text-muted">{{ __('common.qr_code_size_hint') }}</small>
                    </div>
                    <div class="col-md-6 mb-5">
                        <label class="form-label">{{ __('common.qr_code_format') }}</label>
                        <select name="qr_code_format" class="form-select">
                            <option value="png" {{ old('qr_code_format', $shortLink->qr_code_format ?? 'png') == 'png' ? 'selected' : '' }}>PNG</option>
                            <option value="svg" {{ old('qr_code_format', $shortLink->qr_code_format) == 'svg' ? 'selected' : '' }}>SVG</option>
                        </select>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-5">
                        <div class="form-check form-switch form-check-custom form-check-solid mt-9">
                            <input class="form-check-input" type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', $shortLink->is_active) ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_active">
                                {{ __('common.is_active') }}
                            </label>
                        </div>
                    </div>
                </div>

                <div class="d-flex justify-content-end">
                    <a href="{{ route('short-link.index') }}" class="btn btn-light me-3">{{ __('common.cancel') }}</a>
                    <button type="submit" class="btn btn-primary">{{ __('common.update') }}</button>
                </div>
            </form>
        </div>
    </div>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const passwordProtectedCheckbox = document.getElementById('password_protected');
            const passwordField = document.getElementById('password_field');

            function togglePasswordField() {
                if (passwordProtectedCheckbox.checked) {
                    passwordField.style.display = 'block';
                } else {
                    passwordField.style.display = 'none';
                }
            }
            passwordProtectedCheckbox.addEventListener('change', togglePasswordField);
            togglePasswordField(); // Initial call
        });
    </script>
    @endpush
</x-default-layout>
