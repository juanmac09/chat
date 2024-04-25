<?php

namespace App\Http\Requests\Message;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
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
        $rules = [
            'content' => 'string|required',
            'recipient_entity_id' => [
                'required',
                'integer',
                'numeric'
            ],
            'recipient_type' => 'required|numeric|between:1,2',
        ];
        if ($this->input('recipient_type') == 1) {
            $rules['recipient_entity_id'][] = Rule::exists('users', 'id');
            $rules['recipient_entity_id'][] = Rule::notIn([Auth::id()]);
        } else {
            $rules['recipient_entity_id'][] = Rule::exists('groups', 'id');
        }

        return $rules;
    }
    /**
     * Define additional validation rules after the main validation.
     *
     * @param \Illuminate\Contracts\Validation\Validator $validator
     */
    public function withValidator($validator)
    {
        $validator->after(function ($validator) {

            if ($this->input('recipient_type') != 1) {
                $id = $this->input('recipient_entity_id');
                $status = DB::table('groups')->where('id', $id)->value('status');
                if ($status !== 1) {
                    $validator->errors()->add('id', 'The group with ID ' . $id . ' does not have active status.');
                }

                if (!Auth::user()->groups()->where('groups.id', $id)->first()) {
                    $validator->errors()->add('id', 'The user is not in the group with id ' . $id);
                }
            }
        });
    }
    /**
     * Handle the failed validation attempt.
     *
     * @param  \Illuminate\Contracts\Validation\Validator  $validator  The validator instance.
     *
     * @return void
     *
     * @throws \Illuminate\Http\Exceptions\HttpResponseException  Throws an exception with a 422 HTTP response containing the validation errors.
     */
    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(
            response()->json(['error' => $validator->errors()], 422)
        );
    }
}
