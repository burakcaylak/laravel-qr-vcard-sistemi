<x-default-layout>

    @section('title')
        {{ __('common.edit_user') }}
    @endsection

    @section('breadcrumbs')
        {{ Breadcrumbs::render('user-management.users.show', $user) }}
    @endsection

    <div class="card">
        <div class="card-header">
            <div class="card-title">
                <h3 class="fw-bold m-0">{{ __('common.edit_user') }}</h3>
            </div>
        </div>
        <div class="card-body">
            <form action="{{ route('user-management.users.update', $user) }}" method="POST">
                @csrf
                @method('PUT')
                
                <div class="row">
                    <div class="col-md-6 mb-5">
                        <label class="form-label required">Ad Soyad</label>
                        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $user->name) }}" required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6 mb-5">
                        <label class="form-label required">E-Posta</label>
                        <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email', $user->email) }}" required>
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-5">
                        <label class="form-label">{{ __('common.password_change_hint') }}</label>
                        <input type="password" name="password" class="form-control @error('password') is-invalid @enderror">
                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6 mb-5">
                        <label class="form-label">Bölüm</label>
                        <input type="text" name="department" class="form-control" value="{{ old('department', $user->department) }}">
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-5">
                        <label class="form-label">Unvan</label>
                        <input type="text" name="title" class="form-control" value="{{ old('title', $user->title) }}">
                    </div>
                    <div class="col-md-6 mb-5">
                        <label class="form-label">Hesap ID</label>
                        <input type="text" name="account_id" class="form-control @error('account_id') is-invalid @enderror" value="{{ old('account_id', $user->account_id) }}">
                        @error('account_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="row">
                    @if(!auth()->user()->hasRole('editor'))
                        <div class="col-md-6 mb-5">
                            <label class="form-label required">Rol</label>
                            <select name="role" class="form-select @error('role') is-invalid @enderror" required>
                                <option value="">{{ __('common.select_role') }}</option>
                                @foreach($roles as $role)
                                    @php
                                    $roleNames = [
                                        'superadmin' => __('common.super_admin'),
                                        'editor' => __('common.editor'),
                                    ];
                                        $roleDisplay = $roleNames[$role->name] ?? ucfirst($role->name);
                                    @endphp
                                    <option value="{{ $role->name }}" {{ old('role', $user->roles->first()?->name) == $role->name ? 'selected' : '' }}>{{ $roleDisplay }}</option>
                                @endforeach
                            </select>
                            @error('role')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    @else
                        <div class="col-md-6 mb-5">
                            <label class="form-label">Rol</label>
                            <div class="form-control-plaintext">
                                @foreach($user->roles as $role)
                                    @php
                                    $roleNames = [
                                        'superadmin' => __('common.super_admin'),
                                        'editor' => __('common.editor'),
                                    ];
                                        $roleDisplay = $roleNames[$role->name] ?? ucfirst($role->name);
                                    @endphp
                                    <span class="badge badge-lg badge-light-primary">{{ $roleDisplay }}</span>
                                @endforeach
                            </div>
                            <input type="hidden" name="role" value="{{ $user->roles->first()?->name }}">
                        </div>
                    @endif
                    <div class="col-md-6 mb-5">
                        <label class="form-label required">{{ __('common.panel_language') }}</label>
                        <select name="language" class="form-select" required>
                            <option value="tr" {{ old('language', $user->language ?? 'tr') == 'tr' ? 'selected' : '' }}>{{ __('common.turkish') }}</option>
                            <option value="en" {{ old('language', $user->language) == 'en' ? 'selected' : '' }}>{{ __('common.english') }}</option>
                        </select>
                    </div>
                </div>

                <div class="d-flex justify-content-end">
                    <a href="{{ route('user-management.users.show', $user) }}" class="btn btn-light me-3">{{ __('common.cancel') }}</a>
                    <button type="submit" class="btn btn-primary">{{ __('common.save') }}</button>
                </div>
            </form>
        </div>
    </div>

</x-default-layout>

