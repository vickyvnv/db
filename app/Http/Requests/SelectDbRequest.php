<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SelectDbRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        // You can add authorization logic here if needed
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = [];

        // Only apply validation if the field is null or empty
        if ($this->isEmptyOrNull('sw_version')) {
            $rules['sw_version'] = 'required|integer';
        }

        if ($this->isEmptyOrNull('db_user')) {
            $rules['db_user'] = 'required|string|max:255';
        }

        if ($this->isEmptyOrNull('prod_instance')) {
            $rules['prod_instance'] = 'required|string|max:255';
        }

        if ($this->isEmptyOrNull('test_instance')) {
            $rules['test_instance'] = 'required|string|max:255';
        }

        // Always validate source_code
        $rules['source_code'] = 'required|string';

        return $rules;
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'sw_version.required' => 'The software version is required.',
            'sw_version.integer' => 'The software version must be an integer.',
            'db_user.required' => 'The database user is required.',
            'source_code.required' => 'The source code is required.',
            'prod_instance.required' => 'The production instance is required.',
            'test_instance.required' => 'The test instance is required.',
        ];
    }

    /**
     * Check if a field is empty or null.
     *
     * @param string $field
     * @return bool
     */
    private function isEmptyOrNull($field)
    {
        return $this->$field === null || $this->$field === '';
    }
}