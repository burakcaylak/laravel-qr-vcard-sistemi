<x-auth-layout>

    <!--begin::Form-->
    <form class="form w-100" novalidate="novalidate" id="kt_sign_in_form" data-kt-redirect-url="{{ route('dashboard') }}" action="{{ route('login') }}" method="POST">
        @csrf
        <!--begin::Logo-->
        <div class="text-center mb-10">
            <a href="{{ route('dashboard') }}">
                <img alt="Logo" src="{{ getLogo('dark') }}" class="h-50px h-lg-60px"/>
            </a>
        </div>
        <!--end::Logo-->
        
        <!--begin::Heading-->
        <div class="text-center mb-11">
            <!--begin::Title-->
            <h1 class="text-gray-900 fw-bolder mb-3">
                Giriş Yap
            </h1>
            <!--end::Title-->

            <!--begin::Subtitle-->
            <div class="text-gray-500 fw-semibold fs-6">
                Dosya Yönetim Sistemi
            </div>
            <!--end::Subtitle--->
        </div>
        <!--begin::Heading-->


        <!--begin::Input group--->
        <div class="fv-row mb-8">
            <!--begin::Email-->
            <input type="email" placeholder="E-Posta" name="email" autocomplete="email" id="email-input" class="form-control bg-transparent @error('email') is-invalid @enderror" value="{{ old('email') }}"/>
            <datalist id="email-suggestions">
                <option value="@worldmedicine.com.tr">
            </datalist>
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    const emailInput = document.getElementById('email-input');
                    if (emailInput) {
                        emailInput.setAttribute('list', 'email-suggestions');
                        
                        // Kullanıcı @ yazdığında otomatik tamamla
                        emailInput.addEventListener('keydown', function(e) {
                            if (e.key === '@') {
                                setTimeout(function() {
                                    const value = emailInput.value;
                                    if (value.endsWith('@')) {
                                        emailInput.value = value + 'worldmedicine.com.tr';
                                        // Cursor'u @ işaretinden sonraki konuma taşı
                                        const cursorPos = value.length;
                                        emailInput.setSelectionRange(cursorPos, cursorPos);
                                    }
                                }, 10);
                            }
                        });
                    }
                });
            </script>
            @error('email')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
            <!--end::Email-->
        </div>

        <!--end::Input group--->
        <div class="fv-row mb-3">
            <!--begin::Password-->
            <input type="password" placeholder="Şifre" name="password" autocomplete="off" class="form-control bg-transparent @error('password') is-invalid @enderror"/>
            @error('password')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
            <!--end::Password-->
        </div>
        <!--end::Input group--->
        
        @if($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!--begin::Wrapper-->
        <!-- Şifremi Unuttum linki kaldırıldı - Sadece süperadmin şifre değiştirebilir -->
        <!--end::Wrapper-->

        <!--begin::Submit button-->
        <div class="d-grid mb-10">
            <button type="submit" id="kt_sign_in_submit" class="btn btn-primary">
                @include('partials/general/_button-indicator', ['label' => 'Giriş Yap'])
            </button>
        </div>
        <!--end::Submit button-->

        <!--begin::Sign up-->
        <!-- Register kaldırıldı - Sadece superadmin kullanıcı oluşturabilir -->
        <!--end::Sign up-->
    </form>
    <!--end::Form-->

</x-auth-layout>
