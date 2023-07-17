<?php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreMessageRequest extends FormRequest
{
    // ...

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'from' => ['required', 'exists:users,id'],
            'chatId' => ['required', 'exists:chats,id'],
            'message' => ['required', 'string', 'min:1'],
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
            'from.required' => 'The "from" field is required.',
            'chatId.required' => 'The "chatId" field is required.',
            'message.required' => 'The "message" field is required.',
            'message.min' => 'The "message" field must have at least :min character.',
        ];
    }
}
