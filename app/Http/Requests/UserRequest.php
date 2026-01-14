<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $userId = $this->route('user')?->id;

        return [
            'name' => 'required|string|max:255',
            'email' => ['required', 'email', 'max:255', Rule::unique('users')->ignore($userId)],
            'password' => $userId 
                ? ['nullable', 'string', 'min:8', 'regex:/[A-Z]/', 'regex:/[a-z]/', 'regex:/[0-9]/'] 
                : ['required', 'string', 'min:8', 'regex:/[A-Z]/', 'regex:/[a-z]/', 'regex:/[0-9]/'],
            'department' => 'nullable|string|max:255',
            'title' => 'nullable|string|max:255',
            'account_id' => ['nullable', 'string', 'max:255', Rule::unique('users')->ignore($userId)],
            'language' => 'required|in:tr,en',
            'role' => 'required|string|exists:roles,name',
        ];
    }
}

