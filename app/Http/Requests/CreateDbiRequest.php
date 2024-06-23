<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateDbiRequest extends FormRequest
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
            'category' => 'required|string|max:255',
            'priority_id' => 'required|exists:priorities,name',
            'tt_id' => 'required|string|max:255',
            'serf_cr_id' => 'required|string|max:255',
            'reference_dbi' => 'required|string|max:255',
            'brief_desc' => 'required|string|max:1000',
            'problem_desc' => 'required|string',
            'business_impact' => 'required|string',
            'dbi_type' => 'required',
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
            'category.required' => 'The category field is required.',
            'priority_id.required' => 'The priority field is required.',
            'tt_id.required' => 'The TT ID field is required.',
            'serf_cr_id.required' => 'The SERF/CR ID field is required.',
            'reference_dbi.required' => 'The reference DBI field is required.',
            'brief_desc.required' => 'A brief description is required.',
            'problem_desc.required' => 'A problem description is required.',
            'business_impact.required' => 'The business impact field is required.',
            'dbi_type.required_if' => 'The DBI type is required when category is SP.',
        ];
    }
}