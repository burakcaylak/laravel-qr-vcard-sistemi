<x-default-layout>

    @section('title')
        {{ __('common.vcard_template_management') }}
    @endsection

    @section('breadcrumbs')
        {{ Breadcrumbs::render('v-card-template.index') }}
    @endsection

    <div class="card">
        <div class="card-header border-0 pt-6">
            <div class="card-title">
                <h3 class="fw-bold m-0">{{ __('common.vcard_templates') }}</h3>
            </div>
            <div class="card-toolbar">
                <a href="{{ route('v-card-template.create') }}" class="btn btn-primary">
                    <i class="ki-solid ki-plus fs-2"></i>
                    {{ __('common.create_vcard_template') }}
                </a>
            </div>
        </div>
        <div class="card-body py-4">
            <div class="table-responsive">
                <table class="table table-row-dashed table-row-gray-300 align-middle gs-0 gy-4">
                    <thead>
                        <tr class="fw-bold text-muted">
                            <th>{{ __('common.template_name') }}</th>
                            <th>{{ __('common.template_logo') }}</th>
                            <th>{{ __('common.template_color') }}</th>
                            <th>{{ __('common.template_background') }}</th>
                            <th>{{ __('common.status') }}</th>
                            <th>{{ __('common.actions') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($templates as $template)
                            <tr>
                                <td>{{ $template->name }}</td>
                                <td>
                                    @if($template->logo_path)
                                        <img src="{{ asset('storage/' . $template->logo_path) }}" alt="Logo" class="img-thumbnail" style="max-width: 80px; max-height: 40px;">
                                    @else
                                        -
                                    @endif
                                </td>
                                <td>
                                    @if($template->color)
                                        <span class="badge" style="background-color: {{ $template->color }}; color: white;">{{ $template->color }}</span>
                                    @else
                                        -
                                    @endif
                                </td>
                                <td>
                                    @if($template->background_path)
                                        <img src="{{ asset('storage/' . $template->background_path) }}" alt="Background" class="img-thumbnail" style="max-width: 80px; max-height: 40px;">
                                    @else
                                        -
                                    @endif
                                </td>
                                <td>
                                    @if($template->is_active)
                                        <span class="badge badge-light-success">{{ __('common.active') }}</span>
                                    @else
                                        <span class="badge badge-light-secondary">{{ __('common.inactive') }}</span>
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('v-card-template.edit', $template) }}" class="btn btn-sm btn-light-primary me-2">
                                        <i class="ki-solid ki-pencil fs-6"></i>
                                    </a>
                                    <form action="{{ route('v-card-template.destroy', $template) }}" method="POST" class="d-inline" onsubmit="return confirm('{{ __('common.delete_template_confirm') }}');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-light-danger">
                                            <i class="ki-solid ki-trash fs-6"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted">{{ __('common.no_templates') }}</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</x-default-layout>
