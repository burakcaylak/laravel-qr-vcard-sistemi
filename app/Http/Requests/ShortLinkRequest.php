<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ShortLinkRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'original_url' => ['required', 'url', 'max:2048'],
            'title' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:1000'],
            'category_id' => ['nullable', 'exists:categories,id'],
            'short_code' => ['nullable', 'string', 'max:50', 'unique:short_links,short_code,' . $this->route('shortLink')?->id],
            'is_active' => ['nullable', 'boolean'],
            'expires_at' => ['nullable', 'date', 'after_or_equal:today'],
            'password' => ['nullable', 'string', 'min:4', 'max:255'],
            'qr_code_size' => ['nullable', 'integer', 'min:100', 'max:1000'],
            'qr_code_format' => ['nullable', 'in:png,svg'],
            'regenerate_qr' => ['nullable', 'boolean'],
        ];
    }

    public function messages(): array
    {
        return [
            'original_url.required' => __('common.url_required'),
            'original_url.url' => __('common.invalid_url'),
            'short_code.unique' => __('common.short_code_already_exists'),
            'expires_at.after_or_equal' => __('common.expires_at_must_be_today_or_later'),
        ];
    }
}
