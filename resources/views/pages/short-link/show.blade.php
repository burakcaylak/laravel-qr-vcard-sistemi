<x-default-layout>

    @section('title')
        {{ $shortLink->title ?? $shortLink->short_code }}
    @endsection

    @section('breadcrumbs')
        {{ Breadcrumbs::render('short-link.show', $shortLink) }}
    @endsection

    <div class="card">
        <div class="card-header">
            <div class="card-title">
                <span class="svg-icon svg-icon-success svg-icon-2 me-2">
                    {!! getIcon('disconnect', 'fs-2') !!}
                </span>
                <h3 class="fw-bold m-0 d-inline">{{ $shortLink->title ?? $shortLink->short_code }}</h3>
            </div>
            <div class="card-toolbar">
                @if($shortLink->qr_code_path && \Illuminate\Support\Facades\Storage::disk('public')->exists($shortLink->qr_code_path))
                <a href="{{ route('short-link.qr.download', $shortLink) }}" class="btn btn-primary me-3">
                    {!! getIcon('download', 'fs-2', '', 'i') !!}
                    {{ __('common.download_qr_code') }}
                </a>
                @endif
                <a href="{{ route('short-link.edit', $shortLink) }}" class="btn btn-light">
                    {!! getIcon('pencil', 'fs-2', '', 'i') !!}
                    {{ __('common.edit') }}
                </a>
            </div>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-10">
                        <h4 class="fw-bold mb-3">{{ __('common.short_link_info') }}</h4>
                        <div class="alert alert-info d-flex align-items-center p-5">
                            <div class="flex-grow-1">
                                <h5 class="mb-2">{{ __('common.short_url') }}</h5>
                                <a href="{{ $shortLink->short_url }}" target="_blank" class="text-primary fw-bold fs-4">{{ $shortLink->short_url }}</a>
                            </div>
                            <button type="button" class="btn btn-sm btn-light-primary" onclick="copyToClipboard('{{ $shortLink->short_url }}')">
                                <i class="ki-solid ki-copy fs-4"></i>
                            </button>
                        </div>
                    </div>
                    <div class="mb-10">
                        <h4 class="fw-bold mb-3">{{ __('common.qr_code') }}</h4>
                        <div class="text-center">
                            @if($shortLink->qr_code_path && \Illuminate\Support\Facades\Storage::disk('public')->exists($shortLink->qr_code_path))
                                <img src="{{ asset('storage/' . $shortLink->qr_code_path) }}" alt="QR Code" class="img-fluid" style="max-width: 300px;">
                                <div class="mt-3">
                                    <a href="{{ route('short-link.qr.download', $shortLink) }}" class="btn btn-sm btn-primary">
                                        {!! getIcon('download', 'fs-2', '', 'i') !!}
                                        {{ __('common.download') }}
                                    </a>
                                </div>
                            @else
                                <div class="alert alert-warning">
                                    <p class="mb-0">{{ __('common.qr_code_not_generated') }}</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <table class="table table-borderless">
                        <tr>
                            <th>{{ __('common.short_code') }}:</th>
                            <td><code>{{ $shortLink->short_code }}</code></td>
                        </tr>
                        <tr>
                            <th>{{ __('common.original_url') }}:</th>
                            <td><a href="{{ $shortLink->original_url }}" target="_blank">{{ \Illuminate\Support\Str::limit($shortLink->original_url, 50) }}</a></td>
                        </tr>
                        @if($shortLink->title)
                        <tr>
                            <th>{{ __('common.title') }}:</th>
                            <td>{{ $shortLink->title }}</td>
                        </tr>
                        @endif
                        @if($shortLink->description)
                        <tr>
                            <th>{{ __('common.description') }}:</th>
                            <td>{{ $shortLink->description }}</td>
                        </tr>
                        @endif
                        <tr>
                            <th>{{ __('common.category') }}:</th>
                            <td>{{ $shortLink->category ? $shortLink->category->name : '-' }}</td>
                        </tr>
                        <tr>
                            <th>{{ __('common.click_count') }}:</th>
                            <td><span class="badge badge-light-info">{{ $shortLink->click_count }}</span></td>
                        </tr>
                        <tr>
                            <th>{{ __('common.status') }}:</th>
                            <td>
                                @if($shortLink->is_expired)
                                    <span class="badge badge-light-danger">{{ __('common.expired') }}</span>
                                @elseif($shortLink->is_active)
                                    <span class="badge badge-light-success">{{ __('common.active') }}</span>
                                @else
                                    <span class="badge badge-light-secondary">{{ __('common.inactive') }}</span>
                                @endif
                            </td>
                        </tr>
                        @if($shortLink->hasPassword())
                        <tr>
                            <th>{{ __('common.password_protected') }}:</th>
                            <td><span class="badge badge-light-warning">{{ __('common.yes') }}</span></td>
                        </tr>
                        @endif
                        @if($shortLink->expires_at)
                        <tr>
                            <th>{{ __('common.expires_at') }}:</th>
                            <td>{{ $shortLink->expires_at->translatedFormat('d F Y, H:i') }}</td>
                        </tr>
                        @endif
                        <tr>
                            <th>{{ __('common.created_at') }}:</th>
                            <td>{{ $shortLink->created_at->translatedFormat('d F Y, H:i') }}</td>
                        </tr>
                    </table>
                </div>
            </div>

            @if($shortLink->clicks && $shortLink->clicks->count() > 0)
            <div class="separator separator-dashed my-10"></div>
            <div class="row">
                <div class="col-12">
                    <h4 class="fw-bold mb-5">{{ __('common.click_history') }}</h4>
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>{{ __('common.date_time') }}</th>
                                    <th>{{ __('common.ip_address') }}</th>
                                    <th>{{ __('common.browser') }}</th>
                                    <th>{{ __('common.platform') }}</th>
                                    <th>{{ __('common.device_type') }}</th>
                                    <th>{{ __('common.referer') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($shortLink->clicks as $click)
                                <tr>
                                    <td>{{ $click->created_at->translatedFormat('d F Y, H:i') }}</td>
                                    <td>{{ $click->ip_address }}</td>
                                    <td>{{ $click->browser }}</td>
                                    <td>{{ $click->platform }}</td>
                                    <td><span class="badge badge-light-info">{{ $click->device_type }}</span></td>
                                    <td>{{ $click->referer ? \Illuminate\Support\Str::limit($click->referer, 30) : '-' }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>

    @push('scripts')
    <script>
        function copyToClipboard(text) {
            navigator.clipboard.writeText(text).then(function() {
                Swal.fire({
                    icon: 'success',
                    title: '{{ __('common.copied') }}',
                    text: '{{ __('common.copied_to_clipboard') }}',
                    timer: 2000,
                    showConfirmButton: false
                });
            });
        }
    </script>
    @endpush

</x-default-layout>
