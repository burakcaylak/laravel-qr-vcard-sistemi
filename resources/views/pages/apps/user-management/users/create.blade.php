<x-default-layout>

    @section('title')
        Yeni Kullanıcı
    @endsection

    @section('breadcrumbs')
        {{ Breadcrumbs::render('user-management.users.index') }}
    @endsection

    <div class="card">
        <div class="card-header">
            <div class="card-title">
                <h3 class="fw-bold m-0">Yeni Kullanıcı Oluştur</h3>
            </div>
        </div>
        <div class="card-body">
            <form action="{{ route('user-management.users.store') }}" method="POST">
                @csrf
                
                <div class="row">
                    <div class="col-md-6 mb-5">
                        <label class="form-label required">Ad Soyad</label>
                        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6 mb-5">
                        <label class="form-label required">E-Posta</label>
                        <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}" required>
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-5">
                        <label class="form-label required">Şifre</label>
                        <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" required>
                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6 mb-5">
                        <label class="form-label">Bölüm</label>
                        <input type="text" name="department" class="form-control" value="{{ old('department') }}">
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-5">
                        <label class="form-label">Unvan</label>
                        <input type="text" name="title" class="form-control" value="{{ old('title') }}">
                    </div>
                    <div class="col-md-6 mb-5">
                        <label class="form-label">Hesap ID</label>
                        <input type="text" name="account_id" class="form-control @error('account_id') is-invalid @enderror" value="{{ old('account_id') }}">
                        @error('account_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-5">
                        <label class="form-label required">Rol</label>
                        <select name="role" class="form-select @error('role') is-invalid @enderror" required>
                            <option value="">Rol Seçin</option>
                            @foreach($roles as $role)
                                @php
                                    $roleNames = [
                                        'superadmin' => 'Süper Yönetici',
                                        'editor' => 'Editör',
                                    ];
                                    $roleDisplay = $roleNames[$role->name] ?? ucfirst($role->name);
                                @endphp
                                <option value="{{ $role->name }}" {{ old('role') == $role->name ? 'selected' : '' }}>{{ $roleDisplay }}</option>
                            @endforeach
                        </select>
                        @error('role')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6 mb-5">
                        <label class="form-label required">Panel Dili</label>
                        <select name="language" class="form-select" required>
                            <option value="tr" {{ old('language', 'tr') == 'tr' ? 'selected' : '' }}>{{ __('common.turkish') }}</option>
                            <option value="en" {{ old('language') == 'en' ? 'selected' : '' }}>{{ __('common.english') }}</option>
                        </select>
                    </div>
                </div>

                <div class="d-flex justify-content-end">
                    <a href="{{ route('user-management.users.index') }}" class="btn btn-light me-3">İptal</a>
                    <button type="submit" class="btn btn-primary">Oluştur</button>
                </div>
            </form>
        </div>
    </div>

</x-default-layout>

