<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;


class EnrollmentRequest extends FormRequest
{
    
    public $validator;
    public function failedValidation(Validator $validator)
    {
        $this->validator = $validator;
    }

    public function rules(): array
    {
        if ($this->isMethod('post')) {
            return $this->createRules();
        }

        return $this->updateRules();
    }

    private function createRules(): array
    {
        return [
            'user_id' => 'required',
			'group_id' => 'required',
        ];
    }

    private function updateRules(): array
    {
        return [
            'user_id' => 'required',
			'group_id' => 'required',
        ];
    }

    
}
