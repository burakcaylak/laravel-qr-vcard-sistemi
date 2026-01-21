<div class="d-flex justify-content-end">
    <a href="{{ route('v-card.show', $vCard) }}" class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm me-1" data-bs-toggle="tooltip" title="{{ __('common.view') }}">
        {!! getIcon('eye', 'fs-2') !!}
    </a>
    <a href="{{ route('v-card.edit', $vCard) }}" class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm me-1" data-bs-toggle="tooltip" title="{{ __('common.edit') }}">
        {!! getIcon('pencil', 'fs-2') !!}
    </a>
    <button type="button" class="btn btn-icon btn-bg-light btn-active-color-danger btn-sm" data-kt-action="delete_v_card" data-kt-v-card-id="{{ $vCard->id }}" data-bs-toggle="tooltip" title="{{ __('common.delete') }}">
        {!! getIcon('trash', 'fs-2') !!}
    </button>
</div>
