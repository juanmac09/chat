<?php

namespace App\Http\Requests\Message;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rule;
class SendMessageRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'content' => 'string|required',
            'recipient_entity_id' => [
                'required',
                'numeric',
                'integer',
                Rule::when(
                    $this->input('recipient_type') == 1,
                    Rule::exists('users', 'id'),
                    Rule::exists('groups', 'id')
                ),
            ],
            'recipient_type' => 'required|numeric|between:1,2',
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(
            response()->json(['error' => $validator->errors()], 422)
        );
    }
}
