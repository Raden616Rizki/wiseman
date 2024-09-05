<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;
use ConvertsBase64ToFiles;

class UpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    // public function authorize(): bool
    // {
    //     return false;
    // }

    /**
     * inisialisasi key "photo" dengan value base64 sebagai "FILE"
     *
     * @return array
     */
    protected function base64FileKeys(): array
    {
        return [
            'photo_profile' => 'user-photo.jpg',
        ];
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'id' => 'required|exists:m_user,id',
            'username' => 'nullable|max:100',
            'email' => 'nullable|email|unique:m_user',
            'password' => 'nullable|min:6',
            'phone' => 'nullable|max:25',
            'photo_profile' => 'nullable|file|image',
        ];
    }

    public function attributes() {
        return [
            'password' => 'Kolom Password',
            'photo_profile' => 'Kolom Photo Profile',
            'phone' => 'Kolom Phone Number',
        ];
    }
}
