<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class QrCodeRequest extends FormRequest
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
            'category' => ['nullable', 'string', 'max:100'],
            'category_id' => ['nullable', 'exists:categories,id'],
            'requested_by' => ['nullable', 'string', 'max:255'],
            'request_date' => ['nullable', 'date'],
            'description' => ['nullable', 'string', 'max:1000'],
            'qr_type' => ['required', 'in:file,url,multi_file'],
            'page_title' => ['nullable', 'string', 'max:255'],
            'size' => ['nullable', 'integer', 'min:100', 'max:1000'],
            'format' => ['nullable', 'in:png,svg'],
            'is_active' => ['nullable', 'boolean'],
            'expires_at' => ['nullable', 'date', 'after_or_equal:today'],
            'password' => ['nullable', 'string', 'min:4', 'max:255'],
            'password_protected' => ['nullable', 'boolean'],
        ];

        if ($this->qr_type === 'file') {
            $rules['file_id'] = ['required', 'exists:files,id'];
        } elseif ($this->qr_type === 'multi_file') {
            $rules['file_ids'] = ['required', 'array', 'min:1'];
            $rules['file_ids.*'] = ['exists:files,id'];
            $rules['button_names'] = ['required', 'array', 'min:1'];
            $rules['button_names.*'] = ['required', 'string', 'max:255'];
            $rules['page_title'] = ['required', 'string', 'max:255'];
        } else {
            $rules['content'] = ['required', 'string'];
        }

        return $rules;
    }

    public function messages(): array
    {
        return [
            'file_id.required' => __('common.file_id_required'),
            'file_id.exists' => __('common.file_not_found'),
            'file_ids.required' => __('common.at_least_one_file'),
            'file_ids.array' => __('common.invalid_file_selection'),
            'file_ids.min' => __('common.at_least_one_file'),
            'file_ids.*.exists' => __('common.file_not_found'),
            'button_names.required' => __('common.button_names_required'),
            'button_names.array' => __('common.invalid_button_names'),
            'button_names.*.required' => __('common.button_name_required'),
            'page_title.required' => __('common.page_title_required'),
            'content.required' => __('common.content_required'),
            'qr_type.required' => __('common.qr_type_required'),
            'expires_at.after_or_equal' => __('common.expires_at_must_be_today_or_later'),
        ];
    }
}
