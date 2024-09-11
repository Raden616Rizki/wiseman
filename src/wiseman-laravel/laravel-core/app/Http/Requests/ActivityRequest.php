<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;


class ActivityRequest extends FormRequest
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
            'group_id' => 'nullable',
			'user_id' => 'required',
			'description' => 'required',
			'start_time' => 'required',
			'end_time' => 'required',
			'is_priority' => 'required',
			'is_finished' => 'required',
        ];
    }

    private function updateRules(): array
    {
        return [
            'group_id' => 'nullable',
			'user_id' => 'required',
			'description' => 'required',
			'start_time' => 'required',
			'end_time' => 'required',
			'is_priority' => 'required',
			'is_finished' => 'required',
        ];
    }


}
