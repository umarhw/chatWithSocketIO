<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCounterRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'chatId' => ['required', 'exists:chats,id'],
            'counter' => ['required', 'numeric', 'min:0'],
        ];
    }

    /**
     * Get the validation messages for the request.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'chatId.required' => 'The "chatId" field is required.',
            'chatId.exists' => 'The selected "chatId" does not exist.',
            'counter.required' => 'The "counter" field is required.',
            'counter.numeric' => 'The "counter" field must be a numeric value.',
            'counter.min' => 'The "counter" field must be greater than or equal to zero.',
        ];
    }
}
