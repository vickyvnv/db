<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreTemporaryTableRequest extends FormRequest
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
        return [
            'tables' => 'required|array',
            'tables.*.name' => 'required|string|max:255',
            'tables.*.type' => 'required|string|in:temporary,permanent',
            'tables.*.drop_date' => 'required_if:tables.*.type,temporary|date',
            'tables.*.sql' => 'required|string',
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'tables.required' => 'At least one table must be provided.',
            'tables.*.name.required' => 'Each table must have a name.',
            'tables.*.type.required' => 'Each table must have a type.',
            'tables.*.type.in' => 'Table type must be either temporary or permanent.',
            'tables.*.drop_date.required_if' => 'Drop date is required for temporary tables.',
            'tables.*.sql.required' => 'SQL definition is required for each table.',
        ];
    }
}