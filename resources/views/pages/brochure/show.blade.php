<x-default-layout>

    @section('title')
        {{ __('common.brochure') }} {{ __('common.details') }}
    @endsection

    @section('breadcrumbs')
        {{ Breadcrumbs::render('brochure.show', $brochure) }}
    @endsection

    <div class="card">
        <div class="card-header">
            <div class="card-title">
                <span class="svg-icon svg-icon-success svg-icon-2 me-2">
                    <i class="ki-solid ki-document fs-2"></i>
                </span>
                <h3 class="fw-bold m-0 d-inline">{{ $brochure->name }}</h3>
            </div>
            <div class="card-toolbar">
                <a href="{{ route('brochure.download', $brochure) }}" class="btn btn-primary me-3">
                    {!! getIcon('download', 'fs-2', '', 'i') !!}
                    {{ __('common.download_qr_code') }}
                </a>
                <a href="{{ route('brochure.edit', $brochure) }}" class="btn btn-light">
                    {!! getIcon('pencil', 'fs-2', '', 'i') !!}
                    {{ __('common.edit') }}
                </a>
            </div>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <div class="text-center mb-10">
                        @if($brochure->qr_code_path && \Illuminate\Support\Facades\Storage::disk('public')->exists($brochure->qr_code_path))
                            <img src="{{ asset('storage/' . $brochure->qr_code_path) }}" alt="QR Code" class="img-fluid mb-5" style="max-width: 300px;">
                        @endif
                    </div>
                    <div class="text-center">
                        <p class="text-muted mb-2">
                            Token: <code>{{ $brochure->token }}</code>
                        </p>
                        @php
                            $publicUrl = route('brochure.access', $brochure->token);
                        @endphp
                        <a href="{{ $publicUrl }}" target="_blank" class="text-primary text-decoration-none">
                            {{ $publicUrl }}
                        </a>
                    </div>
                </div>
                <div class="col-md-6">
                    <table class="table table-borderless">
                        <tr>
                            <th>{{ __('common.category') }}:</th>
                            <td>{{ $brochure->category->name ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th>{{ __('common.name') }}:</th>
                            <td>{{ $brochure->name }}</td>
                        </tr>
                        <tr>
                            <th>{{ __('common.description') }}:</th>
                            <td>{{ $brochure->description ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th>{{ __('common.background_type') }}:</th>
                            <td>
                                @if($brochure->background_type === 'image')
                                    <span class="badge badge-light-info">{{ __('common.image') }}</span>
                                    @if($brochure->background_image_path)
                                        <br><img src="{{ asset('storage/' . $brochure->background_image_path) }}" alt="Background" class="img-thumbnail mt-2" style="max-width: 150px;">
                                    @endif
                                @else
                                    <div class="d-flex align-items-center gap-2">
                                        <span class="badge badge-light-primary">{{ __('common.color') }}</span>
                                        <div style="width: 30px; height: 30px; background-color: {{ $brochure->background_color ?? '#ffffff' }}; border: 1px solid #ddd; border-radius: 3px;"></div>
                                        <code>{{ $brochure->background_color ?? '#ffffff' }}</code>
                                    </div>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th>{{ __('common.status') }}:</th>
                            <td>
                                @if($brochure->is_expired)
                                    <span class="badge badge-light-danger">{{ __('common.expired') }}</span>
                                @elseif($brochure->is_active)
                                    <span class="badge badge-light-success">{{ __('common.active') }}</span>
                                @else
                                    <span class="badge badge-light-secondary">{{ __('common.inactive') }}</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th>{{ __('common.view_count') }}:</th>
                            <td>{{ $brochure->view_count }}</td>
                        </tr>
                        <tr>
                            <th>{{ __('common.download_count') }}:</th>
                            <td>{{ $brochure->download_count }}</td>
                        </tr>
                        <tr>
                            <th>{{ __('common.created_at') }}:</th>
                            <td>{{ $brochure->created_at->translatedFormat('d F Y, H:i') }}</td>
                        </tr>
                        @if($brochure->expires_at)
                        <tr>
                            <th>{{ __('common.expires_at') }}:</th>
                            <td>{{ $brochure->expires_at->translatedFormat('d F Y, H:i') }}</td>
                        </tr>
                        @endif
                    </table>
                </div>
            </div>
        </div>
    </div>

</x-default-layout>
