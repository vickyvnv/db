<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRequestStatusRequest extends FormRequest
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
            // 'request_status' => 'required|integer',
            // 'operator_status' => 'required|integer',
            // 'dat_status' => 'required|integer',
            // 'operator_comment' => 'nullable|string',
            // 'dat_comment' => 'nullable|string',
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
            // 'request_status.required' => 'The request status is required.',
            // 'operator_status.required' => 'The operator status is required.',
            // 'dat_status.required' => 'The DAT status is required.',
        ];
    }
}