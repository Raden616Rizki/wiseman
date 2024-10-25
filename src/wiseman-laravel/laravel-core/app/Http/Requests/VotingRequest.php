<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;


class VotingRequest extends FormRequest
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
            'description' => 'required',
			'limit_time' => 'required',
			'group_id' => 'required',
			'voting_options.*.option' => 'required',
			'voting_options.*.total' => 'required',
        ];
    }

    private function updateRules(): array
    {
        return [
            'description' => 'nullable',
			'limit_time' => 'nullable',
			'group_id' => 'nullable',
			'voting_options.*.option' => 'nullable',
			'voting_options.*.total' => 'nullable',
        ];
    }


}
