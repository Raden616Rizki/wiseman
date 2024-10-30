<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;


class MemoRequest extends FormRequest
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
            'group_id' => 'required',
			'message' => 'required',
        ];
    }

    private function updateRules(): array
    {
        return [
            'group_id' => 'required',
			'message' => 'nullable',
        ];
    }


}
