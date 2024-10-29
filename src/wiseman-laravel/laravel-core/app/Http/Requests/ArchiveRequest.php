<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use ProtoneMedia\LaravelMixins\Request\ConvertsBase64ToFiles;

class ArchiveRequest extends FormRequest
{
    use ConvertsBase64ToFiles;

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
			'file' => 'nullable|file',
			'parent_id' => 'nullable',
			'group_id' => 'required',
        ];
    }

    private function updateRules(): array
    {
        return [
            'name' => 'required',
			'file' => 'nullable|file',
			'parent_id' => 'nullable',
			'group_id' => 'required',
        ];
    }

    protected function base64FileKeys(): array
	{
        // $file = $this->file('file');

        // if ($file) {
        //     $extension = $file->getClientOriginalExtension();
        //     $filename = 'file.' . $extension;
        //     return [
        //         'file' => $filename,
        //     ];
        // }

		return [
			'file' => 'file.jpg',
		];
	}
}
