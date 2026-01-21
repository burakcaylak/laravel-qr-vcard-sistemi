<x-default-layout>

@section('title')
    {{ __('common.api_tokens') }}
@endsection

@section('breadcrumbs')
    {{ Breadcrumbs::render('api-tokens.index') }}
@endsection

    <!--begin::Content container-->
    <div id="kt_app_content_container" class="app-container container-xxl">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    @if(session('token'))
                        <div class="alert alert-info alert-dismissible fade show" role="alert">
                            <h4 class="alert-heading">{{ __('common.api_token_created') }}</h4>
                            <p>{{ __('common.api_token_copy_message') }}</p>
                            <hr>
                            <div class="d-flex align-items-center">
                                <code class="flex-grow-1 me-2" id="token-display">{{ session('token') }}</code>
                                <button type="button" class="btn btn-sm btn-light-primary" onclick="copyToken()">
                                    <i class="ki-duotone ki-copy fs-2">
                                        <span class="path1"></span>
                                        <span class="path2"></span>
                                    </i>
                                    {{ __('common.copy') }}
                                </button>
                            </div>
                            <p class="mb-0 mt-2"><small class="text-danger">{{ __('common.api_token_warning') }}</small></p>
                        </div>
                    @endif

                    @if($errors->any())
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <ul class="mb-0">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <!--begin::Card-->
                    <div class="card">
                        <div class="card-header border-0 pt-6">
                            <div class="card-title">
                                <h3 class="fw-bold m-0">{{ __('common.create_api_token') }}</h3>
                            </div>
                        </div>
                        <div class="card-body pt-0">
                            <form action="{{ route('api-tokens.store') }}" method="POST">
                                @csrf
                                <div class="row">
                                    <div class="col-md-8">
                                        <div class="mb-5">
                                            <label class="form-label required">{{ __('common.token_name') }}</label>
                                            <input type="text" name="name" class="form-control" 
                                                   placeholder="{{ __('common.token_name_placeholder') }}" 
                                                   required>
                                        </div>
                                    </div>
                                    <div class="col-md-4 d-flex align-items-end">
                                        <button type="submit" class="btn btn-primary w-100">
                                            <i class="ki-duotone ki-plus fs-2">
                                                <span class="path1"></span>
                                                <span class="path2"></span>
                                            </i>
                                            {{ __('common.create_token') }}
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                    <!--end::Card-->

                    <!--begin::Card-->
                    <div class="card mt-5">
                        <div class="card-header border-0 pt-6">
                            <div class="card-title">
                                <h3 class="fw-bold m-0">{{ __('common.my_api_tokens') }}</h3>
                            </div>
                            @if($tokens->count() > 0)
                                <div class="card-toolbar">
                                    <form action="{{ route('api-tokens.destroy-all') }}" method="POST" 
                                          onsubmit="return confirm('{{ __('common.delete_all_tokens_confirm') }}')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger">
                                            <i class="ki-duotone ki-trash fs-2">
                                                <span class="path1"></span>
                                                <span class="path2"></span>
                                                <span class="path3"></span>
                                                <span class="path4"></span>
                                                <span class="path5"></span>
                                            </i>
                                            {{ __('common.delete_all_tokens') }}
                                        </button>
                                    </form>
                                </div>
                            @endif
                        </div>
                        <div class="card-body pt-0">
                            @if($tokens->count() > 0)
                                <div class="table-responsive">
                                    <table class="table table-row-bordered table-row-dashed gy-7">
                                        <thead>
                                            <tr class="fw-bold fs-6 text-gray-800">
                                                <th>{{ __('common.name') }}</th>
                                                <th>{{ __('common.created_at') }}</th>
                                                <th>{{ __('common.last_used_at') }}</th>
                                                <th class="text-end">{{ __('common.actions') }}</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($tokens as $token)
                                                <tr>
                                                    <td>
                                                        <span class="fw-semibold text-gray-800">{{ $token->name }}</span>
                                                    </td>
                                                    <td>
                                                        <span class="text-gray-600">{{ $token->created_at->format('d.m.Y H:i') }}</span>
                                                    </td>
                                                    <td>
                                                        @if($token->last_used_at)
                                                            <span class="text-gray-600">{{ \Carbon\Carbon::parse($token->last_used_at)->format('d.m.Y H:i') }}</span>
                                                        @else
                                                            <span class="text-muted">{{ __('common.never') }}</span>
                                                        @endif
                                                    </td>
                                                    <td class="text-end">
                                                        <form action="{{ route('api-tokens.destroy', $token->id) }}" 
                                                              method="POST" 
                                                              onsubmit="return confirm('{{ __('common.delete_token_confirm') }}')"
                                                              class="d-inline">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-sm btn-light-danger">
                                                                <i class="ki-duotone ki-trash fs-2">
                                                                    <span class="path1"></span>
                                                                    <span class="path2"></span>
                                                                    <span class="path3"></span>
                                                                    <span class="path4"></span>
                                                                    <span class="path5"></span>
                                                                </i>
                                                                {{ __('common.delete') }}
                                                            </button>
                                                        </form>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @else
                                <div class="text-center py-10">
                                    <i class="ki-duotone ki-information-5 fs-3x text-gray-400 mb-5">
                                        <span class="path1"></span>
                                        <span class="path2"></span>
                                        <span class="path3"></span>
                                    </i>
                                    <p class="text-gray-600 fs-5">{{ __('common.no_api_tokens') }}</p>
                                </div>
                            @endif
                        </div>
                    </div>
                    <!--end::Card-->

                    <!--begin::Card - API Documentation-->
                    <div class="card mt-5">
                        <div class="card-header border-0 pt-6">
                            <div class="card-title">
                                <h3 class="fw-bold m-0">{{ __('common.api_documentation') }}</h3>
                            </div>
                        </div>
                        <div class="card-body pt-0">
                            <div class="mb-5">
                                <h4 class="fw-bold mb-3">{{ __('common.base_url') }}</h4>
                                <code class="d-block p-3 bg-light-primary rounded">{{ url('/api/v1') }}</code>
                            </div>

                            <div class="mb-5">
                                <h4 class="fw-bold mb-3">{{ __('common.authentication') }}</h4>
                                <p class="text-gray-600">{{ __('common.api_auth_description') }}</p>
                                <pre class="bg-light p-3 rounded"><code>Authorization: Bearer YOUR_TOKEN_HERE</code></pre>
                            </div>

                            <div class="mb-5">
                                <h4 class="fw-bold mb-3">{{ __('common.available_endpoints') }}</h4>
                                <div class="table-responsive">
                                    <table class="table table-row-bordered">
                                        <thead>
                                            <tr>
                                                <th>{{ __('common.method') }}</th>
                                                <th>{{ __('common.endpoint') }}</th>
                                                <th>{{ __('common.description') }}</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td><span class="badge badge-success">GET</span></td>
                                                <td><code>/short-links</code></td>
                                                <td>{{ __('common.get_short_links') }}</td>
                                            </tr>
                                            <tr>
                                                <td><span class="badge badge-primary">POST</span></td>
                                                <td><code>/short-links</code></td>
                                                <td>{{ __('common.create_short_link') }}</td>
                                            </tr>
                                            <tr>
                                                <td><span class="badge badge-success">GET</span></td>
                                                <td><code>/qr-codes</code></td>
                                                <td>{{ __('common.get_qr_codes') }}</td>
                                            </tr>
                                            <tr>
                                                <td><span class="badge badge-primary">POST</span></td>
                                                <td><code>/qr-codes</code></td>
                                                <td>{{ __('common.create_qr_code') }}</td>
                                            </tr>
                                            <tr>
                                                <td><span class="badge badge-success">GET</span></td>
                                                <td><code>/brochures</code></td>
                                                <td>{{ __('common.get_brochures') }}</td>
                                            </tr>
                                            <tr>
                                                <td><span class="badge badge-primary">POST</span></td>
                                                <td><code>/brochures</code></td>
                                                <td>{{ __('common.create_brochure') }}</td>
                                            </tr>
                                            <tr>
                                                <td><span class="badge badge-success">GET</span></td>
                                                <td><code>/vcards</code></td>
                                                <td>{{ __('common.get_vcards') }}</td>
                                            </tr>
                                            <tr>
                                                <td><span class="badge badge-primary">POST</span></td>
                                                <td><code>/vcards</code></td>
                                                <td>{{ __('common.create_vcard') }}</td>
                                            </tr>
                                            <tr>
                                                <td><span class="badge badge-success">GET</span></td>
                                                <td><code>/files</code></td>
                                                <td>{{ __('common.get_files') }}</td>
                                            </tr>
                                            <tr>
                                                <td><span class="badge badge-primary">POST</span></td>
                                                <td><code>/files</code></td>
                                                <td>{{ __('common.upload_file') }}</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <div class="mb-5">
                                <h4 class="fw-bold mb-3">{{ __('common.example_request') }}</h4>
                                <pre class="bg-light p-3 rounded"><code>curl -X GET "{{ url('/api/v1/short-links') }}" \
  -H "Authorization: Bearer YOUR_TOKEN_HERE" \
  -H "Accept: application/json"</code></pre>
                            </div>
                        </div>
                    </div>
                    <!--end::Card-->
    </div>
    <!--end::Content container-->
</x-default-layout>

@push('scripts')
<script>
    function copyToken() {
        const tokenDisplay = document.getElementById('token-display');
        const text = tokenDisplay.textContent;
        
        navigator.clipboard.writeText(text).then(function() {
            Swal.fire({
                icon: 'success',
                title: '{{ __('common.copied') }}',
                text: '{{ __('common.token_copied') }}',
                timer: 2000,
                showConfirmButton: false
            });
        });
    }
</script>
@endpush
