<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BrochureRequest extends FormRequest
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
        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:1000'],
            'category_id' => ['nullable', 'exists:categories,id'],
            'file_id' => ['nullable', 'exists:files,id'],
            'pdf_file' => ['nullable', 'file', 'mimes:pdf', 'max:' . config('files.max_file_size')],
            'background_type' => ['nullable', 'in:image,color'],
            'background_image' => ['nullable', 'image', 'mimes:jpeg,jpg,png,gif,webp', 'max:5120'], // 5MB max
            'background_color' => ['nullable', 'string', 'regex:/^#([A-Fa-f0-9]{6}|[A-Fa-f0-9]{3})$/'],
            'is_active' => ['nullable', 'boolean'],
            'expires_at' => ['nullable', 'date', 'after_or_equal:today'],
            'password' => ['nullable', 'string', 'min:4', 'max:255'],
            'password_protected' => ['nullable', 'boolean'],
        ];

        // Create için PDF dosyası zorunlu (file_id veya pdf_file)
        if ($this->isMethod('post')) {
            if (!$this->has('file_id')) {
                $rules['pdf_file'] = ['required', 'file', 'mimes:pdf', 'max:' . config('files.max_file_size')];
            }
        }

        // background_type'a göre validasyon (görsel varsa görsel, yoksa renk zorunlu)
        if ($this->has('background_image_file_id') || $this->hasFile('background_image')) {
            // Görsel seçilmişse renk zorunlu değil
        } else {
            // Görsel yoksa renk zorunlu
            $rules['background_color'] = ['required', 'string', 'regex:/^#([A-Fa-f0-9]{6}|[A-Fa-f0-9]{3})$/'];
        }

        return $rules;
    }

    public function messages(): array
    {
        return [
            'name.required' => __('common.name_required'),
            'pdf_file.required' => __('common.pdf_file_required'),
            'pdf_file.mimes' => __('common.pdf_file_must_be_pdf'),
            'pdf_file.max' => __('common.pdf_file_max_size'),
            'background_type.required' => __('common.background_type_required'),
            'background_image.required' => __('common.background_image_required'),
            'background_color.required' => __('common.background_color_required'),
            'background_color.regex' => __('common.background_color_invalid'),
            'category_id.exists' => __('common.category_not_found'),
            'file_id.exists' => __('common.file_not_found'),
        ];
    }
}
