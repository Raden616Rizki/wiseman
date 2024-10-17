<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;


class GroupRequest extends FormRequest
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
            'name' => 'required',
			'description' => 'nullable',
        ];
    }

    private function updateRules(): array
    {
        return [
            'name' => 'nullable',
			'description' => 'nullable',
        ];
    }


}
