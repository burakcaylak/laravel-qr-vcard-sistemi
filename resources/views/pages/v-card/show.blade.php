<x-default-layout>

    @section('title')
        {{ __('common.v_card') }} {{ __('common.details') }}
    @endsection

    @section('breadcrumbs')
        {{ Breadcrumbs::render('v-card.show', $vCard) }}
    @endsection

    <div class="card">
        <div class="card-header">
            <div class="card-title">
                <span class="svg-icon svg-icon-success svg-icon-2 me-2">
                    <i class="ki-solid ki-profile-user fs-2"></i>
                </span>
                <h3 class="fw-bold m-0 d-inline">{{ $vCard->getLocalizedField('name') ?? 'vCard #' . $vCard->id }}</h3>
            </div>
            <div class="card-toolbar">
                <a href="{{ route('v-card.download', $vCard) }}" class="btn btn-primary me-3">
                    {!! getIcon('download', 'fs-2', '', 'i') !!}
                    {{ __('common.download') }}
                </a>
                <a href="{{ route('v-card.edit', $vCard) }}" class="btn btn-light">
                    {!! getIcon('pencil', 'fs-2', '', 'i') !!}
                    {{ __('common.edit') }}
                </a>
            </div>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <div class="text-center mb-10">
                        @if($vCard->image_path)
                            <img src="{{ asset('storage/' . $vCard->image_path) }}" alt="vCard Image" class="img-thumbnail mb-5" style="max-width: 200px;">
                        @endif
                        @if($vCard->file_path)
                            <img src="{{ asset('storage/' . $vCard->file_path) }}" alt="vCard QR" class="img-fluid" style="max-width: 300px;">
                        @endif
                    </div>
                    <div class="text-center">
                        <p class="text-muted mb-2">
                            Token: <code>{{ $vCard->token }}</code>
                        </p>
                        @php
                            $publicUrl = route('vcard.access', $vCard->token);
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
                            <td>{{ $vCard->category->name ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th>{{ __('common.name') }}:</th>
                            <td>{{ $vCard->getLocalizedField('name') ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th>{{ __('common.title') }}:</th>
                            <td>{{ $vCard->getLocalizedField('title') ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th>{{ __('common.email') }}:</th>
                            <td>{{ $vCard->email ?? $vCard->getLocalizedField('email') ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th>{{ __('common.phone') }}:</th>
                            <td>{{ $vCard->phone ?? $vCard->getLocalizedField('phone') ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th>{{ __('common.company') }}:</th>
                            <td>{{ $vCard->getLocalizedField('company') ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th>{{ __('common.status') }}:</th>
                            <td>
                                @if($vCard->is_expired)
                                    <span class="badge badge-light-danger">{{ __('common.expired') }}</span>
                                @elseif($vCard->is_active)
                                    <span class="badge badge-light-success">{{ __('common.active') }}</span>
                                @else
                                    <span class="badge badge-light-secondary">{{ __('common.inactive') }}</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th>{{ __('common.scan_count') }}:</th>
                            <td>{{ $vCard->scan_count }}</td>
                        </tr>
                        <tr>
                            <th>{{ __('common.created_at') }}:</th>
                            <td>{{ $vCard->created_at->translatedFormat('d F Y, H:i') }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>

</x-default-layout>
