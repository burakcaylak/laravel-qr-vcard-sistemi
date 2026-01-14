<x-default-layout>

    @section('title')
        {{ __('common.category_management') }}
    @endsection

    @section('breadcrumbs')
        {{ Breadcrumbs::render('categories.index') }}
    @endsection

    <div class="card">
        <div class="card-header border-0 pt-6">
            <div class="card-title">
                <h3 class="fw-bold m-0">{{ __('common.categories') }}</h3>
            </div>
            <div class="card-toolbar">
                <a href="{{ route('categories.create') }}" class="btn btn-primary">
                    <i class="ki-solid ki-plus fs-2"></i>
                    {{ __('common.create_category') }}
                </a>
            </div>
        </div>
        <div class="card-body py-4">
            <div class="table-responsive">
                <table class="table table-row-dashed table-row-gray-300 align-middle gs-0 gy-4">
                    <thead>
                        <tr class="fw-bold text-muted">
                            <th>{{ __('common.name') }}</th>
                            <th>{{ __('common.description') }}</th>
                            <th>{{ __('common.color') }}</th>
                            <th>{{ __('common.sort_order') }}</th>
                            <th>{{ __('common.status') }}</th>
                            <th>{{ __('common.actions') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($categories as $category)
                            <tr>
                                <td>{{ $category->name }}</td>
                                <td>{{ $category->description ?? '-' }}</td>
                                <td>
                                    @if($category->color)
                                        <span class="badge" style="background-color: {{ $category->color }}; color: white;">{{ $category->color }}</span>
                                    @else
                                        -
                                    @endif
                                </td>
                                <td>{{ $category->sort_order }}</td>
                                <td>
                                    @if($category->is_active)
                                        <span class="badge badge-light-success">{{ __('common.active') }}</span>
                                    @else
                                        <span class="badge badge-light-secondary">{{ __('common.inactive') }}</span>
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('categories.edit', $category) }}" class="btn btn-sm btn-light-primary me-2">
                                        <i class="ki-solid ki-pencil fs-6"></i>
                                    </a>
                                    <form action="{{ route('categories.destroy', $category) }}" method="POST" class="d-inline" onsubmit="return confirm('{{ __('common.delete_category_confirm') }}');">
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
                                <td colspan="6" class="text-center text-muted">{{ __('common.no_categories') }}</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</x-default-layout>
