<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateDbiRequest extends FormRequest
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
            'priority_id' => 'sometimes|required|exists:priorities,name',
            'brief_desc' => 'sometimes|required|string|max:1000',
            'problem_desc' => 'sometimes|required|string',
            'business_impact' => 'sometimes|required|string',
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
            'priority_id.required' => 'The priority field is required.',
            'brief_desc.required' => 'A brief description is required.',
            'problem_desc.required' => 'A problem description is required.',
            'business_impact.required' => 'The business impact field is required.',
        ];
    }
}