<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class VCardRequest extends FormRequest
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
            'template_id' => ['nullable', 'exists:v_card_templates,id'],
            'category_id' => ['nullable', 'exists:categories,id'],
            
            // Turkish fields
            'name_tr' => ['nullable', 'string', 'max:255'],
            'title_tr' => ['nullable', 'string', 'max:255'],
            'phone_tr' => ['nullable', 'string', 'max:50'],
            'email_tr' => ['nullable', 'email', 'max:255'],
            'company_tr' => ['nullable', 'string', 'max:255'],
            'address_tr' => ['nullable', 'string', 'max:1000'],
            'company_phone_tr' => ['nullable', 'string', 'max:50'],
            'extension_tr' => ['nullable', 'string', 'max:20'],
            'fax_tr' => ['nullable', 'string', 'max:50'],
            'mobile_phone_tr' => ['nullable', 'string', 'max:50'],
            'website_tr' => ['nullable', 'url', 'max:255'],
            
            // English fields
            'name_en' => ['nullable', 'string', 'max:255'],
            'title_en' => ['nullable', 'string', 'max:255'],
            'phone_en' => ['nullable', 'string', 'max:50'],
            'email_en' => ['nullable', 'email', 'max:255'],
            'company_en' => ['nullable', 'string', 'max:255'],
            'address_en' => ['nullable', 'string', 'max:1000'],
            'company_phone_en' => ['nullable', 'string', 'max:50'],
            'extension_en' => ['nullable', 'string', 'max:20'],
            'fax_en' => ['nullable', 'string', 'max:50'],
            'mobile_phone_en' => ['nullable', 'string', 'max:50'],
            'website_en' => ['nullable', 'url', 'max:255'],
            
            // Common fields
            'email' => ['nullable', 'email', 'max:255'],
            'phone' => ['nullable', 'string', 'max:50'],
            'mobile_phone' => ['nullable', 'string', 'max:50'],
            'website' => ['nullable', 'url', 'max:255'],
            'image' => ['nullable', 'image', 'mimes:jpeg,jpg,png,gif,webp', 'max:5120'], // 5MB max
            
            // QR Code related
            'is_active' => ['nullable', 'boolean'],
        ];
    }

    public function messages(): array
    {
        return [
            'category_id.exists' => __('common.category_not_found'),
            'email_tr.email' => __('common.invalid_email'),
            'email_en.email' => __('common.invalid_email'),
            'email.email' => __('common.invalid_email'),
            'website_tr.url' => __('common.invalid_url'),
            'website_en.url' => __('common.invalid_url'),
            'website.url' => __('common.invalid_url'),
            'expires_at.after_or_equal' => __('common.expires_at_must_be_today_or_later'),
        ];
    }
}
