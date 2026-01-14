<!--begin:: Avatar -->
<div class="symbol symbol-circle symbol-50px overflow-hidden me-3">
    <a href="{{ route('file-management.show', $file) }}">
        @if($file->type === 'image')
            <div class="symbol-label">
                <img src="{{ $file->url }}" class="w-100"/>
            </div>
        @else
            <div class="symbol-label fs-3 {{ app(\App\Actions\GetThemeType::class)->handle('bg-light-? text-?', $file->name) }}">
                {!! getIcon('file', 'fs-2') !!}
            </div>
        @endif
    </a>
</div>
<!--end::Avatar-->
<!--begin::File details-->
<div class="d-flex flex-column">
    <a href="{{ route('file-management.show', $file) }}" class="text-gray-800 text-hover-primary mb-1">
        {{ $file->name }}
    </a>
    <span>{{ $file->original_name }}</span>
</div>
<!--begin::File details-->


