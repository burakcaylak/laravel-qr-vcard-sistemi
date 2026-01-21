<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SettingsRequest extends FormRequest
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
            'logo_light' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'logo_light_path' => 'nullable|string',
            'logo_dark' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'logo_dark_path' => 'nullable|string',
            'favicon' => 'nullable|image|mimes:ico,png,svg|max:1024',
            'favicon_path' => 'nullable|string',
            'login_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'login_image_path' => 'nullable|string',
            'index_enabled' => 'nullable|boolean',
            'language' => 'required|in:tr,en',
            'footer_text' => 'nullable|string|max:500',
            'short_link_domain' => 'nullable|string|max:255',
        ];
    }
}
